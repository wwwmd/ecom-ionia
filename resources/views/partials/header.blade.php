 <!-- STICKY HEADER -->
  <header class="ow-header-demo" id="owHeader">
    <div class="ow-header-inner">

      <!-- Left Navigation: Desktop Only -->
      <nav class="ow-nav-left" aria-label="Main navigation">
       <a href="{{url('/')}}">Home</a>
          <a href="{{url('/about-us')}}">About Us</a>
          <a href="{{url('/products')}}">Products</a>
          <a href="{{url('/contact-us')}}">Contact</a>
      </nav>

      <!-- Center Logo -->
      <a class="ow-logo" href="{{url('/')}}" aria-label="OW Organics home">
        <img
          src="{{ asset('public/assets/images/ionia-logo.webp') }}"
          alt="OW Organics Logo"
          class="ow-logo-image"
        >
      </a>

      <!-- Right Header Actions -->
      <div class="ow-header-actions">

        <!-- Shopping Cart Icon -->
        <a class="ow-cart-btn" href="{{ url('/cart') }}" aria-label="Shopping Cart">
          <i class="fa-solid fa-cart-shopping"></i>
          <span class="ow-cart-badge cart-count-badge" id="headerCartCount" style="display: none;">0</span>
        </a>

        <!-- Sign In / Register: Desktop Only -->
        <a class="ow-auth-link" href="{{url('/login')}}">Sign In</a>
        <span class="ow-action-separator">/</span>
        <a class="ow-auth-link" href="{{url('/register')}}">Register</a>

        <!-- Hamburger Menu (always visible) -->
        <button
          class="ow-menu-button"
          id="owMenuButton"
          type="button"
          aria-label="Open menu"
          aria-controls="owSideDrawer"
          aria-expanded="false"
        >
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </header>

  <!-- Cart Notification (hidden demo) -->
  <div class="vap-cart-toast" id="vapCartToast" role="status" aria-live="polite"></div>

  <!-- Sidebar Overlay -->
  <div class="ow-drawer-overlay" id="owDrawerOverlay" aria-hidden="true"></div>

  <!-- RIGHT SIDEBAR DRAWER -->
  <aside
    class="ow-side-drawer"
    id="owSideDrawer"
    role="dialog"
    aria-modal="true"
    aria-label="OW Organics side menu"
    aria-hidden="true"
  >
    <!-- Drawer Header: logo + premium close button -->
    <div class="ow-drawer-top">
      <a class="ow-drawer-logo" href="{{url('/')}}" aria-label="OW Organics home">
        <img
          src="{{ asset('public/assets/images/ionia-logo.webp') }}"
          alt="OW Organics Logo"
          class="ow-logo-image"
        >
      </a>
      <button
        class="ow-close-button"
        id="owDrawerClose"
        type="button"
        aria-label="Close menu"
      >
        &times;
      </button>
    </div>

    <!-- Drawer Navigation (main links) -->
    <nav class="ow-drawer-links" aria-label="Sidebar links">
     <a href="{{url('/')}}">Home</a>
          <a href="{{url('/about-us')}}">About Us</a>
          <a href="{{url('/products')}}">Products</a>
          <a href="{{url('/contact-us')}}">Contact</a>
    </nav>


    <div class="ow-drawer-auth">
      <a href="{{url('/login')}}">Sign In</a>
      <a href="{{url('/register')}}">Register</a>
    </div>

  </aside>

