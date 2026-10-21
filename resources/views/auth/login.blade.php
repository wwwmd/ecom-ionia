@extends('layouts.frontend')
@section('title', 'Sign In — Ionia')

@section('content')
  <nav class="ionp-breadcrumb" aria-label="Breadcrumb">
    <ol class="ionp-breadcrumb-list">
      <li class="ionp-breadcrumb-item">
        <a href="/" class="ionp-breadcrumb-link">Home</a>
      </li>
      <li class="ionp-breadcrumb-sep" aria-hidden="true">/</li>
      <li class="ionp-breadcrumb-item">
        <span class="ionp-breadcrumb-current" aria-current="page">Account</span>
      </li>
    </ol>
  </nav>
<section class="ioncu-auth-page">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="ioncu-auth-wrap">

                    <!-- Heading -->
                    <div class="ioncu-auth-head">
                        <h3>LOGIN</h3>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger py-2 mb-3">{{ session('error') }}</div>
                    @endif
                    @if(session('status'))
                        <div class="alert alert-success py-2 mb-3">{{ session('status') }}</div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ url('/login') }}" class="ioncu-auth-form">
                        @csrf

                        <div class="ioncu-field">
                            {{-- <label>Email</label> --}}
                            <div class="ioncu-input-wrap">
                                <input type="email" class="ioncu-input" name="email" value="{{ old('email') }}" autofocus placeholder="Email Address">
                            </div>
                            @error('email')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>

                        <div class="ioncu-field">
                            {{-- <label>Password</label> --}}
                            <div class="ioncu-input-wrap">
                                <input type="password" class="ioncu-input" name="password" id="password" placeholder="Password">
                                <button type="button" class="ioncu-eye" onclick="togglePwd(this)"><i class="bi bi-eye"></i></button>
                            </div>
                            @error('password')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>

                        <button type="submit" class="ioncu-submit w-100">
                            <span>SIGN IN</span> <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>

                    <!-- Links -->
                    {{-- <p class="ioncu-auth-foot">
                        <a href="#" class="ioncu-link">Forgot your password?</a>
                    </p> --}}
                    <p class="ioncu-auth-foot ioncu-auth-foot-bottom mt-3">
                        <a href="{{ url('/register') }}" class="ioncu-link">Register</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection



@push('scripts')
<script>
function togglePwd(btn) {
    const inp = document.getElementById('password');
    const icon = btn.querySelector('i');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
}
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
