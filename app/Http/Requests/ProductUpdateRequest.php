<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('product.edit');
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $id = $product instanceof \App\Models\Product ? $product->id : $product;

        return [
            'name'              => ['required', 'string', 'max:255'],
            'slug'              => ['nullable', 'string', 'max:280', Rule::unique('products', 'slug')->ignore($id)],
            'sku'               => ['nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($id)],
            'barcode'           => ['nullable', 'string', 'max:100'],
            'type'              => ['required', 'in:simple,variable'],
            'category_id'       => ['required', 'exists:categories,id'],
            'brand_id'          => ['nullable', 'exists:brands,id'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description'       => ['nullable', 'string'],

            'price'             => ['required', 'numeric', 'min:0'],
            'compare_price'     => ['nullable', 'numeric', 'min:0'],
            'cost_price'        => ['nullable', 'numeric', 'min:0'],
            'discount_type'     => ['required', 'in:none,fixed,percent'],
            'discount_value'    => ['nullable', 'numeric', 'min:0'],
            'tax_rate'          => ['nullable', 'numeric', 'min:0', 'max:100'],

            'stock'             => ['required', 'integer', 'min:0'],
            'low_stock_alert'   => ['nullable', 'integer', 'min:0'],
            'manage_stock'      => ['boolean'],

            'weight'            => ['nullable', 'numeric', 'min:0'],
            'length'            => ['nullable', 'numeric', 'min:0'],
            'width'             => ['nullable', 'numeric', 'min:0'],
            'height'            => ['nullable', 'numeric', 'min:0'],

            'thumbnail'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gallery'           => ['nullable', 'array', 'max:8'],
            'gallery.*'         => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_images'     => ['nullable', 'array'],
            'remove_images.*'   => ['integer', 'exists:product_images,id'],

            'specifications'          => ['nullable', 'array'],
            'specifications.*.key'    => ['nullable', 'string', 'max:100'],
            'specifications.*.value'  => ['nullable', 'string', 'max:255'],

            'attributes'        => ['nullable', 'array'],
            'attributes.*'      => ['array'],
            'attributes.*.*'    => ['exists:attribute_values,id'],

            'variants'              => ['nullable', 'array'],
            'variants.*.id'         => ['nullable', 'integer', 'exists:product_variants,id'],
            'variants.*.name'       => ['required_with:variants', 'string', 'max:255'],
            'variants.*.price'      => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.stock'      => ['required_with:variants', 'integer', 'min:0'],
            'remove_variants'       => ['nullable', 'array'],

            'meta_title'        => ['nullable', 'string', 'max:255'],
            'meta_description'  => ['nullable', 'string'],
            'meta_keywords'     => ['nullable', 'string', 'max:255'],

            'status'            => ['required', 'in:active,inactive,draft'],
            'is_featured'       => ['boolean'],
            'is_trending'       => ['boolean'],
            'is_new_arrival'    => ['boolean'],
            'is_best_seller'    => ['boolean'],
        ];
    }
}
