<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private CartService $cart) {}

    public function placeOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $cart = null;

            // 1. Check if explicit cart_id was passed
            if (!empty($data['cart_id'])) {
                $cart = \App\Models\Cart::with('items.product')->find($data['cart_id']);
            }

            // 2. Check through CartService
            if (!$cart || $cart->items->isEmpty()) {
                $cart = $this->cart->getCart(false);
            }

            // 3. Check by user_id or current session_id
            if (!$cart || $cart->items->isEmpty()) {
                $sessionId = \Illuminate\Support\Facades\Session::getId();
                $cart = \App\Models\Cart::where(function ($q) use ($user, $sessionId) {
                    $q->where('user_id', $user->id)->orWhere('session_id', $sessionId);
                })->whereHas('items')->latest()->first();
            }

            // 4. Fallback: most recent active cart with items
            if (!$cart || $cart->items->isEmpty()) {
                $cart = \App\Models\Cart::whereHas('items')->latest()->first();
            }

            if (!$cart || $cart->items->isEmpty()) {
                throw new \RuntimeException('Cart is empty.');
            }

            // Ensure the cart belongs to this user
            if ($cart->user_id !== $user->id) {
                $cart->update(['user_id' => $user->id]);
            }

            // Calculate totals directly from the cart items
            $items = $cart->items()->with('product')->get();
            $subtotal = (float) $items->sum(fn ($i) => $i->price * $i->quantity);

            $discount = 0;
            if ($cart->coupon_code && ($coupon = Coupon::where('code', $cart->coupon_code)->first())) {
                if ($coupon->isValid($subtotal)) {
                    $discount = (float) $coupon->calculateDiscount($subtotal);
                }
            }

            $taxRate = (float) Setting::get('tax_rate', 18);
            $tax = round((($subtotal - $discount) * $taxRate) / 100, 2);

            $shippingMethod = $data['shipping_method'] ?? 'express';
            if ($shippingMethod === 'express') {
                $shipping = 100.00;
            } else {
                $shippingFlat = (float) Setting::get('shipping_flat_rate', 49);
                $freeShippingMin = (float) Setting::get('free_shipping_min', 999);
                $shipping = ($subtotal - $discount) >= $freeShippingMin ? 0 : $shippingFlat;
            }

            $grandTotal = round($subtotal - $discount + $tax + $shipping, 2);

            $order = Order::create([
                'order_number' => Order::generateNumber(),
                'user_id' => $user->id,
                'status' => 'pending',
                'payment_status' => ($data['payment_method'] ?? 'cod') === 'cod' ? 'cod_pending' : 'pending',
                'payment_method' => $data['payment_method'] ?? 'cod',
                'shipping_method' => $shippingMethod,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'grand_total' => $grandTotal,
                'customer_name' => $data['name'] ?? $user->name,
                'customer_email' => $data['email'] ?? $user->email,
                'customer_phone' => $data['phone'] ?? $user->phone,
                'shipping_address' => $data['shipping_address'] ?? [],
                'billing_address' => $data['billing_address'] ?? [],
                'customer_note' => $data['note'] ?? null,
                'coupon_code' => $cart->coupon_code,
            ]);

            foreach ($cart->items as $ci) {
                $product = $ci->product;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $ci->variant_id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $ci->quantity,
                    'price' => $ci->price,
                    'total' => $ci->price * $ci->quantity,
                ]);

                if ($product->manage_stock) {
                    $product->decrement('stock', $ci->quantity);
                    $product->increment('sold_count', $ci->quantity);
                }
            }

            if ($cart->coupon_code && ($coupon = Coupon::where('code', $cart->coupon_code)->first())) {
                $coupon->increment('used_count');
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'discount_amount' => $discount,
                ]);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'note' => 'Order placed',
                'changed_by' => $user->id,
                'changed_by_type' => 'customer',
            ]);

            Invoice::create([
                'invoice_number' => 'INV-'.strtoupper(now()->format('ymd')).'-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
                'order_id' => $order->id,
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $grandTotal,
                'issued_at' => now(),
            ]);

            Payment::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'gateway' => $data['payment_method'] ?? 'cod',
                'amount' => $grandTotal,
                'status' => 'pending',
            ]);

            // Update customer profile stats
            if ($user->customerProfile) {
                $user->customerProfile->increment('total_orders');
                $user->customerProfile->increment('total_spent', $order->grand_total);
            }

            if ($cart) {
                $cart->items()->delete();
                $cart->update(['coupon_code' => null]);
            }
            $this->cart->clear();

            return $order;
        });
    }

    public function updateStatus(Order $order, string $status, ?string $note = null, ?User $by = null): void
    {
        DB::transaction(function () use ($order, $status, $note, $by) {
            $order->update([
                'status' => $status,
                'shipped_at' => $status === 'shipped' ? now() : $order->shipped_at,
                'delivered_at' => $status === 'delivered' ? now() : $order->delivered_at,
                'cancelled_at' => $status === 'cancelled' ? now() : $order->cancelled_at,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $status,
                'note' => $note,
                'changed_by' => $by?->id,
                'changed_by_type' => $by ? ($by->isAgent() ? 'agent' : 'admin') : 'system',
            ]);

            if ($status === 'delivered' && $order->payment_method === 'cod') {
                $order->update(['payment_status' => 'paid', 'paid_amount' => $order->grand_total]);
                $order->payments()->latest()->first()?->update(['status' => 'success', 'paid_at' => now()]);
            }
        });
    }
}
