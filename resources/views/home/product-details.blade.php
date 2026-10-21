@extends('layouts.frontend')
@section('title', 'Product-Details')

@section('content')
  <nav class="ionp-breadcrumb" aria-label="Breadcrumb">
    <ol class="ionp-breadcrumb-list">
      <li class="ionp-breadcrumb-item">
        <a href="/" class="ionp-breadcrumb-link">Home</a>
      </li>
      <li class="ionp-breadcrumb-sep" aria-hidden="true">/</li>
      <li class="ionp-breadcrumb-item">
        <span class="ionp-breadcrumb-current" aria-current="page">Products-Details</span>
      </li>
    </ol>
  </nav>

@php
    $product = $product ?? \App\Models\Product::active()->with(['images', 'brand', 'category'])->first();

    $galleryImages = collect();
    if ($product) {
        if ($product->thumbnail) {
            $galleryImages->push(\App\Models\Product::resolveImageUrl($product->thumbnail));
        }
        foreach ($product->images as $gImg) {
            $url = \App\Models\Product::resolveImageUrl($gImg->path);
            if ($url && !$galleryImages->contains($url)) {
                $galleryImages->push($url);
            }
        }
    }
    if ($galleryImages->isEmpty()) {
        $galleryImages = collect([
            asset('public/assets/images/X-BLUE-5-PLATES-5.webp'),
            asset('public/assets/images/EN-7-P-1.webp'),
            asset('public/assets/images/sm-230.1.webp'),
            asset('public/assets/images/TL-112-9PLATES-1.webp'),
        ]);
    }
@endphp

  <section class="ionpd-section" aria-labelledby="ionpdTitle">
    <div class="ionpd-container">
      <div class="ionpd-grid">

        <!-- ============ LEFT: GALLERY ============ -->
        <div class="ionpd-gallery">

          <!-- Main image with cursor-position zoom -->
          <div class="ionpd-main" id="ionpdMain">
            <!-- Base (normal) image -->
            <div class="ionpd-base" id="ionpdBase"></div>
            <!-- Zoom layer (hover par dikhega) -->
            <div class="ionpd-zoom" id="ionpdZoom" aria-hidden="true"></div>
          </div>

          <!-- Thumbnails -->
          <div class="ionpd-thumbs" id="ionpdThumbs">
            @foreach($galleryImages as $index => $gUrl)
              <button class="ionpd-thumb {{ $index === 0 ? 'ionpd-active' : '' }}" type="button"
                      data-img="{{ $gUrl }}">
                <img src="{{ $gUrl }}" alt="View {{ $index + 1 }}">
              </button>
            @endforeach
          </div>
        </div>

        <!-- ============ RIGHT: INFO ============ -->
        <div class="ionpd-info">
          <h1 class="ionpd-title" id="ionpdTitle">{{ $product->name ?? 'Ionia X-BLUE Alkaline' }}</h1>
          <p class="ionpd-desc">
            {{ $product->short_description ?? $product->description ?? "Korea's #1 alkaline water technology, trusted in 60 countries, now in your home." }}
          </p>
          <p class="ionpd-price">
            ₹{{ number_format($product->price ?? 150000, 2) }}
            @if(!empty($product->compare_price) && $product->compare_price > $product->price)
              <span class="ionp-price-old" style="text-decoration:line-through;color:#999;font-size:16px;margin-left:8px">₹{{ number_format($product->compare_price, 2) }}</span>
            @endif
          </p>

          <div class="ionpd-buyrow">
            <div class="ionpd-qty">
              <button class="ionpd-qty-btn" type="button" data-qty="minus" aria-label="Decrease quantity">−</button>
              <input class="ionpd-qty-input" id="ionpdQty" type="text" value="1" inputmode="numeric">
              <button class="ionpd-qty-btn" type="button" data-qty="plus" aria-label="Increase quantity">+</button>
            </div>
            <button class="ionpd-btn" id="buyNowBtn" type="button" data-product-id="{{ $product->id ?? 14 }}">
              <span id="buyNowText">Buy Now</span>
            </button>
          </div>

          <div class="ionpd-mini-row">
            <a class="ionpd-mini" href="#compare"><i class="fa-solid fa-code-compare"></i> COMPARE</a>
            <a class="ionpd-mini" href="#wishlist"><i class="fa-regular fa-heart"></i> ADD TO WISHLIST</a>
          </div>

          <dl class="ionpd-meta">
            <dt>Vendor:</dt>   <dd>{{ $product->brand->name ?? 'Ionia' }}</dd>
            <dt>Type:</dt>     <dd>{{ $product->category->name ?? ucfirst($product->type ?? 'Alkaline Ionizer') }}</dd>
            <dt>Sku:</dt>      <dd>{{ $product->sku ?? 'ION-XBLUE' }}</dd>
            <dt>Available:</dt><dd>{{ (!$product || $product->isInStock()) ? 'In Stock' : 'Out of Stock' }}</dd>
          </dl>

          <div class="ionpd-visitors">
            Currently there are <span class="ionpd-num">12</span> peoples are currently looking at this product
          </div>

          <div class="ionpd-shipping">
            <i class="fa-solid fa-bag-shopping"></i>
            Spend <strong>₹2,000.00</strong> for FREE SHIPPING
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="iondr-section" aria-label="Product description and reviews">
    <div class="iondr-container">

      <!-- ================= TABS ================= -->
      <div class="iondr-tabs" role="tablist">
        <button class="iondr-tab iondr-active" type="button"
                data-tab="desc" role="tab" aria-selected="true">DESCRIPTION</button>
        <button class="iondr-tab" type="button"
                data-tab="review" role="tab" aria-selected="false">REVIEW</button>
      </div>

      <!-- ================= PANEL: DESCRIPTION ================= -->
      <div class="iondr-panel iondr-active" id="iondrPanelDesc" role="tabpanel">
        <p class="iondr-desc-text">
          {!! nl2br(e($product->description ?? $product->short_description ?? "Korea's #1 alkaline water technology, trusted in 60 countries, now in your home. Every machine turns tap water into pH-controlled alkaline, acidic, and filtered water, with a lifespan of 20–25 years.")) !!}
        </p>
      </div>

      <!-- ================= PANEL: REVIEW ================= -->
      <div class="iondr-panel" id="iondrPanelReview" role="tabpanel">
        <div class="iondr-review-list">

          <article class="iondr-review">
            <div class="iondr-review-stars">★★★★★</div>
            <h4 class="iondr-review-title">Amazing quality</h4>
            <p class="iondr-review-text">The finish is so smooth and it stays all day. Totally worth it.</p>
            <span class="iondr-review-author">— Priya S.</span>
          </article>

          <article class="iondr-review">
            <div class="iondr-review-stars">★★★★★</div>
            <h4 class="iondr-review-title">Lightweight & hydrating</h4>
            <p class="iondr-review-text">Feels like nothing on the skin but covers everything. Love it.</p>
            <span class="iondr-review-author">— Aditya R.</span>
          </article>

          <article class="iondr-review">
            <div class="iondr-review-stars">★★★★☆</div>
            <h4 class="iondr-review-title">Great value</h4>
            <p class="iondr-review-text">Good coverage, blends easily, and a little goes a long way.</p>
            <span class="iondr-review-author">— Sneha K.</span>
          </article>

          <article class="iondr-review">
            <div class="iondr-review-stars">★★★★★</div>
            <h4 class="iondr-review-title">My new favourite</h4>
            <p class="iondr-review-text">Perfect for daily use. Doesn't cake or settle into fine lines.</p>
            <span class="iondr-review-author">— Rohan M.</span>
          </article>

        </div>
      </div>

        <div class="iondr-hero">
