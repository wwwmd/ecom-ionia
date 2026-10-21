@extends('layouts.admin')
@section('title', 'Products')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i><span>Products</span>
@endsection

@section('content')

@php
    $imgUrl = function ($path) {
        return \App\Models\Product::resolveImageUrl($path);
    };

    $stockInfo = function ($p) {
        if (isset($p->manage_stock) && !$p->manage_stock) return ['Unmanaged', 'muted'];
        if ($p->stock <= 0) return ['Out of stock', 'danger'];
        if ($p->stock <= ($p->low_stock_alert ?? 5)) return [$p->stock . ' left', 'warn'];
        return [$p->stock . ' in stock', 'ok'];
    };

    if (!isset($stats) && class_exists(\App\Models\Product::class)) {
        $by = \App\Models\Product::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');
        $stats = ['active' => (int)($by['active'] ?? 0), 'inactive' => (int)($by['inactive'] ?? 0), 'draft' => (int)($by['draft'] ?? 0)];
        $stats['total'] = array_sum($stats);
    }
    $total = max((int)($stats['total'] ?? $products->total()), 0);
    $pct = fn($n) => $total > 0 ? round(($n / $total) * 100) : 0;
    $cur = request('status');
    $cardUrl = fn($s) => route('admin.products', array_filter(array_merge(request()->except('status', 'page'), ['status' => $s])));
    $hasFilter = request()->filled('q') || request()->filled('category') || request()->filled('status');

    $statusCards = [
        ['key' => 'active',   'label' => 'Active',   'icon' => 'bi-check2-circle', 'note' => 'Live'],
        ['key' => 'inactive', 'label' => 'Inactive', 'icon' => 'bi-pause-circle',  'note' => 'Hidden'],
        ['key' => 'draft',    'label' => 'Draft',    'icon' => 'bi-pencil-square', 'note' => 'Draft'],
    ];
@endphp

<style>
/* ============================================
   PREMIUM PRODUCTS PAGE — COMPACT EDITION
   ============================================ */
.pp {
    --pp-primary: #2E6DB4;
    --pp-primary-dark: #1E4C82;
    --pp-primary-light: #E8F1FB;
    --pp-accent: #26A69A;
    --pp-accent-dark: #00796B;
    --pp-ink: #0F1E33;
    --pp-slate: #5A6C8C;
    --pp-muted: #8B9BB4;
    --pp-line: #E3E9F2;
    --pp-bg: #F5F8FC;
    --pp-card: #FFFFFF;
    --pp-emerald: #10B981;
    --pp-emerald-bg: #ECFDF5;
    --pp-amber: #F59E0B;
    --pp-amber-bg: #FFFBEB;
    --pp-rose: #EF4444;
    --pp-rose-bg: #FEF2F2;
    --pp-gradient: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    --pp-gradient-soft: linear-gradient(135deg, rgba(46,109,180,.08) 0%, rgba(38,166,154,.08) 100%);
    --pp-shadow-sm: 0 1px 2px rgba(15,30,51,.04);
    --pp-shadow: 0 4px 16px rgba(15,30,51,.06);
    --pp-shadow-lg: 0 12px 40px rgba(15,30,51,.10);
    --pp-shadow-xl: 0 24px 64px rgba(46,109,180,.14);
    --pp-radius: 14px;
    --pp-radius-sm: 10px;
    --pp-radius-lg: 16px;
    --pp-radius-xl: 18px;

    color: var(--pp-ink);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    -webkit-font-smoothing: antialiased;
}
.pp * { box-sizing: border-box; }

/* ============ HERO (COMPACT) ============ */
.pp-hero {
    position: relative;
    overflow: hidden;
    padding: 20px 26px;
    margin-bottom: 16px;
    border-radius: var(--pp-radius-xl);
    background: linear-gradient(135deg, #0F1E33 0%, #1E4C82 50%, #2E6DB4 100%);
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    box-shadow: 0 10px 30px rgba(46,109,180,.22);
    min-height: 96px;
}
.pp-hero::before {
    content: "";
    position: absolute;
    right: -60px;
    top: -80px;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(38,166,154,.42), transparent 65%);
    pointer-events: none;
}
.pp-hero::after {
    content: "";
    position: absolute;
    left: -60px;
    bottom: -80px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(46,109,180,.5), transparent 65%);
    pointer-events: none;
}
.pp-hero > * { position: relative; z-index: 1; }

