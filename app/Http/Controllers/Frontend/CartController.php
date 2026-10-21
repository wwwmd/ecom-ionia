<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $totals = $this->cart->totals();

        return view('cart.index', [
            'cartItems' => $totals['items'],
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'tax' => $totals['tax'],
            'shipping' => $totals['shipping'],
            'grandTotal' => $totals['grand_total'],
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
        ]);

        $product = Product::active()->findOrFail($data['product_id']);

        if ($product->manage_stock && $product->stock < $data['quantity']) {
            return response()->json(['success' => false, 'message' => 'Out of stock'], 422);
        }

        $this->cart->add($product, $data['quantity'], $data['variant_id'] ?? null);
        $totals = $this->cart->totals();

        return response()->json([
            'success' => true,
            'message' => 'Added to cart',
            'count' => (int) $totals['count'],
            'total' => (float) $totals['grand_total'],
            'subtotal' => (float) $totals['subtotal'],
            'discount' => (float) $totals['discount'],
            'tax' => (float) $totals['tax'],
            'shipping' => (float) $totals['shipping'],
            'grand_total' => (float) $totals['grand_total'],
        ]);
    }

    public function buyNow(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
        ]);

        $product = Product::active()->findOrFail($data['product_id']);

        if ($product->manage_stock && $product->stock < $data['quantity']) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Out of stock'], 422);
            }
            return back()->with('error', 'Product is out of stock.');
        }

        $this->cart->add($product, $data['quantity'], $data['variant_id'] ?? null);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Proceeding to checkout',
                'redirect' => url('/checkout'),
            ]);
        }

        return redirect()->to(url('/checkout'));
    }

    public function data()
    {
        $totals = $this->cart->totals();

        return response()->json([
            'success' => true,
            'count' => $totals['count'],
            'subtotal' => (float) $totals['subtotal'],
            'discount' => (float) $totals['discount'],
            'tax' => (float) $totals['tax'],
            'shipping' => (float) $totals['shipping'],
            'grand_total' => (float) $totals['grand_total'],
            'items' => $totals['items']->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product ? $item->product->name : 'Product',
                    'price' => (float) $item->price,
                    'quantity' => (int) $item->quantity,
                    'line_total' => (float) ($item->price * $item->quantity),
                    'thumbnail' => $item->product ? \App\Models\Product::resolveImageUrl($item->product->thumbnail) : null,
                    'url' => $item->product ? url('product-details/' . $item->product->slug) : '#',
                ];
            }),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = $this->cart->updateItem($data['id'], $data['quantity']);
        if (!$item) return response()->json(['success' => false], 404);

        $totals = $this->cart->totals();

        return response()->json([
            'success' => true,
            'lineTotal' => (float) ($item->price * $item->quantity),
            'subtotal' => (float) $totals['subtotal'],
            'discount' => (float) $totals['discount'],
            'tax' => (float) $totals['tax'],
            'shipping' => (float) $totals['shipping'],
            'grandTotal' => (float) $totals['grand_total'],
            'count' => (int) $totals['count'],
        ]);
    }

    public function remove(Request $request)
    {
        $data = $request->validate(['id' => 'required|integer']);
        $this->cart->removeItem($data['id']);
        $totals = $this->cart->totals();

        return response()->json([
            'success' => true,
            'subtotal' => (float) $totals['subtotal'],
            'discount' => (float) $totals['discount'],
            'tax' => (float) $totals['tax'],
            'shipping' => (float) $totals['shipping'],
            'grandTotal' => (float) $totals['grand_total'],
            'count' => (int) $totals['count'],
        ]);
    }

    public function count()
    {
        $totals = $this->cart->totals();
        return response()->json([
            'count' => $totals['count'],
            'total' => (float) $totals['grand_total'],
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate(['code' => 'required|string|max:60']);
        $result = $this->cart->applyCoupon($data['code']);

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        $totals = $this->cart->totals();

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => (float) $result['discount'],
            'subtotal' => (float) $totals['subtotal'],
            'tax' => (float) $totals['tax'],
            'shipping' => (float) $totals['shipping'],
            'grandTotal' => (float) $totals['grand_total'],
            'count' => (int) $totals['count'],
        ]);
    }
}
