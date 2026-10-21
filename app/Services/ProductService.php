<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create($this->prepareProductData($data));

            $this->handleThumbnail($product, $data['thumbnail'] ?? null);
            $this->handleGallery($product, $data['gallery'] ?? []);
            $this->handleSpecifications($product, $data['specifications'] ?? []);
            $this->handleAttributes($product, $data['attributes'] ?? []);
            $this->handleVariants($product, $data['variants'] ?? []);

            return $product->fresh();
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update($this->prepareProductData($data, $product));

            if (!empty($data['thumbnail'])) {
                $this->deleteFile($product->thumbnail);
                $this->handleThumbnail($product, $data['thumbnail']);
            }

            if (!empty($data['remove_images'])) {
                $this->removeGalleryImages($product, $data['remove_images']);
            }

            $this->handleGallery($product, $data['gallery'] ?? []);
            $this->handleSpecifications($product, $data['specifications'] ?? []);
            $this->handleAttributes($product, $data['attributes'] ?? []);

            if (!empty($data['remove_variants'])) {
                $product->variants()->whereIn('id', $data['remove_variants'])->delete();
            }

            $this->handleVariants($product, $data['variants'] ?? [], true);

            return $product->fresh();
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            $this->deleteFile($product->thumbnail);
            foreach ($product->images as $img) $this->deleteFile($img->path);
            foreach ($product->variants as $v) if ($v->image) $this->deleteFile($v->image);

            $product->images()->delete();
            $product->variants()->delete();
            $product->delete();
        });
    }

    /* ============ PRIVATE ============ */

    private function prepareProductData(array $data, ?Product $product = null): array
    {
        $payload = [
            'name' => $data['name'],
           'slug' => !empty($data['slug'])
            ? Str::slug($data['slug'])
            : ($product?->slug ?? Str::slug($data['name']).'-'.Str::random(5)),
            'sku' => $data['sku'] ?: ($product?->sku ?? 'SKU-'.strtoupper(Str::random(8))),
            'barcode' => $data['barcode'] ?? null,
            'type' => $data['type'],
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'compare_price' => $data['compare_price'] ?? null,
            'cost_price' => $data['cost_price'] ?? null,
            'discount_type' => $data['discount_type'] ?? 'none',
            'discount_value' => $data['discount_value'] ?? 0,
            'tax_rate' => $data['tax_rate'] ?? 0,
            'stock' => $data['stock'],
            'low_stock_alert' => $data['low_stock_alert'] ?? 5,
            'manage_stock' => !empty($data['manage_stock']),
            'weight' => $data['weight'] ?? null,
            'length' => $data['length'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'status' => $data['status'],
            'is_featured' => !empty($data['is_featured']),
            'is_trending' => !empty($data['is_trending']),
            'is_new_arrival' => !empty($data['is_new_arrival']),
            'is_best_seller' => !empty($data['is_best_seller']),
        ];

        if (!$product) $payload['created_by'] = auth()->id();

        return $payload;
    }

    private function handleThumbnail(Product $product, ?UploadedFile $file): void
    {
        if (!$file || !$file instanceof UploadedFile) return;

        $targetDir = public_path('assets/upload');
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = time() . '_' . Str::random(12) . '.' . $file->getClientOriginalExtension();
        $file->move($targetDir, $filename);

        $path = 'assets/upload/' . $filename;
        $product->update(['thumbnail' => $path]);
    }

    private function handleGallery(Product $product, array $files): void
    {
        $targetDir = public_path('assets/upload');
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        foreach ($files as $i => $file) {
            if (!$file instanceof UploadedFile) continue;

            $filename = time() . '_' . Str::random(12) . '.' . $file->getClientOriginalExtension();
            $file->move($targetDir, $filename);

            $path = 'assets/upload/' . $filename;
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'alt' => $product->name,
                'is_primary' => false,
                'sort_order' => $product->images()->count() + $i,
            ]);
        }
    }

    private function removeGalleryImages(Product $product, array $ids): void
    {
        $images = $product->images()->whereIn('id', $ids)->get();
        foreach ($images as $img) {
            $this->deleteFile($img->path);
            $img->delete();
        }
    }

    private function handleSpecifications(Product $product, array $specs): void
    {
        $clean = collect($specs)
            ->filter(fn ($s) => !empty($s['key']) && !empty($s['value']))
            ->mapWithKeys(fn ($s) => [trim($s['key']) => trim($s['value'])])
            ->toArray();

        $product->update(['specifications' => $clean ?: null]);
    }

    private function handleAttributes(Product $product, array $attributes): void
    {
        $sync = [];
        foreach ($attributes as $attributeId => $valueIds) {
            foreach ((array) $valueIds as $valueId) {
                $sync[$valueId] = [
                    'attribute_id' => $attributeId,
                    'attribute_value_id' => $valueId,
                ];
            }
        }
        $product->attributeValues()->sync($sync);
    }

    private function handleVariants(Product $product, array $variants, bool $update = false): void
    {
        foreach ($variants as $v) {
            if (empty($v['name']) || !isset($v['price'])) continue;

            $variantId = $v['id'] ?? null;
            $payload = [
                'name' => $v['name'],
                'sku' => $v['sku'] ?: 'VAR-'.strtoupper(Str::random(8)),
                'price' => $v['price'],
                'compare_price' => $v['compare_price'] ?? null,
                'cost_price' => $v['cost_price'] ?? null,
                'stock' => $v['stock'] ?? 0,
                'weight' => $v['weight'] ?? null,
                'status' => 'active',
            ];

            if (!empty($v['image']) && $v['image'] instanceof UploadedFile) {
                if ($variantId && ($old = ProductVariant::find($variantId))) {
                    $this->deleteFile($old->image);
                }

                $targetDir = public_path('assets/upload');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $filename = time() . '_' . Str::random(12) . '.' . $v['image']->getClientOriginalExtension();
                $v['image']->move($targetDir, $filename);
                $payload['image'] = 'assets/upload/' . $filename;
            }

            if ($variantId && $update) {
                $existing = ProductVariant::where('product_id', $product->id)->find($variantId);
                if ($existing) {
                    $existing->update($payload);
                    continue;
                }
            }

            $payload['product_id'] = $product->id;
            ProductVariant::create($payload);
        }
    }

    private function deleteFile(?string $path): void
    {
        if (!$path) return;
        $clean = ltrim($path, '/');
        $publicFilePath = public_path($clean);
        if (file_exists($publicFilePath) && is_file($publicFilePath)) {
            @unlink($publicFilePath);
            return;
        }
        $storageRelative = preg_replace('#^storage/#', '', $clean);
        if (Storage::disk('public')->exists($storageRelative)) {
            Storage::disk('public')->delete($storageRelative);
        }
    }
}
