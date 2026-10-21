@extends('layouts.admin')
@section('title', 'Product Details')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('admin.products') }}">Products</a>
    <i class="bi bi-chevron-right"></i><span>{{ \Illuminate\Support\Str::limit($product->name, 30) }}</span>
@endsection

@section('content')

@php
    $imgUrl = function ($path) {
        return \App\Models\Product::resolveImageUrl($path);
    };
    $mainImg = $imgUrl($product->thumbnail);

    $discount = 0;
    if ($product->compare_price && $product->compare_price > $product->price) {
        $discount = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
    }

    $stockStatus = 'ok';
    $stockLabel = $product->stock . ' in stock';
    if (!$product->manage_stock) { $stockStatus = 'muted'; $stockLabel = 'Unmanaged'; }
    elseif ($product->stock <= 0) { $stockStatus = 'danger'; $stockLabel = 'Out of stock'; }
    elseif ($product->stock <= ($product->low_stock_alert ?? 5)) { $stockStatus = 'warn'; $stockLabel = 'Low stock'; }

    $flags = [
        'is_featured' => ['Featured', 'bi-star-fill', 'blue'],
        'is_trending' => ['Trending', 'bi-fire', 'rose'],
        'is_new_arrival' => ['New Arrival', 'bi-sparkles', 'emerald'],
        'is_best_seller' => ['Best Seller', 'bi-trophy-fill', 'violet'],
    ];
    $activeFlags = collect($flags)->filter(fn($f, $k) => $product->$k);
@endphp

<style>
/* ============================================================
   PREMIUM PRODUCT DETAILS PAGE
   ============================================================ */
.pd {
    --pd-primary: #2E6DB4;
    --pd-primary-dark: #1E4C82;
    --pd-primary-light: #E8F1FB;
    --pd-accent: #26A69A;
    --pd-accent-dark: #00796B;
    --pd-ink: #0F1E33;
    --pd-slate: #5A6C8C;
    --pd-muted: #8B9BB4;
    --pd-line: #E3E9F2;
    --pd-bg: #F5F8FC;
    --pd-card: #FFFFFF;
    --pd-emerald: #10B981;
    --pd-emerald-bg: #ECFDF5;
    --pd-amber: #F59E0B;
    --pd-amber-bg: #FFFBEB;
    --pd-rose: #EF4444;
    --pd-rose-bg: #FEF2F2;
    --pd-violet: #8B5CF6;
    --pd-violet-bg: #F5F3FF;
    --pd-gradient: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    --pd-shadow-sm: 0 1px 3px rgba(15,30,51,.04);
    --pd-shadow: 0 4px 16px rgba(15,30,51,.06);
    --pd-shadow-lg: 0 12px 40px rgba(15,30,51,.10);

    color: var(--pd-ink);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    -webkit-font-smoothing: antialiased;
    padding-bottom: 24px;
}
.pd * { box-sizing: border-box; }

/* ============ HERO ============ */
.pd-hero {
    position: relative;
    overflow: hidden;
    padding: 22px 26px;
    margin-bottom: 20px;
    border-radius: 18px;
    background: linear-gradient(135deg, #0F1E33 0%, #1E4C82 50%, #2E6DB4 100%);
    color: #fff;
    box-shadow: 0 10px 30px rgba(46,109,180,.22);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    min-height: 96px;
}
.pd-hero::before {
    content: ""; position: absolute;
    right: -60px; top: -80px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(38,166,154,.42), transparent 65%);
    pointer-events: none;
}
.pd-hero::after {
    content: ""; position: absolute;
    left: -60px; bottom: -80px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(46,109,180,.5), transparent 65%);
    pointer-events: none;
}
.pd-hero > * { position: relative; z-index: 1; }
.pd-hero-left { display: flex; align-items: center; gap: 16px; min-width: 0; flex: 1; }
.pd-hero-badge {
    width: 46px; height: 46px; border-radius: 12px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #26A69A; flex-shrink: 0;
}
.pd-hero h1 {
    font-size: 20px; font-weight: 800;
    margin: 0 0 4px; color: #fff;
    letter-spacing: -.02em; line-height: 1.2;
    overflow: hidden; text-overflow: ellipsis;
    display: -webkit-box; -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
}
.pd-hero-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    font-size: 12.5px;
    color: rgba(255,255,255,.72);
}
.pd-hero-meta .sep {
    width: 3px; height: 3px; border-radius: 50%;
    background: rgba(255,255,255,.4);
}
.pd-hero-meta .code {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    background: rgba(255,255,255,.12);
    padding: 2px 8px;
    border-radius: 6px;
    color: #fff;
    font-weight: 600;
    font-size: 11.5px;
    border: 1px solid rgba(255,255,255,.18);
}

