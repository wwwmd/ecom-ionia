@extends('layouts.frontend')
@section('title', 'My Wishlist — Ionia')

@section('content')
<div class="container ioncu-wishlist-page py-5">

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
                <a href="{{ url('/customer/orders') }}"><i class="bi bi-box-seam"></i> My Orders</a>
                <a href="{{ url('/wishlist') }}" class="active"><i class="bi bi-heart"></i> Wishlist</a>
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
                <h1 class="ioncu-page-title mb-0">My Wishlist</h1>
                @if(!$wishlist->isEmpty())
                    <span class="ioncu-page-count">
                        {{ $wishlist->count() }} {{ Str::plural('item', $wishlist->count()) }}
                    </span>
                @endif
            </div>

            @if($wishlist->isEmpty())
                {{-- ============================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ============================================= --}}
                <div class="ioncu-empty-wish">
                    <div class="ioncu-empty-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h4>Your wishlist is empty</h4>
                    <p>Save your favorite products and find them here anytime.</p>
                    <a href="{{ url('/products') }}" class="ioncu-submit">
                        <span>DISCOVER PRODUCTS</span>
                    </a>
                </div>
            @else
                {{-- ============================================= --}}
                {{-- WISHLIST GRID --}}
                {{-- ============================================= --}}
                <div class="row g-3">
                    @foreach($wishlist as $w)
                        <div class="col-6 col-md-4">
                            <div class="ioncu-wish-item">
                                {{-- Remove button --}}
                                <form method="POST" action="{{ url('/wishlist/'.$w->id) }}" class="ioncu-wish-remove" onsubmit="return confirm('Remove from wishlist?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Remove from wishlist">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                </form>

                                {{-- Product card (uses your existing component) --}}
                                <div class="ioncu-wish-product">
                                    <x-product-card :product="$w->product" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ============================================================
       WISHLIST PAGE — IONIA PREMIUM THEME
       ============================================================ */

    .ioncu-wishlist-page {
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
       EMPTY STATE
       ============================================================ */
    .ioncu-empty-wish {
        background: #ffffff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 70px 30px;
        text-align: center;
        box-shadow: 0 8px 24px -12px rgba(0, 0, 0, 0.06);
    }

    .ioncu-empty-icon {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(217, 90, 10, 0.08);
        color: #d95a0a;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 22px;
        font-size: 2.4rem;
        animation: ioncuHeartPulse 2s ease-in-out infinite;
    }

    @keyframes ioncuHeartPulse {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.08); }
    }

    .ioncu-empty-wish h4 {
        font-family: 'Georgia', 'Times New Roman', serif;
        font-size: 1.3rem;
        font-weight: 400;
        color: #2b2b2b;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
    }

    .ioncu-empty-wish p {
        font-size: 0.9rem;
        color: #8a8a8a;
        margin-bottom: 28px;
    }

    /* ============================================================
       WISHLIST ITEM
       ============================================================ */
    .ioncu-wish-item {
        position: relative;
        height: 100%;
        background: #ffffff;
        border: 1px solid #ececec;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px -12px rgba(0, 0, 0, 0.05);
    }

    .ioncu-wish-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #d95a0a, #c8372d);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
    }

    .ioncu-wish-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -18px rgba(217, 90, 10, 0.28);
        border-color: rgba(217, 90, 10, 0.25);
    }

    .ioncu-wish-item:hover::before {
        opacity: 1;
    }

    /* Remove button overlay */
    .ioncu-wish-remove {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 5;
        margin: 0;
    }

    .ioncu-wish-remove button {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.95);
        color: #c8372d;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px -4px rgba(0, 0, 0, 0.18);
        transition: all 0.25s ease;
        backdrop-filter: blur(4px);
    }

    .ioncu-wish-remove button:hover {
        background: #c8372d;
        color: #ffffff;
        transform: scale(1.08);
        box-shadow: 0 8px 18px -6px rgba(200, 55, 45, 0.55);
    }

    .ioncu-wish-remove button:active {
        transform: scale(0.96);
    }

    /* Product card container — override padding if the component brings its own */
    .ioncu-wish-product {
        padding: 4px;
    }

    .ioncu-wish-product > * {
        border: none !important;
        box-shadow: none !important;
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
    @media (max-width: 991px) {
        .ioncu-account-nav {
            position: static;
            margin-bottom: 20px;
        }

        .ioncu-page-title {
            font-size: 22px;
        }

        .ioncu-empty-wish {
            padding: 50px 20px;
        }
    }

    @media (max-width: 575px) {
        .ioncu-page-title {
            font-size: 18px;
            letter-spacing: 1px;
        }

        .ioncu-page-head {
            margin-bottom: 20px;
        }

        .ioncu-empty-icon {
            width: 74px;
            height: 74px;
            font-size: 2rem;
        }

        .ioncu-empty-wish h4 {
            font-size: 1.1rem;
        }

        .ioncu-wish-remove button {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
    }
    .ioncu-wishlist-page {
        margin-top: 100px;
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
