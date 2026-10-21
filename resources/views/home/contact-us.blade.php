 @extends('layouts.frontend')
@section('title', 'Contact Us')

@section('content')
  <!-- ================= BREADCRUMB ================= -->
  {{-- <nav class="ioncu-breadcrumb" aria-label="Breadcrumb">
    <ol class="ioncu-breadcrumb-list">
      <li class="ioncu-breadcrumb-item">
        <a href="/" class="ioncu-breadcrumb-link">Home</a>
      </li>
      <li class="ioncu-breadcrumb-sep" aria-hidden="true">/</li>
      <li class="ioncu-breadcrumb-item">
        <span class="ioncu-breadcrumb-current" aria-current="page">Contact Us</span>
      </li>
    </ol>
  </nav> --}}

  <!-- ================= HERO ================= -->

  <section class="ioncu-strip" aria-label="Free demo call-to-action">
    <div class="ioncu-strip-inner">
      <h2 class="ioncu-strip-title">Contact  <em>Us</em> </h2>
      <p class="ioncu-strip-text">
        Have a question about our alkaline water ionizers? We'd love to hear from you.
        Reach out for a free demo, product details, or support.
      </p>

    </div>
  </section>

  <!-- ================= MAIN ================= -->
  <main class="ioncu-main">
    <div class="ioncu-container">
      <div class="ioncu-grid">

        <!-- LEFT: FORM + MAP -->
        <div>
          <h2 class="ioncu-form-title">Send us a message</h2>
          <p class="ioncu-form-lead">
            Fill out the form below and our team will get back to you within 24 hours.
          </p>

          <form onsubmit="event.preventDefault();" novalidate>
            <div class="ioncu-field">
              <input class="ioncu-input" type="text" name="name" placeholder="Name" required>
            </div>
            <div class="ioncu-field">
              <input class="ioncu-input" type="email" name="email" placeholder="Email" required>
            </div>
            <div class="ioncu-field">
              <input class="ioncu-input" type="tel" name="phone" placeholder="Phone Number">
            </div>
            <div class="ioncu-field">
              <textarea class="ioncu-textarea" name="message" placeholder="Message"></textarea>
            </div>
            <button class="ioncu-submit" type="submit">
              SEND <i class="fa-solid fa-arrow-right"></i>
            </button>
          </form>


        </div>

        <!-- RIGHT: INFO -->
        <aside class="ioncu-aside">

          <!-- Registered address card -->
          <div class="ioncu-address-card">
            <h2 class="ioncu-card-heading">Registered Address</h2>
            <span class="ioncu-card-underline" aria-hidden="true"></span>

            <ul class="ioncu-info-list">
              <li class="ioncu-info-item">
                <span class="ioncu-info-icon" aria-hidden="true">
                  <i class="fa-solid fa-location-dot"></i>
                </span>
                <span class="ioncu-info-text">
                  B-12, Ground Floor, Sector 69,<br>
                  Uttar Pradesh, 201301
                </span>
              </li>

              <li class="ioncu-info-item">
                <span class="ioncu-info-icon" aria-hidden="true">
                  <i class="fa-solid fa-phone"></i>
                </span>
                <span class="ioncu-info-text">
                  <a href="tel:+917428721100">+91-7428721100</a>
                </span>
              </li>

              <li class="ioncu-info-item">
                <span class="ioncu-info-icon" aria-hidden="true">
                  <i class="fa-solid fa-envelope"></i>
                </span>
                <span class="ioncu-info-text">
                  <a href="mailto:ioniaindiaofficials@gmail.com">ioniaindiaofficials@gmail.com</a>
                </span>
              </li>
            </ul>
          </div>

          <!-- Quick actions -->
          <div class="ioncu-quick">
            <a class="ioncu-quick-btn active" href="tel:+917428721100">
              <i class="fa-solid fa-phone"></i> Call Now
            </a>
            <a class="ioncu-quick-btn" href="https://wa.me/917428721100" target="_blank" rel="noopener">
              <i class="fa-brands fa-whatsapp"></i> WhatsApp
            </a>
          </div>

        </aside>
      </div>
    </div>
  </main>

  <!-- ================= SUPPORT STRIP ================= -->
  {{-- <section class="ioncu-strip" aria-label="Free demo call-to-action">
    <div class="ioncu-strip-inner">
      <h2 class="ioncu-strip-title">Book a <em>free live demo</em> today</h2>
      <p class="ioncu-strip-text">
        Experience the science of pH-balanced water in your own home. No obligation, no pressure — just pure, ionized water.
      </p>
      <a class="ioncu-strip-btn" href="tel:+917428721100">
        BOOK A FREE DEMO <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </section> --}}


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
