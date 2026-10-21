/* ============================================================
   store ADMIN — JS
   ============================================================ */
(function ($) {
    'use strict';

    const BASE = window.Z_CONFIG?.baseUrl || '';
    const CSRF = window.Z_CONFIG?.csrf || '';

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' } });

    /* Sidebar toggle */
    $(document).on('click', '#toggleSidebar', function () {
        if (window.innerWidth >= 992) {
            $('.z-sidebar').toggleClass('collapsed');
        } else {
            $('.z-sidebar').addClass('open');
            $('#sbBackdrop').addClass('show');
        }
    });
    $(document).on('click', '#closeSidebar, #sbBackdrop', function () {
        $('.z-sidebar').removeClass('open');
        $('#sbBackdrop').removeClass('show');
    });

    /* SweetAlert2 confirm */
 /* ============================================================
   PREMIUM CONFIRM POPUP — Replaces SweetAlert2 for deletes
   ============================================================ */
window.zConfirm = function (title, text, onConfirm, opts) {
    const defaults = {
        title: title || 'Are you sure?',
        message: text || 'This action cannot be undone.',
        confirmText: 'Yes, Delete',
        cancelText: 'Cancel',
        type: 'danger', // danger | warning | primary
        icon: 'bi-exclamation-triangle-fill',
        onConfirm: onConfirm
    };
    const cfg = typeof opts === 'string'
        ? { ...defaults, confirmText: opts }
        : { ...defaults, ...(opts || {}) };

    const $overlay = $('#zPopupContainer');
    if (!$overlay.length) {
        // Fallback to native confirm
        if (window.confirm(cfg.title + '\n\n' + cfg.message)) {
            if (typeof cfg.onConfirm === 'function') cfg.onConfirm();
        }
        return;
    }
    $overlay.empty();

    const $popup = $(`
        <div class="z-popup z-confirm z-confirm-${cfg.type} no-auto" role="dialog" aria-modal="true">
            <button type="button" class="z-popup-close" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="z-popup-icon">
                <i class="bi ${cfg.icon}"></i>
            </div>
            <h3 class="z-popup-title">${cfg.title}</h3>
            ${cfg.message ? `<p class="z-popup-message">${cfg.message}</p>` : ''}
            <div class="z-confirm-actions">
                <button type="button" class="z-popup-action z-confirm-cancel">
                    <i class="bi bi-x-lg"></i> <span>${cfg.cancelText}</span>
                </button>
                <button type="button" class="z-popup-action z-confirm-ok">
                    <i class="bi bi-check2"></i> <span>${cfg.confirmText}</span>
                </button>
            </div>
        </div>
    `);

    $overlay.append($popup);
    $overlay.addClass('show');

    requestAnimationFrame(() => {
        setTimeout(() => $popup.addClass('show'), 20);
    });

    let closed = false;
    function closePopup(confirmed) {
        if (closed) return;
        closed = true;
        $popup.removeClass('show').addClass('hide');
        $overlay.removeClass('show');
        setTimeout(() => {
            $popup.remove();
            if (confirmed && typeof cfg.onConfirm === 'function') cfg.onConfirm();
        }, 280);
    }

    $popup.find('.z-confirm-ok').on('click', () => closePopup(true));
    $popup.find('.z-confirm-cancel, .z-popup-close').on('click', () => closePopup(false));
    $overlay.on('click', function (e) { if (e.target === this) closePopup(false); });
    $(document).on('keydown.zconfirm', function (e) {
        if (e.key === 'Escape') { closePopup(false); $(document).off('keydown.zconfirm'); }
        if (e.key === 'Enter')  { closePopup(true);  $(document).off('keydown.zconfirm'); }
    });

    // Focus confirm button
    setTimeout(() => $popup.find('.z-confirm-ok').focus(), 350);
};

    /* Generic delete */
    $(document).on('click', '[data-delete]', function (e) {
        e.preventDefault();
        const $el = $(this);
        const url = $el.data('delete');
        zConfirm('Delete this item?', 'This action cannot be undone.', function () {
            $.ajax({ url, method: 'DELETE' })
                .done(() => { zToast('Deleted', 'success'); setTimeout(() => location.reload(), 600); })
                .fail(xhr => zToast(xhr.responseJSON?.message || 'Delete failed', 'error'));
        });
    });

    /* Export */
    $(document).on('click', '.export-btn', function () {
        zToast('Preparing export...', 'info');
    });

    /* Auto-hide alerts */
    setTimeout(() => $('.alert-dismissible').fadeOut(), 5000);

    /* Global AJAX error */
    $(document).ajaxError(function (e, xhr) {
        if (xhr.status === 419) zToast('Session expired', 'error');
        else if (xhr.status === 403) zToast('Unauthorized', 'error');
        else if (xhr.status === 422) {
            const errors = xhr.responseJSON?.errors;
            if (errors) Object.values(errors).flat().forEach(m => zToast(m, 'error'));
        } else if (xhr.status === 500) zToast('Server error', 'error');
    });

})(jQuery);

    /* ============================================================
       PREMIUM CENTER POPUP — Success / Error / Warning / Info
       ============================================================ */
    window.zPopup = function (opts) {
        const defaults = {
            type: 'success',
            title: '',
            message: '',
            buttonText: 'Continue',
            duration: 4000,
            autoDismiss: true,
            onClose: null
        };
        const cfg = { ...defaults, ...opts };

        const icons = {
            success: 'bi-check-lg',
            error: 'bi-x-lg',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-lg'
        };
        const titles = {
            success: 'Success!',
            error: 'Oops! Something went wrong',
            warning: 'Warning',
            info: 'Information'
        };

        const $overlay = $('#zPopupContainer');
        if (!$overlay.length) return console.warn('zPopupContainer not found');
        $overlay.empty();

        const $popup = $(`
            <div class="z-popup z-popup-${cfg.type} ${cfg.autoDismiss ? '' : 'no-auto'}"
                 style="--z-popup-duration:${cfg.duration}ms">
                <button type="button" class="z-popup-close" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="z-popup-icon">
                    <i class="bi ${icons[cfg.type] || icons.info}"></i>
                </div>
                <h3 class="z-popup-title">${cfg.title || titles[cfg.type]}</h3>
                ${cfg.message ? `<p class="z-popup-message">${cfg.message}</p>` : ''}
                <button type="button" class="z-popup-action">
                    <i class="bi bi-check2"></i> <span>${cfg.buttonText}</span>
                </button>
                ${cfg.autoDismiss ? '<div class="z-popup-progress"><i></i></div>' : ''}
            </div>
        `);

        $overlay.append($popup);
        $overlay.addClass('show');

        requestAnimationFrame(() => {
            setTimeout(() => $popup.addClass('show'), 20);
        });

        let closeTimer = null;
        function closePopup() {
            if (closeTimer) clearTimeout(closeTimer);
            $popup.removeClass('show').addClass('hide');
            $overlay.removeClass('show');
            setTimeout(() => {
                $popup.remove();
                if (typeof cfg.onClose === 'function') cfg.onClose();
            }, 280);
        }

        $popup.find('.z-popup-close, .z-popup-action').on('click', closePopup);
        $overlay.on('click', function (e) { if (e.target === this) closePopup(); });
        $(document).on('keydown.zpopup', function (e) {
            if (e.key === 'Escape') { closePopup(); $(document).off('keydown.zpopup'); }
        });

        if (cfg.autoDismiss && cfg.duration > 0) {
            closeTimer = setTimeout(closePopup, cfg.duration);
        }

        return { close: closePopup };
    };

    window.zSuccess = (msg, title = 'Success!', dur = 3500) =>
        zPopup({ type: 'success', title, message: msg, duration: dur });
    window.zError = (msg, title = 'Oops! Something went wrong', dur = 5000) =>
        zPopup({ type: 'error', title, message: msg, duration: dur });
    window.zWarning = (msg, title = 'Warning', dur = 4500) =>
        zPopup({ type: 'warning', title, message: msg, duration: dur });
    window.zInfo = (msg, title = 'Information', dur = 3500) =>
        zPopup({ type: 'info', title, message: msg, duration: dur });


