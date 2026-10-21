@extends('layouts.frontend')
@section('title', 'Store')

@section('content')


<main class="blume-hero-page">
    <section class="blume-hero-slider" aria-label="Featured Ionia alkaline water ionizers" aria-roledescription="carousel" id="heroSlider">

      <!-- Slide 1 -->
      <article class="blume-hero-slide blume-hero-active" aria-hidden="false" data-index="0">
        <div class="blume-hero-background">
          <!-- real background image (Unsplash) -->
          <img
            class="hero-bg-image"
            src="{{asset('public/assets/images/home4.png')}}"
            alt="Pure water droplet"
            loading="eager"
          >
          <!-- overlay layers: colour cast + dark tint -->
          <div class="blume-hero-bg-one"></div>
          <div class="blume-hero-tint"></div>
        </div>
        <div class="blume-hero-copy">
          <p class="blume-hero-eyebrow">Ionia India. Built in Korea.</p>
          <h1 class="blume-hero-title">Not just purified.<br><em>Ionized.</em></h1>
          <p class="blume-hero-description">Korea's #1 alkaline water technology, trusted in 60 countries, now in your home.</p>
          <a class="blume-hero-cta" href="{{ url('product-details') }}">Book a Free Live Demo <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
      </article>

      <!-- Slide 2 -->
      <article class="blume-hero-slide" aria-hidden="true" data-index="1">
        <div class="blume-hero-background">
          <img
            class="hero-bg-image"
            src="{{asset('public/assets/images/home2.png')}}"
            alt="Alkaline water pouring"
            loading="lazy"
          >
          <div class="blume-hero-bg-two"></div>
          <div class="blume-hero-tint"></div>
        </div>
        <div class="blume-hero-copy">
          <p class="blume-hero-eyebrow">pH 2.5 to 11.5, one machine</p>
          <h2 class="blume-hero-title">Water that works<br><em>harder for your body.</em></h2>
          <p class="blume-hero-description">pH-balanced alkaline water from 2.5 to 11.5, for drinking, cooking, skincare, and more.</p>
          <a class="blume-hero-cta" href="{{ url('product-details') }}">Book a Free Live Demo <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
      </article>

      <!-- Slide 3 -->
      <article class="blume-hero-slide" aria-hidden="true" data-index="2">
        <div class="blume-hero-background">
          <img
            class="hero-bg-image"
            src="{{asset('public/assets/images/home3.png')}}"
            alt="Laboratory water science"
            loading="lazy"
          >
          <div class="blume-hero-bg-three"></div>
          <div class="blume-hero-tint"></div>
        </div>
        <div class="blume-hero-copy">
          <p class="blume-hero-eyebrow">Certified globally</p>
          <h2 class="blume-hero-title">40 years of Korean water science.<br><em>One machine for everything.</em></h2>
          <p class="blume-hero-description">Ionia ionizers are built to last 20–25 years with patents, global certifications, and thousands of happy families across India.</p>
          <a class="blume-hero-cta" href="{{ url('products') }}">Explore Products <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
      </article>

      <button class="blume-hero-arrow blume-hero-prev" type="button" aria-label="Previous slide" id="prevBtn"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>
      <button class="blume-hero-arrow blume-hero-next" type="button" aria-label="Next slide" id="nextBtn"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>

      <div class="blume-hero-dots" aria-label="Choose a slide" id="dotsContainer">
        <button class="blume-hero-dot blume-hero-active" type="button" aria-label="Go to slide 1" aria-current="true" data-index="0"></button>
        <button class="blume-hero-dot" type="button" aria-label="Go to slide 2" data-index="1"></button>
        <button class="blume-hero-dot" type="button" aria-label="Go to slide 3" data-index="2"></button>
      </div>

      <span class="blume-hero-bottom-note">40+ years · 60+ countries</span>
    </section>


  <section class="pwt-sci">
    <div class="container-fluid">
      <div class="row align-items-center">

        <!-- LEFT: image + orange block -->
        <div class="col-lg-7">
          <div class="pwt-sci__media">
            <span class="pwt-sci__block" aria-hidden="true"></span>
            <img src="{{asset('public/assets/images/X-BLUE-5-PLATES-5.webp')}}" alt="Retrinal Ceramide lipid-replenishing balm" class="pwt-sci__img">
          </div>
        </div>

        <!-- RIGHT: content -->
        <div class="col-lg-5 ">
          <div class="pwt-sci__content">
            <h2 class="avn-feature-heading" id="avnFeatureTitle">Built in Korea. Trusted worldwide.</h2>
            <p class="avn-feature-description">
            For over 40 years, Ionia has been at the forefront of alkaline water ionizer technology, exporting to 60+ countries. Every machine turns tap water into pH-controlled alkaline, acidic, and filtered water, with a lifespan of 20–25 years.
          </p>
             <a class="blume-hero-cta" href="{{ url('products') }}">Read More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>

          </div>
        </div>

      </div>
    </div>
  </section>
  </main>


 <main class="vap-shop-page">

    <!-- GRID -->
    <section class="vap-shop-grid" aria-label="Product collection">

      @php
          $prodList = (isset($products) && $products->count() > 0)
              ? $products
              : \App\Models\Product::active()->with(['brand', 'category'])->latest()->get();
      @endphp

      @forelse($prodList as $p)
        <article class="vap-shop-card"
                 data-category="{{ $p->category->name ?? 'general' }}"
                 data-product-id="{{ $p->id }}"
                 data-product-name="{{ $p->name }}"
                 data-price="{{ $p->price }}"
                 data-sku="{{ $p->sku ?? '' }}"
                 data-type="{{ $p->category->name ?? 'Alkaline' }}"
                 data-vendor="{{ $p->brand->name ?? 'IONIA' }}"
                 data-description="{{ Str::limit(strip_tags($p->description ?? ''), 100) }}"
                 data-image="{{ \App\Models\Product::resolveImageUrl($p->thumbnail) ?: asset('public/assets/images/X-BLUE-5-PLATES-5.webp') }}">
          <div class="vap-shop-image-wrap">
            <img class="vap-shop-image"
                 src="{{ \App\Models\Product::resolveImageUrl($p->thumbnail) ?: asset('public/assets/images/X-BLUE-5-PLATES-5.webp') }}"
                 alt="{{ $p->name }}" loading="lazy">
            <div class="vap-shop-hover-actions">
              <a href="{{ url('product-details/' . $p->slug) }}" class="vap-shop-action" style="text-decoration:none;">
                <span>PRODUCT DETAILS</span>
                <i class="fa-solid fa-arrow-right"></i>
              </a>
            </div>
          </div>
          <a class="vap-shop-product-name" href="{{ url('product-details/' . $p->slug) }}">{{ $p->name }}</a>
          <p class="vap-shop-price">₹{{ number_format($p->price, 2) }}</p>
        </article>
      @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px;">
          <p style="color: #777; font-size: 16px;">No products available at the moment.</p>
        </div>
      @endforelse

    </section>
  </main>

   <section class="omnx-news-section" aria-labelledby="omnxNewsHeading">
    <div class="omnx-news-inner">

      <!-- HEADER -->
      <header class="omnx-news-header">
        {{-- <p class="omnx-news-eyebrow">Journal</p> --}}
        <h1 class="omnx-news-title" id="omnxNewsHeading">Latest <em>Blogs</em></h1>
        <p class="omnx-news-subtitle">Subscribe for latest news and blog updates from our editor.</p>
      </header>

      <!-- GRID -->
      <div class="omnx-news-grid">

        <!-- CARD 1 -->
        <article class="omnx-news-card"
          data-title="Plastic Free Christmas With Our Love."
          data-date="November 19, 2024"
          data-image="assets/news-christmas-oil.jpg"
          data-description="Hey guys! I wanted to share the best of multiple categories from the Nordstrom Sale, plus a few of our favourite everyday essentials.">
          <a class="omnx-news-image-link" href="#news-1" aria-label="Read Plastic Free Christmas With Our Love">
            <p class="omnx-news-date">Nov 19, 2024</p>
            <img class="omnx-news-image"
                 src="{{asset('public/assets/images/IONIA_Product_1.jpg')}}"
                 alt="Yellow botanical skincare oil bottle" loading="lazy">
          </a>
          <h2 class="omnx-news-card-title"><a href="#news-1">Plastic Free Christmas With Our Love.</a></h2>
          <p class="omnx-news-excerpt">Hey guys! I wanted to share the ‘Best of’ multiple categories from the Nordstrom Sale,...</p>
          <a class="omnx-news-readmore" href="#news-1">READ MORE <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </article>

        <!-- CARD 2 -->
        <article class="omnx-news-card"
          data-title="It’s Been So Much Fun Twinning With Her"
          data-date="November 19, 2024"
          data-image="assets/news-summer-om.jpg"
          data-description="Fruit prints are back on the rise this summer and the high-schooler in me is delighted.">
          <a class="omnx-news-image-link" href="#news-2" aria-label="Read It’s Been So Much Fun Twinning With Her">
            <p class="omnx-news-date">Nov 19, 2024</p>
            <img class="omnx-news-image"
                 src="{{asset('public/assets/images//IONIA_Product_3.jpg')}}"
                 alt="Minimal green skincare bottle" loading="lazy">
          </a>
          <h2 class="omnx-news-card-title"><a href="#news-2">It’s Been So Much Fun Twinning With Her</a></h2>
          <p class="omnx-news-excerpt">Fruit prints are back on the rise this summer and the high-schooler in me is...</p>
          <a class="omnx-news-readmore" href="#news-2">READ MORE <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </article>

        <!-- CARD 3 -->
        <article class="omnx-news-card"
          data-title="Essential Christmas Gift Guide 2024"
          data-date="November 19, 2024"
          data-image="assets/news-gift-guide.jpg"
          data-description="Baths can improve skin health. A thoughtful bathing routine can be a relaxing part of self-care.">
          <a class="omnx-news-image-link" href="#news-3" aria-label="Read Essential Christmas Gift Guide 2024">
            <p class="omnx-news-date">Nov 19, 2024</p>
            <img class="omnx-news-image"
                 src="{{asset('public/assets/images/IONIA_Product_2.jpg')}}"
                 alt="Green botanical oil bottle" loading="lazy">
          </a>
          <h2 class="omnx-news-card-title"><a href="#news-3">Essential Christmas Gift Guide 2024</a></h2>
          <p class="omnx-news-excerpt">Baths can improve skin health. Originally you might think that baths would maybe dry out...</p>
          <a class="omnx-news-readmore" href="#news-3">READ MORE <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </article>

      </div>
    </div>
  </section>

