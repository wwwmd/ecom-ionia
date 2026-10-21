@extends('layouts.frontend')
@section('title', 'Shopping Cart —  Store')

@section('content')

@php
    $imgUrl = function ($path) {
        if (!$path) return null;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//'])) return $path;
        $path = ltrim($path, '/');
        if (\Illuminate\Support\Str::startsWith($path, 'storage/') || file_exists(public_path($path))) return asset($path);
        return asset('storage/' . $path);
    };
    $itemCount = $cartItems->sum('quantity');
@endphp

<style>

.cp {
    --cp-primary: #d95a0a;
    --cp-primary-dark: #c8372d;
    --cp-primary-light: #fdf0e9;
    --cp-accent: #d95a0a;
    --cp-ink: #0F1E33;
    --cp-slate: #5A6C8C;
    --cp-muted: #8B9BB4;
    --cp-line: #E3E9F2;
    --cp-bg: #F5F8FC;
    --cp-gradient: linear-gradient(135deg, #d95a0a 0%, #c8372d 100%);
    --cp-gradient-dark: linear-gradient(135deg, #c8372d 0%, #a02a22 100%);

    color: var(--cp-ink);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    -webkit-font-smoothing: antialiased;
    padding: 100px 0 60px;
    background: #fff;
    min-height: 80vh;
}
.cp * { box-sizing: border-box; }

/* ============ EMPTY STATE ============ */
.cp-empty {
    background: #fff;
    border: 1px solid var(--cp-line);
    border-radius: 20px;
    padding: 80px 24px;
    text-align: center;
    box-shadow: 0 4px 16px rgba(15,30,51,.04);
}
.cp-empty-icon {
    width: 100px; height: 100px;
    border-radius: 50%;
    background: var(--cp-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 44px;
    color: #fff;
    margin: 0 auto 24px;
    box-shadow:
        0 12px 32px -8px rgba(200,55,45,.4),
        0 0 0 14px rgba(200,55,45,.08);
    animation: cpFloat 3s ease-in-out infinite;
}
@keyframes cpFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.cp-empty h2 {
    font-size: 24px;
    font-weight: 800;
    color: var(--cp-ink);
    margin: 0 0 8px;
}
.cp-empty p {
    font-size: 14.5px;
    color: var(--cp-slate);
    margin: 0 0 28px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}
.cp-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 22px;
    border-radius: 99px;
    font-weight: 700;
    font-size: 13px;
    text-decoration: none;
    cursor: pointer;
    transition: all .25s cubic-bezier(.4, 0, .2, 1);
    border: none;
    font-family: inherit;
    white-space: nowrap;
}
.cp-btn-primary {
    background: var(--cp-gradient);
    color: #fff;
    box-shadow: 0 4px 14px rgba(200,55,45,.3);
}
.cp-btn-primary:hover {
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(200,55,45,.4);
}
.cp-btn-glass {
    background: #fff;
    border: 1.5px solid var(--cp-line);
    color: var(--cp-slate);
}
.cp-btn-glass:hover {
    border-color: var(--cp-primary);
    color: var(--cp-primary);
    background: var(--cp-primary-light);
}

/* ============ MAIN GRID ============ */
.cp-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
    align-items: start;
}

/* ============ ITEMS CARD ============ */
.cp-items-card {
    background: #fff;
    border: 1px solid var(--cp-line);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(15,30,51,.04);
}
.cp-items-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 18px 22px;
    background: linear-gradient(180deg, #FAFCFD, #fff);
    border-bottom: 1px solid var(--cp-line);
    flex-wrap: wrap;
}
.cp-items-title {
    font-size: 15px;
    font-weight: 800;
    margin: 0;
    color: var(--cp-ink);
    display: flex;
    align-items: center;
    gap: 10px;
}
.cp-items-title i {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: var(--cp-gradient);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    box-shadow: 0 3px 10px rgba(200,55,45,.24);
}
.cp-items-badge {
    padding: 4px 12px;
    border-radius: 99px;
    background: var(--cp-primary-light);
    color: var(--cp-primary-dark);
    font-size: 11.5px;
    font-weight: 800;
    border: 1px solid rgba(200,55,45,.15);
}

/* ============ ITEM ROW ============ */
.cp-item-list { display: flex; flex-direction: column; }
.cp-item {
    display: grid;
    grid-template-columns: 90px 1fr auto auto auto;
    gap: 18px;
    align-items: center;
    padding: 20px 22px;
    border-bottom: 1px solid #F1F5F9;
    transition: all .2s;
}
.cp-item:last-child { border-bottom: 0; }
.cp-item:hover { background: #FAFCFD; }
.cp-item.removing {
    opacity: 0;
    transform: translateX(-20px);
    pointer-events: none;
}

.cp-item-img {
    position: relative;
    width: 90px;
    height: 90px;
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #F5F8FC, #E8F1FB);
    border: 1px solid var(--cp-line);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #B3BCCF;
    font-size: 28px;
    flex-shrink: 0;
    transition: all .25s;
}
.cp-item:hover .cp-item-img {
    border-color: var(--cp-primary);
    box-shadow: 0 6px 16px rgba(200,55,45,.18);
    transform: scale(1.02);
}
.cp-item-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s;
}
.cp-item:hover .cp-item-img img { transform: scale(1.08); }

.cp-item-info { min-width: 0; flex: 1; }
.cp-item-brand {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10.5px;
    font-weight: 800;
    color: var(--cp-primary-dark);
    letter-spacing: .5px;
    text-transform: uppercase;
    margin-bottom: 4px;
}
.cp-item-name {
    display: block;
    font-size: 14px;
    font-weight: 700;
    color: var(--cp-ink);
    text-decoration: none;
    line-height: 1.4;
    margin-bottom: 6px;
    transition: color .15s;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.cp-item-name:hover { color: var(--cp-primary-dark); }
.cp-item-price {
    font-size: 12.5px;
    color: var(--cp-muted);
}
.cp-item-price strong {
    color: var(--cp-ink);
    font-weight: 700;
    font-size: 13px;
}

/* Quantity */
.cp-qty {
    display: inline-flex;
    align-items: center;
    border: 1.5px solid var(--cp-line);
    border-radius: 99px;
    overflow: hidden;
    background: #fff;
    transition: all .2s;
}
.cp-qty:hover { border-color: var(--cp-primary); }
.cp-qty button {
    width: 36px;
    height: 38px;
    border: none;
    background: transparent;
    color: var(--cp-slate);
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .15s;
    padding: 0;
}
.cp-qty button:hover {
    background: var(--cp-primary-light);
    color: var(--cp-primary-dark);
}
.cp-qty input {
    width: 42px;
    height: 38px;
    border: none;
    text-align: center;
    font-size: 14px;
    font-weight: 800;
    color: var(--cp-ink);
    background: transparent;
    outline: none;
    -moz-appearance: textfield;
    font-family: inherit;
}
.cp-qty input::-webkit-outer-spin-button,
.cp-qty input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

.cp-item-total {
    text-align: right;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 17px;
    font-weight: 800;
    color: var(--cp-primary-dark);
    letter-spacing: -.02em;
    white-space: nowrap;
    min-width: 100px;
}

.cp-item-remove {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: 1.5px solid var(--cp-line);
    background: #fff;
    color: var(--cp-muted);
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .22s;
    flex-shrink: 0;
    padding: 0;
}
.cp-item-remove:hover {
    border-color: #FCA5A5;
    color: #DC2626;
    background: #FEF2F2;
    transform: scale(1.06);
}

/* Trust strip */
.cp-trust {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 16px 22px;
    background: linear-gradient(180deg, #FAFCFD, #fff);
    border-top: 1px solid var(--cp-line);
}
.cp-trust-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--cp-slate);
    padding: 6px 12px;
    border-radius: 99px;
    background: #fff;
    border: 1px solid var(--cp-line);
}
.cp-trust-item i { color: var(--cp-primary); font-size: 12px; }

/* ============ SUMMARY CARD ============ */
.cp-summary {
    background: #fff;
    border: 1px solid var(--cp-line);
    border-radius: 16px;
    overflow: hidden;
    position: sticky;
    top: 100px;
    box-shadow: 0 4px 20px rgba(15,30,51,.06);
}
.cp-summary-head {
    padding: 18px 22px;
    background: var(--cp-gradient);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.cp-summary-head h3 {
    margin: 0;
    font-size: 15px;
    font-weight: 800;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cp-summary-head h3 i { color: #fff; font-size: 15px; }
.cp-summary-head .count {
    padding: 3px 10px;
    border-radius: 99px;
    background: rgba(255,255,255,.2);
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    border: 1px solid rgba(255,255,255,.25);
}
.cp-summary-body { padding: 20px 22px; }

.cp-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    font-size: 13.5px;
    color: var(--cp-slate);
}
.cp-row .label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}
.cp-row .label i {
    width: 16px;
    font-size: 12px;
    color: var(--cp-muted);
    text-align: center;
}
.cp-row .value {
    font-weight: 700;
    color: var(--cp-ink);
    font-size: 13.5px;
}
.cp-row.discount .value { color: var(--cp-primary-dark); }
.cp-row.discount .label i { color: var(--cp-primary-dark); }

.cp-divider {
    height: 1px;
    background-image: linear-gradient(90deg, transparent, var(--cp-line) 20%, var(--cp-line) 80%, transparent);
    margin: 12px 0;
}

/* Total box */
.cp-total-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 18px;
    background: var(--cp-primary-light);
    border: 1px solid rgba(200,55,45,.15);
    border-radius: 12px;
    margin: 12px 0 18px;
}
.cp-total-box .label {
    font-size: 12px;
    font-weight: 800;
    color: var(--cp-primary-dark);
    text-transform: uppercase;
    letter-spacing: .5px;
}
.cp-total-box .value {
    font-size: 22px;
    font-weight: 800;
    color: var(--cp-primary-dark);
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    letter-spacing: -.02em;
}

/* Coupon */
.cp-coupon {
    margin-bottom: 16px;
}
.cp-coupon-label {
    display: block;
    font-size: 12px;
    font-weight: 800;
    color: var(--cp-slate);
    letter-spacing: .3px;
    text-transform: uppercase;
    margin-bottom: 10px;
}
.cp-coupon-form {
    display: flex;
    gap: 8px;
}
.cp-coupon-form input {
    flex: 1;
    height: 42px;
    padding: 0 14px;
    border: 1.5px solid var(--cp-line);
    border-radius: 10px;
    background: #FAFCFD;
    color: var(--cp-ink);
    font-size: 13px;
    font-family: inherit;
    outline: none;
    transition: all .2s;
    min-width: 0;
}
.cp-coupon-form input::placeholder { color: var(--cp-muted); }
.cp-coupon-form input:focus {
    border-color: var(--cp-primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(200,55,45,.1);
}
.cp-coupon-form button {
    height: 42px;
    padding: 0 20px;
    border: none;
    border-radius: 10px;
    background: var(--cp-gradient);
    color: #fff;
    font-weight: 700;
    font-size: 12.5px;
    cursor: pointer;
    transition: all .22s;
    white-space: nowrap;
    font-family: inherit;
}
.cp-coupon-form button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(200,55,45,.35);
}
.cp-coupon-form button:disabled { opacity: .6; cursor: wait; }

.cp-coupon-msg {
    font-size: 12px;
    font-weight: 700;
    padding: 8px 12px;
    border-radius: 8px;
    margin-top: 8px;
    display: none;
}
.cp-coupon-msg.success {
    display: block;
    background: #ECFDF5;
    color: #047857;
    border: 1px solid #A7F3D0;
}
.cp-coupon-msg.error {
    display: block;
    background: #FEF2F2;
    color: #B91C1C;
    border: 1px solid #FECACA;
}

/* Checkout button */
.cp-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    height: 52px;
    border: none;
    border-radius: 99px;
    background: var(--cp-gradient);
    color: #fff;
    text-decoration: none;
    font-size: 14.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .6px;
    box-shadow: 0 8px 24px -4px rgba(200,55,45,.45);
    transition: all .3s cubic-bezier(.4, 0, .2, 1);
    position: relative;
    overflow: hidden;
    font-family: inherit;
}
.cp-checkout::before {
    content: "";
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);
    transition: left .6s ease;
}
.cp-checkout:hover {
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 12px 32px -4px rgba(200,55,45,.55);
}
.cp-checkout:hover::before { left: 100%; }

