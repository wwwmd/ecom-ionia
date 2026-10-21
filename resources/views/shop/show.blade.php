@extends('layouts.frontend')
@section('title', $product->name.' — store Store')
@section('meta_description', $product->meta_description ?? \Illuminate\Support\Str::limit($product->short_description, 155))

@section('content')
<div class="container z-pdp py-4">

    <nav class="z-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <i class="bi bi-chevron-right"></i>
        <a href="{{ url('/shop') }}">Shop</a>
        <i class="bi bi-chevron-right"></i>
        <a href="{{ url('/shop?category='.$product->category_id) }}">{{ $product->category->name ?? 'Category' }}</a>
        <i class="bi bi-chevron-right"></i>
        <span>{{ \Illuminate\Support\Str::limit($product->name, 40) }}</span>
    </nav>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="z-gallery">
                <div class="z-gallery-main">
                    @if($product->thumbnail)
                        <img src="{{ \App\Models\Product::resolveImageUrl($product->thumbnail) }}" id="mainImage" alt="{{ $product->name }}">
                    @else
                        <div class="z-pc-placeholder" style="aspect-ratio:1/1;background:var(--z-light)">
                            <i class="bi bi-image" style="font-size:80px;color:var(--z-border)"></i>
                        </div>
                    @endif
                    @if($product->compare_price > $product->price)
                        <span class="z-gallery-badge">
                            -{{ $product->discount_percentage }}% OFF
                        </span>
                    @endif
                </div>

                @if($product->images->count())
                <div class="z-gallery-thumbs">
                    @foreach($product->images as $img)
                    @php $imgUrl = \App\Models\Product::resolveImageUrl($img->path); @endphp
                    <button class="z-thumb {{ $loop->first ? 'active' : '' }}" data-img="{{ $imgUrl }}">
                        <img src="{{ $imgUrl }}" alt="">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-7">
            <div class="z-pdp-info">
                <span class="z-pdp-brand">{{ $product->brand->name ?? 'store' }}</span>
                <h1>{{ $product->name }}</h1>

                <div class="z-pdp-meta">
                    <x-rating :value="$product->avg_rating" :count="$product->review_count" />
                    <span>·</span>
                    @if($product->isInStock())
                        <span class="text-success"><i class="bi bi-check-circle-fill"></i> In Stock</span>
                    @else
                        <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Out of Stock</span>
                    @endif
                    <span>·</span>
                    <span>SKU: {{ $product->sku }}</span>
                </div>

                <div class="z-pdp-price">
                    <span class="z-price-big">₹{{ number_format($product->price) }}</span>
                    @if($product->compare_price)
                        <span class="z-price-old-big">₹{{ number_format($product->compare_price) }}</span>
                        <span class="z-price-save">Save ₹{{ number_format($product->compare_price - $product->price) }}</span>
                    @endif
                </div>

                <p class="z-pdp-short">{{ $product->short_description }}</p>

                @if($product->variants->count())
                <div class="z-pdp-option">
                    <label>Select Variant</label>
                    <div class="z-variants">
                        @foreach($product->variants as $v)
                            <button class="z-variant {{ $loop->first ? 'active' : '' }}" data-id="{{ $v->id }}">{{ $v->name }}</button>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="z-pdp-qty-row">
                    <div class="z-qty">
                        <button type="button" class="qty-minus">−</button>
                        <input type="number" id="qty" value="1" min="1">
                        <button type="button" class="qty-plus">+</button>
                    </div>
                    <button class="z-btn-primary z-btn-lg flex-grow-1 add-to-cart-btn" data-id="{{ $product->id }}">
                        <i class="bi bi-bag-plus"></i> Add to Cart
                    </button>
                    <button class="z-btn-icon wishlist-btn" data-id="{{ $product->id }}">
                        <i class="bi bi-heart"></i>
                    </button>
                </div>

                <button class="z-btn-buy w-100 mt-2" data-id="{{ $product->id }}">
                    <i class="bi bi-lightning-charge-fill"></i> Buy Now
                </button>

                <div class="z-pdp-features">
                    <div><i class="bi bi-truck"></i> Free delivery above ₹999</div>
                    <div><i class="bi bi-arrow-repeat"></i> 7-day easy returns</div>
                    <div><i class="bi bi-shield-check"></i> 1-year warranty</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="z-pdp-tabs mt-5">
        <ul class="nav nav-tabs" role="tablist">
            <li><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc">Description</button></li>
            <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-specs">Specifications</button></li>
            <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-reviews">Reviews ({{ $product->review_count }})</button></li>
        </ul>
        <div class="tab-content pt-3">
            <div class="tab-pane fade show active" id="tab-desc">
                {!! nl2br(e($product->description ?? $product->short_description)) !!}
            </div>
            <div class="tab-pane fade" id="tab-specs">
                @if($product->specifications)
                <table class="table z-spec-table">
                    @foreach($product->specifications as $k => $v)
                    <tr><th>{{ $k }}</th><td>{{ $v }}</td></tr>
                    @endforeach
                </table>
                @else
                    <p class="text-muted">No specifications available.</p>
                @endif
            </div>
            <div class="tab-pane fade" id="tab-reviews">
                @forelse($product->reviews as $rev)
                <div class="z-review">
                    <div class="z-review-head">
                        <strong>{{ $rev->customer->name ?? 'Guest' }}</strong>
                        <x-rating :value="$rev->rating" />
                    </div>
                    <p>{{ $rev->review }}</p>
                </div>
                @empty
                    <p class="text-muted">No reviews yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Related -->
    @if($related->count())
    <section class="z-section">
        <div class="z-section-head"><h2>You May Also Like</h2></div>
        <div class="row g-3">
            @foreach($related as $rp)
            <div class="col-6 col-md-3"><x-product-card :product="$rp" /></div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
