
<footer class="ion-ft">
  <div class="ion-ft__wrap">

    <div class="ion-ft__grid">

      <!-- COL 1: logo + paragraph + newsletter -->
      <div>
        <a href="{{url('/')}}" class="ion-ft__logo" aria-label="Ionia Home">
          <img src="{{asset('public/assets/images/ionia-logo.webp')}}" alt="Ionia">
        </a>
        <p class="ion-ft__about">
          Alkaline water has a higher PH level than regular drinking water. Scientists believe that it can neutralize the acid in your body. This water contains alkaline minerals and negative oxidation reduction potential (ORP).
        </p>

      </div>

      <!-- COL 2: quick links -->
      <div>
        <h4 class="ion-ft__title">Quick Links</h4>
        <ul class="ion-ft__list">
          <li><a href="{{url('/')}}">Home</a></li>
          <li><a href="{{url('/about-us')}}">About Us</a></li>
          <li><a href="{{url('/products')}}">Products</a></li>
          <li><a href="{{url('/contact-us')}}">Contact</a></li>
        </ul>
      </div>

      <!-- COL 3: contact -->
      <div>
        <h4 class="ion-ft__title">Registered Address</h4>
        <ul class="ion-ft__contact">
          <li><i class="fa-solid fa-location-dot"></i><span>B-12, Ground Floor, Sector 69, Uttar Predesh, 201301</span></li>
          <li><i class="fa-solid fa-phone"></i><a href="tel:+917428721100">+91-7428721100</a></li>
          <li><i class="fa-solid fa-envelope"></i><a href="mailto:ioniaindiaofficials@gmail.com">ioniaindiaofficials@gmail.com</a></li>
        </ul>
      </div>

      <!-- COL 4: social + help -->
      <div>
        <h4 class="ion-ft__title">Stay Connected</h4>
        <p class="ion-ft__text">Follow us for daily inspiration, new launches and behind the scenes.</p>
        <div class="ion-ft__social">
          <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
          <a href="#" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        </div>

      </div>

    </div>

   <section class="ionia-trust-strip" aria-label="Trust and payment methods">
  <div class="ionia-trust-inner">

    <!-- LEFT: 3 features -->
    <ul class="ionia-trust-features">
      <li class="ionia-trust-item">
        <span class="ionia-trust-icon">
          <i class="fa-solid fa-truck-fast"></i>
        </span>
        <div class="ionia-trust-text">
          <strong>Free shipping</strong>
          <span>on orders above ₹999</span>
        </div>
      </li>

      <li class="ionia-trust-item">
        <span class="ionia-trust-icon">
          <i class="fa-solid fa-shield-halved"></i>
        </span>
        <div class="ionia-trust-text">
          <strong>Secure</strong>
          <span>payments</span>
        </div>
      </li>

      <li class="ionia-trust-item">
        <span class="ionia-trust-icon">
          <i class="fa-solid fa-box-open"></i>
        </span>
        <div class="ionia-trust-text">
          <strong>Easy</strong>
          <span>returns</span>
        </div>
      </li>
    </ul>

    <!-- RIGHT: payment icons -->
    <ul class="ionia-trust-payments" aria-label="Accepted payment methods">
      <li class="ionia-pay"><img src="{{asset('public/assets/images/payment-icons.webp')}}" alt="Visa"></li>

    </ul>

  </div>
</section>

    <!-- BOTTOM BAR -->
    <div class="ion-ft__bottom">
      <div>&copy; 2026 <a href="{{url('/')}}">Ionia</a>. All rights reserved.</div>
      <div class="ion-ft__bottom-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms &amp; Condition</a>
      </div>
    </div>

  </div>
</footer>
