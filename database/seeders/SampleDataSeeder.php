<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        /* ============================================================
           CATEGORIES
           ============================================================ */
        $categories = [
            'Electronics'     => ['Mobiles', 'Laptops', 'Headphones'],
            'Fashion'         => ['Men', 'Women'],
            'Home & Kitchen'  => ['Cookware', 'Home Decor'],
            'Beauty'          => ['Skincare', 'Makeup'],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($parentName)],
                [
                    'name' => $parentName,
                    'status' => 'active',
                    'is_featured' => true,
                ]
            );

            foreach ($children as $child) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($child)],
                    [
                        'parent_id' => $parent->id,
                        'name' => $child,
                        'status' => 'active',
                    ]
                );
            }
        }

        /* ============================================================
           BRANDS
           ============================================================ */
        $brands = ['Apple', 'Samsung', 'Sony', 'Nike', 'Boat', 'OnePlus'];
        foreach ($brands as $b) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($b)],
                [
                    'name' => $b,
                    'status' => 'active',
                    'is_featured' => true,
                ]
            );
        }

        /* ============================================================
           ATTRIBUTES
           ============================================================ */
        $attributes = [
            'Color' => ['type' => 'color', 'values' => [
                ['Black', '#000000'], ['White', '#FFFFFF'],
                ['Blue', '#2563EB'], ['Red', '#DC2626'],
            ]],
            'Size' => ['type' => 'size', 'values' => [
                ['S', null], ['M', null], ['L', null], ['XL', null],
            ]],
        ];

        foreach ($attributes as $name => $data) {
            $attr = Attribute::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'type' => $data['type'],
                    'is_variant' => true,
                    'status' => 'active',
                ]
            );

            foreach ($data['values'] as $v) {
                AttributeValue::updateOrCreate(
                    ['attribute_id' => $attr->id, 'slug' => Str::slug($v[0])],
                    [
                        'value' => $v[0],
                        'color_code' => $v[1],
                    ]
                );
            }
        }

        /* ============================================================
           PRODUCTS
           ============================================================ */
        $products = [
            ['iPhone 15 Pro 128GB', 'Mobiles', 'Apple', 129900, 139900, 25, 'Titanium build, A17 Pro chip, 48MP camera.'],
            ['iPhone 15 Base', 'Mobiles', 'Apple', 79900, 89900, 40, 'Dynamic Island, A16 Bionic chip.'],
            ['Galaxy Book4 Pro', 'Laptops', 'Samsung', 149990, 159990, 10, 'Ultra-light 16-inch laptop, AMOLED display.'],
            ['Sony WH-1000XM5', 'Headphones', 'Sony', 29990, 34990, 15, 'Industry-leading noise cancellation.'],
            ['Boat Rockerz 550', 'Headphones', 'Boat', 2999, 4999, 120, 'Wireless over-ear with 20H battery.'],
            ['Nike Air Zoom', 'Men', 'Nike', 8999, 11999, 60, 'Running shoes with Zoom cushion.'],
            ['Men Casual Shirt', 'Men', null, 1299, 1999, 200, 'Premium cotton, slim fit.'],
            ['Women Kurti', 'Women', null, 1899, 2999, 150, 'Elegant ethnic wear.'],
            ['OnePlus Nord Buds', 'Headphones', 'OnePlus', 2499, 3499, 80, 'True wireless earbuds with ANC.'],
            ['Stainless Steel Cookware', 'Cookware', null, 4999, 6999, 45, '5-piece premium cookware set.'],
        ];

        foreach ($products as $p) {
            [$name, $catName, $brandName, $price, $compare, $stock, $short] = $p;

            $category = Category::where('slug', Str::slug($catName))->first();
            $brand = $brandName ? Brand::where('slug', Str::slug($brandName))->first() : null;

            Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'sku' => 'SKU-'.strtoupper(Str::random(8)),
                    'category_id' => $category?->id,
                    'brand_id' => $brand?->id,
                    'type' => 'simple',
                    'short_description' => $short,
                    'description' => $short."\n\nPremium quality product with 1-year warranty. Fast shipping and easy returns.",
                    'price' => $price,
                    'compare_price' => $compare,
                    'tax_rate' => 18,
                    'stock' => $stock,
                    'manage_stock' => true,
                    'status' => 'active',
                    'is_featured' => true,
                    'is_new_arrival' => true,
                    'is_best_seller' => true,
                    'thumbnail' => null,
                ]
            );
        }

        /* ============================================================
           COUPONS
           ============================================================ */
        Coupon::updateOrCreate(
            ['code' => 'WELCOME10'],
            [
                'title' => 'Welcome 10% off',
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 999,
                'max_discount' => 500,
                'usage_limit' => 1000,
                'start_date' => now(),
                'end_date' => now()->addDays(90),
                'status' => 'active',
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'FLAT200'],
            [
                'title' => 'Flat ₹200 off',
                'type' => 'fixed',
                'value' => 200,
                'min_order_amount' => 1499,
                'usage_limit' => 500,
                'start_date' => now(),
                'end_date' => now()->addDays(60),
                'status' => 'active',
            ]
        );

      
    }
}