.pd-hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.pd-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border: none; border-radius: 99px;
    font-weight: 700; font-size: 13px;
    text-decoration: none; cursor: pointer;
    transition: all .22s;
    white-space: nowrap;
}
.pd-btn i { font-size: 14px; }

.pd-btn-primary {
    background: #fff;
    color: var(--pd-primary-dark);
    box-shadow: 0 3px 10px rgba(0,0,0,.15);
}
.pd-btn-primary:hover {
    color: var(--pd-primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,.2);
}

.pd-btn-glass {
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.24);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
}
.pd-btn-glass:hover {
    background: rgba(255,255,255,.24);
    color: #fff;
    transform: translateY(-2px);
}

/* ============ LAYOUT GRID ============ */
.pd-grid {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 18px;
    align-items: start;
}
@media (max-width: 991.98px) {
    .pd-grid { grid-template-columns: 1fr; }
}

/* ============ CARD ============ */
.pd-card {
    background: var(--pd-card);
    border: 1px solid var(--pd-line);
    border-radius: 16px;
    box-shadow: var(--pd-shadow-sm);
    overflow: hidden;
    margin-bottom: 16px;
    position: relative;
}
.pd-card::before {
    content: ""; position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--pd-accent-color, var(--pd-gradient));
    opacity: .9;
}
.pd-card[data-accent="blue"] { --pd-accent-color: linear-gradient(90deg, #2E6DB4, #1E4C82); }
.pd-card[data-accent="teal"] { --pd-accent-color: linear-gradient(90deg, #26A69A, #00796B); }
.pd-card[data-accent="violet"] { --pd-accent-color: linear-gradient(90deg, #8B5CF6, #6D28D9); }
.pd-card[data-accent="amber"] { --pd-accent-color: linear-gradient(90deg, #F59E0B, #D97706); }
.pd-card[data-accent="emerald"] { --pd-accent-color: linear-gradient(90deg, #10B981, #059669); }
.pd-card[data-accent="rose"] { --pd-accent-color: linear-gradient(90deg, #F87171, #DC2626); }

.pd-card-head {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 20px;
    border-bottom: 1px solid var(--pd-line);
    background: linear-gradient(180deg, #FAFCFD, #fff);
}
.pd-card-head i {
    width: 32px; height: 32px;
    border-radius: 9px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 14px;
    color: #fff;
    background: var(--pd-accent-color, var(--pd-gradient));
    box-shadow: 0 3px 10px rgba(46,109,180,.22);
    flex-shrink: 0;
}
.pd-card-head h5 {
    margin: 0; font-size: 14.5px;
    font-weight: 700; color: var(--pd-ink);
    flex: 1;
}
.pd-card-head .badge {
    font-size: 11px; padding: 3px 10px;
    border-radius: 99px;
    background: var(--pd-primary-light);
    color: var(--pd-primary);
    font-weight: 700;
    letter-spacing: .3px;
}
.pd-card-body { padding: 18px 20px; }

/* ============ IMAGE CARD ============ */
.pd-img-card { padding: 0; }
.pd-img-main {
    position: relative;
    aspect-ratio: 1/1;
    background: linear-gradient(135deg, #F5F8FC, #E8F1FB);
    display: flex; align-items: center; justify-content: center;
    color: #B3BCCF;
    font-size: 64px;
    overflow: hidden;
}
.pd-img-main img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .5s;
}
.pd-img-main:hover img { transform: scale(1.04); }
.pd-img-main .discount-tag {
    position: absolute;
    top: 14px; left: 14px;
    padding: 5px 12px;
    border-radius: 99px;
    background: linear-gradient(135deg, #F87171, #DC2626);
    color: #fff;
    font-size: 11px; font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    box-shadow: 0 4px 12px rgba(239,68,68,.35);
}
.pd-img-main .status-tag {
    position: absolute;
    top: 14px; right: 14px;
    padding: 4px 10px;
    border-radius: 99px;
    background: rgba(255,255,255,.94);
    color: var(--pd-emerald);
    font-size: 10.5px; font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    backdrop-filter: blur(8px);
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    display: flex; align-items: center; gap: 5px;
}
.pd-img-main .status-tag::before {
    content: "";
    width: 6px; height: 6px; border-radius: 50%;
    background: currentColor;
    animation: pd-pulse 2s infinite;
}
.pd-img-main .status-tag.inactive { color: var(--pd-slate); }
.pd-img-main .status-tag.draft { color: var(--pd-amber); }
@keyframes pd-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .4; }
}

/* Gallery thumbnails */
.pd-gallery {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
    padding: 14px;
    background: #FAFCFD;
    border-top: 1px solid var(--pd-line);
}
.pd-gallery .thumb {
    aspect-ratio: 1/1;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid var(--pd-line);
    cursor: pointer;
    transition: all .2s;
    background: #fff;
    position: relative;
}
.pd-gallery .thumb:hover {
    border-color: var(--pd-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46,109,180,.15);
}
.pd-gallery .thumb.active {
    border-color: var(--pd-primary);
    box-shadow: 0 0 0 3px var(--pd-primary-light);
}
.pd-gallery .thumb img {
    width: 100%; height: 100%; object-fit: cover;
}

/* Quick stats */
.pd-quick-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1px;
    background: var(--pd-line);
    border-top: 1px solid var(--pd-line);
}
.pd-quick-stats .qs {
    padding: 14px 18px;
    background: #fff;
}
.pd-quick-stats .qs-label {
    font-size: 10.5px;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--pd-muted);
    font-weight: 700;
    margin-bottom: 4px;
}
.pd-quick-stats .qs-value {
    font-size: 16px;
    font-weight: 800;
    color: var(--pd-ink);
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    letter-spacing: -.02em;
}
.pd-quick-stats .qs-value.price { color: #047857; }
.pd-quick-stats .qs-value.old {
    font-size: 12px;
    color: var(--pd-muted);
    text-decoration: line-through;
    margin-left: 6px;
    font-weight: 500;
}

/* Action buttons (edit/delete) */
.pd-img-actions {
    display: flex;
    gap: 8px;
    padding: 14px;
    background: #FAFCFD;
    border-top: 1px solid var(--pd-line);
}
.pd-img-actions .pd-btn { flex: 1; padding: 11px 16px; justify-content: center; }

.pd-btn-edit {
    background: var(--pd-gradient);
    color: #fff;
    box-shadow: 0 4px 14px rgba(46,109,180,.28);
}
.pd-btn-edit:hover {
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(46,109,180,.4);
}
.pd-btn-danger {
    background: #fff;
    border: 1.5px solid var(--pd-line);
    color: var(--pd-slate);
}
.pd-btn-danger:hover {
    border-color: #FCA5A5;
    color: var(--pd-rose);
    background: var(--pd-rose-bg);
}

/* ============ INFO ROWS ============ */
.pd-rows { display: flex; flex-direction: column; }
.pd-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px dashed var(--pd-line);
    font-size: 13.5px;
}
.pd-row:last-child { border-bottom: 0; }
.pd-row-label {
    color: var(--pd-slate);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}
.pd-row-label i {
    width: 16px;
    font-size: 13px;
    color: var(--pd-muted);
    text-align: center;
}
.pd-row-value {
    font-weight: 700;
    color: var(--pd-ink);
    text-align: right;
    font-size: 13.5px;
}
.pd-row-value.mono {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 12.5px;
    background: #F1F5F9;
    padding: 3px 10px;
    border-radius: 6px;
    border: 1px solid #E2E8F0;
}
.pd-row-value.price { color: #047857; font-size: 14px; }

/* ============ STATUS PILLS ============ */
.pd-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 99px;
    font-size: 11.5px; font-weight: 700;
    white-space: nowrap; line-height: 1.4;
}
.pd-pill::before {
    content: ""; width: 6px; height: 6px;
    border-radius: 50%; background: currentColor;
    box-shadow: 0 0 0 3px currentColor;
    animation: pd-pulse 2s infinite;
}
.pd-pill.ok { background: var(--pd-emerald-bg); color: #047857; }
.pd-pill.warn { background: var(--pd-amber-bg); color: #B45309; }
.pd-pill.danger { background: var(--pd-rose-bg); color: #B91C1C; }
.pd-pill.muted { background: #F1F5F9; color: var(--pd-slate); }
.pd-pill.active { background: var(--pd-emerald-bg); color: #047857; }
.pd-pill.inactive { background: #F1F5F9; color: var(--pd-slate); }
.pd-pill.draft { background: var(--pd-amber-bg); color: #B45309; }

/* ============ STOCK BAR ============ */
.pd-stock-bar {
    height: 6px;
    border-radius: 99px;
    background: #EEF2F7;
    overflow: hidden;
    margin-top: 10px;
}
.pd-stock-bar i {
    display: block;
    height: 100%;
    border-radius: 99px;
    transition: width .6s;
    background: linear-gradient(90deg, #10B981, #059669);
}
.pd-stock-bar.warn i { background: linear-gradient(90deg, #F59E0B, #D97706); }
.pd-stock-bar.danger i { background: linear-gradient(90deg, #F87171, #DC2626); }

/* ============ DESCRIPTION ============ */
.pd-desc {
    color: var(--pd-slate);
    line-height: 1.7;
    font-size: 14px;
}
.pd-desc strong { color: var(--pd-ink); }
.pd-desc p { margin: 0 0 12px; }
.pd-desc p:last-child { margin-bottom: 0; }

.pd-short {
    padding: 14px 16px;
    background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
    border: 1px solid #BFDBFE;
    border-radius: 12px;
    font-size: 13.5px;
    color: #1E40AF;
    font-weight: 500;
    line-height: 1.55;
    margin-bottom: 16px;
    display: flex;
    gap: 10px;
    align-items: flex-start;
}
.pd-short i { font-size: 16px; flex-shrink: 0; margin-top: 2px; }

/* ============ SPECS TABLE ============ */
.pd-specs {
    display: grid;
    grid-template-columns: 1fr;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--pd-line);
}
.pd-specs .spec {
    display: grid;
    grid-template-columns: 40% 60%;
    border-bottom: 1px solid var(--pd-line);
}
.pd-specs .spec:last-child { border-bottom: 0; }
.pd-specs .spec:nth-child(even) { background: #FAFCFD; }
.pd-specs .spec-key {
    padding: 12px 16px;
    background: #F7FAFC;
    font-size: 13px;
    font-weight: 700;
    color: var(--pd-slate);
    border-right: 1px solid var(--pd-line);
    display: flex; align-items: center; gap: 8px;
}
.pd-specs .spec-key::before {
    content: ""; width: 4px; height: 12px;
    border-radius: 2px;
    background: var(--pd-gradient);
}
.pd-specs .spec-value {
    padding: 12px 16px;
    font-size: 13.5px;
    color: var(--pd-ink);
    font-weight: 600;
    display: flex; align-items: center;
}

/* ============ ATTRIBUTES CHIPS ============ */
.pd-attrs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.pd-attr-group {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 10px;
    background: linear-gradient(135deg, #F5F3FF, #EDE9FE);
    border: 1px solid #DDD6FE;
}
.pd-attr-group .group-name {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #6D28D9;
}
.pd-attr-group .group-values {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
.pd-attr-val {
    padding: 3px 10px;
    border-radius: 99px;
    background: #fff;
    color: var(--pd-ink);
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #DDD6FE;
}

/* ============ FLAGS ============ */
.pd-flags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.pd-flag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 700;
    border: 1.5px solid transparent;
}
.pd-flag i { font-size: 13px; }
.pd-flag.blue { background: linear-gradient(135deg, #EFF6FF, #DBEAFE); color: #1E40AF; border-color: #BFDBFE; }
.pd-flag.rose { background: linear-gradient(135deg, #FEF2F2, #FEE2E2); color: #B91C1C; border-color: #FECACA; }
.pd-flag.emerald { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); color: #047857; border-color: #A7F3D0; }
.pd-flag.violet { background: linear-gradient(135deg, #F5F3FF, #EDE9FE); color: #6D28D9; border-color: #DDD6FE; }

/* ============ SEO PREVIEW ============ */
.pd-seo-preview {
    padding: 16px;
    border-radius: 12px;
    background: #fff;
    border: 1px solid var(--pd-line);
}
.pd-seo-url {
    font-size: 12px;
    color: #047857;
    margin-bottom: 4px;
    font-weight: 500;
}
.pd-seo-title {
    font-size: 16px;
    color: #1a0dab;
    font-weight: 600;
    margin-bottom: 4px;
    line-height: 1.3;
}
.pd-seo-title:hover { text-decoration: underline; }
.pd-seo-desc {
    font-size: 12.5px;
    color: #4D5156;
    line-height: 1.5;
}
.pd-seo-empty {
    padding: 20px;
    text-align: center;
    color: var(--pd-muted);
    font-size: 12.5px;
}

/* ============ TIMELINE ============ */
.pd-timeline {
    position: relative;
    padding-left: 24px;
}
.pd-timeline::before {
    content: ""; position: absolute;
    left: 7px; top: 8px; bottom: 8px;
    width: 2px;
    background: var(--pd-line);
}
.pd-tl-item {
    position: relative;
    padding: 8px 0 16px;
}
.pd-tl-item:last-child { padding-bottom: 0; }
.pd-tl-dot {
    position: absolute;
    left: -24px; top: 10px;
    width: 14px; height: 14px;
    border-radius: 50%;
    background: var(--pd-gradient);
    border: 3px solid #fff;
    box-shadow: 0 0 0 1px var(--pd-line);
}
.pd-tl-item strong {
    display: block;
    font-size: 13px;
    color: var(--pd-ink);
    font-weight: 700;
    margin-bottom: 2px;
}
.pd-tl-item small {
    font-size: 11.5px;
    color: var(--pd-muted);
}

/* ============ RESPONSIVE ============ */
@media (max-width: 1199.98px) {
    .pd-grid { grid-template-columns: 340px 1fr; }
}
@media (max-width: 991.98px) {
    .pd-grid { grid-template-columns: 1fr; }
    .pd-hero { padding: 18px 20px; min-height: 82px; }
    .pd-hero h1 { font-size: 18px; }
    .pd-hero-badge { width: 40px; height: 40px; font-size: 18px; }
}
@media (max-width: 575.98px) {
    .pd-hero {
        padding: 16px 18px;
        flex-direction: column;
        align-items: stretch;
    }
    .pd-hero-actions { width: 100%; }
    .pd-hero-actions .pd-btn { flex: 1; justify-content: center; }
    .pd-card-body { padding: 14px 16px; }
    .pd-card-head { padding: 14px 16px; }
    .pd-card-head h5 { font-size: 13.5px; }
    .pd-specs .spec { grid-template-columns: 1fr; }
    .pd-specs .spec-key { border-right: 0; border-bottom: 1px solid var(--pd-line); }
    .pd-quick-stats { grid-template-columns: 1fr 1fr; }
    .pd-gallery { grid-template-columns: repeat(4, 1fr); }
}
@media (prefers-reduced-motion: reduce) {
    .pd * { transition: none !important; animation: none !important; }
}
</style>

<div class="pd">

    {{-- ============ HERO ============ --}}
    <div class="pd-hero">
        <div class="pd-hero-left">
            <div class="pd-hero-badge"><i class="bi bi-box-seam-fill"></i></div>
            <div style="min-width:0">
                <h1>{{ $product->name }}</h1>
                <div class="pd-hero-meta">
                    <span class="code">{{ $product->sku }}</span>
                    <span class="sep"></span>
                    <span><i class="bi bi-tag"></i> {{ $product->category->name ?? 'Uncategorized' }}</span>
                    @if($product->brand)
                        <span class="sep"></span>
                        <span><i class="bi bi-award"></i> {{ $product->brand->name }}</span>
                    @endif
                    <span class="sep"></span>
                    <span><i class="bi bi-calendar3"></i> {{ $product->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        <div class="pd-hero-actions">
            <a href="{{ route('admin.products.edit', $product) }}" class="pd-btn pd-btn-primary">
                <i class="bi bi-pencil-fill"></i> Edit
            </a>
            <a href="{{ route('admin.products') }}" class="pd-btn pd-btn-glass">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- ============ MAIN GRID ============ --}}
    <div class="pd-grid">

        {{-- ============ LEFT COLUMN ============ --}}
        <div>

            {{-- Image Card --}}
            <div class="pd-card pd-img-card" data-accent="blue">
                <div class="pd-img-main">
                    @if($mainImg)
                        <img src="{{ $mainImg }}" alt="{{ $product->name }}" id="pdMainImg">
                    @else
                        <i class="bi bi-image"></i>
                    @endif

                    @if($discount > 0)
                        <span class="discount-tag">-{{ $discount }}% OFF</span>
                    @endif

                    <span class="status-tag {{ $product->status !== 'active' ? $product->status : '' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </div>

                @if($product->images->count())
                    <div class="pd-gallery">
                        @if($mainImg)
                            <div class="thumb active" data-img="{{ $mainImg }}">
                                <img src="{{ $mainImg }}" alt="">
                            </div>
                        @endif
                        @foreach($product->images as $img)
                            <div class="thumb" data-img="{{ $imgUrl($img->path) }}">
                                <img src="{{ $imgUrl($img->path) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="pd-quick-stats">
                    <div class="qs">
                        <div class="qs-label">Selling Price</div>
                        <div class="qs-value price">
                            ₹{{ number_format($product->price, 2) }}
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <span class="qs-value old">₹{{ number_format($product->compare_price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="qs">
                        <div class="qs-label">Available Stock</div>
                        <div class="qs-value">{{ $product->stock }} units</div>
                    </div>
                </div>

                <div class="pd-img-actions">
                    <a href="{{ route('admin.products.edit', $product) }}" class="pd-btn pd-btn-edit">
                        <i class="bi bi-pencil-square"></i> Edit Product
                    </a>
                    <button type="button" class="pd-btn pd-btn-danger" id="deleteProductBtn">
                        <i class="bi bi-trash-fill"></i> Delete
                    </button>
                </div>
            </div>

            {{-- Product Flags --}}
            @if($activeFlags->count())
                <div class="pd-card" data-accent="rose">
                    <div class="pd-card-head">
                        <i class="bi bi-star-fill"></i>
                        <h5>Product Flags</h5>
                    </div>
                    <div class="pd-card-body">
                        <div class="pd-flags">
                            @foreach($activeFlags as $key => $flag)
                                @php [$label, $icon, $color] = $flag; @endphp
                                <span class="pd-flag {{ $color }}">
                                    <i class="bi {{ $icon }}"></i> {{ $label }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Timeline --}}
            <div class="pd-card" data-accent="teal">
                <div class="pd-card-head">
                    <i class="bi bi-clock-history"></i>
                    <h5>Activity</h5>
                </div>
                <div class="pd-card-body">
                    <div class="pd-timeline">
                        <div class="pd-tl-item">
                            <div class="pd-tl-dot"></div>
                            <strong>Product Created</strong>
                            <small>{{ $product->created_at->format('d M Y, h:i A') }}</small>
                        </div>
                        <div class="pd-tl-item">
                            <div class="pd-tl-dot" style="background: linear-gradient(135deg, #8B5CF6, #6D28D9)"></div>
                            <strong>Last Updated</strong>
                            <small>{{ $product->updated_at->format('d M Y, h:i A') }}</small>
                        </div>
                        @if($product->sold_count > 0)
                            <div class="pd-tl-item">
                                <div class="pd-tl-dot" style="background: linear-gradient(135deg, #10B981, #059669)"></div>
                                <strong>{{ $product->sold_count }} units sold</strong>
                                <small>Total lifetime sales</small>
                            </div>
                        @endif
                        @if($product->views > 0)
                            <div class="pd-tl-item">
                                <div class="pd-tl-dot" style="background: linear-gradient(135deg, #F59E0B, #D97706)"></div>
                                <strong>{{ number_format($product->views) }} views</strong>
                                <small>Storefront page visits</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ RIGHT COLUMN ============ --}}
        <div>

            {{-- Overview --}}
            <div class="pd-card" data-accent="blue">
                <div class="pd-card-head">
                    <i class="bi bi-info-circle-fill"></i>
                    <h5>Overview</h5>
                    <span class="badge">{{ ucfirst($product->type) }} Product</span>
                </div>
                <div class="pd-card-body">
                    <div class="pd-rows">
                        <div class="pd-row">
                            <span class="pd-row-label"><i class="bi bi-hash"></i>SKU</span>
                            <span class="pd-row-value mono">{{ $product->sku }}</span>
                        </div>
                        @if($product->barcode)
                            <div class="pd-row">
                                <span class="pd-row-label"><i class="bi bi-upc-scan"></i>Barcode</span>
                                <span class="pd-row-value mono">{{ $product->barcode }}</span>
                            </div>
                        @endif
                        <div class="pd-row">
                            <span class="pd-row-label"><i class="bi bi-tags-fill"></i>Category</span>
                            <span class="pd-row-value">{{ $product->category->name ?? '—' }}</span>
                        </div>
                        <div class="pd-row">
                            <span class="pd-row-label"><i class="bi bi-award-fill"></i>Brand</span>
                            <span class="pd-row-value">{{ $product->brand->name ?? '—' }}</span>
                        </div>
                        <div class="pd-row">
                            <span class="pd-row-label"><i class="bi bi-currency-rupee"></i>Selling Price</span>
                            <span class="pd-row-value price">₹{{ number_format($product->price, 2) }}</span>
                        </div>
                        @if($product->compare_price)
                            <div class="pd-row">
                                <span class="pd-row-label"><i class="bi bi-tag"></i>Compare Price</span>
                                <span class="pd-row-value">₹{{ number_format($product->compare_price, 2) }}</span>
                            </div>
                        @endif
                        @if($product->cost_price)
                            <div class="pd-row">
                                <span class="pd-row-label"><i class="bi bi-cash-stack"></i>Cost Price</span>
                                <span class="pd-row-value">₹{{ number_format($product->cost_price, 2) }}</span>
                            </div>
                        @endif
                        <div class="pd-row">
                            <span class="pd-row-label"><i class="bi bi-percent"></i>Tax Rate</span>
                            <span class="pd-row-value">{{ $product->tax_rate }}%</span>
                        </div>
                        <div class="pd-row">
                            <span class="pd-row-label"><i class="bi bi-circle-fill"></i>Status</span>
                            <span class="pd-pill {{ $product->status }}">{{ ucfirst($product->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Inventory --}}
            <div class="pd-card" data-accent="violet">
                <div class="pd-card-head">
                    <i class="bi bi-boxes"></i>
                    <h5>Inventory</h5>
                    <span class="badge">{{ $stockLabel }}</span>
                </div>
                <div class="pd-card-body">
                    <div class="pd-row" style="border:0;padding-top:0">
                        <span class="pd-row-label"><i class="bi bi-box"></i>Stock Quantity</span>
                        <span class="pd-pill {{ $stockStatus }}">{{ $product->stock }} units</span>
                    </div>
                    <div class="pd-stock-bar {{ $stockStatus }}">
                        <i style="width:{{ $product->stock <= 0 ? 0 : min(100, ($product->stock / max($product->stock, 100)) * 100) }}%"></i>
                    </div>
                    <div class="pd-rows" style="margin-top:14px">
                        <div class="pd-row">
                            <span class="pd-row-label"><i class="bi bi-exclamation-triangle"></i>Low Stock Alert</span>
                            <span class="pd-row-value">{{ $product->low_stock_alert }} units</span>
                        </div>
                        <div class="pd-row">
                            <span class="pd-row-label"><i class="bi bi-toggles"></i>Manage Stock</span>
                            <span class="pd-row-value">
                                {{ $product->manage_stock ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        @if($product->sold_count > 0)
                            <div class="pd-row">
                                <span class="pd-row-label"><i class="bi bi-graph-up"></i>Total Sold</span>
                                <span class="pd-row-value">{{ number_format($product->sold_count) }} units</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($product->short_description || $product->description)
                <div class="pd-card" data-accent="emerald">
                    <div class="pd-card-head">
                        <i class="bi bi-file-text-fill"></i>
                        <h5>Description</h5>
                    </div>
                    <div class="pd-card-body">
                        @if($product->short_description)
                            <div class="pd-short">
                                <i class="bi bi-quote"></i>
                                <span>{{ $product->short_description }}</span>
                            </div>
                        @endif
                        @if($product->description)
                            <div class="pd-desc">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Specifications --}}
            @if($product->specifications && count($product->specifications))
                <div class="pd-card" data-accent="teal">
                    <div class="pd-card-head">
                        <i class="bi bi-list-check"></i>
                        <h5>Specifications</h5>
                        <span class="badge">{{ count($product->specifications) }} items</span>
                    </div>
                    <div class="pd-card-body" style="padding:0">
                        <div class="pd-specs">
                            @foreach($product->specifications as $key => $value)
                                <div class="spec">
                                    <div class="spec-key">{{ $key }}</div>
                                    <div class="spec-value">{{ $value }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Attributes --}}
            @if($product->attributeValues->count())
                <div class="pd-card" data-accent="violet">
                    <div class="pd-card-head">
                        <i class="bi bi-sliders"></i>
                        <h5>Attributes</h5>
                    </div>
                    <div class="pd-card-body">
                        <div class="pd-attrs">
                            @php
                                $grouped = $product->attributeValues->groupBy(fn($v) => $v->attribute->name ?? 'Other');
                            @endphp
                            @foreach($grouped as $attrName => $values)
                                <div class="pd-attr-group">
                                    <span class="group-name">{{ $attrName }}</span>
                                    <div class="group-values">
                                        @foreach($values as $val)
                                            <span class="pd-attr-val">{{ $val->value }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Shipping --}}
            @if($product->weight || $product->length || $product->width || $product->height)
                <div class="pd-card" data-accent="amber">
                    <div class="pd-card-head">
                        <i class="bi bi-truck"></i>
                        <h5>Shipping Dimensions</h5>
                    </div>
                    <div class="pd-card-body">
                        <div class="pd-rows">
                            @if($product->weight)
                                <div class="pd-row">
                                    <span class="pd-row-label"><i class="bi bi-speedometer2"></i>Weight</span>
                                    <span class="pd-row-value">{{ $product->weight }} kg</span>
                                </div>
                            @endif
                            @if($product->length)
                                <div class="pd-row">
                                    <span class="pd-row-label"><i class="bi bi-arrows-expand"></i>Length</span>
                                    <span class="pd-row-value">{{ $product->length }} cm</span>
                                </div>
                            @endif
                            @if($product->width)
                                <div class="pd-row">
                                    <span class="pd-row-label"><i class="bi bi-arrows-expand-vertical"></i>Width</span>
                                    <span class="pd-row-value">{{ $product->width }} cm</span>
                                </div>
                            @endif
                            @if($product->height)
                                <div class="pd-row">
                                    <span class="pd-row-label"><i class="bi bi-arrows-collapse"></i>Height</span>
                                    <span class="pd-row-value">{{ $product->height }} cm</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- SEO Preview --}}
            <div class="pd-card" data-accent="blue">
                <div class="pd-card-head">
                    <i class="bi bi-search"></i>
                    <h5>SEO Preview</h5>
                </div>
                <div class="pd-card-body">
                    @if($product->meta_title || $product->meta_description)
                        <div class="pd-seo-preview">
                            <div class="pd-seo-url">
                                {{ url('/product/' . $product->slug) }}
                            </div>
                            <div class="pd-seo-title">
                                {{ $product->meta_title ?? $product->name }}
                            </div>
                            <div class="pd-seo-desc">
                                {{ $product->meta_description ?? $product->short_description ?? 'No description provided.' }}
                            </div>
                        </div>
                    @else
                        <div class="pd-seo-empty">
                            <i class="bi bi-info-circle" style="display:block;font-size:24px;margin-bottom:6px;opacity:.5"></i>
                            No SEO metadata set for this product.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    /* Image gallery switcher */
    $('.pd-gallery .thumb').on('click', function () {
        $('.pd-gallery .thumb').removeClass('active');
        $(this).addClass('active');
        const url = $(this).data('img');
        if (url) $('#pdMainImg').attr('src', url);
    });

    /* Delete product */
    $('#deleteProductBtn').on('click', function () {
        zConfirm('Are You Sure Delete product?', '.', function () {
            $.ajax({
                url: '{{ route("admin.products.destroy", $product) }}',
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).done(() => {
                zToast('Product deleted', 'success');
                setTimeout(() => window.location.href = '{{ route("admin.products") }}', 700);
            }).fail(() => zToast('Delete failed', 'error'));
        });
    });
});
</script>
@endpush
