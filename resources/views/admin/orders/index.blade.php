@extends('layouts.admin')
@section('title', 'Orders')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i><span>Orders</span>
@endsection

@section('content')

@php
    $by = \App\Models\Order::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');

    $pending    = (int)($by['pending'] ?? 0);
    $processing = (int)($by['processing'] ?? 0) + (int)($by['confirmed'] ?? 0) + (int)($by['packed'] ?? 0);
    $shipped    = (int)($by['shipped'] ?? 0) + (int)($by['out_for_delivery'] ?? 0);
    $delivered  = (int)($by['delivered'] ?? 0);
    $cancelled  = (int)($by['cancelled'] ?? 0);
    $returned   = (int)($by['returned'] ?? 0);
    $refunded   = (int)($by['refunded'] ?? 0);
    $totalOrders = (int) $by->sum();

    $pct = fn($n) => $totalOrders > 0 ? round(($n / $totalOrders) * 100) : 0;
    $cur = request('status');
    $curPayment = request('payment');
    $hasFilter = $cur || $curPayment || request('from') || request('to');

    $statusCards = [
        ['key' => 'pending',    'label' => 'Pending',    'icon' => 'bi-hourglass-split',  'count' => $pending],
        ['key' => 'processing', 'label' => 'Processing', 'icon' => 'bi-arrow-repeat',     'count' => $processing],
        ['key' => 'shipped',    'label' => 'Shipped',    'icon' => 'bi-truck',            'count' => $shipped],
        ['key' => 'delivered',  'label' => 'Delivered',  'icon' => 'bi-check2-circle',    'count' => $delivered],
        ['key' => 'cancelled',  'label' => 'Cancelled',  'icon' => 'bi-x-circle',         'count' => $cancelled],
        ['key' => 'returned',   'label' => 'Returned',   'icon' => 'bi-arrow-return-left','count' => $returned],
        ['key' => 'refunded',   'label' => 'Refunded',   'icon' => 'bi-cash-coin',        'count' => $refunded],
    ];
@endphp

<style>
/* ============================================================
   PREMIUM ORDERS PAGE
   ============================================================ */
.op {
    --op-primary: #2E6DB4;
    --op-primary-dark: #1E4C82;
    --op-primary-light: #E8F1FB;
    --op-accent: #26A69A;
    --op-ink: #0F1E33;
    --op-slate: #5A6C8C;
    --op-muted: #8B9BB4;
    --op-line: #E3E9F2;
    --op-bg: #F5F8FC;
    --op-gradient: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    color: var(--op-ink);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    -webkit-font-smoothing: antialiased;
}
.op * { box-sizing: border-box; }

/* ============ HERO ============ */
.op-hero {
    position: relative;
    overflow: hidden;
    padding: 20px 26px;
    margin-bottom: 16px;
    border-radius: 18px;
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
.op-hero::before {
    content: "";
    position: absolute;
    right: -60px; top: -80px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(38,166,154,.42), transparent 65%);
    pointer-events: none;
}
.op-hero::after {
    content: "";
    position: absolute;
    left: -60px; bottom: -80px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(46,109,180,.5), transparent 65%);
    pointer-events: none;
}
.op-hero > * { position: relative; z-index: 1; }
.op-hero-left { display: flex; align-items: center; gap: 16px; min-width: 0; }
.op-hero-badge {
    width: 46px; height: 46px; border-radius: 12px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #26A69A; flex-shrink: 0;
}
.op-hero h1 {
    font-size: 20px; font-weight: 800;
    margin: 0 0 2px; color: #fff;
    letter-spacing: -.02em; line-height: 1.15;
}
.op-hero p { margin: 0; color: rgba(255,255,255,.68); font-size: 13px; line-height: 1.3; }
.op-hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }

.op-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    padding: 9px 18px; border: none; border-radius: 99px;
    background: #fff; color: var(--op-primary-dark);
    font-weight: 700; font-size: 13px; text-decoration: none; cursor: pointer;
    transition: all .22s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 3px 10px rgba(0,0,0,.12); white-space: nowrap;
}
.op-btn:hover { color: var(--op-primary-dark); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.18); }
.op-btn i { font-size: 14px; }
.op-btn-primary {
    background: var(--op-gradient); color: #fff;
    box-shadow: 0 4px 14px rgba(46,109,180,.32);
}
.op-btn-primary:hover {
    background: linear-gradient(135deg, #1E4C82 0%, #00796B 100%);
    color: #fff; box-shadow: 0 8px 22px rgba(46,109,180,.42);
}
.op-btn-ghost {
    background: #fff; color: var(--op-slate);
    border: 1.5px solid var(--op-line); box-shadow: none;
}
.op-btn-ghost:hover {
    border-color: var(--op-primary); color: var(--op-primary);
    background: var(--op-primary-light); box-shadow: none;
}

/* ============ STAT CARDS ============ */
.op-stats {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}
.op-stat {
    position: relative;
    padding: 14px 16px;
    border-radius: 14px;
    background: #fff;
    border: 1px solid var(--op-line);
    text-decoration: none;
    color: var(--op-ink);
    overflow: hidden;
    transition: all .25s;
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 78px;
}
.op-stat:hover {
    color: var(--op-ink);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px -6px rgba(15,30,51,.1);
    border-color: transparent;
}
.op-stat::before {
    content: "";
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--op-accent-c, var(--op-primary));
    transition: width .25s;
}
.op-stat:hover::before { width: 5px; }

