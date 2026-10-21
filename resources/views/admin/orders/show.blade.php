@extends('layouts.admin')
@section('title', 'Order Details')

@php
    $statusIcon = [
        'pending'          => 'bi-hourglass-split',
        'confirmed'        => 'bi-check-circle',
        'processing'       => 'bi-arrow-repeat',
        'packed'           => 'bi-box-seam',
        'shipped'          => 'bi-truck',
        'out_for_delivery' => 'bi-geo-alt',
        'delivered'        => 'bi-check2-circle',
        'cancelled'        => 'bi-x-circle',
        'returned'         => 'bi-arrow-return-left',
        'refunded'         => 'bi-cash-coin',
    ];
@endphp

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('admin.orders') }}">Orders</a>
    <i class="bi bi-chevron-right"></i><span>{{ $order->order_number }}</span>
@endsection

@section('content')

<style>
/* ============================================================
   PREMIUM ORDER DETAILS PAGE
   ============================================================ */
.od {
    color: #0F1E33;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    -webkit-font-smoothing: antialiased;
}
.od * { box-sizing: border-box; }

/* ============ HERO ============ */
.od-hero {
    position: relative;
    overflow: hidden;
    padding: 22px 26px;
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
    min-height: 100px;
}
.od-hero::before {
    content: "";
    position: absolute;
    right: -60px; top: -80px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(38,166,154,.42), transparent 65%);
    pointer-events: none;
}
.od-hero::after {
    content: "";
    position: absolute;
    left: -60px; bottom: -80px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(46,109,180,.5), transparent 65%);
    pointer-events: none;
}
.od-hero > * { position: relative; z-index: 1; }
.od-hero-left { display: flex; align-items: center; gap: 16px; min-width: 0; flex: 1; }
.od-hero-badge {
    width: 56px; height: 56px; border-radius: 14px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; color: #26A69A; flex-shrink: 0;
}
.od-hero-info { min-width: 0; flex: 1; }
.od-hero h1 {
    font-size: 20px; font-weight: 800;
    margin: 0 0 6px; color: #fff;
    letter-spacing: -.02em; line-height: 1.2;
}
.od-hero p {
    margin: 0;
    color: rgba(255,255,255,.72);
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.od-hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.od-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 20px; border: none; border-radius: 99px;
    font-weight: 700; font-size: 13px;
    text-decoration: none; cursor: pointer;
    transition: all .22s;
    white-space: nowrap;
    font-family: inherit;
}
.od-btn i { font-size: 14px; }
.od-btn-primary {
    background: #fff;
    color: #1E4C82;
    box-shadow: 0 3px 10px rgba(0,0,0,.15);
}
.od-btn-primary:hover {
    color: #1E4C82;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,.2);
}
.od-btn-glass {
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.24);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
}
.od-btn-glass:hover {
    background: rgba(255,255,255,.24);
    color: #fff;
    transform: translateY(-2px);
}

/* ============ INFO CARDS ============ */
.od-info-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}
.od-info-card {
    position: relative;
    background: #fff;
    border: 1px solid #E3E9F2;
    border-radius: 14px;
    padding: 16px 18px;
    overflow: hidden;
    transition: all .25s;
    display: flex;
    align-items: center;
    gap: 14px;
    min-height: 84px;
}
.od-info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px -10px rgba(15,30,51,.15);
    border-color: transparent;
}
.od-info-card::before {
    content: "";
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    background: var(--od-accent-gradient, linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%));
    transition: width .25s;
}
.od-info-card:hover::before { width: 6px; }

.od-info-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff;
    background: var(--od-accent-gradient, linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%));
    box-shadow: 0 4px 12px var(--od-accent-shadow, rgba(46,109,180,.28));
    flex-shrink: 0;
}
.od-info-body { flex: 1; min-width: 0; }
.od-info-label {
    font-size: 10.5px; font-weight: 800;
    color: #8B9BB4;
    letter-spacing: .8px;
    text-transform: uppercase;
    margin-bottom: 6px;
}
.od-info-value {
    font-size: 15px; font-weight: 800;
    color: #0F1E33;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    letter-spacing: -.02em;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    word-break: break-word;
}
.od-info-value.mono {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 14px;
    color: #2E6DB4;
}
.od-info-sub {
    font-size: 11.5px;
    color: #8B9BB4;
    margin-top: 3px;
    font-weight: 500;
}

