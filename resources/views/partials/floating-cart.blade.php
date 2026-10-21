@php
    $cartService = app(\App\Services\CartService::class);
    $cartTotals = $cartService->totals();
    $cartCount = (int) ($cartTotals['count'] ?? 0);
@endphp

<!-- ============================================================
     FLOATING SHOPPING CART BUTTON (RIGHT CENTER ALIGNMENT)
     Navigates directly to /cart page without sliding drawer
     ============================================================ -->
<a href="{{ url('/cart') }}" class="sc-floating-cart-btn" id="floatingCartBtn" aria-label="View Shopping Cart" title="View Shopping Cart">
    <div class="sc-floating-inner">
        <i class="fa-solid fa-cart-shopping sc-cart-icon"></i>
        <span class="sc-floating-badge cart-count-badge" id="floatingCartBadge" style="{{ $cartCount > 0 ? '' : 'display:none;' }}">
            {{ $cartCount }}
        </span>
    </div>
</a>

<style>
.sc-floating-cart-btn {
    position: fixed;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1045;
    background: #ffffff;
    border: 1px solid #dcdfe4;
    border-right: none;
    border-radius: 40px 0 0 40px;
    padding: 0;
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: -3px 4px 18px rgba(0, 0, 0, 0.16);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, background 0.2s ease;
    text-decoration: none;
    outline: none;
}
.sc-floating-cart-btn:hover {
    transform: translateY(-50%) translateX(-4px);
    background: #fbfbfb;
    box-shadow: -5px 6px 22px rgba(0, 0, 0, 0.22);
    text-decoration: none;
}
.sc-floating-inner {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}
.sc-cart-icon {
    font-size: 20px;
    color: #1a1a1a;
    transition: transform 0.2s ease;
}
.sc-floating-cart-btn:hover .sc-cart-icon {
    transform: scale(1.1);
    color: #000000;
}
.sc-floating-badge {
    position: absolute;
    top: 3px;
    right: 6px;
    background: #e53935;
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    line-height: 1;
    min-width: 19px;
    height: 19px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    box-shadow: 0 2px 5px rgba(229, 57, 53, 0.45);
    border: 2px solid #ffffff;
    pointer-events: none;
}
.sc-pulse-anim {
    animation: scButtonPulse 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes scButtonPulse {
    0% { transform: translateY(-50%) scale(1); }
    40% { transform: translateY(-50%) scale(1.22); background: #fef2f2; }
    100% { transform: translateY(-50%) scale(1); }
}
</style>

<script>
window.updateCartBadge = function(count) {
    count = parseInt(count, 10) || 0;
    document.querySelectorAll('.cart-count-badge, #floatingCartBadge, #headerCartCount').forEach(badge => {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    });
};
window.pulseFloatingCart = function() {
    const btn = document.getElementById('floatingCartBtn');
    if (!btn) return;
    btn.classList.remove('sc-pulse-anim');
    void btn.offsetWidth;
    btn.classList.add('sc-pulse-anim');
    setTimeout(() => btn.classList.remove('sc-pulse-anim'), 800);
};
</script>
