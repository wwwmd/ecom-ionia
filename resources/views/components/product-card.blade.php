@props(['product'])

@php
    $price = $product->price;
    $compare = $product->compare_price;
    $discount = ($compare && $compare > $price) ? round((($compare - $price) / $compare) * 100) : 0;
@endphp

<div class="z-product-card">
    <div class="z-pc-media">
        <a href="{{ url('/product/'.$product->slug) }}">
            @if($product->thumbnail)
                <img src="{{ \App\Models\Product::resolveImageUrl($product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy">
            @else
                <div class="z-pc-placeholder">
                    <i class="bi bi-image"></i>
                </div>
            @endif
        </a>

        @if($discount > 0)
            <span class="z-pc-discount">-{{ $discount }}%</span>
        @endif

        @if($product->is_new_arrival)
            <span class="z-pc-tag">New</span>
        @endif

        <div class="z-pc-actions">
            <button class="z-pc-act wishlist-btn" data-id="{{ $product->id }}" title="Wishlist">
                <i class="bi bi-heart"></i>
            </button>
        </div>
    </div>

    <div class="z-pc-body">
        <div class="z-pc-brand">{{ $product->brand->name ?? 'store' }}</div>
        <h3 class="z-pc-title">
            <a href="{{ url('/product/'.$product->slug) }}">{{ \Illuminate\Support\Str::limit($product->name, 45) }}</a>
        </h3>

        <x-rating :value="$product->avg_rating ?? 4.5" :count="$product->review_count ?? 0" />

        <div class="z-pc-price">
            <span class="z-price-now">₹{{ number_format($price) }}</span>
            @if($compare && $compare > $price)
                <span class="z-price-old">₹{{ number_format($compare) }}</span>
            @endif
        </div>

        <button class="z-btn-cart add-to-cart-btn" data-id="{{ $product->id }}">
            <i class="bi bi-bag-plus"></i> Add to Cart
        </button>
    </div>
</div>