<video class="iondr-hero-video"
       src="{{asset('public/assets/videos/baground.mp4')}}"

       autoplay muted loop playsinline></video>

          <div class="iondr-hero-overlay" aria-hidden="true"></div>
        </div>

    </div>
  </section>
   <section class="ionp-section" aria-labelledby="ionpTitle">
    <div class="ionp-container">

      <!-- PAGE TITLE -->
      <header class="omnx-news-header">
        <h1 class="omnx-news-title" id="omnxNewsHeading">You may also  <em>like</em></h1>
      </header>

      <!-- PRODUCT GRID -->
      <div class="ionp-grid" id="ionpGrid">
        @php
            $relList = (isset($relatedProducts) && $relatedProducts->count() > 0)
                ? $relatedProducts
                : \App\Models\Product::active()->where('id', '!=', $product->id ?? 0)->take(4)->get();
        @endphp

        @forelse($relList as $rel)
          <article class="ionp-card" data-name="{{ $rel->name }}" data-price="{{ $rel->price }}">
            <div class="ionp-img-wrap">
              <img class="ionp-img" src="{{ \App\Models\Product::resolveImageUrl($rel->thumbnail) ?: asset('public/assets/images/X-BLUE-5-PLATES-5.webp') }}" alt="{{ $rel->name }}">
              <div class="ionp-actions">
                <a href="{{ url('product-details/' . $rel->slug) }}" class="ionp-action">PRODUCT DETAILS <i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
            <a class="ionp-name" href="{{ url('product-details/' . $rel->slug) }}">{{ $rel->name }}</a>
            <p class="ionp-price">
              ₹{{ number_format($rel->price, 2) }}
              @if(!empty($rel->compare_price) && $rel->compare_price > $rel->price)
                <span class="ionp-price-old">₹{{ number_format($rel->compare_price, 2) }}</span>
              @endif
            </p>
          </article>
        @empty
          <div style="grid-column: 1 / -1; text-align: center; padding: 40px 20px;">
            <p style="color: #777; font-size: 15px;">No related products found.</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>

  @include('partials.floating-cart')
