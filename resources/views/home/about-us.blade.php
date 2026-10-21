 @extends('layouts.frontend')
@section('title', 'About Us')

@section('content')
  <!-- ================= BREADCRUMB ================= -->
  <nav class="ionp-breadcrumb" aria-label="Breadcrumb">
    <ol class="ionp-breadcrumb-list">
      <li class="ionp-breadcrumb-item">
        <a href="/" class="ionp-breadcrumb-link">Home</a>
      </li>
      <li class="ionp-breadcrumb-sep" aria-hidden="true">/</li>
      <li class="ionp-breadcrumb-item">
        <span class="ionp-breadcrumb-current" aria-current="page">About Us</span>
      </li>
    </ol>
  </nav>
    <section class="ionab-profile" aria-labelledby="ionabProfileTitle">
    <div class="ionab-container">
      <div class="ionab-profile-grid">

        <div class="ionab-profile-copy">
          <span class="ionab-eyebrow">Easy and Simple</span>
          <h2 class="ionab-mission-title" id="ionabProfileTitle">
            Take a look at our <em>profile</em>
          </h2>
          <p class="ionab-mission-text">
            Made in Korea. Certified globally. Designed for your family.
            Every Ionia device comes with a lifespan of 20–25 years, multiple international
            certifications, and the kind of after-sales reliability that only 4 decades of
            manufacturing experience can deliver.
          </p>
          <a class="ionab-btn" href="contact-us">
            <span>Contact Us</span>
            <i class="fa-solid fa-arrow-right"></i>
          </a>

          <!-- animated counters -->
          {{-- <div class="ionab-counters">
            <div>
              <span class="ionab-counter-num"
                    data-count="40"
                    data-suffix="+">0</span>
              <p class="ionab-counter-label">Years of Experience</p>
            </div>
            <div>
              <span class="ionab-counter-num"
                    data-count="60"
                    data-suffix="+">0</span>
              <p class="ionab-counter-label">Countries Served</p>
            </div>
            <div>
              <span class="ionab-counter-num"
                    data-count="4.9"
                    data-suffix="★"
                    data-decimal="1">0</span>
              <p class="ionab-counter-label">Customer Rating</p>
            </div>
          </div> --}}
        </div>

        <div class="ionab-profile-visual">
          <img src="{{asset('public/assets/images/IONIA_Product_3.jpg')}}"
               alt="Ionia product showcase">
        </div>

      </div>
    </div>
  </section>
   <section class="ionab-stats" aria-label="Company statistics">
    <div class="ionab-stats-inner">

      <div class="ionab-stat">
        <p class="ionab-stat-num"
           data-count="40"
           data-suffix="+">0</p>
        <p class="ionab-stat-label">Years of Innovation</p>
      </div>

      <div class="ionab-stat">
        <p class="ionab-stat-num"
           data-count="60"
           data-suffix="+">0</p>
        <p class="ionab-stat-label">Countries Served</p>
      </div>

      <div class="ionab-stat">
        <p class="ionab-stat-num"
           data-count="47000"
           data-suffix="+">0</p>
        <p class="ionab-stat-label">Happy Customers</p>
      </div>

    </div>
  </section>
    <section class="ionab-mission" aria-labelledby="ionabMissionTitle">
    <div class="ionab-container">
      <div class="ionab-mission-grid">

        <div class="ionab-mission-visual">
          <img src="{{asset('public/assets/images/IONIA_Product_2.jpg')}}"
               alt="Ionia product showcase">
        </div>

        <div class="ionab-mission-copy">
          <span class="ionab-eyebrow">Purpose</span>
          <h2 class="ionab-mission-title" id="ionabMissionTitle">
            Our <em>mission</em>
          </h2>
          <p class="ionab-mission-text">
            Ionia India. Built in Korea. Trusted Worldwide. Now in India.
            For over 40+ years, Ionia has been at the forefront of alkaline water ionizer
            technology, exporting to 60+ countries across America, Europe, the Middle East,
            Australia, and Africa. Our mission is simple: bring the science of pH-balanced
            water into every Indian home, so families can live healthier, every single day.
          </p>
          <a class="ionab-btn" href="products">
            <span>Explore Our Products</span>
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>

      </div>
    </div>
  </section>



  <!-- ============================================================
       SECTION 3 · FEATURES
       ============================================================ -->
  <section class="ionab-features" aria-label="What makes us different">
    <div class="ionab-features-inner">

      <div class="ionab-feature">
        <span class="ionab-feature-icon">
          <i class="fa-solid fa-user-group"></i>
        </span>
        <h3 class="ionab-feature-title">47,000+ Happy Customer</h3>
        <p class="ionab-feature-text">
          Families across India trust Ionia for clean, pH-balanced water that supports
          hydration, digestion, and everyday wellness.
        </p>
      </div>

      <div class="ionab-feature">
        <span class="ionab-feature-icon">
          <i class="fa-solid fa-trophy"></i>
        </span>
        <h3 class="ionab-feature-title">20 Awards Won</h3>
        <p class="ionab-feature-text">
          Recognized globally for engineering excellence, medical device manufacturing
          standards, and 4 decades of Korean water science.
        </p>
      </div>

      <div class="ionab-feature">
        <span class="ionab-feature-icon">
          <i class="fa-solid fa-certificate"></i>
        </span>
        <h3 class="ionab-feature-title">24 Years of Experience</h3>
        <p class="ionab-feature-text">
          Built to last 20–25 years with patents, international certifications, and
          reliable after-sales support across 60+ countries.
        </p>
      </div>

    </div>
  </section>


@endsection
@push('scripts')

  <script>

   (function () {

      const nums = document.querySelectorAll('.ionab-stat-num[data-count], .ionab-counter-num[data-count]');
      if (!nums.length) return;

      const DURATION = 1800;
      const started = new WeakSet();

      function animate(el) {
        if (started.has(el)) return;
        started.add(el);

        const target  = parseFloat(el.getAttribute('data-count')) || 0;
        const suffix  = el.getAttribute('data-suffix') || '';
        const decimal = parseInt(el.getAttribute('data-decimal'), 10) || 0;

        const start = performance.now();

        function tick(now) {
          const elapsed  = now - start;
          const progress = Math.min(elapsed / DURATION, 1);
          const eased    = 1 - Math.pow(1 - progress, 3);

          const raw = target * eased;

          if (decimal > 0) {
            el.textContent = raw.toFixed(decimal) + suffix;
          } else {
            // thousand separator for large numbers
            const n = Math.round(raw);
            el.textContent = (n >= 1000 ? n.toLocaleString('en-IN') : n) + suffix;
          }

          if (progress < 1) requestAnimationFrame(tick);
        }

        requestAnimationFrame(tick);
      }

      if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              animate(entry.target);
              io.unobserve(entry.target);
            }
          });
        }, { threshold: 0.35 });

        nums.forEach(function (n) { io.observe(n); });
      } else {
        nums.forEach(animate);
      }
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
