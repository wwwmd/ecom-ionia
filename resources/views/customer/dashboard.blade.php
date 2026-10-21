@extends('layouts.frontend')
@section('title', 'My Dashboard — Ionia')

@section('content')
<div class="container ioncu-dashboard-page py-5">
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
                <a href="{{ url('/customer/dashboard') }}" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ url('/customer/orders') }}"><i class="bi bi-box-seam"></i> My Orders</a>
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
            <h1 class="ioncu-page-title">Welcome back, {{ auth()->user()->name }}</h1>

            {{-- ============================================= --}}
            {{-- STATS GRID --}}
            {{-- ============================================= --}}
            <div class="row g-3 mb-4">
                @php
                    $stats = [
                        ['Total Orders', $stats['total'],     'bi-bag-check',       'blue'],
                        ['Pending',      $stats['pending'],   'bi-hourglass-split', 'orange'],
                        ['Completed',    $stats['completed'], 'bi-check2-circle',   'green'],
                        ['Cancelled',    $stats['cancelled'], 'bi-x-circle',        'red'],
                    ];
                @endphp

                @foreach($stats as $s)
                    <div class="col-6 col-lg-3">
                        <div class="ioncu-stat">
                            <div class="ioncu-stat-icon ioncu-stat-{{ $s[3] }}">
                                <i class="bi {{ $s[2] }}"></i>
                            </div>
                            <div class="ioncu-stat-info">
                                <span class="ioncu-stat-label">{{ $s[0] }}</span>
                                <strong class="ioncu-stat-value">{{ $s[1] }}</strong>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ============================================= --}}
            {{-- RECENT ORDERS --}}
            {{-- ============================================= --}}
            <div class="ioncu-card">
                <div class="ioncu-card-head">
                    <h5 class="ioncu-card-title mb-0">Recent Orders</h5>
                    <a href="{{ url('/customer/orders') }}" class="ioncu-btn-outline">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="ioncu-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recentOrders as $o)
                            <tr>
                                <td><strong>{{ $o->order_number }}</strong></td>
                                <td>{{ $o->created_at->format('d M Y') }}</td>
                                <td><strong class="ioncu-price">₹{{ number_format($o->grand_total) }}</strong></td>
                                <td>
                                    <span class="ioncu-status ioncu-status-{{ $o->status }}">
                                        {{ ucfirst($o->status) }}
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
                                <td colspan="5">
                                    <div class="ioncu-empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>No orders yet</p>
                                        <a href="{{ url('/products') }}" class="ioncu-submit">
                                            <span>START SHOPPING</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ============================================================
       DASHBOARD PAGE — IONIA PREMIUM THEME
       ============================================================ */

    .ioncu-dashboard-page {
        font-family: 'Segoe UI', Roboto, sans-serif;
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

    /* Logout button */
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
       PAGE TITLE
       ============================================================ */
    .ioncu-page-title {
        font-family: 'Georgia', 'Times New Roman', serif;
        font-size: 26px;
        font-weight: 400;
        color: #2b2b2b;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 26px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f0f0f0;
    }

    /* ============================================================
       STATS CARDS
       ============================================================ */
    .ioncu-stat {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #ffffff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 18px 16px;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px -12px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .ioncu-stat::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #d95a0a, #c8372d);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .ioncu-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px -14px rgba(217, 90, 10, 0.25);
        border-color: rgba(217, 90, 10, 0.2);
    }

    .ioncu-stat:hover::before {
        opacity: 1;
    }

    .ioncu-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .ioncu-stat:hover .ioncu-stat-icon {
        transform: scale(1.06) rotate(-4deg);
    }

    /* Colored icon variants */
    .ioncu-stat-blue {
        background: rgba(46, 109, 180, 0.10);
        color: #2e6db4;
    }

    .ioncu-stat-orange {
        background: rgba(217, 90, 10, 0.10);
        color: #d95a0a;
    }

    .ioncu-stat-green {
        background: rgba(38, 166, 154, 0.10);
        color: #1e9e6a;
    }

    .ioncu-stat-red {
        background: rgba(200, 55, 45, 0.10);
        color: #c8372d;
    }

    .ioncu-stat-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .ioncu-stat-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #8a8a8a;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .ioncu-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2b2b2b;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    /* ============================================================
       CARD
       ============================================================ */
    .ioncu-card {
        background: #ffffff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 26px 24px;
        box-shadow: 0 8px 24px -12px rgba(0, 0, 0, 0.06);
        transition: box-shadow 0.3s ease;
    }

    .ioncu-card:hover {
        box-shadow: 0 12px 32px -14px rgba(0, 0, 0, 0.09);
    }

    .ioncu-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f0f0f0;
        flex-wrap: wrap;
        gap: 10px;
    }

    .ioncu-card-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2b2b2b;
        letter-spacing: 0.4px;
        text-transform: uppercase;
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
        padding: 12px 14px;
        text-align: left;
        border-bottom: 1.5px solid #f0f0f0;
        white-space: nowrap;
    }

    .ioncu-table tbody td {
        padding: 16px 14px;
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

    /* Status badges */
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
    .ioncu-status-paid {
        background: rgba(38, 166, 154, 0.10);
        color: #1e7a70;
    }

    .ioncu-status-cancelled,
    .ioncu-status-failed {
        background: rgba(200, 55, 45, 0.10);
        color: #c8372d;
    }

    .ioncu-status-processing,
    .ioncu-status-shipped {
        background: rgba(46, 109, 180, 0.10);
        color: #2e6db4;
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
        padding: 40px 20px;
    }

    .ioncu-empty-state i {
        font-size: 3rem;
        color: #d4d4d4;
        display: block;
        margin-bottom: 12px;
    }

    .ioncu-empty-state p {
        font-size: 0.9rem;
        color: #8a8a8a;
        margin-bottom: 22px;
    }

    /* ============================================================
       BUTTONS
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

    .ioncu-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: transparent;
        border: 1.5px solid #d95a0a;
        color: #d95a0a;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
    }

    .ioncu-btn-outline:hover {
        background: #d95a0a;
        color: #ffffff;
        box-shadow: 0 10px 20px -8px rgba(217, 90, 10, 0.55);
    }

    .ioncu-btn-outline i {
        transition: transform 0.25s ease;
    }

    .ioncu-btn-outline:hover i {
        transform: translateX(3px);
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
            padding: 20px 16px;
        }

        .ioncu-page-title {
            font-size: 18px;
            letter-spacing: 1px;
        }

        .ioncu-stat {
            padding: 14px 12px;
            gap: 10px;
        }

        .ioncu-stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }

        .ioncu-stat-value {
            font-size: 1.25rem;
        }

        .ioncu-stat-label {
            font-size: 0.66rem;
        }

        .ioncu-table thead th,
        .ioncu-table tbody td {
            padding: 12px 10px;
            font-size: 0.8rem;
        }
    }
    .ioncu-dashboard-page {
   margin-top: 100px;
   margin-bottom: 49px;
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
