 @extends('layouts.frontend')
@section('title', 'Products')

@section('content')
  <!-- ================= BREADCRUMB ================= -->
  <nav class="ionp-breadcrumb" aria-label="Breadcrumb">
    <ol class="ionp-breadcrumb-list">
      <li class="ionp-breadcrumb-item">
        <a href="/" class="ionp-breadcrumb-link">Home</a>
      </li>
      <li class="ionp-breadcrumb-sep" aria-hidden="true">/</li>
      <li class="ionp-breadcrumb-item">
        <span class="ionp-breadcrumb-current" aria-current="page">Products</span>
      </li>
    </ol>
  </nav>
 <section class="ionp-section" aria-labelledby="ionpTitle">
    <div class="ionp-container">

      <!-- PAGE TITLE -->
      <header class="omnx-news-header">

        <h1 class="omnx-news-title" id="omnxNewsHeading">Our <em>Products</em></h1>
        {{-- <p class="omnx-news-subtitle">Subscribe for latest news and blog updates from our editor.</p> --}}
      </header>


      <!-- TOOLBAR -->


      <!-- PRODUCT GRID -->
      <div class="ionp-grid" id="ionpGrid">

        @php
            $prodList = (isset($products) && $products->count() > 0)
                ? $products
                : \App\Models\Product::active()->with(['brand', 'category'])->latest()->get();
        @endphp

        @forelse($prodList as $p)
          <article class="ionp-card" data-name="{{ $p->name }}" data-price="{{ $p->price }}">
            <div class="ionp-img-wrap">
              <img class="ionp-img" src="{{ \App\Models\Product::resolveImageUrl($p->thumbnail) ?: asset('public/assets/images/X-BLUE-5-PLATES-5.webp') }}" alt="{{ $p->name }}">
              <div class="ionp-actions">
                <a href="{{ url('product-details/' . $p->slug) }}" class="ionp-action">PRODUCT DETAILS <i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
            <a class="ionp-name" href="{{ url('product-details/' . $p->slug) }}">{{ $p->name }}</a>
            <p class="ionp-price">
              ₹{{ number_format($p->price, 2) }}
              @if(!empty($p->compare_price) && $p->compare_price > $p->price)
                <span class="ionp-price-old">₹{{ number_format($p->compare_price, 2) }}</span>
              @endif
            </p>
          </article>
        @empty
          <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px;">
            <p style="color: #777; font-size: 16px;">No products found.</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>
@endsection
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
