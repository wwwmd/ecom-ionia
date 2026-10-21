<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->active()
            ->with(['brand', 'category'])
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->category, fn ($q, $c) => $q->where('category_id', $c))
            ->when($request->brand, fn ($q, $b) => $q->where('brand_id', $b))
            ->when($request->min, fn ($q, $m) => $q->where('price', '>=', $m))
            ->when($request->max, fn ($q, $m) => $q->where('price', '<=', $m))
            ->when($request->filter === 'featured', fn ($q) => $q->featured())
            ->when($request->filter === 'trending', fn ($q) => $q->trending())
            ->when($request->filter === 'new', fn ($q) => $q->newArrival())
            ->when($request->filter === 'best', fn ($q) => $q->bestSeller())
            ->when($request->filter === 'deals', fn ($q) => $q->whereNotNull('compare_price'))
            ->when($request->sort === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when($request->sort === 'rating', fn ($q) => $q->orderByDesc('avg_rating'))
            ->when($request->sort === 'popular', fn ($q) => $q->orderByDesc('sold_count'))
            ->when(!$request->sort || $request->sort === 'latest', fn ($q) => $q->latest())
            ->paginate(24)
            ->withQueryString();

        $categories = Category::active()->whereNull('parent_id')->get();
        $brands = Brand::where('status', 'active')->get();

        return view('shop.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        $product->increment('views');

        $product->load([
            'images',
            'variants',
            'brand',
            'category',
            'reviews' => fn ($q) => $q->where('status', 'approved')->latest(),
        ]);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }
}