.od-info-number  { --od-accent-gradient: linear-gradient(135deg, #2E6DB4, #1E4C82); --od-accent-shadow: rgba(46,109,180,.28); }
.od-info-status  { --od-accent-gradient: linear-gradient(135deg, #10B981, #059669); --od-accent-shadow: rgba(16,185,129,.28); }
.od-info-payment { --od-accent-gradient: linear-gradient(135deg, #8B5CF6, #6D28D9); --od-accent-shadow: rgba(139,92,246,.28); }
.od-info-date    { --od-accent-gradient: linear-gradient(135deg, #F59E0B, #D97706); --od-accent-shadow: rgba(245,158,11,.28); }

.od-info-status[data-status="pending"]         { --od-accent-gradient: linear-gradient(135deg, #F59E0B, #D97706); --od-accent-shadow: rgba(245,158,11,.28); }
.od-info-status[data-status="processing"],
.od-info-status[data-status="confirmed"]       { --od-accent-gradient: linear-gradient(135deg, #60A5FA, #2563EB); --od-accent-shadow: rgba(59,130,246,.28); }
.od-info-status[data-status="shipped"],
.od-info-status[data-status="packed"],
.od-info-status[data-status="out_for_delivery"] { --od-accent-gradient: linear-gradient(135deg, #A78BFA, #7C3AED); --od-accent-shadow: rgba(139,92,246,.28); }
.od-info-status[data-status="delivered"]        { --od-accent-gradient: linear-gradient(135deg, #10B981, #059669); --od-accent-shadow: rgba(16,185,129,.28); }
.od-info-status[data-status="cancelled"],
.od-info-status[data-status="returned"],
.od-info-status[data-status="refunded"]         { --od-accent-gradient: linear-gradient(135deg, #F87171, #DC2626); --od-accent-shadow: rgba(239,68,68,.28); }

.od-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px 5px 9px;
    border-radius: 99px;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.4;
    text-transform: capitalize;
    border: 1px solid transparent;
    white-space: nowrap;
}
.od-pill::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    box-shadow: 0 0 0 3px currentColor;
    animation: od-pulse 2s infinite;
}
@keyframes od-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .5; }
}
.od-pill-pending { background: linear-gradient(135deg, #FFFBEB, #FEF3C7); color: #B45309; border-color: #FDE68A; }
.od-pill-confirmed, .od-pill-processing { background: linear-gradient(135deg, #EFF6FF, #DBEAFE); color: #1E40AF; border-color: #BFDBFE; }
.od-pill-packed { background: linear-gradient(135deg, #EEF2FF, #E0E7FF); color: #4338CA; border-color: #C7D2FE; }
.od-pill-shipped, .od-pill-out_for_delivery { background: linear-gradient(135deg, #F5F3FF, #EDE9FE); color: #6D28D9; border-color: #DDD6FE; }
.od-pill-delivered, .od-pill-completed, .od-pill-paid { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); color: #047857; border-color: #A7F3D0; }
.od-pill-cancelled, .od-pill-returned, .od-pill-refunded { background: linear-gradient(135deg, #FEF2F2, #FEE2E2); color: #B91C1C; border-color: #FECACA; }
.od-pill-cod_pending { background: linear-gradient(135deg, #FFF7ED, #FFEDD5); color: #9A3412; border-color: #FED7AA; }

/* ============ 4 KPI CARDS ============ */
.od-kpi-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}
.od-kpi-card {
    position: relative;
    background: #fff;
    border: 1px solid #E3E9F2;
    border-radius: 14px;
    padding: 18px 20px;
    overflow: hidden;
    transition: all .25s cubic-bezier(.4, 0, .2, 1);
    display: flex;
    align-items: center;
    gap: 16px;
    min-height: 88px;
}
.od-kpi-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--od-kpi-gradient, linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%));
    opacity: .9;
}
.od-kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 32px -12px var(--od-kpi-shadow, rgba(46,109,180,.28));
    border-color: transparent;
}
.od-kpi-card-icon {
    width: 54px; height: 54px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: #fff;
    background: var(--od-kpi-gradient, linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%));
    box-shadow: 0 6px 16px -4px var(--od-kpi-shadow, rgba(46,109,180,.35)),
                inset 0 1px 0 rgba(255,255,255,.25);
    flex-shrink: 0;
    transition: transform .25s;
}
.od-kpi-card:hover .od-kpi-card-icon {
    transform: scale(1.08) rotate(-4deg);
}
.od-kpi-card-body { flex: 1; min-width: 0; }
.od-kpi-card-value {
    font-size: 24px; font-weight: 800;
    color: #0F1E33;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    letter-spacing: -.03em;
    line-height: 1.1;
    margin-bottom: 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.od-kpi-card-label {
    font-size: 11.5px; font-weight: 700;
    color: #5A6C8C;
    letter-spacing: .6px;
    text-transform: uppercase;
}
.od-kpi-card[data-color="blue"] {
    --od-kpi-gradient: linear-gradient(135deg, #3B82F6 0%, #1E4C82 100%);
    --od-kpi-shadow: rgba(46, 109, 180, .35);
}
.od-kpi-card[data-color="emerald"] {
    --od-kpi-gradient: linear-gradient(135deg, #26A69A 0%, #00796B 100%);
    --od-kpi-shadow: rgba(38, 166, 154, .35);
}
.od-kpi-card[data-color="teal"] {
    --od-kpi-gradient: linear-gradient(135deg, #10B981 0%, #059669 100%);
    --od-kpi-shadow: rgba(16, 185, 129, .35);
}
.od-kpi-card[data-color="violet"] {
    --od-kpi-gradient: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%);
    --od-kpi-shadow: rgba(139, 92, 246, .35);
}

/* ============ MAIN GRID ============ */
.od-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 16px;
    align-items: start;
}

/* ============ CARD ============ */
.od-card {
    background: #fff;
    border: 1px solid #E3E9F2;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(15,30,51,.04);
    overflow: hidden;
    margin-bottom: 16px;
    position: relative;
}
.od-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--od-accent-color, linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%));
    opacity: .9;
}
.od-card[data-accent="blue"]    { --od-accent-color: linear-gradient(90deg, #2E6DB4, #1E4C82); }
.od-card[data-accent="teal"]    { --od-accent-color: linear-gradient(90deg, #26A69A, #00796B); }
.od-card[data-accent="violet"]  { --od-accent-color: linear-gradient(90deg, #8B5CF6, #6D28D9); }
.od-card[data-accent="amber"]   { --od-accent-color: linear-gradient(90deg, #F59E0B, #D97706); }
.od-card[data-accent="emerald"] { --od-accent-color: linear-gradient(90deg, #10B981, #059669); }
.od-card[data-accent="rose"]    { --od-accent-color: linear-gradient(90deg, #F87171, #DC2626); }

.od-card-head {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 18px;
    border-bottom: 1px solid #E3E9F2;
    background: linear-gradient(180deg, #FAFCFD, #fff);
}
.od-card-head i {
    width: 32px; height: 32px;
    border-radius: 9px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 14px; color: #fff;
    background: var(--od-accent-color, linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%));
    box-shadow: 0 3px 10px rgba(46,109,180,.22);
    flex-shrink: 0;
}
.od-card-head h5 {
    margin: 0;
    font-size: 14.5px;
    font-weight: 700;
    color: #0F1E33;
    flex: 1;
}
.od-card-head .badge {
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 99px;
    background: #E8F1FB;
    color: #2E6DB4;
    font-weight: 700;
}
.od-card-body { padding: 16px 18px; }

/* ============ ORDER ITEMS TABLE ============ */
.od-items {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.od-items thead th {
    padding: 12px 14px;
    font-size: 10.5px;
    font-weight: 800;
    color: #5A6C8C;
    text-align: left;
    background: linear-gradient(180deg, #F7FAFC, #EEF3F7);
    border-bottom: 2px solid #E3E9F2;
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap;
}
.od-items thead th:last-child { text-align: right; }
.od-items tbody tr { transition: background .15s; }
.od-items tbody tr:hover { background: #F8FBFE; }
.od-items td {
    padding: 14px;
    border-bottom: 1px solid #EEF2F7;
    vertical-align: middle;
    font-size: 13.5px;
}
.od-items tbody tr:last-child td { border-bottom: 0; }
.od-items td:last-child { text-align: right; }

.od-item { display: flex; align-items: center; gap: 12px; min-width: 220px; }
.od-item-img {
    width: 44px; height: 44px;
    border-radius: 10px;
    background: linear-gradient(135deg, #F5F8FC, #E8F1FB);
    border: 1px solid #E3E9F2;
    display: flex; align-items: center; justify-content: center;
    color: #A8B5C4; font-size: 18px;
    overflow: hidden; flex-shrink: 0;
    position: relative;
}
.od-item-img img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.od-item-info { min-width: 0; flex: 1; }
.od-item-name { display: block; font-weight: 700; font-size: 13.5px; color: #0F1E33; line-height: 1.35; margin-bottom: 2px; }
.od-item-sku {
    display: inline-block;
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 10.5px; font-weight: 700;
    color: #5A6C8C;
    background: #F1F5F9;
    padding: 2px 7px; border-radius: 5px; border: 1px solid #E2E8F0;
}
.od-qty {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 34px; padding: 3px 10px; border-radius: 8px;
    background: #E8F1FB; color: #2E6DB4;
    font-size: 12.5px; font-weight: 800; border: 1px solid #BFDBFE;
}
.od-price { font-weight: 600; color: #5A6C8C; font-size: 13px; }
.od-total {
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-weight: 800; color: #047857; font-size: 14px; letter-spacing: -.02em;
}

/* ============ TIMELINE ============ */
.od-timeline { position: relative; padding-left: 28px; }
.od-timeline::before {
    content: ""; position: absolute;
    left: 11px; top: 8px; bottom: 8px; width: 2px;
    background: linear-gradient(180deg, #2E6DB4, #26A69A);
    opacity: .15; border-radius: 2px;
}
.od-tl-item { position: relative; padding: 10px 0 16px; }
.od-tl-item:last-child { padding-bottom: 0; }
.od-tl-dot {
    position: absolute; left: -28px; top: 12px;
    width: 22px; height: 22px; border-radius: 50%;
    background: #fff;
    border: 2px solid #2E6DB4;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; color: #2E6DB4;
    box-shadow: 0 0 0 4px rgba(46,109,180,.08);
    z-index: 1;
}
.od-tl-item:first-child .od-tl-dot {
    background: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    color: #fff; border-color: transparent;
    box-shadow: 0 4px 12px rgba(46,109,180,.35), 0 0 0 4px rgba(46,109,180,.08);
}
.od-tl-content {
    background: #FAFCFD;
    border: 1px solid #E3E9F2;
    border-radius: 12px;
    padding: 12px 14px;
    transition: all .2s;
}
.od-tl-item:first-child .od-tl-content {
    background: linear-gradient(135deg, #EFF6FF, #F5FAFF);
    border-color: #BFDBFE;
}
.od-tl-item:hover .od-tl-content {
    box-shadow: 0 4px 12px -4px rgba(46,109,180,.15);
    transform: translateX(2px);
}
.od-tl-status {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 700; color: #0F1E33;
    margin-bottom: 4px; text-transform: capitalize;
}
.od-tl-status i { font-size: 12px; color: #2E6DB4; }
.od-tl-meta {
    display: flex; align-items: center; gap: 10px;
    font-size: 11.5px; color: #8B9BB4; flex-wrap: wrap;
}
.od-tl-meta .sep {
    width: 3px; height: 3px; border-radius: 50%;
    background: currentColor; opacity: .4;
}
.od-tl-note {
    font-size: 12.5px; color: #5A6C8C;
    margin-top: 6px; padding-top: 6px;
    border-top: 1px dashed #E3E9F2;
    font-style: italic;
}

/* ============ CUSTOMER ============ */
.od-customer {
    display: flex; gap: 14px; align-items: center;
    padding-bottom: 14px;
    border-bottom: 1px dashed #E3E9F2;
    margin-bottom: 14px;
}
.od-customer-avatar {
    width: 54px; height: 54px; border-radius: 50%;
    background: linear-gradient(135deg, #2E6DB4, #26A69A);
    color: #fff; font-weight: 800; font-size: 20px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 6px 16px rgba(46,109,180,.28);
}
.od-customer-info { min-width: 0; flex: 1; }
.od-customer-name {
    font-size: 15px; font-weight: 800;
    color: #0F1E33; margin: 0 0 3px;
    overflow: hidden; text-overflow: ellipsis;
}
.od-customer-email {
    font-size: 12.5px; color: #5A6C8C;
    overflow: hidden; text-overflow: ellipsis;
}
.od-contact-rows { display: flex; flex-direction: column; gap: 10px; }
.od-contact-row {
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; color: #0F1E33;
}
.od-contact-icon {
    width: 32px; height: 32px; border-radius: 9px;
    background: #E8F1FB; color: #2E6DB4;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.od-contact-text { min-width: 0; flex: 1; }
.od-contact-text a {
    color: #2E6DB4; text-decoration: none;
    overflow: hidden; text-overflow: ellipsis;
    white-space: nowrap; display: block;
}
.od-contact-text a:hover { text-decoration: underline; }

/* ============ PAYMENT SUMMARY ============ */
.od-summary { display: flex; flex-direction: column; gap: 10px; }
.od-summary-row {
    display: flex; justify-content: space-between; align-items: center;
    gap: 12px; font-size: 13.5px; color: #5A6C8C;
}
.od-summary-row .label { display: flex; align-items: center; gap: 8px; }
.od-summary-row .label i {
    width: 16px; font-size: 12px; color: #8B9BB4; text-align: center;
}
.od-summary-row .value { font-weight: 700; color: #0F1E33; }
.od-summary-row.discount .value { color: #047857; }

.od-summary-total {
    display: flex; justify-content: space-between; align-items: center;
    padding: 14px 16px; margin-top: 6px;
    background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
    border: 1px solid #A7F3D0; border-radius: 12px;
}
.od-summary-total .label {
    font-size: 13px; font-weight: 700; color: #047857;
    text-transform: uppercase; letter-spacing: .3px;
}
.od-summary-total .value {
    font-size: 20px; font-weight: 800; color: #047857;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    letter-spacing: -.02em;
}

/* ============ ADDRESS ============ */
.od-address {
    padding: 14px; border-radius: 12px;
    background: linear-gradient(135deg, #F8FAFD, #EEF4FA);
    border: 1px solid #E1EAF4;
    font-size: 13px; line-height: 1.7; color: #5A6C8C;
}
.od-address strong {
    display: block; color: #0F1E33;
    font-weight: 700; margin-bottom: 6px; font-size: 13.5px;
}

/* ============ INFO ROWS ============ */
.od-rows { display: flex; flex-direction: column; }
.od-row {
    display: flex; justify-content: space-between; align-items: center;
    gap: 16px; padding: 12px 0;
    border-bottom: 1px dashed #E3E9F2;
    font-size: 13.5px;
}
.od-row:last-child { border-bottom: 0; padding-bottom: 0; }
.od-row:first-child { padding-top: 0; }
.od-row-label {
    display: flex; align-items: center; gap: 8px;
    color: #5A6C8C; font-weight: 500;
}
.od-row-label i {
    width: 16px; font-size: 13px;
    color: #8B9BB4; text-align: center;
}
.od-row-value {
    font-weight: 700; color: #0F1E33;
    text-align: right; font-size: 13.5px;
}
.od-row-value.mono {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 12.5px;
    background: #F1F5F9;
    padding: 3px 10px; border-radius: 6px;
    border: 1px solid #E2E8F0;
}

/* ============================================================
   PREMIUM STATUS MODAL
   ============================================================ */
.od-modal-backdrop {
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
.od-modal-backdrop.show { opacity: 1; visibility: visible; }

.od-modal {
    position: fixed;
    top: 50%; left: 50%;
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
.od-modal.show {
    opacity: 1;
    visibility: visible;
    transform: translate(-50%, -50%) scale(1);
}

.od-modal-header {
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
.od-modal-header::before {
    content: "";
    position: absolute;
    right: -40px; top: -60px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(38,166,154,.4), transparent 65%);
    pointer-events: none;
}
.od-modal-header > * { position: relative; z-index: 1; }

.od-modal-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(10px);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #26A69A; flex-shrink: 0;
}
.od-modal-title { flex: 1; min-width: 0; }
.od-modal-title h3 {
    font-size: 17px; font-weight: 800;
    margin: 0 0 2px; color: #fff;
}
.od-modal-title p {
    margin: 0; color: rgba(255,255,255,.68); font-size: 12.5px;
}
.od-modal-close {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    color: #fff; font-size: 16px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .2s; flex-shrink: 0;
}
.od-modal-close:hover {
    background: rgba(255,255,255,.24);
    transform: rotate(90deg);
}

.od-modal-body {
    padding: 24px 26px;
    overflow-y: auto;
    flex: 1;
}
.od-modal-body::-webkit-scrollbar { width: 6px; }
.od-modal-body::-webkit-scrollbar-track { background: transparent; }
.od-modal-body::-webkit-scrollbar-thumb {
    background: #E3E9F2; border-radius: 4px;
}

.od-form-group { margin-bottom: 20px; }
.od-form-group:last-child { margin-bottom: 0; }

.od-form-label {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; font-weight: 800;
    color: #5A6C8C;
    margin-bottom: 10px;
    letter-spacing: .3px;
    text-transform: uppercase;
}
.od-form-label i { color: #2E6DB4; font-size: 13px; }

.od-select-wrap {
    position: relative;
    display: flex; align-items: center;
    border: 1.5px solid #E3E9F2;
    border-radius: 12px;
    background: #FAFCFD;
    transition: all .2s;
    overflow: hidden;
}
.od-select-wrap:focus-within {
    border-color: #2E6DB4;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(46,109,180,.1);
}
.od-select-icon {
    width: 46px; height: 46px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #E8F1FB, #F5F8FC);
    color: #2E6DB4;
    font-size: 18px; flex-shrink: 0;
    border-right: 1px solid #E3E9F2;
}
.od-select {
    flex: 1; width: 100%;
    padding: 12px 40px 12px 14px;
    border: none;
    background: transparent;
    color: #0F1E33;
    font-size: 14px; font-weight: 600;
    font-family: inherit; outline: none;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none' stroke='%235A6C8C' stroke-width='2'%3E%3Cpath d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
}

.od-textarea {
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
.od-textarea::placeholder { color: #8B9BB4; }
.od-textarea:focus {
    border-color: #2E6DB4;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(46,109,180,.1);
}

.od-quick-status { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; }
.od-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: 99px;
    background: #F5F8FC;
    border: 1.5px solid #E3E9F2;
    color: #5A6C8C;
    font-size: 11.5px; font-weight: 700;
    cursor: pointer; transition: all .2s;
    text-transform: capitalize;
    font-family: inherit;
}
.od-chip:hover {
    border-color: #2E6DB4;
    color: #2E6DB4;
    background: #E8F1FB;
}
.od-chip.active {
    background: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 3px 10px rgba(46,109,180,.3);
}

.od-modal-footer {
    padding: 16px 26px;
    border-top: 1px solid #E3E9F2;
    background: linear-gradient(180deg, #FAFCFD, #fff);
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    flex-shrink: 0;
}
.od-btn-modal {
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
.od-btn-modal-cancel {
    background: #fff;
    color: #5A6C8C;
    border: 1.5px solid #E3E9F2;
}
.od-btn-modal-cancel:hover {
    border-color: #5A6C8C;
    color: #0F1E33;
    background: #F5F8FC;
}
.od-btn-modal-submit {
    background: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    color: #fff;
    box-shadow: 0 6px 20px -4px rgba(46,109,180,.45);
}
.od-btn-modal-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px -4px rgba(46,109,180,.55);
}
.od-btn-modal-submit[disabled] { opacity: .7; cursor: wait; transform: none; }

/* ============ RESPONSIVE ============ */
@media (max-width: 1199.98px) {
    .od-grid { grid-template-columns: 1fr; }
}
@media (max-width: 991.98px) {
    .od-info-cards { grid-template-columns: repeat(2, 1fr); }
    .od-kpi-cards { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 767.98px) {
    .od-hero { padding: 18px 20px; }
    .od-hero-badge { width: 48px; height: 48px; font-size: 22px; }
    .od-hero h1 { font-size: 17px; }
    .od-info-card { padding: 14px 16px; min-height: 76px; gap: 12px; }
    .od-info-icon { width: 40px; height: 40px; font-size: 18px; }
    .od-info-value { font-size: 14px; }
    .od-info-value.mono { font-size: 13px; }
    .od-kpi-card { padding: 16px; gap: 14px; min-height: 80px; }
    .od-kpi-card-icon { width: 48px; height: 48px; font-size: 21px; border-radius: 12px; }
    .od-kpi-card-value { font-size: 20px; }
    .od-items thead th, .od-items td { padding: 11px 10px; }
    .od-item-img { width: 38px; height: 38px; font-size: 16px; }
}
@media (max-width: 575.98px) {
    .od-hero {
        padding: 16px 18px;
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    .od-hero-actions { width: 100%; }
    .od-hero-actions .od-btn { flex: 1; justify-content: center; }
    .od-info-cards { grid-template-columns: 1fr; gap: 10px; }
    .od-kpi-cards { grid-template-columns: 1fr; gap: 10px; }
    .od-info-card { padding: 12px 14px; min-height: 68px; gap: 10px; }
    .od-info-icon { width: 36px; height: 36px; font-size: 16px; border-radius: 10px; }
    .od-info-value { font-size: 14px; }
    .od-info-value.mono { font-size: 12.5px; }
    .od-info-label { font-size: 10px; }
    .od-kpi-card { padding: 14px 16px; gap: 12px; min-height: 72px; }
    .od-kpi-card-icon { width: 44px; height: 44px; font-size: 19px; border-radius: 11px; }
    .od-kpi-card-value { font-size: 18px; }
    .od-kpi-card-label { font-size: 10px; }
    .od-card-head { padding: 13px 14px; }
    .od-card-body { padding: 14px; }
    .od-customer { gap: 10px; padding-bottom: 12px; margin-bottom: 12px; }
    .od-customer-avatar { width: 46px; height: 46px; font-size: 17px; }
    .od-customer-name { font-size: 14px; }
    .od-summary-total { padding: 12px 14px; }
    .od-summary-total .value { font-size: 17px; }
    .od-modal { max-width: 100%; border-radius: 18px; }
    .od-modal-header { padding: 18px 20px; }
    .od-modal-icon { width: 42px; height: 42px; font-size: 20px; }
    .od-modal-title h3 { font-size: 15px; }
    .od-modal-body { padding: 20px; }
    .od-modal-footer { padding: 14px 20px; flex-direction: column-reverse; }
    .od-btn-modal { justify-content: center; width: 100%; }
}
@media (prefers-reduced-motion: reduce) {
    .od *, .od-modal, .od-modal-backdrop {
        transition: none !important;
        animation: none !important;
    }
}
</style>

<div class="od">

    {{-- HERO --}}
    <div class="od-hero">
        <div class="od-hero-left">
            <div class="od-hero-badge"><i class="bi bi-receipt"></i></div>
            <div class="od-hero-info">
                <h1>Order Details</h1>
                <p>
                    <i class="bi bi-clock-history"></i>
                    Placed {{ $order->created_at->format('d M Y, h:i A') }}
                </p>
            </div>
        </div>
        <div class="od-hero-actions">
            <button type="button" class="od-btn od-btn-primary" id="openStatusModal">
                <i class="bi bi-pencil-fill"></i> Update Status
            </button>
            <a href="{{ route('admin.orders') }}" class="od-btn od-btn-glass">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- 4 INFO CARDS --}}
    <div class="od-info-cards">

        <div class="od-info-card od-info-number">
            <div class="od-info-icon"><i class="bi bi-hash"></i></div>
            <div class="od-info-body">
                <div class="od-info-label">Order Number</div>
                <div class="od-info-value mono">{{ $order->order_number }}</div>
                <div class="od-info-sub">{{ $order->items->count() }} item{{ $order->items->count() != 1 ? 's' : '' }}</div>
            </div>
        </div>

        <div class="od-info-card od-info-status" data-status="{{ $order->status }}">
            <div class="od-info-icon">
                <i class="bi {{ $statusIcon[$order->status] ?? 'bi-circle' }}"></i>
            </div>
            <div class="od-info-body">
                <div class="od-info-label">Order Status</div>
                <div class="od-info-value">
                    <span class="od-pill od-pill-{{ $order->status }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="od-info-card od-info-payment">
            <div class="od-info-icon"><i class="bi bi-credit-card-fill"></i></div>
            <div class="od-info-body">
                <div class="od-info-label">Payment</div>
                <div class="od-info-value">
                    <span class="od-pill od-pill-{{ $order->payment_status }}">
                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                    </span>
                </div>
                <div class="od-info-sub">
                    {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                </div>
            </div>
        </div>

        <div class="od-info-card od-info-date">
            <div class="od-info-icon"><i class="bi bi-calendar3"></i></div>
            <div class="od-info-body">
                <div class="od-info-label">Order Date</div>
                <div class="od-info-value">{{ $order->created_at->format('d M Y') }}</div>
                <div class="od-info-sub">{{ $order->created_at->format('h:i A') }} · {{ $order->created_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>

    {{-- 4 KPI CARDS --}}
    <div class="od-kpi-cards">

        <div class="od-kpi-card" data-color="blue">
            <div class="od-kpi-card-icon"><i class="bi bi-currency-rupee"></i></div>
            <div class="od-kpi-card-body">
                <div class="od-kpi-card-value">₹{{ number_format($order->grand_total, 0) }}</div>
                <div class="od-kpi-card-label">Grand Total</div>
            </div>
        </div>

        <div class="od-kpi-card" data-color="emerald">
            <div class="od-kpi-card-icon"><i class="bi bi-box-seam-fill"></i></div>
            <div class="od-kpi-card-body">
                <div class="od-kpi-card-value">{{ $order->items->sum('quantity') ?? $order->items->count() }}</div>
                <div class="od-kpi-card-label">Items</div>
            </div>
        </div>

        <div class="od-kpi-card" data-color="teal">
            <div class="od-kpi-card-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="od-kpi-card-body">
                <div class="od-kpi-card-value">₹{{ number_format($order->paid_amount, 0) }}</div>
                <div class="od-kpi-card-label">Paid</div>
            </div>
        </div>

        <div class="od-kpi-card" data-color="violet">
            <div class="od-kpi-card-icon"><i class="bi bi-truck"></i></div>
            <div class="od-kpi-card-body">
                <div class="od-kpi-card-value">{{ ucfirst($order->shipping_method ?? 'Standard') }}</div>
                <div class="od-kpi-card-label">Shipping</div>
            </div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="od-grid">

        {{-- LEFT --}}
        <div>

            {{-- Order Items --}}
            <div class="od-card" data-accent="blue">
                <div class="od-card-head">
                    <i class="bi bi-bag-check-fill"></i>
                    <h5>Order Items</h5>
                    <span class="badge">{{ $order->items->count() }} item{{ $order->items->count() != 1 ? 's' : '' }}</span>
                </div>
                @if($order->items->count())
                    <div style="overflow-x:auto">
                        <table class="od-items">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="od-item">
                                            <div class="od-item-img">
                                                <i class="bi bi-image"></i>
                                                @if(!empty($item->product?->thumbnail))
                                                    <img src="{{ \App\Models\Product::resolveImageUrl($item->product->thumbnail) }}" alt="" onerror="this.remove()">
                                                @endif
                                            </div>
                                            <div class="od-item-info">
                                                <span class="od-item-name">{{ $item->product_name }}</span>
                                                @if($item->sku)
                                                    <span class="od-item-sku">{{ $item->sku }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="od-qty">×{{ $item->quantity }}</span></td>
                                    <td><span class="od-price">₹{{ number_format($item->price, 0) }}</span></td>
                                    <td><span class="od-total">₹{{ number_format($item->total, 0) }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="od-card-body text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size:40px;opacity:.4;display:block;margin-bottom:10px"></i>
                        No items in this order.
                    </div>
                @endif
            </div>

            {{-- Timeline --}}
            <div class="od-card" data-accent="teal">
                <div class="od-card-head">
                    <i class="bi bi-clock-history"></i>
                    <h5>Order Timeline</h5>
                    <span class="badge">{{ $order->statusHistory()->count() }} events</span>
                </div>
                <div class="od-card-body">
                    @php $history = $order->statusHistory()->latest()->get(); @endphp
                    @if($history->count())
                        <div class="od-timeline">
                            @foreach($history as $h)
                                <div class="od-tl-item">
                                    <div class="od-tl-dot">
                                        <i class="bi {{ $statusIcon[$h->status] ?? 'bi-circle' }}"></i>
                                    </div>
                                    <div class="od-tl-content">
                                        <div class="od-tl-status">
                                            {{ ucfirst(str_replace('_', ' ', $h->status)) }}
                                        </div>
                                        <div class="od-tl-meta">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $h->created_at->format('d M Y, h:i A') }}
                                            @if($h->changed_by_type)
                                                <span class="sep"></span>
                                                <span>by {{ ucfirst($h->changed_by_type) }}</span>
                                            @endif
                                            <span class="sep"></span>
                                            <span>{{ $h->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($h->note)
                                            <div class="od-tl-note">"{{ $h->note }}"</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-clock" style="font-size:40px;opacity:.4;display:block;margin-bottom:10px"></i>
                            No status history yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div>

            {{-- Customer --}}
            <div class="od-card" data-accent="violet">
                <div class="od-card-head">
                    <i class="bi bi-person-fill"></i>
                    <h5>Customer</h5>
                </div>
                <div class="od-card-body">
                    <div class="od-customer">
                        <div class="od-customer-avatar">
                            {{ strtoupper(substr($order->customer_name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="od-customer-info">
                            <div class="od-customer-name">{{ $order->customer_name ?? 'Guest' }}</div>
                            @if($order->customer_email)
                                <div class="od-customer-email">{{ $order->customer_email }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="od-contact-rows">
                        @if($order->customer_phone)
                            <div class="od-contact-row">
                                <div class="od-contact-icon"><i class="bi bi-telephone-fill"></i></div>
                                <div class="od-contact-text"><a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a></div>
                            </div>
                        @endif
                        @if($order->customer_email)
                            <div class="od-contact-row">
                                <div class="od-contact-icon"><i class="bi bi-envelope-fill"></i></div>
                                <div class="od-contact-text"><a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Payment Summary --}}
            <div class="od-card" data-accent="emerald">
                <div class="od-card-head">
                    <i class="bi bi-cash-stack"></i>
                    <h5>Payment Summary</h5>
                </div>
                <div class="od-card-body">
                    <div class="od-summary">
                        <div class="od-summary-row">
                            <span class="label"><i class="bi bi-receipt"></i>Subtotal</span>
                            <span class="value">₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="od-summary-row discount">
                                <span class="label"><i class="bi bi-tag-fill"></i>Discount</span>
                                <span class="value">−₹{{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="od-summary-row">
                            <span class="label"><i class="bi bi-percent"></i>Tax</span>
                            <span class="value">₹{{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="od-summary-row">
                            <span class="label"><i class="bi bi-truck"></i>Shipping</span>
                            <span class="value">₹{{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                    </div>
                    <div class="od-summary-total">
                        <span class="label">Grand Total</span>
                        <span class="value">₹{{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            @if($order->shipping_address)
                <div class="od-card" data-accent="amber">
                    <div class="od-card-head">
                        <i class="bi bi-geo-alt-fill"></i>
                        <h5>Shipping Address</h5>
                    </div>
                    <div class="od-card-body">
                        <div class="od-address">
                            <strong><i class="bi bi-house-door-fill"></i> {{ $order->customer_name }}</strong>
                            {{ $order->shipping_address['line1'] ?? '' }}<br>
                            @if(!empty($order->shipping_address['line2']))
                                {{ $order->shipping_address['line2'] }}<br>
                            @endif
                            {{ $order->shipping_address['city'] ?? '' }}@if(!empty($order->shipping_address['state'])), {{ $order->shipping_address['state'] }}@endif
                            @if(!empty($order->shipping_address['pincode'])) — {{ $order->shipping_address['pincode'] }}@endif
                            <br>{{ $order->shipping_address['country'] ?? 'India' }}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Order Info --}}
            <div class="od-card" data-accent="blue">
                <div class="od-card-head">
                    <i class="bi bi-info-circle-fill"></i>
                    <h5>More Info</h5>
                </div>
                <div class="od-card-body">
                    <div class="od-rows">
                        @if($order->tracking_number)
                            <div class="od-row">
                                <span class="od-row-label"><i class="bi bi-upc-scan"></i>Tracking</span>
                                <span class="od-row-value mono">{{ $order->tracking_number }}</span>
                            </div>
                        @endif
                        @if($order->coupon_code)
                            <div class="od-row">
                                <span class="od-row-label"><i class="bi bi-ticket-perforated"></i>Coupon</span>
                                <span class="od-row-value mono">{{ $order->coupon_code }}</span>
                            </div>
                        @endif
                        <div class="od-row">
                            <span class="od-row-label"><i class="bi bi-credit-card"></i>Method</span>
                            <span class="od-row-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</span>
                        </div>
                        <div class="od-row">
                            <span class="od-row-label"><i class="bi bi-check-circle"></i>Paid</span>
                            <span class="od-row-value">₹{{ number_format($order->paid_amount, 2) }}</span>
                        </div>
                        @if($order->shipped_at)
                            <div class="od-row">
                                <span class="od-row-label"><i class="bi bi-truck"></i>Shipped</span>
                                <span class="od-row-value">{{ $order->shipped_at->format('d M Y') }}</span>
                            </div>
                        @endif
                        @if($order->delivered_at)
                            <div class="od-row">
                                <span class="od-row-label"><i class="bi bi-check2-circle"></i>Delivered</span>
                                <span class="od-row-value">{{ $order->delivered_at->format('d M Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- PREMIUM STATUS MODAL --}}
<div class="od-modal-backdrop" id="statusModalBackdrop"></div>

<div class="od-modal" id="statusModal" role="dialog" aria-modal="true">
    <div class="od-modal-header">
        <div class="od-modal-icon"><i class="bi bi-pencil-square"></i></div>
        <div class="od-modal-title">
            <h3>Update Order Status</h3>
            <p>Order {{ $order->order_number }}</p>
        </div>
        <button type="button" class="od-modal-close" id="closeStatusModal">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="od-modal-body">
        <div class="od-form-group">
            <label class="od-form-label">
                <i class="bi bi-circle-fill"></i> Select New Status
            </label>
            <div class="od-select-wrap">
                <div class="od-select-icon">
                    <i class="bi {{ $statusIcon[$order->status] ?? 'bi-circle' }}" id="statusIcon"></i>
                </div>
                <select class="od-select" id="newStatus">
                    @foreach(['pending','confirmed','processing','packed','shipped','out_for_delivery','delivered','cancelled','returned','refunded'] as $s)
                        <option value="{{ $s }}" @selected($order->status == $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="od-quick-status">
                @foreach(['confirmed','processing','shipped','delivered','cancelled'] as $s)
                    <button type="button" class="od-chip {{ $order->status == $s ? 'active' : '' }}" data-status="{{ $s }}">
                        <i class="bi {{ $statusIcon[$s] ?? 'bi-circle' }}"></i>
                        {{ ucfirst($s) }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="od-form-group">
            <label class="od-form-label">
                <i class="bi bi-chat-left-text"></i> Add a Note (optional)
            </label>
            <textarea class="od-textarea" id="statusNote" rows="3" placeholder="Add a note about this status change..."></textarea>
        </div>
    </div>

    <div class="od-modal-footer">
        <button type="button" class="od-btn-modal od-btn-modal-cancel" id="cancelStatusModal">
            <i class="bi bi-x-lg"></i> Cancel
        </button>
        <button type="button" class="od-btn-modal od-btn-modal-submit" id="confirmStatus">
            <i class="bi bi-check2-circle"></i> Update Status
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    const $modal = $('#statusModal');
    const $backdrop = $('#statusModalBackdrop');

    /* ---------- Modal Open / Close ---------- */
    function openModal() {
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

    $('#openStatusModal').on('click', openModal);
    $('#closeStatusModal, #cancelStatusModal').on('click', closeModal);
    $backdrop.on('click', closeModal);
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $modal.hasClass('show')) closeModal();
    });

    /* ---------- Status Icons ---------- */
    const statusIcons = @json($statusIcon);

    function updateStatusIcon(status) {
        const icon = statusIcons[status] || 'bi-circle';
        $('#statusIcon').attr('class', 'bi ' + icon);
    }

    /* ---------- Quick Chips ---------- */
    $('.od-chip').on('click', function () {
        const status = $(this).data('status');
        $('#newStatus').val(status);
        $('.od-chip').removeClass('active');
        $(this).addClass('active');
        updateStatusIcon(status);
    });

    /* ---------- Select Change ---------- */
    $('#newStatus').on('change', function () {
        const status = $(this).val();
        $('.od-chip').removeClass('active').filter('[data-status="' + status + '"]').addClass('active');
        updateStatusIcon(status);
    });

    /* ---------- Submit ---------- */
    $('#confirmStatus').on('click', function () {
        const $btn = $(this);
        const status = $('#newStatus').val();
        const note = $('#statusNote').val().trim();

        if (!status) {
            if (window.zError) zError('Please select a status.');
            return;
        }

        $btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Updating...');

        $.post('{{ route("admin.orders.status", $order) }}', {
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
            const msg = xhr.responseJSON?.message || 'Failed to update status. Please try again.';
            if (window.zError) {
                zError(msg, 'Update Failed');
            } else {
                alert(msg);
            }
        });
    });
});
</script>
@endpush
