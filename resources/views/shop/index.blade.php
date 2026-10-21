@extends('layouts.frontend')
@section('title', 'Shop All Products — store Store')

@section('content')
<div class="container z-shop-page">
    <nav class="z-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <i class="bi bi-chevron-right"></i>
        <span>Shop</span>
    </nav>

    <div class="row g-4">

        <aside class="col-lg-3">
            <div class="z-filter-card">
                <h5>Filters</h5>

                <div class="z-filter-group">
                    <h6>Category</h6>
                    @foreach($categories as $cat)
                    <label class="z-filter-opt">
                        <input type="checkbox" class="cat-filter" value="{{ $cat->id }}" @checked(request('category') == $cat->id)>
                        <span>{{ $cat->name }}</span>
                    </label>
                    @endforeach
                </div>

                <div class="z-filter-group">
                    <h6>Price Range</h6>
                    <div class="z-price-range">
                        <input type="number" id="minPrice" placeholder="Min" value="{{ request('min') }}">
                        <span>–</span>
                        <input type="number" id="maxPrice" placeholder="Max" value="{{ request('max') }}">
                    </div>
                    <button class="z-btn-ghost w-100 mt-2" id="applyPrice">Apply</button>
                </div>

                <div class="z-filter-group">
                    <h6>Brands</h6>
                    @foreach($brands as $brand)
                    <label class="z-filter-opt">
                        <input type="checkbox" class="brand-filter" value="{{ $brand->id }}" @checked(request('brand') == $brand->id)>
                        <span>{{ $brand->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </aside>

        <div class="col-lg-9">
            <div class="z-shop-toolbar">
                <div><strong>{{ $products->total() }}</strong> products found</div>
                <select class="z-select" id="sortBy">
                    <option value="latest" @selected(request('sort') == 'latest')>Latest</option>
                    <option value="price_asc" @selected(request('sort') == 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected(request('sort') == 'price_desc')>Price: High to Low</option>
                    <option value="popular" @selected(request('sort') == 'popular')>Popularity</option>
                    <option value="rating" @selected(request('sort') == 'rating')>Top Rated</option>
                </select>
            </div>

            <div class="row g-3 g-md-4">
                @forelse($products as $product)
                <div class="col-6 col-md-4">
                    <x-product-card :product="$product" />
                </div>
                @empty
                <div class="col-12">
                    <div class="z-empty">
                        <i class="bi bi-inbox"></i>
                        <h4>No products found</h4>
                        <p>Try changing filters or search query.</p>
                        <a href="{{ url('/shop') }}" class="z-btn-primary">Reset Filters</a>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#sortBy').on('change', function () {
        const p = new URLSearchParams(window.location.search);
        p.set('sort', this.value);
        window.location.search = p.toString();
    });

    $('.cat-filter, .brand-filter').on('change', function () {
        const p = new URLSearchParams(window.location.search);
        p.delete('category'); p.delete('brand');

        $('.cat-filter:checked').each((i, el) => p.append('category', el.value));
        $('.brand-filter:checked').each((i, el) => p.append('brand', el.value));

        window.location.search = p.toString();
    });

    $('#applyPrice').on('click', function () {
        const p = new URLSearchParams(window.location.search);
        const min = $('#minPrice').val();
        const max = $('#maxPrice').val();
        if (min) p.set('min', min); else p.delete('min');
        if (max) p.set('max', max); else p.delete('max');
        window.location.search = p.toString();
    });
});
</script>
@endpush
