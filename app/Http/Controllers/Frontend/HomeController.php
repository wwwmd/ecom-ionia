<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 'active')
            ->where('position', 'hero')
            ->orderBy('sort_order')
            ->get();

        $categories = Category::active()
            ->whereNull('parent_id')
            ->where('is_featured', true)
            ->take(6)
            ->get();

        $brands = Brand::where('status', 'active')
            ->where('is_featured', true)
            ->take(6)
            ->get();

        $featuredProducts = Product::active()
            ->featured()
            ->with(['brand', 'category'])
            ->take(8)
            ->get();

        $products = Product::active()
            ->with(['brand', 'category'])
            ->latest()
            ->get();

        return view('home.index', compact('banners', 'categories', 'brands', 'featuredProducts', 'products'));
    }

    public function products()
    {
        $products = Product::active()
            ->with(['brand', 'category'])
            ->latest()
            ->get();

        return view('home.product', compact('products'));
    }

    public function producDetail(\Illuminate\Http\Request $request, $slug = null)
    {
        $productSlug = $slug ?: $request->query('slug');
        $productId = $request->query('id');

        $product = null;
        if ($productSlug) {
            $product = Product::active()->with(['images', 'brand', 'category'])->where('slug', $productSlug)->first();
        } elseif ($productId) {
            $product = Product::active()->with(['images', 'brand', 'category'])->find($productId);
        }

        if (!$product) {
            $product = Product::active()->with(['images', 'brand', 'category'])->first();
        }

        $relatedProducts = Product::active()
            ->when($product, fn ($q) => $q->where('id', '!=', $product->id))
            ->take(4)
            ->get();

        return view('home.product-details', compact('product', 'relatedProducts'));
    }

    public function about()
    {
        return view('home.about-us');
    }

    public function contact()
    {
        return view('home.contact-us');
    }

    public function checkout(\App\Services\CartService $cartService)
    {
        $totals = $cartService->totals();
        $cartItems = $totals['items'];
        $subtotal = $totals['subtotal'];
        $discount = $totals['discount'] ?? 0;
        $tax = $totals['tax'];
        $shipping = $totals['shipping'];
        $grandTotal = $totals['grand_total'];
        $count = $totals['count'];

        return view('home.checkout', compact('cartItems', 'subtotal', 'discount', 'tax', 'shipping', 'grandTotal', 'count'));
    }
}
