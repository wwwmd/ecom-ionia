@extends('layouts.frontend')
@section('title', 'Order ' . $order->order_number . ' — Ionia')

@section('content')
@php
    $statusIcon = [
        'pending'          => 'bi-hourglass-split',
        'confirmed'        => 'bi-check-circle',
        'processing'       => 'bi-arrow-repeat',
        'packed'           => 'bi-box-seam',
        'shipped'          => 'bi-truck',
        'out_for_delivery' => 'bi-geo-alt',
        'delivered'        => 'bi-check2-circle',
        'cancelled'        => 'bi-x-circle',
        'returned'         => 'bi-arrow-return-left',
        'refunded'         => 'bi-cash-coin',
    ];

    $stepOrder = ['pending','confirmed','processing','packed','shipped','out_for_delivery','delivered'];
    $currentStep = array_search($order->status, $stepOrder);
    if ($currentStep === false) $currentStep = -1;

    $isCancelled = in_array($order->status, ['cancelled', 'returned', 'refunded']);
@endphp

<div class="container ioncu-order-page py-5">


    <div class="row  order_show g-4">

        {{-- ==================== SIDEBAR ==================== --}}
        <aside class="col-lg-3">
            <div class="ioncu-account-nav">
                <div class="ioncu-account-user">
                    <div class="ioncu-au-avatar">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="ioncu-au-info">
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->email }}</small>
                    </div>
                </div>
                <a href="{{ url('/customer/dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ url('/customer/orders') }}" class="active"><i class="bi bi-box-seam"></i> My Orders</a>
                <a href="{{ url('/wishlist') }}"><i class="bi bi-heart"></i> Wishlist</a>
                <a href="{{ url('/customer/profile') }}"><i class="bi bi-gear"></i> Profile Settings</a>
                <form action="{{ url('/logout') }}" method="POST" class="ioncu-logout-form">
                    @csrf
                    <button class="ioncu-logout-link"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </aside>

        {{-- ==================== MAIN ==================== --}}
        <div class="col-lg-9">

            {{-- ============================================= --}}
            {{-- HERO --}}
            {{-- ============================================= --}}
            <div class="co-hero">
                <div class="co-hero-left">
                    <div class="co-hero-icon"><i class="bi bi-receipt-cutoff"></i></div>
                    <div>
                        <h1>Order Details</h1>
                        <p>
                            <span class="co-hero-meta-code">{{ $order->order_number }}</span>
                            <span>·</span>
                            <span>Placed {{ $order->created_at->format('d M Y, h:i A') }}</span>
                        </p>
                    </div>
                </div>
                <div class="co-hero-actions">
                    <a href="{{ route('customer.orders.invoice', $order) }}" class="co-btn co-btn-primary" target="_blank">
                        <i class="bi bi-download"></i> Invoice
                    </a>
                    <a href="{{ route('customer.orders') }}" class="co-btn co-btn-glass">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- TRACKING TIMELINE --}}
            {{-- ============================================= --}}
            <div class="co-card mb-4">
                <div class="co-card-head">
                    <i class="bi bi-geo-alt-fill"></i>
                    <h5>Order Tracking</h5>
                    <span class="badge">
                        @if($isCancelled)
                            {{ ucfirst($order->status) }}
                        @else
                            Current: {{ ucfirst(str_replace('_',' ', $order->status)) }}
                        @endif
                    </span>
                </div>
                <div class="co-card-body">
                    <div class="co-steps">
                        @foreach($stepOrder as $index => $step)
                            @php
                                $isCompleted = $currentStep > $index;
                                $isActive = $currentStep === $index;
                                $stepLabel = [
                                    'pending'          => 'Placed',
                                    'confirmed'        => 'Confirmed',
                                    'processing'       => 'Processing',
                                    'packed'           => 'Packed',
                                    'shipped'          => 'Shipped',
                                    'out_for_delivery' => 'Out for Delivery',
                                    'delivered'        => 'Delivered',
                                ][$step];
                            @endphp
                            <div class="co-step {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }}">
                                <div class="co-step-dot">
                                    <i class="bi {{ $statusIcon[$step] ?? 'bi-circle' }}"></i>
                                </div>
                                <div class="co-step-label">{{ $stepLabel }}</div>
                            </div>
                        @endforeach
                    </div>

                    @if($isCancelled)
                        <div class="co-cancelled-banner">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                @if($order->status === 'cancelled')
                                    <strong>Order Cancelled</strong>
                                    @if($order->cancellation_reason) — {{ $order->cancellation_reason }} @endif
                                @elseif($order->status === 'returned')
                                    <strong>Order Returned</strong> — Return request has been processed.
                                @elseif($order->status === 'refunded')
                                    <strong>Order Refunded</strong> — Your refund has been processed.
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row g-4">
                {{-- ============================================= --}}
                {{-- LEFT COL: ITEMS + ACTIVITY --}}
                {{-- ============================================= --}}
                <div class="col-xl-7">

                    {{-- Order Items --}}
                    <div class="co-card">
                        <div class="co-card-head">
                            <i class="bi bi-bag-check-fill"></i>
                            <h5>Order Items</h5>
                            <span class="badge">{{ $order->items->count() }}</span>
                        </div>
                        <div class="co-card-body">
                            <div class="co-items-list">
                                @foreach($order->items as $item)
                                    <div class="co-item">
                                        <div class="co-item-img">
                                            <i class="bi bi-image"></i>
                                            @if(!empty($item->product?->thumbnail))
                                                <img src="{{ \App\Models\Product::resolveImageUrl($item->product->thumbnail) }}" alt="" onerror="this.remove()">
                                            @endif
                                        </div>
                                        <div class="co-item-info">
                                            <h6 class="co-item-name">{{ $item->product_name }}</h6>
                                            @if($item->sku)
                                                <span class="co-item-sku">{{ $item->sku }}</span>
                                            @endif
                                            <div class="co-item-meta">
                                                <span><strong>₹{{ number_format($item->price, 2) }}</strong> each</span>
                                                <span class="co-item-qty">× {{ $item->quantity }}</span>
                                            </div>
                                        </div>
                                        <div class="co-item-total">₹{{ number_format($item->total, 2) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Order Activity --}}
                    <div class="co-card">
                        <div class="co-card-head">
                            <i class="bi bi-clock-history"></i>
                            <h5>Order Activity</h5>
                            <span class="badge">{{ $order->statusHistory->count() }}</span>
                        </div>
                        <div class="co-card-body">
                            <div class="co-activity">
                                @foreach($order->statusHistory->sortByDesc('created_at') as $history)
                                    <div class="co-activity-item">
                                        <div class="co-activity-icon">
                                            <i class="bi {{ $statusIcon[$history->status] ?? 'bi-circle' }}"></i>
                                        </div>
                                        <div class="co-activity-content">
                                            <h6>{{ ucfirst(str_replace('_', ' ', $history->status)) }}</h6>
                                            <span class="co-activity-date">
                                                <i class="bi bi-clock"></i> {{ $history->created_at->format('d M Y, h:i A') }}
                                            </span>
                                            @if($history->note)
                                                <p class="co-activity-note">"{{ $history->note }}"</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ============================================= --}}
                {{-- RIGHT COL: SUMMARY, PAYMENT, ADDRESS --}}
                {{-- ============================================= --}}
                <div class="col-xl-5">

                    {{-- Info Summary --}}
                    <div class="co-card">
                        <div class="co-card-head">
                            <i class="bi bi-info-circle-fill"></i>
                            <h5>Summary</h5>
                        </div>
                        <div class="co-card-body p-0">
                            <div class="co-info-list">
                                <div class="co-info-item">
                                    <span>Status</span>
                                    <span class="co-pill co-pill-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                </div>
                                <div class="co-info-item">
                                    <span>Payment</span>
                                    <span class="co-pill co-pill-{{ $order->payment_status }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                                </div>
                                <div class="co-info-item">
                                    <span>Method</span>
                                    <strong>{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</strong>
                                </div>
                                @if($order->tracking_number)
                                    <div class="co-info-item">
                                        <span>Tracking</span>
                                        <strong class="co-mono">{{ $order->tracking_number }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Payment Breakdown --}}
                    <div class="co-card">
                        <div class="co-card-head">
                            <i class="bi bi-cash-stack"></i>
                            <h5>Payment Breakdown</h5>
                        </div>
                        <div class="co-card-body p-0">
                            <div class="co-price-list">
                                <div class="co-price-item">
                                    <span>Subtotal</span>
                                    <strong>₹{{ number_format($order->subtotal, 2) }}</strong>
                                </div>
                                @if($order->discount > 0)
                                    <div class="co-price-item text-success">
                                        <span>Discount</span>
                                        <strong>−₹{{ number_format($order->discount, 2) }}</strong>
                                    </div>
                                @endif
                                <div class="co-price-item">
                                    <span>Tax</span>
                                    <strong>₹{{ number_format($order->tax, 2) }}</strong>
                                </div>
                                <div class="co-price-item">
                                    <span>Shipping</span>
                                    <strong>
                                        @if($order->shipping_cost > 0)
                                            ₹{{ number_format($order->shipping_cost, 2) }}
                                        @else
                                            <span class="text-success">FREE</span>
                                        @endif
                                    </strong>
                                </div>
                                <div class="co-price-total">
                                    <span>Total</span>
                                    <strong>₹{{ number_format($order->grand_total, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Address --}}
                    @if($order->shipping_address)
                        <div class="co-card">
                            <div class="co-card-head">
                                <i class="bi bi-geo-alt-fill"></i>
                                <h5>Shipping Address</h5>
                            </div>
                            <div class="co-card-body">
                                <div class="co-address">
                                    <strong>{{ $order->customer_name }}</strong>
                                    {{ $order->shipping_address['line1'] ?? '' }}<br>
                                    @if(!empty($order->shipping_address['line2']))
                                        {{ $order->shipping_address['line2'] }}<br>
                                    @endif
                                    {{ $order->shipping_address['city'] ?? '' }}@if(!empty($order->shipping_address['state'])), {{ $order->shipping_address['state'] }}@endif
                                    @if(!empty($order->shipping_address['pincode'])) — {{ $order->shipping_address['pincode'] }}@endif
                                    <br>{{ $order->shipping_address['country'] ?? 'India' }}
                                    @if($order->customer_phone)
                                        <br><i class="bi bi-telephone-fill"></i> {{ $order->customer_phone }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Need Help --}}
                    <div class="co-help">
                        <i class="bi bi-headset"></i>
                        <h6>Need Help?</h6>
                        <p>Questions about this order? We're here to help!</p>
                        <a href="{{ url('/customer/support') }}" class="ioncu-submit">
                            <span>CONTACT SUPPORT</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ============================================================
       ORDER DETAILS PAGE — IONIA PREMIUM THEME
       ============================================================ */

    .ioncu-order-page {
        font-family: 'Segoe UI', Roboto, sans-serif;
    }

    /* ============================================================
       BREADCRUMB
       ============================================================ */
    .ioncu-breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.82rem;
        color: #8a8a8a;
        margin-bottom: 26px;
        letter-spacing: 0.2px;
    }

    .ioncu-breadcrumb a {
        color: #8a8a8a;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .ioncu-breadcrumb a:hover {
        color: #d95a0a;
    }

    .ioncu-breadcrumb i {
        font-size: 0.7rem;
        color: #b0b0b0;
    }

    .ioncu-breadcrumb span {
        color: #2b2b2b;
        font-weight: 600;
    }

    /* ============================================================
       SIDEBAR NAV
       ============================================================ */
    .ioncu-account-nav {
        background: #ffffff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 18px 14px;
        position: sticky;
        top: 24px;
        box-shadow: 0 8px 24px -12px rgba(0, 0, 0, 0.06);
    }

    .ioncu-account-user {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 6px 8px 18px;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 12px;
    }

    .ioncu-au-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d95a0a, #c8372d);
        color: #ffffff;
        font-size: 1.15rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
        box-shadow: 0 6px 14px -6px rgba(217, 90, 10, 0.55);
    }

    .ioncu-au-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ioncu-au-info {
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .ioncu-au-info strong {
        font-size: 0.9rem;
        color: #2b2b2b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ioncu-au-info small {
        font-size: 0.75rem;
        color: #8a8a8a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ioncu-account-nav a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        font-size: 0.88rem;
        font-weight: 500;
        color: #4a4a4a;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 4px;
    }

    .ioncu-account-nav a i {
        font-size: 1rem;
        color: #b0b0b0;
        transition: color 0.2s ease;
    }

    .ioncu-account-nav a:hover {
        background: rgba(217, 90, 10, 0.06);
        color: #d95a0a;
    }

    .ioncu-account-nav a:hover i {
        color: #d95a0a;
    }

    .ioncu-account-nav a.active {
        background: linear-gradient(135deg, #d95a0a, #c8372d);
        color: #ffffff;
        box-shadow: 0 8px 18px -8px rgba(217, 90, 10, 0.55);
    }

    .ioncu-account-nav a.active i {
        color: #ffffff;
    }

    .ioncu-logout-form {
        margin-top: 10px;
        padding-top: 14px;
        border-top: 1px solid #f0f0f0;
    }

    .ioncu-logout-link {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 11px 14px;
        font-size: 0.88rem;
        font-weight: 500;
        color: #c8372d;
        background: transparent;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .ioncu-logout-link i {
        font-size: 1rem;
    }

    .ioncu-logout-link:hover {
        background: rgba(200, 55, 45, 0.06);
    }

    /* ============================================================
       HERO
       ============================================================ */
    .co-hero {
        position: relative;
        overflow: hidden;
        padding: 26px 28px;
        margin-bottom: 20px;
        border-radius: 16px;
        background: linear-gradient(135deg, #d95a0a 0%, #c8372d 100%);
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        box-shadow: 0 12px 36px -12px rgba(200, 55, 45, 0.4);
    }

    .co-hero::before {
        content: "";
        position: absolute;
        right: -60px; top: -80px;
        width: 260px; height: 260px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.15), transparent 65%);
        pointer-events: none;
    }

    .co-hero > * { position: relative; z-index: 1; }

    .co-hero-left { display: flex; align-items: center; gap: 16px; min-width: 0; flex: 1; }

    .co-hero-icon {
        width: 62px; height: 62px;
        border-radius: 16px;
        background: rgba(255,255,255,.15);
        border: 1.5px solid rgba(255,255,255,.22);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px;
        color: #fff;
        flex-shrink: 0;
    }

    .co-hero h1 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 6px;
        color: #fff;
        letter-spacing: -.02em;
        line-height: 1.2;
    }

    .co-hero p {
        margin: 0;
        color: rgba(255,255,255,.85);
        font-size: 13.5px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .co-hero-meta-code {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        background: rgba(255,255,255,.2);
        padding: 3px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12.5px;
        color: #fff;
    }

    .co-hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }

    .co-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        cursor: pointer;
        transition: all .22s;
        border: none;
        font-family: inherit;
        white-space: nowrap;
    }

    .co-btn-primary {
        background: #ffffff;
        color: #c8372d;
        box-shadow: 0 4px 12px rgba(0,0,0,.15);
    }

    .co-btn-primary:hover {
        color: #d95a0a;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0,0,0,.2);
    }

    .co-btn-glass {
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        backdrop-filter: blur(12px);
        color: #fff;
    }

    .co-btn-glass:hover {
        background: rgba(255,255,255,.25);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ============================================================
       CARDS
       ============================================================ */
    .co-card {
        background: #ffffff;
        border: 1px solid #ececec;
        border-radius: 12px;
        box-shadow: 0 8px 24px -12px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 20px;
        transition: box-shadow 0.3s ease;
    }

    .co-card:hover {
        box-shadow: 0 12px 32px -14px rgba(0, 0, 0, 0.09);
    }

    .co-card-head {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        background: #fafafa;
    }

    .co-card-head i {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px;
        color: #fff;
        background: linear-gradient(135deg, #d95a0a, #c8372d);
        box-shadow: 0 4px 10px -4px rgba(217, 90, 10, 0.4);
        flex-shrink: 0;
    }

    .co-card-head h5 {
        font-size: 0.92rem;
        font-weight: 700;
        margin: 0;
        color: #2b2b2b;
        flex: 1;
        letter-spacing: 0.3px;
    }

    .co-card-head .badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        background: rgba(217, 90, 10, 0.1);
        color: #d95a0a;
    }

    .co-card-body { padding: 20px; }

    /* ============================================================
       TRACKING TIMELINE
       ============================================================ */
    .co-steps {
        position: relative;
        display: flex;
        justify-content: space-between;
        gap: 4px;
        padding: 0 8px;
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .co-steps::before {
        content: "";
        position: absolute;
        left: 32px; right: 32px;
        top: 17px;
        height: 2px;
        background: #f0f0f0;
        border-radius: 2px;
        z-index: 0;
    }

    .co-steps::after {
        content: "";
        position: absolute;
        left: 32px;
        top: 17px;
        height: 2px;
        background: linear-gradient(90deg, #d95a0a 0%, #c8372d 100%);
        border-radius: 2px;
        transition: width .5s ease;
        z-index: 1;
        width: calc({{ $isCancelled ? 0 : ($currentStep < 0 ? 0 : ($currentStep / (count($stepOrder) - 1))) }} * (100% - 64px));
    }

    .co-step {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 80px;
        text-align: center;
    }

    .co-step-dot {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #b0b0b0;
        font-size: 15px;
        transition: all .3s;
    }

    .co-step.completed .co-step-dot {
        background: linear-gradient(135deg, #d95a0a, #c8372d);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 4px 12px -4px rgba(200, 55, 45, 0.5);
    }

    .co-step.active .co-step-dot {
        background: #fff;
        border-color: #d95a0a;
        color: #d95a0a;
        animation: coPulse 2s infinite;
        box-shadow: 0 0 0 5px rgba(217, 90, 10, 0.15);
    }

    @keyframes coPulse {
        0%, 100% { box-shadow: 0 0 0 5px rgba(217, 90, 10, 0.15); }
        50% { box-shadow: 0 0 0 8px rgba(217, 90, 10, 0.05); }
    }

    .co-step-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #8a8a8a;
        line-height: 1.25;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .co-step.completed .co-step-label,
    .co-step.active .co-step-label {
        color: #2b2b2b;
    }

    /* Cancelled Banner */
    .co-cancelled-banner {
        display: flex;
        gap: 12px;
        align-items: center;
        padding: 14px 16px;
        margin-top: 20px;
        border-radius: 8px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        font-size: 13px;
        color: #991b1b;
        font-weight: 500;
    }

    .co-cancelled-banner i { font-size: 20px; flex-shrink: 0; color: #ef4444; }

    /* ============================================================
       ORDER ITEMS
       ============================================================ */
    .co-items-list { display: flex; flex-direction: column; }

    .co-item {
        display: flex;
        gap: 14px;
        padding: 16px 0;
        border-bottom: 1px solid #f0f0f0;
        align-items: flex-start;
    }

    .co-item:last-child { border-bottom: 0; padding-bottom: 0; }
    .co-item:first-child { padding-top: 0; }

    .co-item-img {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        background: #fafafa;
        border: 1px solid #ececec;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #d4d4d4;
        flex-shrink: 0;
        overflow: hidden;
        position: relative;
    }

    .co-item-img img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .co-item-info { flex: 1; min-width: 0; }

    .co-item-name {
        font-size: 14px;
        font-weight: 600;
        color: #2b2b2b;
        margin: 0 0 4px;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .co-item-sku {
        display: inline-block;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: 10px;
        font-weight: 600;
        color: #8a8a8a;
        background: #f7f7f7;
        padding: 2px 6px;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .co-item-meta {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        font-size: 12px;
        color: #6a6a6a;
        margin-top: 4px;
    }

    .co-item-meta strong { color: #2b2b2b; font-weight: 600; }

    .co-item-qty {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 2px 8px;
        border-radius: 6px;
        background: rgba(217, 90, 10, 0.08);
        color: #d95a0a;
        font-size: 11px;
        font-weight: 700;
    }

    .co-item-total {
        text-align: right;
        font-size: 15px;
        font-weight: 700;
        color: #d95a0a;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* ============================================================
       ORDER ACTIVITY
       ============================================================ */
    .co-activity {
        position: relative;
        padding-left: 18px;
    }

    .co-activity::before {
        content: "";
        position: absolute;
        left: 6px; top: 8px; bottom: 8px;
        width: 2px;
        background: #f0f0f0;
    }

    .co-activity-item {
        position: relative;
        padding-bottom: 22px;
    }

    .co-activity-item:last-child {
        padding-bottom: 0;
    }

    .co-activity-icon {
        position: absolute;
        left: -24px;
        top: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #ececec;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #b0b0b0;
        z-index: 2;
    }

    .co-activity-item:first-child .co-activity-icon {
        border-color: #d95a0a;
        color: #d95a0a;
        box-shadow: 0 0 0 4px rgba(217, 90, 10, 0.1);
    }

    .co-activity-content h6 {
        font-size: 13px;
        font-weight: 600;
        color: #2b2b2b;
        margin: 0 0 2px;
        text-transform: capitalize;
    }

    .co-activity-date {
        font-size: 11px;
        color: #8a8a8a;
    }

    .co-activity-note {
        margin-top: 6px;
        font-size: 12px;
        color: #6a6a6a;
        font-style: italic;
        background: #fafafa;
        padding: 6px 10px;
        border-radius: 6px;
        border-left: 2px solid #d95a0a;
    }

    /* ============================================================
       INFO LIST (Status / Payment)
       ============================================================ */
    .co-info-list { display: flex; flex-direction: column; }

    .co-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-bottom: 1px solid #f6f6f6;
        font-size: 13px;
    }

    .co-info-item:last-child { border-bottom: 0; }

    .co-info-item > span:first-child {
        color: #6a6a6a;
        font-weight: 500;
    }

    .co-info-item > strong {
        color: #2b2b2b;
        font-weight: 600;
    }

    .co-mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: 11px;
        background: #f7f7f7;
        padding: 3px 8px;
        border-radius: 4px;
    }

    /* Pills */
    .co-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        line-height: 1.4;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .co-pill-pending, .co-pill-cod_pending { background: rgba(217, 90, 10, 0.10); color: #d95a0a; }
    .co-pill-confirmed, .co-pill-processing { background: rgba(46, 109, 180, 0.10); color: #2e6db4; }
    .co-pill-packed { background: rgba(139, 92, 246, 0.10); color: #7c3aed; }
    .co-pill-shipped, .co-pill-out_for_delivery { background: rgba(139, 92, 246, 0.10); color: #7c3aed; }
    .co-pill-delivered, .co-pill-completed, .co-pill-paid { background: rgba(38, 166, 154, 0.10); color: #1e7a70; }
    .co-pill-cancelled, .co-pill-returned, .co-pill-refunded, .co-pill-failed { background: rgba(200, 55, 45, 0.10); color: #c8372d; }

    /* ============================================================
       PRICE LIST
       ============================================================ */
    .co-price-list { padding: 8px 0; }

    .co-price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        font-size: 13px;
        color: #6a6a6a;
    }

    .co-price-item strong { color: #2b2b2b; font-weight: 600; }

    .co-price-item.text-success,
    .co-price-item .text-success {
        color: #1e7a70 !important;
        font-weight: 600;
    }

    .co-price-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 20px;
        margin-top: 8px;
        background: #fafafa;
        border-top: 1px solid #f0f0f0;
        font-size: 14px;
        font-weight: 600;
        color: #2b2b2b;
    }

    .co-price-total strong {
        font-size: 20px;
        color: #d95a0a;
        font-weight: 800;
    }

    /* ============================================================
       ADDRESS
       ============================================================ */
    .co-address {
        padding: 0;
        font-size: 13px;
        line-height: 1.7;
        color: #6a6a6a;
    }

    .co-address strong {
        display: block;
        color: #2b2b2b;
        font-weight: 600;
        margin-bottom: 4px;
        font-size: 13px;
    }

    .co-address i {
        color: #d95a0a;
        margin-right: 4px;
    }

    /* ============================================================
       HELP CARD
       ============================================================ */
    .co-help {
        padding: 24px 20px;
        background: #fafafa;
        border: 1px solid #ececec;
        border-radius: 12px;
        text-align: center;
    }

    .co-help i {
        font-size: 28px;
        color: #d95a0a;
        display: block;
        margin-bottom: 10px;
    }

    .co-help h6 {
        font-size: 15px;
        font-weight: 700;
        margin: 0 0 6px;
        color: #2b2b2b;
    }

    .co-help p {
        font-size: 12.5px;
        color: #8a8a8a;
        margin: 0 0 16px;
        line-height: 1.5;
    }

    /* ============================================================
       BUTTON — PREMIUM
       ============================================================ */
    .ioncu-submit {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-width: 130px;
        height: 46px;
        padding: 0 28px;
        border: 0;
        font-family: 'Inter', system-ui, sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: #ffffff;
        background: #d95a0a;
        cursor: pointer;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 14px 30px -12px rgba(217, 90, 10, 0.7);
        transition: background .3s ease, letter-spacing .3s ease, box-shadow .3s ease, transform .3s ease;
        text-decoration: none;
    }

    .ioncu-submit:hover {
        background: #c8372d;
        letter-spacing: .20em;
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -14px rgba(200, 55, 45, 0.85);
        color: #ffffff;
    }

    .ioncu-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -130%;
        width: 100%;
        height: 100%;
        background: linear-gradient(100deg, transparent 25%, rgba(255,255,255,0.30) 50%, transparent 75%);
        transition: left 0.75s cubic-bezier(0.2, 0.9, 0.3, 1);
        z-index: 2;
        pointer-events: none;
    }

    .ioncu-submit:hover::before {
        left: 130%;
    }

    .ioncu-submit span {
        position: relative;
        z-index: 3;
    }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 991.98px) {
        .ioncu-account-nav {
            position: static;
            margin-bottom: 20px;
        }

        .co-steps {
            padding-bottom: 14px;
        }

        .co-steps::before, .co-steps::after {
            top: 16px;
        }

        .co-step-dot {
            width: 32px; height: 32px; font-size: 13px;
        }

        .co-step-label {
            font-size: 10px;
        }
    }

    @media (max-width: 575.98px) {
        .co-hero { padding: 20px; flex-direction: column; align-items: stretch; gap: 14px; }
        .co-hero-icon { width: 52px; height: 52px; font-size: 24px; }
        .co-hero h1 { font-size: 18px; }
        .co-hero-actions { width: 100%; display: flex; gap: 10px; }
        .co-hero-actions .co-btn { flex: 1; justify-content: center; }
        .co-card-head { padding: 14px 16px; }
        .co-card-body { padding: 16px; }

        .co-steps { padding: 0; }
        .co-steps::before, .co-steps::after { left: 16px; right: 16px; top: 12px; }
        .co-step-dot { width: 26px; height: 26px; font-size: 11px; border-width: 2px; }
        .co-step-label { font-size: 9px; }
        .co-step { min-width: 50px; }

        .co-item { gap: 10px; }
        .co-item-img { width: 60px; height: 60px; font-size: 20px; }
        .co-item-name { font-size: 13px; }
        .co-item-total { font-size: 13px; }

        .co-price-total { padding: 14px 16px; }
        .co-price-total strong { font-size: 18px; }
    }
    .order_show{
        margin-top: 80px;
        margin-bottom: 40px;
    }
</style>
@endpush
@push('scripts')

  <script>

(() => {
      const header = document.getElementById('owHeader');
      const menuButton = document.getElementById('owMenuButton');
      const drawer = document.getElementById('owSideDrawer');
      const overlay = document.getElementById('owDrawerOverlay');
      const closeButton = document.getElementById('owDrawerClose');
      let lastFocus = null;

      function owOpenDrawer() {
        lastFocus = document.activeElement;
        header.classList.add('ow-menu-open');
        drawer.classList.add('ow-visible');
        overlay.classList.add('ow-visible');
        drawer.setAttribute('aria-hidden', 'false');
        overlay.setAttribute('aria-hidden', 'false');
        menuButton.setAttribute('aria-expanded', 'true');
        menuButton.setAttribute('aria-label', 'Close menu');
        document.body.classList.add('ow-body-locked');
        closeButton.focus();
      }
      function owCloseDrawer() {
        header.classList.remove('ow-menu-open');
        drawer.classList.remove('ow-visible');
        overlay.classList.remove('ow-visible');
        drawer.setAttribute('aria-hidden', 'true');
        overlay.setAttribute('aria-hidden', 'true');
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.setAttribute('aria-label', 'Open menu');
        document.body.classList.remove('ow-body-locked');
        if (lastFocus) lastFocus.focus();
      }

      menuButton.addEventListener('click', () => drawer.classList.contains('ow-visible') ? owCloseDrawer() : owOpenDrawer());
      closeButton.addEventListener('click', owCloseDrawer);
      overlay.addEventListener('click', owCloseDrawer);
      drawer.querySelectorAll('a').forEach(link => link.addEventListener('click', owCloseDrawer));
      document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && drawer.classList.contains('ow-visible')) owCloseDrawer();
      });
      document.getElementById('owNewsletterForm').addEventListener('submit', event => {
        event.preventDefault();
        document.getElementById('owNewsletterMessage').textContent = 'Thanks! Connect this form to your newsletter service to collect subscriptions.';
        event.target.reset();
      });
    })();

        (function() {
      const slider = document.getElementById('heroSlider');
      const slides = Array.from(document.querySelectorAll('.blume-hero-slide'));
      const dots = Array.from(document.querySelectorAll('.blume-hero-dot'));
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const dotsContainer = document.getElementById('dotsContainer');

      let currentIndex = 0;
      let slideInterval = null;
      const SLIDE_DURATION = 6000; // ms, matches dot animation

      // ---- helper: go to slide ----
      function goToSlide(index) {
        // wrap around
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;

        // update slides
        slides.forEach((slide, i) => {
          slide.classList.toggle('blume-hero-active', i === index);
        });

        // update dots
        dots.forEach((dot, i) => {
          dot.classList.toggle('blume-hero-active', i === index);
        });

        currentIndex = index;
        resetTimer();
      }

      // ---- next / prev ----
      function nextSlide() {
        goToSlide(currentIndex + 1);
      }

      function prevSlide() {
        goToSlide(currentIndex - 1);
      }

      // ---- autoplay timer ----
      function startTimer() {
        if (slideInterval) clearInterval(slideInterval);
        slideInterval = setInterval(() => {
          nextSlide();
        }, SLIDE_DURATION);
      }

      function resetTimer() {
        if (slideInterval) {
          clearInterval(slideInterval);
          slideInterval = null;
        }
        // only restart if not paused (we don't have pause now, but keep logic)
        startTimer();
      }

      // pause on hover (optional, nice UX)
      slider.addEventListener('mouseenter', () => {
        slider.classList.add('is-paused');
        if (slideInterval) {
          clearInterval(slideInterval);
          slideInterval = null;
        }
      });
      slider.addEventListener('mouseleave', () => {
        slider.classList.remove('is-paused');
        startTimer();
      });

      // ---- event listeners ----
      prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        prevSlide();
      });

      nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        nextSlide();
      });

      dots.forEach((dot, idx) => {
        dot.addEventListener('click', (e) => {
          e.preventDefault();
          goToSlide(idx);
        });
      });

      // keyboard navigation
      document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
          prevSlide();
        } else if (e.key === 'ArrowRight') {
          nextSlide();
        }
      });

      // ---- init ----
      // ensure first slide is active (already is in markup, but just in case)
      goToSlide(0);
      // start autoplay
      startTimer();

      // Cleanup on page unload (optional)
      window.addEventListener('beforeunload', () => {
        if (slideInterval) clearInterval(slideInterval);
      });
    })();

  </script>
@endpush