.cp-continue {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    border-radius: 99px;
    border: 1.5px solid var(--cp-line);
    background: #fff;
    color: var(--cp-slate);
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    margin-top: 10px;
    transition: all .2s;
}
.cp-continue:hover {
    border-color: var(--cp-primary);
    color: var(--cp-primary-dark);
    background: var(--cp-primary-light);
}

/* ============ RESPONSIVE ============ */
@media (max-width: 991.98px) {
    .cp-grid { grid-template-columns: 1fr; }
    .cp-summary { position: static; }
}
@media (max-width: 575.98px) {
    .cp { padding: 80px 0 50px; }

    .cp-item {
        grid-template-columns: 70px 1fr auto;
        grid-template-areas:
            "img info remove"
            "img qty qty"
            "total total total";
        gap: 10px 12px;
        padding: 16px 14px;
    }
    .cp-item-img { grid-area: img; width: 70px; height: 70px; font-size: 22px; border-radius: 10px; }
    .cp-item-info { grid-area: info; }
    .cp-qty { grid-area: qty; justify-self: start; }
    .cp-item-remove { grid-area: remove; }
    .cp-item-total {
        grid-area: total;
        text-align: left;
        font-size: 15px;
        padding-top: 8px;
        border-top: 1px dashed var(--cp-line);
    }

    .cp-items-head { padding: 14px 16px; }
    .cp-items-title { font-size: 14px; }
    .cp-summary-body { padding: 16px 18px; }
    .cp-summary-head { padding: 14px 18px; }
    .cp-total-box .value { font-size: 18px; }
    .cp-checkout { height: 48px; font-size: 13px; }
}