.pp-hero-left { display: flex; align-items: center; gap: 16px; min-width: 0; }
.pp-hero-badge {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #26A69A;
    flex-shrink: 0;
}
.pp-hero h1 {
    font-size: 20px;
    font-weight: 800;
    margin: 0 0 2px;
    color: #fff;
    letter-spacing: -.02em;
    line-height: 1.15;
}
.pp-hero p {
    margin: 0;
    color: rgba(255,255,255,.68);
    font-size: 13px;
    line-height: 1.3;
}

.pp-hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }

.pp-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 9px 18px;
    border: none;
    border-radius: 99px;
    background: #fff;
    color: var(--pp-primary-dark);
    font-weight: 700;
    font-size: 13px;
    text-decoration: none;
    cursor: pointer;
    transition: all .22s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 3px 10px rgba(0,0,0,.12);
    white-space: nowrap;
}
.pp-btn:hover {
    color: var(--pp-primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,.18);
}
.pp-btn i { font-size: 14px; }

.pp-btn-primary {
    background: var(--pp-gradient);
    color: #fff;
    box-shadow: 0 4px 14px rgba(46,109,180,.32);
}
.pp-btn-primary:hover {
    background: linear-gradient(135deg, #1E4C82 0%, #00796B 100%);
    color: #fff;
    box-shadow: 0 8px 22px rgba(46,109,180,.42);
}

.pp-btn-ghost {
    background: #fff;
    color: var(--pp-slate);
    border: 1.5px solid var(--pp-line);
    box-shadow: none;
}
.pp-btn-ghost:hover {
    border-color: var(--pp-primary);
    color: var(--pp-primary);
    background: var(--pp-primary-light);
    box-shadow: none;
}

/* ============ STAT CARDS (SLIM HORIZONTAL) ============ */
.pp-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}

.pp-stat {
    position: relative;
    padding: 14px 16px;
    border-radius: var(--pp-radius);
    background: var(--pp-card);
    border: 1px solid var(--pp-line);
    text-decoration: none;
    color: var(--pp-ink);
    overflow: hidden;
    transition: all .25s cubic-bezier(.4,0,.2,1);
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 78px;
}
.pp-stat:hover {
    color: var(--pp-ink);
    transform: translateY(-2px);
    box-shadow: var(--pp-shadow);
    border-color: transparent;
}
.pp-stat::before {
    content: "";
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--pp-accent-c, var(--pp-primary));
    transition: width .25s;
}
.pp-stat:hover::before { width: 5px; }

.pp-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
    background: var(--pp-accent-gradient, var(--pp-gradient));
    box-shadow: 0 3px 10px var(--pp-accent-shadow, rgba(46,109,180,.24));
    flex-shrink: 0;
}

.pp-stat-body { flex: 1; min-width: 0; }
.pp-stat-value {
    font-size: 22px;
    font-weight: 800;
    line-height: 1;
    letter-spacing: -.03em;
    color: var(--pp-ink);
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    margin-bottom: 2px;
}
.pp-stat-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--pp-ink);
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.pp-stat-pct {
    position: absolute;
    top: 10px;
    right: 12px;
    padding: 2px 8px;
    border-radius: 99px;
    background: var(--pp-accent-bg, var(--pp-primary-light));
    color: var(--pp-accent-c, var(--pp-primary));
    font-size: 10px;
    font-weight: 700;
    letter-spacing: -.01em;
    line-height: 1.6;
}