@endsection
@push('scripts')

  <script>
    (function () {
      const main    = document.getElementById('ionpdMain');
      const base    = document.getElementById('ionpdBase');
      const zoom    = document.getElementById('ionpdZoom');
      const thumbs  = document.querySelectorAll('.ionpd-thumb');
      const qtyInput = document.getElementById('ionpdQty');
      const qtyBtns  = document.querySelectorAll('.ionpd-qty-btn');

      // current active image URL (base + zoom both use same)
      let currentSrc = thumbs.length && thumbs[0].getAttribute('data-img') ? thumbs[0].getAttribute('data-img') : '';

      // set both layers with same image
      function applyImage(src) {
        if (!src) return;
        currentSrc = src;
        base.style.backgroundImage = 'url("' + src + '")';
        zoom.style.backgroundImage = 'url("' + src + '")';
        zoom.style.backgroundPosition = '50% 50%';
      }

      // ============ CURSOR POSITION MAGNIFIER ============
      function moveZoom(e) {
        const rect = main.getBoundingClientRect();
        let x = e.clientX - rect.left;
        let y = e.clientY - rect.top;

        let ratioX = x / rect.width;
        let ratioY = y / rect.height;

        if (ratioX < 0) ratioX = 0;
        if (ratioY < 0) ratioY = 0;
        if (ratioX > 1) ratioX = 1;
        if (ratioY > 1) ratioY = 1;

        const px = ratioX * 100;
        const py = ratioY * 100;

        zoom.style.backgroundPosition = px + '% ' + py + '%';
      }

      main.addEventListener('mouseenter', function (e) {
        moveZoom(e);
      });

      main.addEventListener('mousemove', function (e) {
        if (window.innerWidth < 768) return;
        moveZoom(e);
      });

      // ============ THUMBNAIL SWAP ============
      thumbs.forEach(function (t) {
        const swap = function () {
          thumbs.forEach(function (x) { x.classList.remove('ionpd-active'); });
          t.classList.add('ionpd-active');
          const newSrc = t.getAttribute('data-img');
          if (newSrc) applyImage(newSrc);
        };
        t.addEventListener('mouseenter', swap);
        t.addEventListener('click', swap);
        t.addEventListener('focus', swap);
      });

      // ============ QUANTITY ============
      qtyBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
          let v = parseInt(qtyInput.value, 10);
          if (isNaN(v)) v = 1;
          if (btn.getAttribute('data-qty') === 'plus') v = v + 1;
          else v = v > 1 ? v - 1 : 1;
          qtyInput.value = v;
        });
      });
      qtyInput.addEventListener('input', function () {
        let v = qtyInput.value.replace(/\D/g, '');
        if (v === '' || parseInt(v, 10) < 1) v = '1';
        qtyInput.value = v;
      });

      // ============ BUY NOW / ADD TO CART FUNCTIONALITY ============
      const buyNowBtn = document.getElementById('buyNowBtn');
      if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function (e) {
          e.preventDefault();
          const pId = this.getAttribute('data-product-id');
          const qtyVal = parseInt(qtyInput.value, 10) || 1;
          const btnText = document.getElementById('buyNowText');
          if (btnText) btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
          buyNowBtn.disabled = true;

          fetch('{{ url("api/cart/add") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              product_id: pId,
              quantity: qtyVal
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              const newCount = data.count || 1;
              if (typeof window.updateCartBadge === 'function') {
                window.updateCartBadge(newCount);
              } else {
                document.querySelectorAll('.cart-count-badge, #floatingCartBadge, #headerCartCount').forEach(el => {
                  el.textContent = newCount;
                  el.style.display = newCount > 0 ? 'flex' : 'none';
                });
              }

              // Pulse the floating cart
              if (typeof window.pulseFloatingCart === 'function') {
                window.pulseFloatingCart();
              }

              if (btnText) btnText.innerHTML = '<i class="fa-solid fa-check"></i> Added to Cart';

              if (typeof window.zToast === 'function') {
                window.zToast(qtyVal + ' item(s) added to cart!', 'success');
              }

              setTimeout(() => {
                if (btnText) btnText.textContent = 'Buy Now';
                buyNowBtn.disabled = false;
              }, 1800);
            } else {
              if (btnText) btnText.textContent = 'Buy Now';
              buyNowBtn.disabled = false;
              alert(data.message || 'Could not add to cart.');
            }
          })
          .catch(() => {
            if (btnText) btnText.textContent = 'Buy Now';
            buyNowBtn.disabled = false;
          });
        });
      }

      // init
      if (currentSrc) applyImage(currentSrc);
    })();

       (function () {
      const tabs = document.querySelectorAll('.iondr-tab');
      const panels = {
        desc:   document.getElementById('iondrPanelDesc'),
        review: document.getElementById('iondrPanelReview')
      };

      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          const key = tab.getAttribute('data-tab');

          tabs.forEach(function (t) {
            t.classList.remove('iondr-active');
            t.setAttribute('aria-selected', 'false');
          });
          tab.classList.add('iondr-active');
          tab.setAttribute('aria-selected', 'true');

          Object.keys(panels).forEach(function (k) {
            if (panels[k]) panels[k].classList.remove('iondr-active');
          });
          if (panels[key]) panels[key].classList.add('iondr-active');
        });
      });
    })();

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
