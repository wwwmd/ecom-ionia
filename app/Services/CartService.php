<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart(bool $create = true): ?Cart
    {
        $user = auth()->user();
        $sessionId = Session::getId();

        if ($user) {
            $userCart = Cart::where('user_id', $user->id)->first();
            $sessionCart = Cart::where('session_id', $sessionId)
                ->where('id', '!=', $userCart->id ?? 0)
                ->whereHas('items')
                ->latest()
                ->first();

            if ($sessionCart) {
                if ($userCart) {
                    foreach ($sessionCart->items as $sItem) {
                        $existing = $userCart->items()
                            ->where('product_id', $sItem->product_id)
                            ->where('variant_id', $sItem->variant_id)
                            ->first();
                        if ($existing) {
                            $existing->increment('quantity', $sItem->quantity);
                        } else {
                            $sItem->update(['cart_id' => $userCart->id]);
                        }
                    }
                    if (!$userCart->coupon_code && $sessionCart->coupon_code) {
                        $userCart->update(['coupon_code' => $sessionCart->coupon_code]);
                    }
                    $sessionCart->delete();
                    return $userCart->fresh(['items']);
                } else {
                    $sessionCart->update(['user_id' => $user->id]);
                    return $sessionCart->fresh(['items']);
                }
            }

            if ($userCart) {
                return $userCart;
            }

            return $create ? Cart::findOrCreateFor($user) : null;
        }

        return $create ? Cart::findOrCreateFor(null, $sessionId) : Cart::where('session_id', $sessionId)->first();
    }

    public function add(Product $product, int $qty = 1, ?int $variantId = null): CartItem
    {
        $cart = $this->getCart();

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('variant_id', $variantId)
            ->first();

        if ($item) {
            $item->increment('quantity', $qty);
            return $item->fresh();
        }

        $price = $product->price;
        if ($variantId && ($v = $product->variants()->find($variantId))) {
            $price = $v->price;
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'variant_id' => $variantId,
            'quantity' => $qty,
            'price' => $price,
        ]);
    }

    public function updateItem(int $itemId, int $qty): ?CartItem
    {
        $cart = $this->getCart(false);
        if (!$cart) return null;
        $item = $cart->items()->find($itemId);
        if (!$item) return null;
        $item->update(['quantity' => max(1, $qty)]);
        return $item->fresh();
    }

    public function removeItem(int $itemId): bool
    {
        $cart = $this->getCart(false);
        if (!$cart) return false;
        return (bool) $cart->items()->where('id', $itemId)->delete();
    }

    public function applyCoupon(string $code): array
    {
        $cart = $this->getCart();
        $subtotal = $cart->subtotal();
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid($subtotal)) {
            return ['success' => false, 'message' => 'Invalid or expired coupon.'];
        }

        $cart->update(['coupon_code' => $code]);
        $discount = $coupon->calculateDiscount($subtotal);

        return [
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => $discount,
        ];
    }

    public function totals(): array
    {
        $cart = $this->getCart();
        $items = $cart->items()->with('product')->get();
        $subtotal = $items->sum(fn ($i) => $i->price * $i->quantity);

        $discount = 0;
        if ($cart->coupon_code && ($coupon = Coupon::where('code', $cart->coupon_code)->first())) {
            if ($coupon->isValid($subtotal)) $discount = $coupon->calculateDiscount($subtotal);
        }

        $taxRate = (float) Setting::get('tax_rate', 18);
        $tax = round((($subtotal - $discount) * $taxRate) / 100, 2);

        $shippingFlat = (float) Setting::get('shipping_flat_rate', 49);
        $freeShippingMin = (float) Setting::get('free_shipping_min', 999);
        $shipping = ($subtotal - $discount) >= $freeShippingMin ? 0 : $shippingFlat;

        $grandTotal = round($subtotal - $discount + $tax + $shipping, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'tax' => $tax,
            'shipping' => $shipping,
            'grand_total' => $grandTotal,
            'items' => $items,
            'count' => $items->sum('quantity'),
        ];
    }

    public function clear(): void
    {
        $cart = $this->getCart(false);
        if ($cart) {
            $cart->items()->delete();
            $cart->update(['coupon_code' => null]);
        }
    }
}