/* Stat variants */
.pp-stat-total {
    --pp-accent-c: #0F1E33;
    --pp-accent-bg: #E9EDF2;
    --pp-accent-gradient: linear-gradient(135deg, #35567F, #0F1E33);
    --pp-accent-shadow: rgba(15,30,51,.22);
}
.pp-stat-active {
    --pp-accent-c: var(--pp-accent-dark);
    --pp-accent-bg: var(--pp-emerald-bg);
    --pp-accent-gradient: linear-gradient(135deg, #26A69A, #00796B);
    --pp-accent-shadow: rgba(38,166,154,.28);
}
.pp-stat-inactive {
    --pp-accent-c: var(--pp-slate);
    --pp-accent-bg: #F1F5F9;
    --pp-accent-gradient: linear-gradient(135deg, #5A6C8C, #3A4A53);
    --pp-accent-shadow: rgba(90,108,140,.24);
}
.pp-stat-draft {
    --pp-accent-c: #B45309;
    --pp-accent-bg: var(--pp-amber-bg);
    --pp-accent-gradient: linear-gradient(135deg, #F59E0B, #D97706);
    --pp-accent-shadow: rgba(245,158,11,.28);
}

.pp-stat.is-active {
    border-color: var(--pp-accent-c);
    box-shadow: 0 0 0 3px var(--pp-accent-bg), var(--pp-shadow-sm);
}

/* ============ FILTERS ============ */
.pp-card {
    background: var(--pp-card);
    border: 1px solid var(--pp-line);
    border-radius: var(--pp-radius-lg);
    box-shadow: var(--pp-shadow-sm);
    overflow: hidden;
}

.pp-filters {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(0, 1.4fr) minmax(0, 1fr) auto;
    gap: 12px;
    align-items: end;
    padding: 16px;
    margin-bottom: 14px;
}
.pp-fi { min-width: 0; }
.pp-fi label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: var(--pp-slate);
    margin-bottom: 6px;
    letter-spacing: .02em;
    text-transform: uppercase;
}
.pp-input {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--pp-line);
    border-radius: var(--pp-radius-sm);
    background: #FAFCFD;
    color: var(--pp-ink);
    font-size: 13px;
    font-family: inherit;
    transition: all .2s;
    appearance: none;
    -webkit-appearance: none;
}
.pp-input::placeholder { color: var(--pp-muted); }
.pp-input:hover { border-color: #CBD5E1; }
.pp-input:focus {
    outline: none;
    border-color: var(--pp-primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(46,109,180,.1);
}
select.pp-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none' stroke='%235A6C8C' stroke-width='2'%3E%3Cpath d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 34px;
    cursor: pointer;
}
.pp-fi-search { position: relative; }
.pp-fi-search .pp-input { padding-left: 38px; }
.pp-fi-search::before {
    content: "\F52A";
    font-family: "bootstrap-icons";
    position: absolute;
    left: 13px;
    bottom: 10px;
    font-size: 14px;
    color: var(--pp-muted);
    pointer-events: none;
}

.pp-fbtn { display: flex; gap: 6px; }

/* ============ LIST HEADER ============ */
.pp-listhead {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-bottom: 1px solid var(--pp-line);
    flex-wrap: wrap;
}
.pp-listhead-count {
    font-size: 12.5px;
    color: var(--pp-slate);
    font-weight: 500;
}
.pp-listhead-count strong { color: var(--pp-ink); font-weight: 700; }

.pp-toggle {
    display: inline-flex;
    padding: 3px;
    border-radius: 10px;
    background: var(--pp-bg);
    border: 1px solid var(--pp-line);
    gap: 2px;
}
.pp-toggle button {
    border: 0;
    background: transparent;
    padding: 6px 11px;
    border-radius: 7px;
    color: var(--pp-muted);
    cursor: pointer;
    font-size: 14px;
    transition: all .18s;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
}
.pp-toggle button:hover { color: var(--pp-primary); }
.pp-toggle button.on {
    background: #fff;
    color: var(--pp-primary);
    box-shadow: 0 1px 4px rgba(15,30,51,.08);
}

/* ============ TABLE ============ */
.pp-tablewrap { overflow-x: auto; max-height: 70vh; }
.pp-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
    min-width: 780px;
}
.pp-table thead th {
    padding: 11px 18px;
    font-size: 10.5px;
    font-weight: 700;
    color: var(--pp-slate);
    text-align: left;
    background: #F7FAFC;
    border-bottom: 1px solid var(--pp-line);
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 2;
}
.pp-table td {
    padding: 14px 18px;
    border-bottom: 1px solid #EEF2F7;
    vertical-align: middle;
    font-size: 13.5px;
    color: var(--pp-ink);
}
.pp-table tbody tr { transition: background .15s; }
.pp-table tbody tr:hover { background: #F8FBFE; }
.pp-table tbody tr:last-child td { border-bottom: 0; }

.pp-pc { display: flex; align-items: center; gap: 12px; min-width: 240px; }
.pp-img {
    position: relative;
    width: 44px;
    height: 44px;
    border-radius: 11px;
    background: linear-gradient(135deg, #F5F8FC, #E8F1FB);
    border: 1px solid var(--pp-line);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #A8B5C4;
    font-size: 18px;
    overflow: hidden;
    flex: none;
    transition: all .22s;
}
.pp-table tbody tr:hover .pp-img { border-color: var(--pp-primary); }
.pp-img img {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .35s;
}
.pp-table tbody tr:hover .pp-img img { transform: scale(1.06); }

.pp-pc-info { min-width: 0; }
.pp-pc-name {
    display: block;
    font-weight: 600;
    font-size: 13.5px;
    color: var(--pp-ink);
    line-height: 1.35;
    margin-bottom: 3px;
    transition: color .18s;
}
.pp-table tbody tr:hover .pp-pc-name { color: var(--pp-primary); }
.pp-pc-meta {
    font-size: 11.5px;
    color: var(--pp-muted);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.pp-pc-meta .pp-dot {
    width: 3px; height: 3px; border-radius: 50%;
    background: currentColor; opacity: .5;
}
.pp-tags { display: flex; gap: 4px; flex-wrap: wrap; margin-top: 5px; }
.pp-tag {
    display: inline-flex;
    align-items: center;
    padding: 2px 7px;
    border-radius: 99px;
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
    background: var(--pp-primary-light);
    color: var(--pp-primary);
}
.pp-tag-hot { background: #FEE2E2; color: #B91C1C; }
.pp-tag-new { background: var(--pp-emerald-bg); color: var(--pp-accent-dark); }
.pp-tag-top { background: #F5F3FF; color: #6D28D9; }

.pp-sku {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 11.5px;
    color: var(--pp-slate);
    font-weight: 600;
}
.pp-price-current {
    display: block;
    font-weight: 700;
    font-size: 13.5px;
    color: var(--pp-ink);
    letter-spacing: -.01em;
}
.pp-price-old {
    display: block;
    font-size: 11px;
    color: var(--pp-muted);
    text-decoration: line-through;
    margin-top: 1px;
}

.pp-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 99px;
    font-size: 11.5px;
    font-weight: 600;
    white-space: nowrap;
    line-height: 1.4;
}
.pp-pill::before {
    content: "";
    width: 5px; height: 5px;
    border-radius: 50%;
    background: currentColor;
    flex-shrink: 0;
}
.pp-pill-ok      { background: var(--pp-emerald-bg); color: var(--pp-accent-dark); }
.pp-pill-warn    { background: var(--pp-amber-bg);   color: #B45309; }
.pp-pill-danger  { background: var(--pp-rose-bg);    color: #B91C1C; }
.pp-pill-muted   { background: #F1F5F9;              color: var(--pp-slate); }
.pp-pill-active  { background: var(--pp-emerald-bg); color: var(--pp-accent-dark); }
.pp-pill-inactive{ background: #F1F5F9;              color: var(--pp-slate); }
.pp-pill-draft   { background: var(--pp-amber-bg);   color: #B45309; }

.pp-acts { display: flex; gap: 4px; justify-content: flex-end; }
.pp-act {
    width: 32px;
    height: 32px;
    border: 1.5px solid var(--pp-line);
    border-radius: 9px;
    background: #fff;
    color: var(--pp-slate);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    cursor: pointer;
    transition: all .18s;
    font-size: 14px;
}
.pp-act:hover {
    border-color: var(--pp-primary);
    color: var(--pp-primary);
    background: var(--pp-primary-light);
    transform: translateY(-1px);
}
.pp-act-del:hover {
    border-color: #FCA5A5;
    color: #DC2626;
    background: var(--pp-rose-bg);
}

/* ============ GRID VIEW ============ */
.pp-grid {
    display: none;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 14px;
    padding: 18px;
}
.pp-gc {
    border: 1px solid var(--pp-line);
    border-radius: var(--pp-radius-lg);
    background: #fff;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all .25s cubic-bezier(.4,0,.2,1);
}
.pp-gc:hover {
    box-shadow: var(--pp-shadow-lg);
    transform: translateY(-3px);
    border-color: transparent;
}
.pp-gc-img {
    position: relative;
    aspect-ratio: 1/1;
    background: linear-gradient(135deg, #F5F8FC, #E8F1FB);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #A8B5C4;
    font-size: 42px;
    overflow: hidden;
}
.pp-gc-img img {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .45s;
}
.pp-gc:hover .pp-gc-img img { transform: scale(1.05); }
.pp-gc-badge {
    position: absolute;
    top: 10px; left: 10px;
    z-index: 1;
    box-shadow: 0 2px 6px rgba(0,0,0,.08);
    background: rgba(255,255,255,.95) !important;
}
.pp-gc-actions {
    position: absolute;
    top: 10px; right: 10px;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 5px;
    opacity: 0;
    transform: translateX(6px);
    transition: all .2s;
}
.pp-gc:hover .pp-gc-actions { opacity: 1; transform: translateX(0); }
.pp-gc-actions .pp-act {
    width: 30px;
    height: 30px;
    background: rgba(255,255,255,.96);
    box-shadow: 0 2px 6px rgba(0,0,0,.1);
}

.pp-gc-body { padding: 12px 14px; flex: 1; display: flex; flex-direction: column; gap: 5px; }
.pp-gc-cat {
    font-size: 10px;
    font-weight: 700;
    color: var(--pp-primary);
    text-transform: uppercase;
    letter-spacing: .4px;
}
.pp-gc-title {
    font-weight: 600;
    font-size: 13px;
    line-height: 1.35;
    color: var(--pp-ink);
    min-height: 35px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.pp-gc-sku {
    font-family: ui-monospace, monospace;
    font-size: 10.5px;
    color: var(--pp-muted);
}
.pp-gc-foot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-top: 1px solid var(--pp-line);
    background: #FAFCFD;
}

.pp.is-grid .pp-tablewrap { display: none; }
.pp.is-grid .pp-grid { display: grid; }

/* ============ EMPTY STATE ============ */
.pp-empty { text-align: center; padding: 60px 24px; }
.pp-empty-icon {
    width: 72px; height: 72px;
    border-radius: 20px;
    margin: 0 auto 16px;
    background: var(--pp-gradient-soft);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: var(--pp-primary);
}
.pp-empty h3 { font-size: 17px; font-weight: 700; margin: 0 0 6px; }
.pp-empty p { color: var(--pp-muted); margin: 0 0 20px; font-size: 13px; }

/* ============ PAGINATION ============ */
.pp-pager {
    padding: 16px;
    border-top: 1px solid var(--pp-line);
    display: flex;
    justify-content: center;
    background: #FAFCFD;
}
.pp-pager .pagination { margin: 0; gap: 3px; }
.pp-pager .page-link {
    border: none;
    color: var(--pp-slate);
    font-weight: 600;
    border-radius: 8px;
    padding: 7px 12px;
    font-size: 12.5px;
    transition: all .15s;
    background: #fff;
}
.pp-pager .page-link:hover { background: var(--pp-primary-light); color: var(--pp-primary); }
.pp-pager .page-item.active .page-link {
    background: var(--pp-gradient);
    color: #fff;
    box-shadow: 0 3px 10px rgba(46,109,180,.28);
}
.pp-pager .page-item.disabled .page-link { color: #CBD5E1; background: transparent; }

/* ============ RESPONSIVE ============ */
@media (max-width: 1199.98px) {
    .pp-stats { grid-template-columns: repeat(2, 1fr); }
    .pp-filters { grid-template-columns: 1fr 1fr; }
    .pp-fi-search { grid-column: 1 / -1; }
    .pp-fbtn { grid-column: 1 / -1; }
    .pp-fbtn .pp-btn { flex: 1; }
}

@media (max-width: 767.98px) {
    .pp-hero { padding: 16px 18px; min-height: 82px; }
    .pp-hero h1 { font-size: 17px; }
    .pp-hero p { font-size: 12px; }
    .pp-hero-badge { width: 40px; height: 40px; font-size: 18px; }
    .pp-hero-actions { width: 100%; }
    .pp-hero-actions .pp-btn { flex: 1; }
    .pp-stat { padding: 12px 14px; min-height: 70px; gap: 10px; }
    .pp-stat-value { font-size: 19px; }
    .pp-stat-icon { width: 36px; height: 36px; font-size: 16px; border-radius: 10px; }
    .pp-filters { padding: 14px; gap: 10px; }
    .pp-listhead { padding: 12px 14px; }
    .pp-table thead th, .pp-table td { padding: 12px 14px; }
    .pp-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; padding: 12px; }
    .pp-gc-body { padding: 10px 12px; }
    .pp-gc-foot { padding: 8px 12px; }
}

@media (max-width: 575.98px) {
    .pp-hero { padding: 14px 16px; min-height: 76px; gap: 12px; }
    .pp-hero h1 { font-size: 16px; }
    .pp-hero p { font-size: 11.5px; }
    .pp-hero-badge { width: 36px; height: 36px; font-size: 16px; border-radius: 10px; }
    .pp-hero-actions .pp-btn { padding: 8px 16px; font-size: 12.5px; }
    .pp-stats { gap: 8px; }
    .pp-stat { padding: 10px 12px; min-height: 64px; gap: 8px; }
    .pp-stat-value { font-size: 17px; }
    .pp-stat-label { font-size: 11px; }
    .pp-stat-pct { font-size: 9px; padding: 2px 6px; top: 8px; right: 8px; }
    .pp-stat-icon { width: 32px; height: 32px; font-size: 14px; border-radius: 9px; }
    .pp-filters { grid-template-columns: 1fr; padding: 12px; gap: 10px; }
    .pp-filters .pp-fi-search, .pp-filters .pp-fbtn { grid-column: auto; }
    .pp-fbtn { flex-direction: row; }
    .pp-table { min-width: auto; }
    .pp-pc { min-width: 0; gap: 8px; }
    .pp-img { width: 40px; height: 40px; font-size: 16px; border-radius: 10px; }
    .pp-pc-name { font-size: 13px; }
    .pp-grid { grid-template-columns: 1fr; }
    .pp-listhead { padding: 10px 12px; }
    .pp-pager { padding: 12px; }
}

@media (prefers-reduced-motion: reduce) {
    .pp * { transition: none !important; animation: none !important; }
}
.pp-act:focus-visible, .pp-btn:focus-visible, .pp-input:focus-visible {
    outline: 2px solid var(--pp-primary);
    outline-offset: 2px;
}
</style>

<div class="pp" id="ppRoot">

    {{-- HERO --}}
    <div class="pp-hero">
        <div class="pp-hero-left">
            <div class="pp-hero-badge"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <h1>Products</h1>
                <p>Manage your catalog, pricing and stock.</p>
            </div>
        </div>
        <div class="pp-hero-actions">
            <a href="{{ route('admin.products.create') }}" class="pp-btn">
                <i class="bi bi-plus-lg"></i> Add Product
            </a>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="pp-stats">
        <a href="{{ $cardUrl(null) }}" class="pp-stat pp-stat-total {{ !$cur ? 'is-active' : '' }}">
            <div class="pp-stat-icon"><i class="bi bi-box-seam"></i></div>
            <div class="pp-stat-body">
                <div class="pp-stat-value">{{ number_format($total) }}</div>
                <div class="pp-stat-label">All Products</div>
            </div>
        </a>

        @foreach($statusCards as $sc)
            @php $n = (int)($stats[$sc['key']] ?? 0); @endphp
            <a href="{{ $cardUrl($sc['key']) }}"
               class="pp-stat pp-stat-{{ $sc['key'] }} {{ $cur === $sc['key'] ? 'is-active' : '' }}">
                <div class="pp-stat-icon"><i class="bi {{ $sc['icon'] }}"></i></div>
                <div class="pp-stat-body">
                    <div class="pp-stat-value">{{ number_format($n) }}</div>
                    <div class="pp-stat-label">{{ $sc['label'] }}</div>
                </div>
                <span class="pp-stat-pct">{{ $pct($n) }}%</span>
            </a>
        @endforeach
    </div>

    {{-- FILTERS --}}
    <form method="GET" class="pp-card pp-filters">
        <div class="pp-fi pp-fi-search">
            <label>Search</label>
            <input type="text" name="q" class="pp-input" value="{{ request('q') }}" placeholder="Search by name or SKU...">
        </div>
        <div class="pp-fi">
            <label>Category</label>
            <select name="category" class="pp-input">
                <option value="">All Categories</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="pp-fi">
            <label>Status</label>
            <select name="status" class="pp-input">
                <option value="">All Status</option>
                <option value="active" @selected($cur == 'active')>Active</option>
                <option value="inactive" @selected($cur == 'inactive')>Inactive</option>
                <option value="draft" @selected($cur == 'draft')>Draft</option>
            </select>
        </div>
        <div class="pp-fi pp-fbtn">
            <button type="submit" class="pp-btn pp-btn-primary"><i class="bi bi-funnel"></i> Filter</button>
            @if($hasFilter)
                <a href="{{ route('admin.products') }}" class="pp-btn pp-btn-ghost"><i class="bi bi-arrow-clockwise"></i> Reset</a>
            @endif
        </div>
    </form>

    {{-- LIST --}}
    <div class="pp-card">
        <div class="pp-listhead">
            <div class="pp-listhead-count">
                Showing <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
                of <strong>{{ $products->total() }}</strong> products
            </div>
            <div class="pp-toggle" role="group">
                <button type="button" data-view="table" class="on"><i class="bi bi-list-ul"></i></button>
                <button type="button" data-view="grid"><i class="bi bi-grid-3x3-gap"></i></button>
            </div>
        </div>

        @if($products->count())
        <div class="pp-tablewrap">
            <table class="pp-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="d-none d-lg-table-cell">SKU</th>
                        <th class="d-none d-md-table-cell">Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="d-none d-sm-table-cell">Status</th>
                        <th class="text-end" style="width:120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($products as $p)
                    @php
                        [$stockLabel, $stockCls] = $stockInfo($p);
                        $img = $imgUrl($p->thumbnail);
                    @endphp
                    <tr>
                        <td>
                            <div class="pp-pc">
                                <div class="pp-img">
                                    <i class="bi bi-image"></i>
                                    @if($img)<img src="{{ $img }}" alt="" loading="lazy" onerror="this.remove()">@endif
                                </div>
                                <div class="pp-pc-info">
                                    <a href="{{ route('admin.products.show', $p) }}" class="pp-pc-name">{{ \Illuminate\Support\Str::limit($p->name, 42) }}</a>
                                    <div class="pp-pc-meta">
                                        <span>{{ $p->brand->name ?? 'No brand' }}</span>
                                        <span class="pp-dot"></span>
                                        <span class="d-lg-none">{{ $p->sku }}</span>
                                    </div>
                                    @if($p->is_featured || $p->is_trending || $p->is_new_arrival || $p->is_best_seller)
                                    <div class="pp-tags">
                                        @if($p->is_featured)<span class="pp-tag">Featured</span>@endif
                                        @if($p->is_trending)<span class="pp-tag pp-tag-hot">Trending</span>@endif
                                        @if($p->is_new_arrival)<span class="pp-tag pp-tag-new">New</span>@endif
                                        @if($p->is_best_seller)<span class="pp-tag pp-tag-top">Best</span>@endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="d-none d-lg-table-cell"><span class="pp-sku">{{ $p->sku }}</span></td>
                        <td class="d-none d-md-table-cell">{{ $p->category->name ?? '—' }}</td>
                        <td>
                            <span class="pp-price-current">₹{{ number_format($p->price, 2) }}</span>
                            @if(($p->compare_price ?? 0) > $p->price)
                                <span class="pp-price-old">₹{{ number_format($p->compare_price, 2) }}</span>
                            @endif
                        </td>
                        <td><span class="pp-pill pp-pill-{{ $stockCls }}">{{ $stockLabel }}</span></td>
                        <td class="d-none d-sm-table-cell"><span class="pp-pill pp-pill-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                        <td>
                            <div class="pp-acts">
                                <a href="{{ route('admin.products.show', $p) }}" class="pp-act" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.products.edit', $p) }}" class="pp-act" title="Edit"><i class="bi bi-pencil"></i></a>
                                <button type="button" class="pp-act pp-act-del delete-product" data-id="{{ $p->id }}" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

       <div class="pp-grid">
    @foreach($products as $p)
        @php
            [$stockLabel, $stockCls] = $stockInfo($p);
            $img = $imgUrl($p->thumbnail);
        @endphp
        <div class="pp-gc">
            <div class="pp-gc-img">
                <i class="bi bi-image"></i>
                @if($img)<img src="{{ $img }}" alt="" loading="lazy" onerror="this.remove()">@endif
                <span class="pp-pill pp-pill-{{ $p->status }} pp-gc-badge">{{ ucfirst($p->status) }}</span>
                <div class="pp-gc-actions">
                    <a href="{{ route('admin.products.edit', $p) }}" class="pp-act" title="Edit"><i class="bi bi-pencil"></i></a>
                    <button type="button" class="pp-act pp-act-del delete-product" data-id="{{ $p->id }}" title="Delete"><i class="bi bi-trash"></i></button>
                </div>
            </div>
            <div class="pp-gc-body">
                <div class="pp-gc-cat">{{ $p->category->name ?? 'Uncategorized' }}</div>
                <div class="pp-gc-title">{{ \Illuminate\Support\Str::limit($p->name, 42) }}</div>
                <div class="pp-gc-sku">{{ $p->sku }}</div>
                <div>
                    <span class="pp-price-current">₹{{ number_format($p->price, 2) }}</span>
                    @if(($p->compare_price ?? 0) > $p->price)
                        <span class="pp-price-old">₹{{ number_format($p->compare_price, 2) }}</span>
                    @endif
                </div>
                <div><span class="pp-pill pp-pill-{{ $stockCls }}">{{ $stockLabel }}</span></div>
            </div>
            <div class="pp-gc-foot">
                <a href="{{ route('admin.products.show', $p) }}" class="pp-act" title="View"><i class="bi bi-eye"></i></a>
                <span class="pp-gc-sku">{{ $p->brand->name ?? '—' }}</span>
            </div>
        </div>
    @endforeach
</div>

        @else
            <div class="pp-empty">
                <div class="pp-empty-icon"><i class="bi bi-inbox"></i></div>
                <h3>{{ $hasFilter ? 'No products found' : 'No products yet' }}</h3>
                <p>{{ $hasFilter ? 'Try changing filters or search query.' : 'Start building your catalog by adding your first product.' }}</p>
                <a href="{{ route('admin.products.create') }}" class="pp-btn pp-btn-primary">
                    <i class="bi bi-plus-lg"></i> Add Product
                </a>
            </div>
        @endif

        @if($products->hasPages())
            <div class="pp-pager">{{ $products->withQueryString()->links() }}</div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    const root = $('#ppRoot');
    let view = null;
    try { view = localStorage.getItem('pp_view'); } catch (e) {}
    if (!view) view = window.innerWidth < 576 ? 'grid' : 'table';
    function setView(v) {
        root.toggleClass('is-grid', v === 'grid');
        $('.pp-toggle button').removeClass('on').filter('[data-view="' + v + '"]').addClass('on');
    }
    setView(view);
    $('.pp-toggle button').on('click', function () {
        const v = $(this).data('view'); setView(v);
        try { localStorage.setItem('pp_view', v); } catch (e) {}
    });

$(document).on('click', '.delete-product', function () {
    const id = $(this).data('id');
    const $row = $(this).closest('tr, .pp-gc');
    const name = $row.find('.pp-pc-name, .pp-gc-title').text().trim() || 'this product';

    zConfirm(
        'Delete this product?',
        'You are about to delete <strong>' + name + '</strong>.',
        function () {
            $.ajax({
                url: '{{ url("/admin/products") }}/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .done(() => {
                zSuccess('Product deleted successfully!');
                $row.fadeOut(300, function () { $(this).remove(); });
            })
            .fail((xhr) => {
                if (xhr.status === 404) {
                    zInfo('Product was already deleted.');
                    $row.fadeOut(300, function () { $(this).remove(); });
                } else {
                    zError('Could not delete product.');
                }
            });
        },
        { type: 'danger', confirmText: 'Yes, Delete' }
    );
});

});
</script>
@endpush
