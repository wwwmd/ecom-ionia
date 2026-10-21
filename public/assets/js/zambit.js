/* ============================================================
   store STORE — Frontend JS
   ============================================================ */
(function ($) {
    'use strict';

    const BASE = window.Z_CONFIG?.baseUrl || '';
    const CSRF = window.Z_CONFIG?.csrf || '';

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
    });

    /* Toast */
    window.zToast = function (msg, type = 'info') {
        const icons = { success: 'bi-check-circle-fill', error: 'bi-x-circle-fill', info: 'bi-info-circle-fill' };
        const $t = $(`<div class="z-toast ${type}"><i class="bi ${icons[type] || icons.info}"></i><div>${msg}</div></div>`);
        $('#zToastContainer').append($t);
        setTimeout(() => $t.fadeOut(300, () => $t.remove()), 3500);
    };

    /* Header counts */
    function refreshHeaderCounts() {
        $.get(BASE + '/api/cart/count', function (r) {
            $('#cartCount, #mobileCartCount').text(r.count || 0);
            $('#cartTotalHeader').text('₹' + (r.total || 0).toLocaleString());
        }).fail(() => {});
        $.get(BASE + '/api/wishlist/count', function (r) {
            $('#wishlistCount').text(r.count || 0);
        }).fail(() => {});
    }
    refreshHeaderCounts();

    /* Add to cart */
    $(document).on('click', '.add-to-cart-btn, .z-btn-buy', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const id = $btn.data('id');
        const qty = parseInt($('#qty').val() || 1);
        const variantId = $('.z-variant.active').data('id') || null;

        $btn.prop('disabled', true);

        $.post(BASE + '/api/cart/add', { product_id: id, quantity: qty, variant_id: variantId })
            .done(r => {
                if (r.success) {
                    zToast(r.message || 'Added to cart', 'success');
                    refreshHeaderCounts();
                    if ($btn.hasClass('z-btn-buy')) {
                        setTimeout(() => window.location.href = BASE + '/checkout', 500);
                    }
                } else {
                    zToast(r.message || 'Could not add', 'error');
                }
            })
            .fail(xhr => {
                if (xhr.status === 401) {
                    zToast('Please login to add items', 'error');
                    setTimeout(() => window.location.href = BASE + '/login', 800);
                } else {
                    zToast(xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            })
            .always(() => $btn.prop('disabled', false));
    });

    /* Wishlist */
    $(document).on('click', '.wishlist-btn', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const id = $btn.data('id');

        $.post(BASE + '/api/wishlist/toggle', { product_id: id })
            .done(r => {
                if (r.success) {
                    $btn.toggleClass('active', r.added);
                    $btn.find('i').toggleClass('bi-heart bi-heart-fill');
                    zToast(r.message, 'success');
                    refreshHeaderCounts();
                }
            })
            .fail(xhr => {
                if (xhr.status === 401) {
                    zToast('Please login to use wishlist', 'error');
                    setTimeout(() => window.location.href = BASE + '/login', 800);
                }
            });
    });

    /* Cart qty update */
    $(document).on('click', '.z-qty .qty-plus', function () {
        const $inp = $(this).siblings('input');
        const v = parseInt($inp.val()) + 1;
        $inp.val(v);
        if ($inp.hasClass('cart-qty')) updateQty($inp.data('id'), v);
    });
    $(document).on('click', '.z-qty .qty-minus', function () {
        const $inp = $(this).siblings('input');
        const v = Math.max(1, parseInt($inp.val()) - 1);
        $inp.val(v);
        if ($inp.hasClass('cart-qty')) updateQty($inp.data('id'), v);
    });
    $(document).on('change', '.cart-qty', function () {
        updateQty($(this).data('id'), Math.max(1, parseInt($(this).val())));
    });

    function updateQty(id, qty) {
        $.post(BASE + '/api/cart/update', { id, quantity: qty })
            .done(r => {
                if (r.success) {
                    $('.z-cart-item[data-id="' + id + '"] .z-ci-total').text('₹' + r.lineTotal.toLocaleString());
                    $('#subTotal').text('₹' + r.subtotal.toLocaleString());
                    $('#grandTotal').text('₹' + r.grandTotal.toLocaleString());
                    refreshHeaderCounts();
                }
            });
    }

    /* Remove cart item */
    $(document).on('click', '.remove-cart', function () {
        if (!confirm('Remove this item?')) return;
        const id = $(this).data('id');
        $.post(BASE + '/api/cart/remove', { id })
            .done(r => {
                if (r.success) {
                    $('.z-cart-item[data-id="' + id + '"]').fadeOut(300, function () { $(this).remove(); });
                    $('#subTotal').text('₹' + r.subtotal.toLocaleString());
                    $('#grandTotal').text('₹' + r.grandTotal.toLocaleString());
                    refreshHeaderCounts();
                    zToast('Item removed', 'success');
                }
            });
    });

    /* Coupon */
    $(document).on('click', '#applyCoupon', function () {
        const code = $('#couponCode').val().trim();
        if (!code) return;
        $.post(BASE + '/api/cart/coupon', { code })
            .done(r => {
                if (r.success) {
                    $('#discount').text('−₹' + r.discount.toLocaleString());
                    $('#grandTotal').text('₹' + r.grandTotal.toLocaleString());
                    zToast('Coupon applied', 'success');
                }
            })
            .fail(xhr => zToast(xhr.responseJSON?.message || 'Invalid coupon', 'error'));
    });

    /* Newsletter */
    $(document).on('submit', '#newsletterForm', function (e) {
        e.preventDefault();
        const email = $(this).find('input').val();
        zToast('Subscribed successfully!', 'success');
        $(this)[0].reset();
    });

    /* PDP: thumbnail switch */
    $(document).on('click', '.z-thumb', function () {
        $('.z-thumb').removeClass('active');
        $(this).addClass('active');
        $('#mainImage').attr('src', $(this).data('img'));
    });

    /* PDP: variant select */
    $(document).on('click', '.z-variant', function () {
        $('.z-variant').removeClass('active');
        $(this).addClass('active');
    });

    /* AJAX error */
    $(document).ajaxError(function (e, xhr) {
        if (xhr.status === 419) zToast('Session expired. Refresh page.', 'error');
        else if (xhr.status === 403) zToast('Not authorized', 'error');
        else if (xhr.status === 500) zToast('Server error', 'error');
    });

})(jQuery);
