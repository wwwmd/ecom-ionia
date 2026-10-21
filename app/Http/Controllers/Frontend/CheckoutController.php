<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private OrderService $orders
    ) {}

    public function index()
    {
        $totals = $this->cart->totals();
        $cartItems = $totals['items'];
        $subtotal = $totals['subtotal'];
        $discount = $totals['discount'] ?? 0;
        $tax = $totals['tax'];
        $shipping = $totals['shipping'];
        $grandTotal = $totals['grand_total'];
        $count = $totals['count'];

        return view('home.checkout', compact('cartItems', 'subtotal', 'discount', 'tax', 'shipping', 'grandTotal', 'count'));
    }

    public function place(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:25',
            'email' => 'required|email|max:150',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pincode' => 'required|string|max:20',
        ];

        if (!auth()->check() && $request->filled('create_account')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        if (!$request->filled('same_as_billing')) {
            $rules['shipping_address'] = 'nullable|string|max:255';
            $rules['shipping_country'] = 'nullable|string|max:100';
            $rules['shipping_state'] = 'nullable|string|max:100';
            $rules['shipping_city'] = 'nullable|string|max:100';
            $rules['shipping_pincode'] = 'nullable|string|max:20';
        }

        $validated = $request->validate($rules, [
            'name.required' => 'The Full Name field is required.',
            'phone.required' => 'The Mobile field is required.',
            'email.required' => 'The Email field is required.',
            'address.required' => 'The Address field is required.',
            'country.required' => 'The Country field is required.',
            'state.required' => 'The State field is required.',
            'city.required' => 'The City field is required.',
            'pincode.required' => 'The Pin Code field is required.',
            'password.required' => 'The Password field is required.',
        ]);

        try {
            // 1. Locate Cart BEFORE any session or auth changes
            $cartId = $request->input('cart_id');
            $currentSessionId = session()->getId();

            $cart = null;
            if ($cartId) {
                $cart = \App\Models\Cart::with('items.product')->find($cartId);
            }
            if (!$cart || $cart->items->isEmpty()) {
                $cart = \App\Models\Cart::where('session_id', $currentSessionId)
                    ->whereHas('items')
                    ->latest()
                    ->first();
            }
            if (!$cart || $cart->items->isEmpty()) {
                if (auth()->check()) {
                    $cart = \App\Models\Cart::where('user_id', auth()->id())
                        ->whereHas('items')
                        ->latest()
                        ->first();
                }
            }
            if (!$cart || $cart->items->isEmpty()) {
                $cart = \App\Models\Cart::whereHas('items')->latest()->first();
            }

            // Get Customer Role ID
            $customerRole = \App\Models\Role::where('slug', 'customer')->first();
            $roleId = $customerRole ? $customerRole->id : 5;

            // Determine User
            if (auth()->check()) {
                $user = auth()->user();
            } elseif ($request->filled('create_account')) {
                $user = \App\Models\User::where('email', $validated['email'])->first();
                if ($user) {
                    $user->update([
                        'name' => $validated['name'],
                        'phone' => $validated['phone'],
                        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                    ]);
                } else {
                    $user = \App\Models\User::create([
                        'role_id' => $roleId,
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'],
                        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                        'status' => 'active',
                    ]);
                    if (!$user->customerProfile) {
                        $user->customerProfile()->create([]);
                    }
                }
                auth()->login($user);
            } else {
                $user = \App\Models\User::where('email', $validated['email'])->first();
                if (!$user) {
                    $user = \App\Models\User::create([
                        'role_id' => $roleId,
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'],
                        'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                        'status' => 'active',
                    ]);
                    if (!$user->customerProfile) {
                        $user->customerProfile()->create([]);
                    }
                }
            }

            // Link the identified cart to this user
            if ($cart) {
                $cart->update([
                    'user_id' => $user->id,
                    'session_id' => session()->getId(),
                ]);
            }

            $billingAddress = [
                'line1' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'pincode' => $validated['pincode'],
                'country' => $validated['country'],
            ];

            if ($request->filled('same_as_billing') || empty($request->shipping_address)) {
                $shippingAddress = $billingAddress;
                $shippingName = $validated['name'];
            } else {
                $shippingAddress = [
                    'line1' => $request->input('shipping_address', $validated['address']),
                    'city' => $request->input('shipping_city', $validated['city']),
                    'state' => $request->input('shipping_state', $validated['state']),
                    'pincode' => $request->input('shipping_pincode', $validated['pincode']),
                    'country' => $request->input('shipping_country', $validated['country']),
                ];
                $shippingName = $request->input('shipping_name', $validated['name']);
            }

            $order = $this->orders->placeOrder($user, [
                'cart_id' => $cart?->id,
                'name' => $shippingName,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'shipping_method' => $request->input('shipping_method', 'express'),
                'payment_method' => $request->input('payment_method', 'cod'),
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
            ]);

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Order placed successfully!');

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Checkout Place Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