@media (prefers-reduced-motion: reduce) {
    .cp *, .cp-empty-icon {
        transition: none !important;
        animation: none !important;
    }
}
</style>

<div class="cp">
    <div class="container">

        {{-- EMPTY STATE --}}
        @if($cartItems->isEmpty())
            <div class="cp-empty">
                <div class="cp-empty-icon">
                    <i class="bi bi-bag-x"></i>
                </div>
                <h2>Your Cart is Empty</h2>
                <p>Looks like you haven't added anything yet. Explore our collection and find something you love.</p>
                <a href="{{ url('/products') }}" class="cp-btn cp-btn-primary" style="padding: 14px 32px; font-size: 14px;">
                    <i class="bi bi-bag-plus"></i> Start Shopping
                </a>
            </div>
        @else

        {{-- MAIN GRID --}}
        <div class="cp-grid">

            {{-- LEFT: CART ITEMS --}}
            <div>
                <div class="cp-items-card">
                    <div class="cp-items-head">
                        <h3 class="cp-items-title">
                            <i class="bi bi-cart-check-fill"></i>
                            Your Items
                        </h3>
                        <span class="cp-items-badge" id="cpItemCountBadge">
                            {{ $itemCount }} {{ $itemCount == 1 ? 'item' : 'items' }}
                        </span>
                    </div>

                    <div class="cp-item-list" id="cpItemList">
                        @foreach($cartItems as $item)
                            @php
                                $prod = $item->product;
                                $thumb = $imgUrl($prod->thumbnail ?? null);
                                $pUrl = $prod ? url('/product/' . $prod->slug) : '#';
                                $pName = $prod ? $prod->name : 'Product';
                                $brand = $prod && $prod->brand ? $prod->brand->name : null;
                            @endphp
                            <div class="cp-item" data-id="{{ $item->id }}">
                                <div class="cp-item-img">

                                    @if($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $pName }}" onerror="this.remove()">
                                    @endif
                                </div>
                                <div class="cp-item-info">
                                    @if($brand)
                                        <span class="cp-item-brand">
                                            <i class="bi bi-award-fill"></i> {{ $brand }}
                                        </span>
                                    @endif
                                    <a href="{{ $pUrl }}" class="cp-item-name">{{ $pName }}</a>
                                    <div class="cp-item-price">
                                        Unit price: <strong>₹{{ number_format($item->price, 0) }}</strong>
                                    </div>
                                </div>
                                <div class="cp-qty">
                                    <button type="button" class="cpQtyMinus" aria-label="Decrease">−</button>
                                    <input type="number" min="1" max="99" value="{{ $item->quantity }}" class="cpQtyInput" data-id="{{ $item->id }}">
                                    <button type="button" class="cpQtyPlus" aria-label="Increase">+</button>
                                </div>
                                <div class="cp-item-total" data-price="{{ $item->price }}">
                                    ₹{{ number_format($item->price * $item->quantity, 0) }}
                                </div>
                                <button type="button" class="cp-item-remove cpRemoveBtn" data-id="{{ $item->id }}" title="Remove">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    {{-- Trust strip --}}
                    <div class="cp-trust">
                        <div class="cp-trust-item"><i class="bi bi-shield-check"></i> 100% Secure Payment</div>
                        <div class="cp-trust-item"><i class="bi bi-truck"></i> Fast Delivery</div>
                        <div class="cp-trust-item"><i class="bi bi-arrow-repeat"></i> 7-Day Returns</div>
                        <div class="cp-trust-item"><i class="bi bi-headset"></i> 24/7 Support</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: SUMMARY --}}
            <div>
                <div class="cp-summary">
                    <div class="cp-summary-head">
                        <h3><i class="bi bi-receipt"></i> Order Summary</h3>
                        <span class="count" id="cpSummaryCount">{{ $itemCount }} items</span>
                    </div>
                    <div class="cp-summary-body">
                        <div class="cp-row">
                            <span class="label"><i class="bi bi-cart"></i>Subtotal</span>
                            <span class="value" id="cpSubtotal">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="cp-row discount" id="cpDiscountRow" style="{{ ($discount ?? 0) > 0 ? '' : 'display:none;' }}">
                            <span class="label"><i class="bi bi-tag-fill"></i>Discount</span>
                            <span class="value" id="cpDiscount">−₹{{ number_format($discount ?? 0, 2) }}</span>
                        </div>
                        <div class="cp-row">
                            <span class="label"><i class="bi bi-percent"></i>GST</span>
                            <span class="value" id="cpTax">₹{{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="cp-row">
                            <span class="label"><i class="bi bi-truck"></i>Shipping</span>
                            <span class="value" id="cpShipping">
                                @if(($shipping ?? 0) > 0)
                                    ₹{{ number_format($shipping, 2) }}
                                @else
                                    <span style="color:var(--cp-primary-dark);font-weight:800">FREE</span>
                                @endif
                            </span>
                        </div>

                        <div class="cp-divider"></div>

                        <div class="cp-total-box">
                            <span class="label">Grand Total</span>
                            <span class="value" id="cpGrandTotal">₹{{ number_format($grandTotal, 2) }}</span>
                        </div>

                        {{-- Coupon --}}
                        <div class="cp-coupon">
                            <label class="cp-coupon-label">
                                <i class="bi bi-ticket-perforated"></i> Have a coupon?
                            </label>
                            <div class="cp-coupon-form">
                                <input type="text" id="cpCouponInput" placeholder="Enter code" autocomplete="off">
                                <button type="button" id="cpCouponBtn">Apply</button>
                            </div>
                            <div class="cp-coupon-msg" id="cpCouponMsg"></div>
                        </div>

                        {{-- Checkout --}}
                        <a href="{{ url('/checkout') }}" class="cp-checkout">
                            <i class="bi bi-lock-fill"></i>
                            Proceed to Checkout
                            <i class="bi bi-arrow-right"></i>
                        </a>

                        <a href="{{ url('/shop') }}" class="cp-continue">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
    'use strict';

    const $ = (s) => document.querySelector(s);
    const csrfToken = '{{ csrf_token() }}';
    const baseUrl = '{{ url("/") }}';

    const fmt = (val) => '₹' + (parseFloat(val) || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const fmtInt = (val) => '₹' + Math.round(parseFloat(val) || 0).toLocaleString('en-IN');

    /* ---------- Update Summary ---------- */
    function updateSummary(data) {
        if (!data) return;
        const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
        set('cpSubtotal', fmt(data.subtotal));
        set('cpTax', fmt(data.tax));
        set('cpGrandTotal', fmt(data.grandTotal || data.grand_total));
        set('cpDiscount', '−' + fmt(data.discount || 0));

        const discountRow = document.getElementById('cpDiscountRow');
        if (discountRow) discountRow.style.display = (data.discount > 0) ? '' : 'none';

        const shipEl = document.getElementById('cpShipping');
        if (shipEl) {
            shipEl.innerHTML = (data.shipping > 0)
                ? fmt(data.shipping)
                : '<span style="color:var(--cp-primary-dark);font-weight:800">FREE</span>';
        }

        const countBadge = document.getElementById('cpItemCountBadge');
        const summaryCount = document.getElementById('cpSummaryCount');
        if (countBadge) countBadge.textContent = data.count + ' ' + (data.count === 1 ? 'item' : 'items');
        if (summaryCount) summaryCount.textContent = data.count + ' items';

        if (typeof window.updateCartBadge === 'function') window.updateCartBadge(data.count);
        if (typeof window.zbUpdateCartBadge === 'function') window.zbUpdateCartBadge(data.count);
    }

    /* ---------- Quantity Update ---------- */
    let qtyTimer = null;
    function updateQty(id, qty, inputEl) {
        qty = Math.max(1, parseInt(qty, 10) || 1);
        if (inputEl) inputEl.value = qty;

        clearTimeout(qtyTimer);
        qtyTimer = setTimeout(() => {
            fetch(baseUrl + '/api/cart/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ id, quantity: qty })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector('.cp-item[data-id="' + id + '"]');
                    if (row) {
                        const price = parseFloat(row.querySelector('.cp-item-total').dataset.price) || 0;
                        const totEl = row.querySelector('.cp-item-total');
                        if (totEl) totEl.textContent = fmtInt(price * qty);
                    }
                    updateSummary(data);
                    if (typeof window.zbPulseCartFab === 'function') window.zbPulseCartFab();
                    if (typeof window.pulseFloatingCart === 'function') window.pulseFloatingCart();
                }
            })
            .catch(() => {});
        }, 300);
    }

    document.addEventListener('click', function(e) {
        const minus = e.target.closest('.cpQtyMinus');
        const plus = e.target.closest('.cpQtyPlus');
        if (minus || plus) {
            e.preventDefault();
            const wrap = (minus || plus).closest('.cp-qty');
            const input = wrap.querySelector('.cpQtyInput');
            let v = parseInt(input.value, 10) || 1;
            if (minus && v > 1) v--;
            if (plus && v < 99) v++;
            input.value = v;
            updateQty(input.dataset.id, v, input);
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('cpQtyInput')) {
            updateQty(e.target.dataset.id, e.target.value, e.target);
        }
    });

    /* ---------- Remove Item ---------- */
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.cpRemoveBtn');
        if (!btn) return;
        e.preventDefault();
        if (!confirm('Remove this item from your cart?')) return;

        const id = btn.dataset.id;
        const row = btn.closest('.cp-item');
        if (row) row.classList.add('removing');

        fetch(baseUrl + '/api/cart/remove', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ id })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (row) setTimeout(() => row.remove(), 250);
                updateSummary(data);

                const rows = document.querySelectorAll('.cp-item');
                if (rows.length <= 1 || data.count === 0) {
                    setTimeout(() => window.location.reload(), 400);
                }
                if (typeof window.zSuccess === 'function') window.zSuccess('Item removed from cart');
            } else if (row) {
                row.classList.remove('removing');
            }
        })
        .catch(() => { if (row) row.classList.remove('removing'); });
    });

    /* ---------- Coupon ---------- */
    const couponBtn = document.getElementById('cpCouponBtn');
    const couponInput = document.getElementById('cpCouponInput');
    const couponMsg = document.getElementById('cpCouponMsg');

    function applyCoupon() {
        if (!couponInput) return;
        const code = couponInput.value.trim();
        if (!code) {
            if (couponMsg) {
                couponMsg.textContent = 'Please enter a coupon code.';
                couponMsg.className = 'cp-coupon-msg error';
            }
            return;
        }

        if (couponBtn) { couponBtn.disabled = true; couponBtn.textContent = '...'; }

        fetch(baseUrl + '/api/cart/coupon', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ code })
        })
        .then(r => r.json())
        .then(data => {
            if (couponBtn) { couponBtn.disabled = false; couponBtn.textContent = 'Apply'; }
            if (data.success) {
                if (couponMsg) {
                    couponMsg.textContent = '✓ Coupon applied successfully!';
                    couponMsg.className = 'cp-coupon-msg success';
                }
                const dEl = document.getElementById('cpDiscount');
                if (dEl) dEl.textContent = '−' + fmt(data.discount || 0);
                const tEl = document.getElementById('cpGrandTotal');
                if (tEl) tEl.textContent = fmt(data.grandTotal || data.grand_total);
                const dRow = document.getElementById('cpDiscountRow');
                if (dRow && data.discount > 0) dRow.style.display = '';
                if (typeof window.zSuccess === 'function') window.zSuccess('Coupon applied!');
            } else {
                if (couponMsg) {
                    couponMsg.textContent = data.message || 'Invalid coupon code.';
                    couponMsg.className = 'cp-coupon-msg error';
                }
            }
        })
        .catch(() => {
            if (couponBtn) { couponBtn.disabled = false; couponBtn.textContent = 'Apply'; }
            if (couponMsg) {
                couponMsg.textContent = 'Could not apply coupon.';
                couponMsg.className = 'cp-coupon-msg error';
            }
        });
    }

    if (couponBtn) couponBtn.addEventListener('click', applyCoupon);
    if (couponInput) couponInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); applyCoupon(); }
    });

})();
</script>
@endpush