<section class="om-insta-gallery" aria-label="YouTube Shorts gallery">
  <div class="om-insta-slider">
    <div class="om-insta-track">

      <!-- ===== SET 1 ===== -->
      <div class="om-insta-item">
        <iframe src="https://www.youtube.com/embed/lm5qdz6g6Wk?si=wWdgGxIHzkwWB-NW" title="YouTube video player" loading="lazy" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>

      <div class="om-insta-item">
        <iframe src="https://www.youtube.com/embed/lm5qdz6g6Wk?si=wWdgGxIHzkwWB-NW" title="YouTube video player" loading="lazy" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>

      <div class="om-insta-item">
        <iframe src="https://www.youtube.com/embed/lm5qdz6g6Wk?si=wWdgGxIHzkwWB-NW" title="YouTube video player" loading="lazy" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>

      <div class="om-insta-item">
        <iframe src="https://www.youtube.com/embed/lm5qdz6g6Wk?si=wWdgGxIHzkwWB-NW" title="YouTube video player" loading="lazy" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>

      <div class="om-insta-item">
        <iframe src="https://www.youtube.com/embed/lm5qdz6g6Wk?si=wWdgGxIHzkwWB-NW" title="YouTube video player" loading="lazy" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>

      <!-- ===== SET 2 (duplicate, infinite loop ke liye - same IDs rakho) ===== -->
      <div class="om-insta-item" aria-hidden="true">
        <iframe src="https://www.youtube.com/embed/lm5qdz6g6Wk?si=wWdgGxIHzkwWB-NW" title="YouTube video player" loading="lazy" frameborder="0" tabindex="-1"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>

      <div class="om-insta-item" aria-hidden="true">
        <iframe src="https://www.youtube.com/embed/lm5qdz6g6Wk?si=wWdgGxIHzkwWB-NW" title="YouTube video player" loading="lazy" frameborder="0" tabindex="-1"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>

      <div class="om-insta-item" aria-hidden="true">
        <iframe src="https://www.youtube.com/embed/lm5qdz6g6Wk?si=wWdgGxIHzkwWB-NW" title="YouTube video player" loading="lazy" frameborder="0" tabindex="-1"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>

      <div class="om-insta-item" aria-hidden="true">
        <iframe src="https://www.youtube.com/embed/lm5qdz6g6Wk?si=wWdgGxIHzkwWB-NW" title="YouTube video player" loading="lazy" frameborder="0" tabindex="-1"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>

      <div class="om-insta-item" aria-hidden="true">
        <iframe src="https://www.youtube.com/embed/lm5qdz6g6Wk?si=wWdgGxIHzkwWB-NW" title="YouTube video player" loading="lazy" frameborder="0" tabindex="-1"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>

    </div>
  </div>
</section>

@endsection

