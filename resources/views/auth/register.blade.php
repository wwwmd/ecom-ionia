@extends('layouts.frontend')
@section('title', 'Create Account — Ionia')

@section('content')
  <nav class="ionp-breadcrumb" aria-label="Breadcrumb">
    <ol class="ionp-breadcrumb-list">
      <li class="ionp-breadcrumb-item">
        <a href="/" class="ionp-breadcrumb-link">Home</a>
      </li>
      <li class="ionp-breadcrumb-sep" aria-hidden="true">/</li>
      <li class="ionp-breadcrumb-item">
        <span class="ionp-breadcrumb-current" aria-current="page">Create Account</span>
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
                        <h3>Register</h3>
                        <p>Create your account — it only takes a minute.</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger py-2 mb-3">{{ session('error') }}</div>
                    @endif
                    @if(session('status'))
                        <div class="alert alert-success py-2 mb-3">{{ session('status') }}</div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ url('/register') }}" id="registerForm" class="ioncu-auth-form" novalidate>
                        @csrf

                        <div class="ioncu-field">

                            <div class="ioncu-input-wrap">
                                <input type="text" id="name" class="ioncu-input" name="name" value="{{ old('name') }}"
                                       placeholder="Full name" autocomplete="name" data-rule="name">
                            </div>
                            @error('name')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                            <div class="ioncu-js-error"><span></span></div>
                        </div>

                        <div class="ioncu-field">

                            <div class="ioncu-input-wrap">
                                <input type="email" id="email" class="ioncu-input" name="email" value="{{ old('email') }}"
                                       placeholder="Email Address" autocomplete="email" data-rule="email">
                            </div>
                            @error('email')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                            <div class="ioncu-js-error"><span></span></div>
                        </div>

                        <div class="ioncu-field">

                            <div class="ioncu-input-wrap">
                                <input type="tel" id="phone" class="ioncu-input" name="phone" value="{{ old('phone') }}"
                                       placeholder="Phone number" autocomplete="tel" data-rule="phone">
                            </div>
                            @error('phone')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                            <div class="ioncu-js-error"><span></span></div>
                        </div>

                        <div class="ioncu-field">

                            <div class="ioncu-input-wrap">
                                <input type="password" id="password" class="ioncu-input" name="password"
                                       placeholder="Password" autocomplete="new-password" data-rule="password">
                                <button type="button" class="ioncu-eye" data-toggle="password" aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                            <div class="ioncu-js-error"><span></span></div>
                            <div class="ioncu-meter" id="ioncuMeter"><span></span><span></span><span></span><span></span></div>
                            <div class="ioncu-meter-text" id="ioncuMeterText"></div>
                        </div>

                        <div class="ioncu-field">

                            <div class="ioncu-input-wrap">
                                <input type="password" id="password_confirmation" class="ioncu-input" name="password_confirmation"
                                       placeholder="Confirm password" autocomplete="new-password" data-rule="confirm">
                                <button type="button" class="ioncu-eye" data-toggle="password_confirmation" aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                            <div class="ioncu-js-error"><span></span></div>
                        </div>

                        <button type="submit" class="ioncu-submit w-100" id="ioncuSubmit">
                            <span>Create Account</span>
                        </button>
                    </form>

                    <!-- Links -->
                    <p class="ioncu-auth-foot">
                        Already registered? <a href="{{ url('/login') }}" class="ioncu-link">Sign in</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('scripts')
<script>
(function () {
    const form    = document.getElementById('registerForm');
    const submit  = document.getElementById('ioncuSubmit');
    const pass    = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');

    const rules = {
        name: v => v.trim().length < 3 ? 'Enter your full name (at least 3 characters).' : '',
        email: v => !v.trim() ? 'Email address is required.'
                  : !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v) ? 'Enter a valid email address.' : '',
        phone: v => {
            const d = v.replace(/\D/g, '');
            return !v.trim() ? 'Phone number is required.'
                 : (d.length < 10 || d.length > 13) ? 'Enter a valid phone number (10 digits).' : '';
        },
        password: v => v.length < 8 ? 'Password must be at least 8 characters.' : '',
        confirm:  v => v !== pass.value || !v ? 'Passwords do not match.' : ''
    };

    function setState(input, msg) {
        const field = input.closest('.ioncu-field');
        const box   = input.closest('.ioncu-input-wrap');
        const jsErr = field.querySelector('.ioncu-js-error');
        const srvErr = field.querySelector('small.text-danger');

        if (srvErr) srvErr.style.display = 'none';

        box.classList.remove('is-valid', 'is-invalid');
        if (msg) {
            box.classList.add('is-invalid');
            jsErr.querySelector('span').textContent = msg;
            jsErr.classList.add('show');
        } else {
            if (input.value) box.classList.add('is-valid');
            jsErr.classList.remove('show');
        }
        return !msg;
    }

    function validate(input) {
        const rule = rules[input.dataset.rule];
        return rule ? setState(input, rule(input.value)) : true;
    }

    const inputs = form.querySelectorAll('input[data-rule]');
    inputs.forEach(input => {
        input.addEventListener('blur', () => validate(input));
        input.addEventListener('input', () => {
            if (input.closest('.ioncu-input-wrap').classList.contains('is-invalid')) validate(input);
        });
    });

    /* Password strength meter */
    const bars  = document.querySelectorAll('#ioncuMeter span');
    const text  = document.getElementById('ioncuMeterText');
    const colors = ['#DC3545', '#F59E0B', '#26A69A', '#1E9E6A'];
    const labels = ['Weak', 'Fair', 'Good', 'Strong'];

    pass.addEventListener('input', () => {
        const v = pass.value;
        let s = 0;
        if (v.length >= 8) s++;
        if (/[A-Z]/.test(v) && /[a-z]/.test(v)) s++;
        if (/\d/.test(v)) s++;
        if (/[^A-Za-z0-9]/.test(v)) s++;
        bars.forEach((b, i) => b.style.background = (v && i < s) ? colors[s - 1] : '');
        text.textContent = v ? 'Password strength: ' + labels[Math.max(s - 1, 0)] : '';
        if (confirm.value) validate(confirm);
    });

    /* Eye toggles */
    document.querySelectorAll('[data-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.toggle);
            const show = target.type === 'password';
            target.type = show ? 'text' : 'password';
            btn.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    });

    /* Submit */
    form.addEventListener('submit', e => {
        let ok = true, first = null;
        inputs.forEach(input => {
            if (!validate(input)) { ok = false; first = first || input; }
        });
        if (!ok) { e.preventDefault(); first.focus(); return; }

        submit.disabled = true;
        submit.querySelector('span').innerHTML =
            'CREATING ACCOUNT...';
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
