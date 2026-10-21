
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

    (function () {
  var track = document.getElementById('omInstaTrack');
  if (!track) return;

  /* 1) Infinite loop ke liye cards ka duplicate set automatically */
  var originals = Array.prototype.slice.call(track.children);
  originals.forEach(function (card) {
    var clone = card.cloneNode(true);
    clone.setAttribute('aria-hidden', 'true');
    clone.setAttribute('tabindex', '-1');
    track.appendChild(clone);
  });

  /* 2) Thumbnail fallback (maxres na mile to hqdefault) */
  track.querySelectorAll('.om-insta-thumb img').forEach(function (img) {
    img.addEventListener('error', function () {
      var id = img.closest('.om-insta-item').dataset.videoId;
      var fallback = 'https://img.youtube.com/vi/' + id + '/hqdefault.jpg';
      if (img.src !== fallback) img.src = fallback;
    });
  });

  /* 3) Sabhi videos band */
  function stopAll() {
    track.querySelectorAll('.om-insta-item.om-playing').forEach(function (item) {
      var f = item.querySelector('.om-insta-iframe');
      var c = item.querySelector('.om-insta-close');
      if (f) f.remove();
      if (c) c.remove();
      item.classList.remove('om-playing');
    });
    track.classList.remove('om-paused');
  }

  /* 4) Video play (sound ke saath) */
  function play(item) {
    if (!item || item.classList.contains('om-playing')) return;
    stopAll();

    var iframe = document.createElement('iframe');
    iframe.className = 'om-insta-iframe';
    iframe.src = 'https://www.youtube.com/embed/' + item.dataset.videoId +
                 '?autoplay=1&rel=0&playsinline=1';
    iframe.setAttribute('frameborder', '0');
    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
    iframe.allowFullscreen = true;
    iframe.title = 'YouTube video player';

    var closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'om-insta-close';
    closeBtn.setAttribute('aria-label', 'Close video');
    closeBtn.innerHTML = '&times;';

    item.appendChild(iframe);
    item.appendChild(closeBtn);
    item.classList.add('om-playing');
    track.classList.add('om-paused'); /* slider ruk jayega */
  }

  /* 5) Events */
  track.addEventListener('click', function (e) {
    if (e.target.closest('.om-insta-close')) { stopAll(); return; }
    play(e.target.closest('.om-insta-item'));
  });

  track.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') {
      var item = e.target.closest('.om-insta-item');
      if (item && !item.classList.contains('om-playing')) {
        e.preventDefault();
        play(item);
      }
    }
  });

  /* Card ke bahar click ya ESC par video band, slider resume */
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.om-insta-item')) stopAll();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') stopAll();
  });
})();


    (function () {
      const grid = document.getElementById('ionpGrid');
      const viewBtns = document.querySelectorAll('.ionp-view-btn');
      const sortSelect = document.getElementById('ionpSort');
      const countEl = document.getElementById('ionpCount');

      if (!grid) return;

      // count
      function updateCount() {
        const n = grid.querySelectorAll('.ionp-card').length;
        countEl.textContent = n + (n === 1 ? ' Product' : ' Products');
      }
      updateCount();

      // view toggle
      viewBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
          viewBtns.forEach(function (b) { b.classList.remove('ionp-active'); });
          btn.classList.add('ionp-active');

          const cols = parseInt(btn.getAttribute('data-cols'), 10) || 4;
          if (window.innerWidth < 768) {
            grid.style.gridTemplateColumns = '';
            return;
          }
          if (window.innerWidth < 1024) {
            grid.style.gridTemplateColumns = 'repeat(3, minmax(0, 1fr))';
            return;
          }
          grid.style.gridTemplateColumns = 'repeat(' + cols + ', minmax(0, 1fr))';
        });
      });

      // sorting
      sortSelect.addEventListener('change', function () {
        const mode = sortSelect.value;
        const cards = Array.from(grid.querySelectorAll('.ionp-card'));

        cards.sort(function (a, b) {
          const nameA = (a.getAttribute('data-name') || '').toLowerCase();
          const nameB = (b.getAttribute('data-name') || '').toLowerCase();
          const priceA = parseFloat(a.getAttribute('data-price')) || 0;
          const priceB = parseFloat(b.getAttribute('data-price')) || 0;

          switch (mode) {
            case 'alpha-asc':  return nameA.localeCompare(nameB);
            case 'alpha-desc': return nameB.localeCompare(nameA);
            case 'price-asc':  return priceA - priceB;
            case 'price-desc': return priceB - priceA;
            default: return 0;
          }
        });

        cards.forEach(function (c) { grid.appendChild(c); });
      });

      // reset column override on resize
      let t;
      window.addEventListener('resize', function () {
        clearTimeout(t);
        t = setTimeout(function () {
          grid.style.gridTemplateColumns = '';
        }, 200);
      });
    })();
   (function () {
      const main    = document.getElementById('ionpdMain');
      const base    = document.getElementById('ionpdBase');
      const zoom    = document.getElementById('ionpdZoom');
      const thumbs  = document.querySelectorAll('.ionpd-thumb');
      const qtyInput = document.getElementById('ionpdQty');
      const qtyBtns  = document.querySelectorAll('.ionpd-qty-btn');

      // current active image URL (base + zoom both use same)
      let currentSrc = thumbs[0].getAttribute('data-img');

      // set both layers with same image
      function applyImage(src) {
        currentSrc = src;
        base.style.backgroundImage = 'url("' + src + '")';
        zoom.style.backgroundImage = 'url("' + src + '")';
        // reset zoom position to center
        zoom.style.backgroundPosition = '50% 50%';
      }

      // ============ CURSOR POSITION MAGNIFIER ============
      function moveZoom(e) {
        const rect = main.getBoundingClientRect();
        let x = e.clientX - rect.left;
        let y = e.clientY - rect.top;

        // clamp within bounds (0..1)
        let ratioX = x / rect.width;
        let ratioY = y / rect.height;

        if (ratioX < 0) ratioX = 0;
        if (ratioY < 0) ratioY = 0;
        if (ratioX > 1) ratioX = 1;
        if (ratioY > 1) ratioY = 1;

        // convert to percentage — cursor ke exact position par zoom
        const px = ratioX * 100;
        const py = ratioY * 100;

        zoom.style.backgroundPosition = px + '% ' + py + '%';
      }

      main.addEventListener('mouseenter', function (e) {
        moveZoom(e);
      });

      main.addEventListener('mousemove', function (e) {
        // mobile / touch par zoom skip
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

      // init
      applyImage(currentSrc);
    })();
    (function() {
      const menuButton = document.getElementById('owMenuButton');
      const sideDrawer = document.getElementById('owSideDrawer');
      const overlay = document.getElementById('owDrawerOverlay');
      const closeButton = document.getElementById('owDrawerClose');

      function openDrawer() {
        sideDrawer.classList.add('open');
        overlay.classList.add('active');
        sideDrawer.setAttribute('aria-hidden', 'false');
        overlay.setAttribute('aria-hidden', 'false');
        menuButton.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
      }

      function closeDrawer() {
        sideDrawer.classList.remove('open');
        overlay.classList.remove('active');
        sideDrawer.setAttribute('aria-hidden', 'true');
        overlay.setAttribute('aria-hidden', 'true');
        menuButton.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }

      menuButton.addEventListener('click', openDrawer);
      closeButton.addEventListener('click', closeDrawer);
      overlay.addEventListener('click', closeDrawer);

      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sideDrawer.classList.contains('open')) {
          closeDrawer();
        }
      });
    })();
