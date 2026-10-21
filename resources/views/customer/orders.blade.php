@extends('layouts.frontend')
@section('title', 'My Orders — Ionia')

@section('content')
<div class="container ioncu-orders-page py-5">

    {{-- ==================== BREADCRUMB ==================== --}}


    <div class="row g-4">

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
            <div class="ioncu-page-head">
                <h1 class="ioncu-page-title mb-0">My Orders</h1>
                <span class="ioncu-page-count">{{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}</span>
            </div>

            <div class="ioncu-card">
                <div class="table-responsive">
                    <table class="ioncu-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $o)
                            <tr>
                                <td><strong>{{ $o->order_number }}</strong></td>
                                <td>{{ $o->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="ioncu-items-count">
                                        <i class="bi bi-box-seam"></i> {{ $o->items->count() }}
                                    </span>
                                </td>
                                <td><strong class="ioncu-price">₹{{ number_format($o->grand_total) }}</strong></td>
                                <td>
                                    <span class="ioncu-status ioncu-status-{{ $o->payment_status }}">
                                        {{ ucfirst($o->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="ioncu-status ioncu-status-{{ $o->status }}">
                                        {{ ucfirst(str_replace('_',' ',$o->status)) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ url('/customer/orders/'.$o->id) }}" class="ioncu-view-link">
                                        View <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="ioncu-empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>No orders yet</p>
                                        <a href="{{ url('/shop') }}" class="ioncu-submit">
                                            <span>START SHOPPING</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($orders->hasPages())
                    <div class="ioncu-pagination">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ============================================================
       ORDERS PAGE — IONIA PREMIUM THEME
       ============================================================ */

    .ioncu-orders-page {
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
       PAGE HEAD
       ============================================================ */
    .ioncu-page-head {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 26px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f0f0f0;
    }

    .ioncu-page-title {
        font-family: 'Georgia', 'Times New Roman', serif;
        font-size: 26px;
        font-weight: 400;
        color: #2b2b2b;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .ioncu-page-count {
        font-size: 0.78rem;
        font-weight: 600;
        color: #d95a0a;
        background: rgba(217, 90, 10, 0.08);
        padding: 5px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    /* ============================================================
       CARD
       ============================================================ */
    .ioncu-card {
        background: #ffffff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 8px 24px 24px;
        box-shadow: 0 8px 24px -12px rgba(0, 0, 0, 0.06);
        transition: box-shadow 0.3s ease;
    }

    .ioncu-card:hover {
        box-shadow: 0 12px 32px -14px rgba(0, 0, 0, 0.09);
    }

    /* ============================================================
       TABLE
       ============================================================ */
    .ioncu-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }

    .ioncu-table thead th {
        font-size: 0.72rem;
        font-weight: 700;
        color: #8a8a8a;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 18px 14px 12px;
        text-align: left;
        border-bottom: 1.5px solid #f0f0f0;
        white-space: nowrap;
    }

    .ioncu-table tbody td {
        padding: 18px 14px;
        font-size: 0.88rem;
        color: #4a4a4a;
        vertical-align: middle;
        border-bottom: 1px solid #f6f6f6;
        transition: background 0.2s ease;
    }

    .ioncu-table tbody tr {
        transition: background 0.2s ease;
    }

    .ioncu-table tbody tr:hover td {
        background: rgba(217, 90, 10, 0.025);
    }

    .ioncu-table tbody tr:last-child td {
        border-bottom: none;
    }

    .ioncu-table strong {
        color: #2b2b2b;
        font-weight: 600;
    }

    .ioncu-price {
        color: #d95a0a;
        font-weight: 700;
    }

    /* Items count chip */
    .ioncu-items-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.82rem;
        color: #6a6a6a;
        background: #f7f7f7;
        padding: 4px 11px;
        border-radius: 20px;
        font-weight: 500;
    }

    .ioncu-items-count i {
        font-size: 0.85rem;
        color: #b0b0b0;
    }

    /* ============================================================
       STATUS BADGES
       ============================================================ */
    .ioncu-status {
        display: inline-block;
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-radius: 20px;
        white-space: nowrap;
    }

    .ioncu-status-pending {
        background: rgba(217, 90, 10, 0.10);
        color: #d95a0a;
    }

    .ioncu-status-completed,
    .ioncu-status-delivered,
    .ioncu-status-paid,
    .ioncu-status-success {
        background: rgba(38, 166, 154, 0.10);
        color: #1e7a70;
    }

    .ioncu-status-cancelled,
    .ioncu-status-failed {
        background: rgba(200, 55, 45, 0.10);
        color: #c8372d;
    }

    .ioncu-status-processing,
    .ioncu-status-shipped,
    .ioncu-status-out_for_delivery {
        background: rgba(46, 109, 180, 0.10);
        color: #2e6db4;
    }

    .ioncu-status-refunded,
    .ioncu-status-on_hold {
        background: rgba(139, 92, 246, 0.10);
        color: #7c3aed;
    }

    /* View link */
    .ioncu-view-link {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #d95a0a;
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 6px;
        transition: all 0.2s ease;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .ioncu-view-link:hover {
        background: rgba(217, 90, 10, 0.08);
        color: #c8372d;
    }

    .ioncu-view-link i {
        font-size: 1.1rem;
        transition: transform 0.2s ease;
    }

    .ioncu-view-link:hover i {
        transform: translateX(3px);
    }

    /* ============================================================
       EMPTY STATE
       ============================================================ */
    .ioncu-empty-state {
        text-align: center;
        padding: 50px 20px;
    }

    .ioncu-empty-state i {
        font-size: 3.5rem;
        color: #e0e0e0;
        display: block;
        margin-bottom: 14px;
    }

    .ioncu-empty-state p {
        font-size: 0.92rem;
        color: #8a8a8a;
        margin-bottom: 24px;
    }

    /* ============================================================
       BUTTON
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
       PAGINATION
       ============================================================ */
    .ioncu-pagination {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: center;
    }

    .ioncu-pagination .pagination {
        gap: 6px;
        margin: 0;
    }

    .ioncu-pagination .page-link {
        border: 1.5px solid #ececec;
        color: #4a4a4a;
        border-radius: 8px !important;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 8px 14px;
        transition: all 0.2s ease;
        box-shadow: none;
    }

    .ioncu-pagination .page-link:hover {
        background: rgba(217, 90, 10, 0.06);
        border-color: #d95a0a;
        color: #d95a0a;
    }

    .ioncu-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #d95a0a, #c8372d);
        border-color: #d95a0a;
        color: #ffffff;
        box-shadow: 0 8px 18px -8px rgba(217, 90, 10, 0.55);
    }

    .ioncu-pagination .page-item.disabled .page-link {
        color: #c0c0c0;
        border-color: #ececec;
        background: #fafafa;
    }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 991px) {
        .ioncu-account-nav {
            position: static;
            margin-bottom: 20px;
        }

        .ioncu-page-title {
            font-size: 22px;
        }
    }

    @media (max-width: 575px) {
        .ioncu-card {
            padding: 4px 14px 18px;
        }

        .ioncu-page-title {
            font-size: 18px;
            letter-spacing: 1px;
        }

        .ioncu-page-head {
            margin-bottom: 20px;
        }

        .ioncu-table thead th,
        .ioncu-table tbody td {
            padding: 14px 10px;
            font-size: 0.8rem;
        }

        .ioncu-table thead th {
            padding-top: 14px;
            font-size: 0.66rem;
        }

        .ioncu-status {
            font-size: 0.62rem;
            padding: 3px 9px;
        }

        .ioncu-items-count {
            font-size: 0.72rem;
            padding: 3px 8px;
        }

        .ioncu-pagination .page-link {
            padding: 6px 10px;
            font-size: 0.78rem;
        }
    }
    .ioncu-orders-page {

    margin-top: 100px !important;
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