.op-stat-icon {
    width: 40px; height: 40px;
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #fff;
    background: var(--op-accent-gradient, var(--op-gradient));
    box-shadow: 0 3px 10px var(--op-accent-shadow, rgba(46,109,180,.24));
    flex-shrink: 0;
}
.op-stat-body { flex: 1; min-width: 0; }
.op-stat-value {
    font-size: 22px; font-weight: 800;
    line-height: 1; letter-spacing: -.03em;
    color: var(--op-ink);
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    margin-bottom: 2px;
}
.op-stat-label { font-size: 11.5px; font-weight: 600; color: var(--op-ink); line-height: 1.2; }
.op-stat-pct {
    position: absolute;
    top: 10px; right: 12px;
    padding: 2px 8px;
    border-radius: 99px;
    background: var(--op-accent-bg, var(--op-primary-light));
    color: var(--op-accent-c, var(--op-primary));
    font-size: 10px; font-weight: 700;
    line-height: 1.6;
}

.op-stat-total {
    --op-accent-c: #0F1E33;
    --op-accent-bg: #E9EDF2;
    --op-accent-gradient: linear-gradient(135deg, #35567F, #0F1E33);
    --op-accent-shadow: rgba(15,30,51,.22);
}
.op-stat-pending {
    --op-accent-c: #B45309;
    --op-accent-bg: #FFFBEB;
    --op-accent-gradient: linear-gradient(135deg, #F59E0B, #D97706);
    --op-accent-shadow: rgba(245,158,11,.28);
}
.op-stat-processing {
    --op-accent-c: #1E40AF;
    --op-accent-bg: #EFF6FF;
    --op-accent-gradient: linear-gradient(135deg, #60A5FA, #2563EB);
    --op-accent-shadow: rgba(59,130,246,.28);
}
.op-stat-shipped {
    --op-accent-c: #6D28D9;
    --op-accent-bg: #F5F3FF;
    --op-accent-gradient: linear-gradient(135deg, #A78BFA, #7C3AED);
    --op-accent-shadow: rgba(139,92,246,.28);
}
.op-stat-delivered {
    --op-accent-c: #047857;
    --op-accent-bg: #ECFDF5;
    --op-accent-gradient: linear-gradient(135deg, #10B981, #059669);
    --op-accent-shadow: rgba(16,185,129,.28);
}
.op-stat-cancelled {
    --op-accent-c: #B91C1C;
    --op-accent-bg: #FEF2F2;
    --op-accent-gradient: linear-gradient(135deg, #F87171, #DC2626);
    --op-accent-shadow: rgba(239,68,68,.28);
}
.op-stat.is-active {
    border-color: var(--op-accent-c);
    box-shadow: 0 0 0 3px var(--op-accent-bg), 0 1px 3px rgba(15,30,51,.04);
}

/* ============ FILTERS ============ */
.op-card {
    background: #fff;
    border: 1px solid var(--op-line);
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(15,30,51,.04);
    overflow: hidden;
}
.op-filters {
    display: grid;
    grid-template-columns: 1.2fr 1.2fr 1fr 1fr auto;
    gap: 12px;
    align-items: end;
    padding: 16px;
    margin-bottom: 14px;
}
.op-fi { min-width: 0; }
.op-fi label {
    display: block;
    font-size: 11px; font-weight: 700;
    color: var(--op-slate);
    margin-bottom: 6px;
    letter-spacing: .02em;
    text-transform: uppercase;
}
.op-input {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--op-line);
    border-radius: 10px;
    background: #FAFCFD;
    color: var(--op-ink);
    font-size: 13px;
    font-family: inherit;
    transition: all .2s;
    appearance: none; -webkit-appearance: none;
}
.op-input::placeholder { color: var(--op-muted); }
.op-input:hover { border-color: #CBD5E1; }
.op-input:focus {
    outline: none;
    border-color: var(--op-primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(46,109,180,.1);
}
select.op-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none' stroke='%235A6C8C' stroke-width='2'%3E%3Cpath d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 34px;
    cursor: pointer;
}
.op-fbtn { display: flex; gap: 6px; }

/* ============ LIST HEADER ============ */
.op-listhead {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-bottom: 1px solid var(--op-line);
    flex-wrap: wrap;
}
.op-listhead-count { font-size: 12.5px; color: var(--op-slate); font-weight: 500; }
.op-listhead-count strong { color: var(--op-ink); font-weight: 700; }

/* ============ TABLE ============ */
.op-tablewrap { overflow-x: auto; }
.op-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    min-width: 950px;
}
.op-table thead th {
    padding: 13px 18px;
    font-size: 10.5px;
    font-weight: 800;
    color: var(--op-slate);
    text-align: left;
    background: linear-gradient(180deg, #F7FAFC 0%, #EEF3F7 100%);
    border-bottom: 2px solid var(--op-line);
    text-transform: uppercase;
    letter-spacing: .8px;
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 2;
}
.op-table thead th:first-child { padding-left: 22px; }
.op-table thead th:last-child  { padding-right: 22px; text-align: right; }

.op-table tbody tr {
    position: relative;
    transition: background .18s;
    background: #fff;
}

.op-table tbody tr:hover::before { width: 4px; }
.op-table tbody tr:hover { background: linear-gradient(90deg, #F5FBFF 0%, #FAFCFE 100%); }

.op-table td {
    padding: 16px 18px;
    border-bottom: 1px solid #EEF2F7;
    vertical-align: middle;
    font-size: 13.5px;
    color: var(--op-ink);
    position: relative;
}
.op-table td:first-child { padding-left: 22px; }
.op-table td:last-child  { padding-right: 22px; }
.op-table tbody tr:last-child td { border-bottom: 0; }

/* Order number chip */
.op-num {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--op-primary);
    background: var(--op-primary-light);
    padding: 5px 10px;
    border-radius: 8px;
    border: 1px solid #BFDBFE;
    text-decoration: none;
    transition: all .18s;
}
.op-num:hover {
    background: var(--op-primary);
    color: #fff;
    border-color: var(--op-primary);
}
.op-num i { font-size: 11px; }

/* Customer */
.op-user {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 220px;
}
.op-avatar {
    position: relative;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2E6DB4, #26A69A);
    color: #fff;
    font-weight: 800;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(46,109,180,.22);
    transition: all .25s;
}
.op-table tbody tr:hover .op-avatar {
    box-shadow: 0 6px 16px rgba(46,109,180,.32);
    transform: scale(1.05);
}
.op-user-info { min-width: 0; flex: 1; }
.op-user-name {
    display: block;
    font-weight: 700;
    font-size: 13.5px;
    color: var(--op-ink);
    line-height: 1.35;
    margin-bottom: 2px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.op-user-email {
    display: block;
    font-size: 11.5px;
    color: var(--op-muted);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Date */
.op-date {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.op-date-main {
    font-size: 13px;
    font-weight: 600;
    color: var(--op-ink);
}
.op-date-time {
    font-size: 11px;
    color: var(--op-muted);
}

/* Total */
.op-total {
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 14.5px;
    font-weight: 800;
    color: #047857;
    letter-spacing: -.02em;
    white-space: nowrap;
}
.op-total .currency { font-size: 12px; opacity: .75; }

/* Status pill */
.op-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px 5px 9px;
    border-radius: 99px;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
    line-height: 1.4;
    border: 1px solid transparent;
    text-transform: capitalize;
}
.op-stat-returned {
    --op-accent-c: #9A3412;
    --op-accent-bg: #FFF7ED;
    --op-accent-gradient: linear-gradient(135deg, #FB923C, #EA580C);
    --op-accent-shadow: rgba(234,88,12,.28);
}
.op-stat-refunded {
    --op-accent-c: #475569;
    --op-accent-bg: #F1F5F9;
    --op-accent-gradient: linear-gradient(135deg, #94A3B8, #64748B);
    --op-accent-shadow: rgba(100,116,139,.28);
}
.op-status::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    box-shadow: 0 0 0 3px currentColor;
    animation: op-pulse 2s infinite;
}
@keyframes op-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .5; }
}
.op-status-pending { background: linear-gradient(135deg, #FFFBEB, #FEF3C7); color: #B45309; border-color: #FDE68A; }
.op-status-confirmed,
.op-status-processing { background: linear-gradient(135deg, #EFF6FF, #DBEAFE); color: #1E40AF; border-color: #BFDBFE; }
.op-status-packed { background: linear-gradient(135deg, #EEF2FF, #E0E7FF); color: #4338CA; border-color: #C7D2FE; }
.op-status-shipped,
.op-status-out_for_delivery { background: linear-gradient(135deg, #F5F3FF, #EDE9FE); color: #6D28D9; border-color: #DDD6FE; }
.op-status-delivered,
.op-status-completed,
.op-status-paid { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); color: #047857; border-color: #A7F3D0; }
.op-status-cancelled,
.op-status-returned,
.op-status-refunded { background: linear-gradient(135deg, #FEF2F2, #FEE2E2); color: #B91C1C; border-color: #FECACA; }
.op-status-cod_pending { background: linear-gradient(135deg, #FFF7ED, #FFEDD5); color: #9A3412; border-color: #FED7AA; }

/* Actions */
.op-acts { display: flex; gap: 6px; justify-content: flex-end; }
.op-act {
    width: 34px;
    height: 34px;
    border: 1.5px solid var(--op-line);
    border-radius: 9px;
    background: #fff;
    color: var(--op-slate);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    cursor: pointer;
    transition: all .22s cubic-bezier(.4,0,.2,1);
    font-size: 14px;
    padding: 0;
}
.op-act i { transition: transform .22s; }
.op-act:hover i { transform: scale(1.15); }
.op-act-view:hover {
    border-color: #2E6DB4;
    color: #fff;
    background: linear-gradient(135deg, #2E6DB4, #1E4C82);
    box-shadow: 0 4px 12px rgba(46,109,180,.35);
}
.op-act-print:hover {
    border-color: #26A69A;
    color: #fff;
    background: linear-gradient(135deg, #26A69A, #00796B);
    box-shadow: 0 4px 12px rgba(38,166,154,.35);
}

/* Empty state */
.op-empty {
    text-align: center;
    padding: 60px 24px;
}
.op-empty-icon {
    width: 72px; height: 72px;
    border-radius: 20px;
    margin: 0 auto 16px;
    background: linear-gradient(135deg, rgba(46,109,180,.08), rgba(38,166,154,.08));
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; color: var(--op-primary);
}
.op-empty h3 { font-size: 17px; font-weight: 700; margin: 0 0 6px; }
.op-empty p { color: var(--op-muted); margin: 0 0 20px; font-size: 13px; }

/* Pagination */
.op-pager {
    padding: 16px;
    border-top: 1px solid var(--op-line);
    display: flex;
    justify-content: center;
    background: #FAFCFD;
}
.op-pager .pagination { margin: 0; gap: 3px; }
.op-pager .page-link {
    border: none;
    color: var(--op-slate);
    font-weight: 600;
    border-radius: 8px;
    padding: 7px 12px;
    font-size: 12.5px;
    transition: all .15s;
    background: #fff;
}
.op-pager .page-link:hover { background: var(--op-primary-light); color: var(--op-primary); }
.op-pager .page-item.active .page-link {
    background: var(--op-gradient);
    color: #fff;
    box-shadow: 0 3px 10px rgba(46,109,180,.28);
}
.op-pager .page-item.disabled .page-link { color: #CBD5E1; background: transparent; }

/* ============ RESPONSIVE ============ */
@media (max-width: 1399.98px) {
    .op-stats { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 1199.98px) {
    .op-stats { grid-template-columns: repeat(3, 1fr); }
    .op-filters { grid-template-columns: 1fr 1fr; }
    .op-fbtn { grid-column: 1 / -1; }
    .op-fbtn .op-btn { flex: 1; }
}
@media (max-width: 767.98px) {
    .op-hero { padding: 16px 18px; min-height: 82px; }
    .op-hero h1 { font-size: 17px; }
    .op-hero p { font-size: 12px; }
    .op-hero-badge { width: 40px; height: 40px; font-size: 18px; }
    .op-stats { grid-template-columns: repeat(2, 1fr); }
    .op-stat { padding: 12px 14px; min-height: 70px; }
    .op-stat-value { font-size: 19px; }
    .op-stat-icon { width: 36px; height: 36px; font-size: 16px; }
    .op-table thead th, .op-table td { padding: 12px 14px; }
    .op-filters { padding: 14px; gap: 10px; }
}
@media (max-width: 575.98px) {
    .op-hero { padding: 14px 16px; min-height: 76px; }
    .op-hero h1 { font-size: 16px; }
    .op-stats { gap: 8px; }
    .op-stat { padding: 10px 12px; min-height: 64px; gap: 8px; }
    .op-stat-value { font-size: 17px; }
    .op-stat-icon { width: 32px; height: 32px; font-size: 14px; }
    .op-stat-label { font-size: 10.5px; }
    .op-stat-pct { font-size: 9px; padding: 2px 6px; top: 8px; right: 8px; }
    .op-filters { grid-template-columns: 1fr; }
    .op-table { min-width: auto; }
}
@media (prefers-reduced-motion: reduce) {
    .op * { transition: none !important; animation: none !important; }
}
@media (max-width: 1599.98px) {
    .op-stats { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 1199.98px) {
    .op-stats { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 767.98px) {
    .op-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 575.98px) {
    .op-stats { grid-template-columns: repeat(2, 1fr); }
}
/* ============================================================
   PREMIUM STATUS MODAL (Order Index)
   ============================================================ */
.op-modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 9998;
    background: rgba(15, 30, 51, .55);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    opacity: 0;
    visibility: hidden;
    transition: all .3s;
}
.op-modal-backdrop.show { opacity: 1; visibility: visible; }

.op-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    z-index: 9999;
    width: calc(100% - 32px);
    max-width: 520px;
    max-height: calc(100vh - 64px);
    overflow: hidden;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 32px 80px rgba(15,30,51,.3), 0 0 0 1px rgba(15,30,51,.05);
    transform: translate(-50%, -50%) scale(.9);
    opacity: 0;
    visibility: hidden;
    transition: all .35s cubic-bezier(.34, 1.3, .64, 1);
    display: flex;
    flex-direction: column;
}
.op-modal.show {
    opacity: 1;
    visibility: visible;
    transform: translate(-50%, -50%) scale(1);
}

.op-modal-header {
    position: relative;
    overflow: hidden;
    padding: 22px 26px;
    background: linear-gradient(135deg, #0F1E33 0%, #1E4C82 50%, #2E6DB4 100%);
    color: #fff;
    display: flex;
    align-items: center;
    gap: 14px;
    flex-shrink: 0;
}
.op-modal-header::before {
    content: "";
    position: absolute;
    right: -40px;
    top: -60px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(38,166,154,.4), transparent 65%);
    pointer-events: none;
}
.op-modal-header > * { position: relative; z-index: 1; }

.op-modal-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #26A69A;
    flex-shrink: 0;
}
.op-modal-title { flex: 1; min-width: 0; }
.op-modal-title h3 {
    font-size: 17px;
    font-weight: 800;
    margin: 0 0 2px;
    color: #fff;
}
.op-modal-title p {
    margin: 0;
    color: rgba(255,255,255,.68);
    font-size: 12.5px;
}
.op-modal-close {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    color: #fff;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .2s;
    flex-shrink: 0;
}
.op-modal-close:hover {
    background: rgba(255,255,255,.24);
    transform: rotate(90deg);
}

.op-modal-body {
    padding: 24px 26px;
    overflow-y: auto;
    flex: 1;
}
.op-modal-body::-webkit-scrollbar { width: 6px; }
.op-modal-body::-webkit-scrollbar-track { background: transparent; }
.op-modal-body::-webkit-scrollbar-thumb {
    background: #E3E9F2;
    border-radius: 4px;
}

.op-form-group { margin-bottom: 20px; }
.op-form-group:last-child { margin-bottom: 0; }

.op-form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 800;
    color: #5A6C8C;
    margin-bottom: 10px;
    letter-spacing: .3px;
    text-transform: uppercase;
}
.op-form-label i { color: #2E6DB4; font-size: 13px; }

.op-select-wrap {
    position: relative;
    display: flex;
    align-items: center;
    border: 1.5px solid #E3E9F2;
    border-radius: 12px;
    background: #FAFCFD;
    transition: all .2s;
    overflow: hidden;
}
.op-select-wrap:focus-within {
    border-color: #2E6DB4;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(46,109,180,.1);
}
.op-select-icon {
    width: 46px;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #E8F1FB, #F5F8FC);
    color: #2E6DB4;
    font-size: 18px;
    flex-shrink: 0;
    border-right: 1px solid #E3E9F2;
}
.op-select {
    flex: 1;
    width: 100%;
    padding: 12px 40px 12px 14px;
    border: none;
    background: transparent;
    color: #0F1E33;
    font-size: 14px;
    font-weight: 600;
    font-family: inherit;
    outline: none;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none' stroke='%235A6C8C' stroke-width='2'%3E%3Cpath d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
}

.op-textarea {
    width: 100%;
    padding: 13px 16px;
    border: 1.5px solid #E3E9F2;
    border-radius: 12px;
    background: #FAFCFD;
    color: #0F1E33;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    min-height: 90px;
    outline: none;
    transition: all .2s;
    line-height: 1.5;
}
.op-textarea::placeholder { color: #8B9BB4; }
.op-textarea:focus {
    border-color: #2E6DB4;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(46,109,180,.1);
}

.op-quick-status {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 10px;
}
.op-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 99px;
    background: #F5F8FC;
    border: 1.5px solid #E3E9F2;
    color: #5A6C8C;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    text-transform: capitalize;
    font-family: inherit;
}
.op-chip:hover {
    border-color: #2E6DB4;
    color: #2E6DB4;
    background: #E8F1FB;
}
.op-chip.active {
    background: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 3px 10px rgba(46,109,180,.3);
}

.op-modal-footer {
    padding: 16px 26px;
    border-top: 1px solid #E3E9F2;
    background: linear-gradient(180deg, #FAFCFD, #fff);
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    flex-shrink: 0;
}
.op-btn-modal {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 24px;
    border: none;
    border-radius: 99px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all .22s;
    font-family: inherit;
    white-space: nowrap;
}
.op-btn-modal-cancel {
    background: #fff;
    color: #5A6C8C;
    border: 1.5px solid #E3E9F2;
}
.op-btn-modal-cancel:hover {
    border-color: #5A6C8C;
    color: #0F1E33;
    background: #F5F8FC;
}
.op-btn-modal-submit {
    background: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    color: #fff;
    box-shadow: 0 6px 20px -4px rgba(46,109,180,.45);
}
.op-btn-modal-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px -4px rgba(46,109,180,.55);
}
.op-btn-modal-submit[disabled] { opacity: .7; cursor: wait; transform: none; }

/* Responsive */
@media (max-width: 575.98px) {
    .op-modal { max-width: 100%; border-radius: 18px; }
    .op-modal-header { padding: 18px 20px; }
    .op-modal-icon { width: 42px; height: 42px; font-size: 20px; }
    .op-modal-title h3 { font-size: 15px; }
    .op-modal-body { padding: 20px; }
    .op-modal-footer {
        padding: 14px 20px;
        flex-direction: column-reverse;
    }
    .op-btn-modal { justify-content: center; width: 100%; }
}
.op-act-edit:hover {
    border-color: #F59E0B;
    color: #fff;
    background: linear-gradient(135deg, #F59E0B, #D97706);
    box-shadow: 0 4px 12px rgba(245,158,11,.35);
}
</style>

<div class="op">

    {{-- HERO --}}
    <div class="op-hero">
        <div class="op-hero-left">
            <div class="op-hero-badge"><i class="bi bi-bag-check-fill"></i></div>
            <div>
                <h1>Orders</h1>
                <p>{{ number_format($orders->total()) }} total orders · Manage and track all customer orders</p>
            </div>
        </div>
        <div class="op-hero-actions">
            <a href="{{ route('admin.orders') }}" class="op-btn op-btn-ghost">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </a>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="op-stats">
        <a href="{{ route('admin.orders') }}" class="op-stat op-stat-total {{ !$cur ? 'is-active' : '' }}">
            <div class="op-stat-icon"><i class="bi bi-bag"></i></div>
            <div class="op-stat-body">
                <div class="op-stat-value">{{ number_format($totalOrders) }}</div>
                <div class="op-stat-label">All Orders</div>
            </div>
        </a>

        @foreach($statusCards as $sc)
            @php $n = $sc['count']; @endphp
            <a href="{{ route('admin.orders', array_merge(request()->except('status', 'page'), ['status' => $sc['key']])) }}"
               class="op-stat op-stat-{{ $sc['key'] }} {{ $cur === $sc['key'] ? 'is-active' : '' }}">
                <div class="op-stat-icon"><i class="bi {{ $sc['icon'] }}"></i></div>
                <div class="op-stat-body">
                    <div class="op-stat-value">{{ number_format($n) }}</div>
                    <div class="op-stat-label">{{ $sc['label'] }}</div>
                </div>
                <span class="op-stat-pct">{{ $pct($n) }}%</span>
            </a>
        @endforeach
    </div>

    {{-- FILTERS --}}
    <form method="GET" class="op-card op-filters">
        <div class="op-fi">
            <label>Status</label>
            <select name="status" class="op-input">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','processing','packed','shipped','out_for_delivery','delivered','cancelled','returned','refunded'] as $s)
                    <option value="{{ $s }}" @selected($cur == $s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="op-fi">
            <label>Payment</label>
            <select name="payment" class="op-input">
                <option value="">All Payments</option>
                @foreach(['pending','paid','cod_pending','refunded','failed'] as $s)
                    <option value="{{ $s }}" @selected($curPayment == $s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="op-fi">
            <label>From</label>
            <input type="date" name="from" class="op-input" value="{{ request('from') }}">
        </div>
        <div class="op-fi">
            <label>To</label>
            <input type="date" name="to" class="op-input" value="{{ request('to') }}">
        </div>
        <div class="op-fi op-fbtn">
            <button type="submit" class="op-btn op-btn-primary"><i class="bi bi-funnel"></i> Filter</button>
            @if($hasFilter)
                <a href="{{ route('admin.orders') }}" class="op-btn op-btn-ghost"><i class="bi bi-arrow-clockwise"></i> Reset</a>
            @endif
        </div>
    </form>

    {{-- TABLE --}}
    <div class="op-card">
        <div class="op-listhead">
            <div class="op-listhead-count">
                Showing <strong>{{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }}</strong>
                of <strong>{{ $orders->total() }}</strong> orders
            </div>
        </div>

        @if($orders->count())
            <div class="op-tablewrap">
                <table class="op-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th style="width:110px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $o)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $o) }}" class="op-num">
                                    <i class="bi bi-hash"></i>{{ $o->order_number }}
                                </a>
                            </td>
                            <td>
                                <div class="op-user">
                                    <div class="op-avatar">{{ strtoupper(substr($o->customer_name ?? 'U', 0, 1)) }}</div>
                                    <div class="op-user-info">
                                        <span class="op-user-name">{{ $o->customer_name ?? 'Guest' }}</span>
                                        @if($o->customer_email)
                                            <span class="op-user-email">{{ $o->customer_email }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="op-date">
                                    <span class="op-date-main">{{ $o->created_at->format('d M Y') }}</span>
                                    <span class="op-date-time">{{ $o->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="op-total">
                                    <span class="currency">₹</span>{{ number_format($o->grand_total, 0) }}
                                </span>
                            </td>
                            <td>
                                <span class="op-status op-status-{{ $o->payment_status }}">
                                    {{ ucfirst(str_replace('_', ' ', $o->payment_status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="op-status op-status-{{ $o->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $o->status)) }}
                                </span>
                            </td>
                           <td>
                            <div class="op-acts">
                                <a href="{{ route('admin.orders.show', $o) }}" class="op-act op-act-view" title="View Order">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <button type="button" class="op-act op-act-edit update-status-btn"
                                        data-id="{{ $o->id }}"
                                        data-number="{{ $o->order_number }}"
                                        data-status="{{ $o->status }}"
                                        title="Update Status">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                {{-- <a href="{{ route('admin.orders.show', $o) }}#print" class="op-act op-act-print" title="Print">
                                    <i class="bi bi-printer-fill"></i>
                                </a> --}}
                            </div>
                        </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="op-empty">
                <div class="op-empty-icon"><i class="bi bi-bag-x"></i></div>
                <h3>{{ $hasFilter ? 'No orders match your filters' : 'No orders yet' }}</h3>
                <p>{{ $hasFilter ? 'Try changing filters or clearing date range.' : 'Orders will appear here once customers start purchasing.' }}</p>
                @if($hasFilter)
                    <a href="{{ route('admin.orders') }}" class="op-btn op-btn-primary">
                        <i class="bi bi-arrow-clockwise"></i> Clear Filters
                    </a>
                @endif
            </div>
        @endif

        @if($orders->hasPages())
            <div class="op-pager">{{ $orders->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
        @if($orders->hasPages())
            <div class="op-pager">{{ $orders->withQueryString()->links() }}</div>
        @endif
    </div>
</div>

{{-- ============================================================
     PREMIUM STATUS MODAL (Single modal, dynamic order)
     ============================================================ --}}
<div class="op-modal-backdrop" id="statusModalBackdrop"></div>

<div class="op-modal" id="statusModal" role="dialog" aria-modal="true">
    <div class="op-modal-header">
        <div class="op-modal-icon"><i class="bi bi-pencil-square"></i></div>
        <div class="op-modal-title">
            <h3>Update Order Status</h3>
            <p id="modalOrderNumber">Order</p>
        </div>
        <button type="button" class="op-modal-close" id="closeStatusModal">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="op-modal-body">
        <input type="hidden" id="modalOrderId">
        <input type="hidden" id="modalOrderStatus">

        <div class="op-form-group">
            <label class="op-form-label">
                <i class="bi bi-circle-fill"></i> Select New Status
            </label>
            <div class="op-select-wrap">
                <div class="op-select-icon">
                    <i class="bi bi-hourglass-split" id="statusIcon"></i>
                </div>
                <select class="op-select" id="newStatus">
                    @foreach(['pending','confirmed','processing','packed','shipped','out_for_delivery','delivered','cancelled','returned','refunded'] as $s)
                        <option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="op-quick-status">
                @foreach(['confirmed','processing','shipped','delivered','cancelled'] as $s)
                    <button type="button" class="op-chip" data-status="{{ $s }}">
                        <i class="bi bi-circle"></i>
                        {{ ucfirst($s) }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="op-form-group">
            <label class="op-form-label">
                <i class="bi bi-chat-left-text"></i> Add a Note (optional)
            </label>
            <textarea class="op-textarea" id="statusNote" rows="3" placeholder="Add a note about this status change..."></textarea>
        </div>
    </div>

    <div class="op-modal-footer">
        <button type="button" class="op-btn-modal op-btn-modal-cancel" id="cancelStatusModal">
            <i class="bi bi-x-lg"></i> Cancel
        </button>
        <button type="button" class="op-btn-modal op-btn-modal-submit" id="confirmStatus">
            <i class="bi bi-check2-circle"></i> Update Status
        </button>
    </div>
</div>


@endsection
@push('scripts')
<script>
/* ============================================================
   ORDER STATUS MODAL — Index Page
   ============================================================ */
(function () {
    const $modal = $('#statusModal');
    const $backdrop = $('#statusModalBackdrop');

    // Status icons map
    const statusIcons = {
        'pending':          'bi-hourglass-split',
        'confirmed':        'bi-check-circle',
        'processing':       'bi-arrow-repeat',
        'packed':           'bi-box-seam',
        'shipped':          'bi-truck',
        'out_for_delivery': 'bi-geo-alt',
        'delivered':        'bi-check2-circle',
        'cancelled':        'bi-x-circle',
        'returned':         'bi-arrow-return-left',
        'refunded':         'bi-cash-coin'
    };

    function updateStatusIcon(status) {
        const icon = statusIcons[status] || 'bi-circle';
        $('#statusIcon').attr('class', 'bi ' + icon);
    }

    function updateChipIcons() {
        $('.op-chip').each(function () {
            const status = $(this).data('status');
            const icon = statusIcons[status] || 'bi-circle';
            $(this).find('i').attr('class', 'bi ' + icon);
        });
    }
    updateChipIcons();

    /* Open Modal */
    function openModal(orderId, orderNumber, currentStatus) {
        $('#modalOrderId').val(orderId);
        $('#modalOrderStatus').val(currentStatus);
        $('#modalOrderNumber').text('Order ' + orderNumber);
        $('#newStatus').val(currentStatus);
        $('#statusNote').val('');
        updateStatusIcon(currentStatus);

        // Set active chip
        $('.op-chip').removeClass('active');
        $('.op-chip[data-status="' + currentStatus + '"]').addClass('active');

        $backdrop.addClass('show');
        setTimeout(() => $modal.addClass('show'), 30);
        $('body').css('overflow', 'hidden');
        setTimeout(() => $('#newStatus').focus(), 400);
    }

    function closeModal() {
        $modal.removeClass('show');
        $backdrop.removeClass('show');
        $('body').css('overflow', '');
    }

    /* Trigger via update button */
    $(document).on('click', '.update-status-btn', function () {
        const id = $(this).data('id');
        const number = $(this).data('number');
        const status = $(this).data('status');
        openModal(id, number, status);
    });

    /* Close handlers */
    $('#closeStatusModal, #cancelStatusModal').on('click', closeModal);
    $backdrop.on('click', closeModal);
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $modal.hasClass('show')) closeModal();
    });

    /* Chips */
    $('.op-chip').on('click', function () {
        const status = $(this).data('status');
        $('#newStatus').val(status);
        $('.op-chip').removeClass('active');
        $(this).addClass('active');
        updateStatusIcon(status);
    });

    /* Select change */
    $('#newStatus').on('change', function () {
        const status = $(this).val();
        $('.op-chip').removeClass('active').filter('[data-status="' + status + '"]').addClass('active');
        updateStatusIcon(status);
    });

    /* Submit */
    $('#confirmStatus').on('click', function () {
        const $btn = $(this);
        const orderId = $('#modalOrderId').val();
        const status = $('#newStatus').val();
        const note = $('#statusNote').val().trim();

        if (!orderId || !status) {
            if (window.zError) zError('Missing order or status.');
            return;
        }

        $btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Updating...');

        // Base URL with dynamic id
        const baseUrl = '{{ url("/admin/orders") }}';
        const url = baseUrl + '/' + orderId + '/status';

        $.post(url, {
            status: status,
            note: note,
            _token: '{{ csrf_token() }}'
        })
        .done(() => {
            closeModal();
            if (window.zSuccess) {
                zSuccess('Order status updated successfully!', 'Updated!', 2000);
                setTimeout(() => location.reload(), 800);
            } else {
                location.reload();
            }
        })
        .fail((xhr) => {
            $btn.prop('disabled', false).html('<i class="bi bi-check2-circle"></i> Update Status');
            const msg = xhr.responseJSON?.message || 'Failed to update status.';
            if (window.zError) {
                zError(msg, 'Update Failed');
            } else {
                alert(msg);
            }
        });
    });
})();
</script>
@endpush
