<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\ActivityLog;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand'])
            ->when($request->q, fn ($q, $s) => $q->where(function ($sub) use ($s) {
                $sub->where('name', 'like', "%{$s}%")
                    ->orWhere('sku', 'like', "%{$s}%");
            }))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->category, fn ($q, $c) => $q->where('category_id', $c))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $categories = Category::active()->whereNull('parent_id')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => Category::active()->whereNull('parent_id')->with('children')->get(),
            'brands' => Brand::where('status', 'active')->get(),
            'attributes' => Attribute::with('values')->where('status', 'active')->get(),
        ]);
    }

    public function store(ProductStoreRequest $request)
    {
        $product = $this->service->create($request->validated());

        ActivityLog::record('products', 'create', "Product created: {$product->name}");

        return redirect()->route('admin.products')
            ->with('success', "Product \"{$product->name}\" created successfully.");
    }

    public function show(Product $product)
    {
        $product->load(['images', 'variants', 'category', 'brand', 'reviews', 'attributeValues']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'variants', 'attributeValues']);

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::active()->whereNull('parent_id')->with('children')->get(),
            'brands' => Brand::where('status', 'active')->get(),
            'attributes' => Attribute::with('values')->where('status', 'active')->get(),
        ]);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product = $this->service->update($product, $request->validated());

        ActivityLog::record('products', 'update', "Product updated: {$product->name}");

        return redirect()->route('admin.products')
            ->with('success', "Product updated successfully.");
    }

   public function destroy($product)
{
    // Try to find (including soft-deleted)
    $productModel = Product::withTrashed()->find($product);


    if (!$productModel) {
        return response()->json([
            'success' => true,
            'message' => 'Product was already deleted.',
        ]);
    }

    // Already soft-deleted? Force delete or return success
    if ($productModel->trashed()) {
        return response()->json([
            'success' => true,
            'message' => 'Product was already deleted.',
        ]);
    }

    ActivityLog::record('products', 'delete', "Product deleted: {$productModel->name}");
    $this->service->delete($productModel);

    return response()->json([
        'success' => true,
        'message' => 'Product deleted successfully.',
    ]);
}

    public function toggleStatus(Product $product)
    {
        $product->update([
            'status' => $product->status === 'active' ? 'inactive' : 'active',
        ]);

        return response()->json([
            'success' => true,
            'status' => $product->status,
        ]);
    }
}
