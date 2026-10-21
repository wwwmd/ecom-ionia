@extends('layouts.admin')
@section('title', 'Customer Profile')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('admin.customers') }}">Customers</a>
    <i class="bi bi-chevron-right"></i><span>{{ $customer->name }}</span>
@endsection

@section('content')

@php
    // ----- Avatar
    $avatarUrl = function ($user) {
        if ($user->avatar) {
            $path = ltrim($user->avatar, '/');
            if (\Illuminate\Support\Str::startsWith($path, ['http://','https://'])) return $path;
            if (\Illuminate\Support\Str::startsWith($path, 'storage/') || file_exists(public_path($path))) return asset($path);
            return asset('storage/' . $path);
        }
        $name = urlencode($user->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=2E6DB4&color=fff&bold=true&size=256";
    };

    $avatar = $avatarUrl($customer);
    $profile = $customer->customerProfile;
    $totalOrders = (int) ($profile->total_orders ?? $customer->orders->count() ?? 0);
    $totalSpent  = (float) ($profile->total_spent ?? $customer->orders->sum('grand_total') ?? 0);
    $avgOrderValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;
    $memberSince = $customer->created_at;
    $daysSinceJoin = (int) $memberSince->diffInDays(now());
@endphp

<style>
/* ============================================================
   PREMIUM CUSTOMER PROFILE
   ============================================================ */
.cd {
    --cd-primary: #2E6DB4;
    --cd-primary-dark: #1E4C82;
    --cd-primary-light: #E8F1FB;
    --cd-accent: #26A69A;
    --cd-ink: #0F1E33;
    --cd-slate: #5A6C8C;
    --cd-muted: #8B9BB4;
    --cd-line: #E3E9F2;
    --cd-bg: #F5F8FC;
    --cd-gradient: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    color: var(--cd-ink);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    -webkit-font-smoothing: antialiased;
}
.cd * { box-sizing: border-box; }

/* ============ HERO ============ */
.cd-hero {
    position: relative;
    overflow: hidden;
    padding: 22px 26px;
    margin-bottom: 18px;
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
.cd-hero::before {
    content: "";
    position: absolute;
    right: -60px; top: -80px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(38,166,154,.42), transparent 65%);
    pointer-events: none;
}
.cd-hero::after {
    content: "";
    position: absolute;
    left: -60px; bottom: -80px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(46,109,180,.5), transparent 65%);
    pointer-events: none;
}
.cd-hero > * { position: relative; z-index: 1; }

.cd-hero-left {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
    flex: 1;
}
.cd-hero-avatar {
    position: relative;
    width: 62px;
    height: 62px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    background: linear-gradient(135deg, #2E6DB4, #26A69A);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: 22px;
    box-shadow: 0 8px 24px rgba(0,0,0,.25), 0 0 0 3px rgba(255,255,255,.15);
}
.cd-hero-avatar img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cd-hero-avatar .status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid #1E4C82;
    z-index: 2;
}
.cd-hero-avatar .status.active   { background: #10B981; }
.cd-hero-avatar .status.inactive { background: #94A3B8; }
.cd-hero-avatar .status.blocked  { background: #DC2626; }

.cd-hero-info { min-width: 0; flex: 1; }
.cd-hero-info h1 {
    font-size: 20px;
    font-weight: 800;
    margin: 0 0 6px;
    color: #fff;
    letter-spacing: -.02em;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cd-hero-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    font-size: 12.5px;
    color: rgba(255,255,255,.72);
}
.cd-hero-meta .sep {
    width: 3px;
    height: 3px;
    border-radius: 50%;
    background: rgba(255,255,255,.4);
}
.cd-hero-meta .code {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    background: rgba(255,255,255,.12);
    padding: 2px 8px;
    border-radius: 6px;
    color: #fff;
    font-weight: 600;
    font-size: 11.5px;
    border: 1px solid rgba(255,255,255,.18);
}
.cd-hero-meta .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 99px;
    background: rgba(255,255,255,.15);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    border: 1px solid rgba(255,255,255,.2);
}
.cd-hero-meta .status-pill::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #10B981;
    box-shadow: 0 0 0 3px rgba(16,185,129,.25);
}

.cd-hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.cd-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border: none; border-radius: 99px;
    font-weight: 700; font-size: 13px;
    text-decoration: none; cursor: pointer;
    transition: all .22s;
    white-space: nowrap;
}
.cd-btn i { font-size: 14px; }
.cd-btn-primary {
    background: #fff;
    color: var(--cd-primary-dark);
    box-shadow: 0 3px 10px rgba(0,0,0,.15);
}
.cd-btn-primary:hover {
    color: var(--cd-primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,.2);
}
.cd-btn-glass {
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.24);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
}
.cd-btn-glass:hover {
    background: rgba(255,255,255,.24);
    color: #fff;
    transform: translateY(-2px);
}

/* ============ KPI ROW ============ */
.cd-kpis {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 18px;
}
.cd-kpi {
    position: relative;
    background: #fff;
    border: 1px solid var(--cd-line);
    border-radius: 14px;
    padding: 16px 18px;
    overflow: hidden;
    transition: all .25s;
    display: flex;
    align-items: center;
    gap: 14px;
}
.cd-kpi:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px -8px rgba(15,30,51,.12);
    border-color: transparent;
}
.cd-kpi::before {
    content: "";
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--cd-accent-gradient, var(--cd-gradient));
}
.cd-kpi-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
    background: var(--cd-accent-gradient, var(--cd-gradient));
    box-shadow: 0 4px 12px var(--cd-accent-shadow, rgba(46,109,180,.28));
    flex-shrink: 0;
}
.cd-kpi-body { flex: 1; min-width: 0; }
.cd-kpi-value {
    font-size: 22px;
    font-weight: 800;
    color: var(--cd-ink);
    line-height: 1;
    letter-spacing: -.03em;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    margin-bottom: 3px;
}
.cd-kpi-label {
    font-size: 11.5px;
    font-weight: 600;
    color: var(--cd-slate);
    letter-spacing: .2px;
    text-transform: uppercase;
}

.cd-kpi-orders { --cd-accent-gradient: linear-gradient(135deg, #2E6DB4, #1E4C82); --cd-accent-shadow: rgba(46,109,180,.28); }
.cd-kpi-spent  { --cd-accent-gradient: linear-gradient(135deg, #10B981, #059669); --cd-accent-shadow: rgba(16,185,129,.28); }
.cd-kpi-avg    { --cd-accent-gradient: linear-gradient(135deg, #8B5CF6, #6D28D9); --cd-accent-shadow: rgba(139,92,246,.28); }
.cd-kpi-days   { --cd-accent-gradient: linear-gradient(135deg, #F59E0B, #D97706); --cd-accent-shadow: rgba(245,158,11,.28); }

/* ============ LAYOUT GRID ============ */
.cd-grid {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 18px;
    align-items: start;
}
@media (max-width: 991.98px) {
    .cd-grid { grid-template-columns: 1fr; }
}

/* ============ CARD ============ */
.cd-card {
    background: #fff;
    border: 1px solid var(--cd-line);
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(15,30,51,.04);
    overflow: hidden;
    margin-bottom: 16px;
    position: relative;
}
.cd-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--cd-accent-color, var(--cd-gradient));
    opacity: .9;
}
.cd-card[data-accent="blue"]    { --cd-accent-color: linear-gradient(90deg, #2E6DB4, #1E4C82); }
.cd-card[data-accent="teal"]    { --cd-accent-color: linear-gradient(90deg, #26A69A, #00796B); }
.cd-card[data-accent="violet"]  { --cd-accent-color: linear-gradient(90deg, #8B5CF6, #6D28D9); }
.cd-card[data-accent="amber"]   { --cd-accent-color: linear-gradient(90deg, #F59E0B, #D97706); }
.cd-card[data-accent="emerald"] { --cd-accent-color: linear-gradient(90deg, #10B981, #059669); }

.cd-card-head {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 18px;
    border-bottom: 1px solid var(--cd-line);
    background: linear-gradient(180deg, #FAFCFD, #fff);
}
.cd-card-head i {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #fff;
    background: var(--cd-accent-color, var(--cd-gradient));
    box-shadow: 0 3px 10px rgba(46,109,180,.22);
    flex-shrink: 0;
}
.cd-card-head h5 {
    margin: 0;
    font-size: 14.5px;
    font-weight: 700;
    color: var(--cd-ink);
    flex: 1;
}
.cd-card-head .badge {
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 99px;
    background: var(--cd-primary-light);
    color: var(--cd-primary);
    font-weight: 700;
}
.cd-card-body { padding: 16px 18px; }

/* ============ CONTACT ROWS ============ */
.cd-rows { display: flex; flex-direction: column; }
.cd-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px dashed var(--cd-line);
    font-size: 13.5px;
}
.cd-row:last-child { border-bottom: 0; padding-bottom: 0; }
.cd-row:first-child { padding-top: 0; }

.cd-row-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--cd-slate);
    font-weight: 500;
}
.cd-row-label i {
    width: 16px;
    font-size: 13px;
    color: var(--cd-muted);
    text-align: center;
}
.cd-row-value {
    font-weight: 700;
    color: var(--cd-ink);
    text-align: right;
    font-size: 13.5px;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 60%;
}
.cd-row-value a { color: var(--cd-primary); text-decoration: none; }
.cd-row-value a:hover { text-decoration: underline; }
.cd-row-value.mono {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 12.5px;
    background: #F1F5F9;
    padding: 3px 10px;
    border-radius: 6px;
    border: 1px solid #E2E8F0;
}

/* ============ TABS ============ */
.cd-tabs {
    display: flex;
    gap: 4px;
    padding: 6px;
    background: #F5F8FC;
    border-bottom: 1px solid var(--cd-line);
    overflow-x: auto;
    scrollbar-width: thin;
}
.cd-tabs::-webkit-scrollbar { height: 4px; }
.cd-tabs::-webkit-scrollbar-thumb { background: var(--cd-line); border-radius: 4px; }
.cd-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    color: var(--cd-slate);
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
    text-decoration: none;
}
.cd-tab i { font-size: 14px; }
.cd-tab:hover { color: var(--cd-primary); background: rgba(46,109,180,.06); }
.cd-tab.active {
    background: #fff;
    color: var(--cd-primary);
    box-shadow: 0 2px 8px rgba(15,30,51,.08);
}
.cd-tab .count {
    font-size: 10.5px;
    padding: 1px 7px;
    border-radius: 99px;
    background: var(--cd-primary-light);
    color: var(--cd-primary);
    font-weight: 700;
    margin-left: 2px;
}
.cd-tab.active .count {
    background: var(--cd-gradient);
    color: #fff;
}

.cd-tab-content { padding: 18px; }

/* ============ ORDERS TABLE ============ */
.cd-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.cd-table thead th {
    padding: 11px 14px;
    font-size: 10.5px;
    font-weight: 800;
    color: var(--cd-slate);
    text-align: left;
    background: #F7FAFC;
    border-bottom: 1px solid var(--cd-line);
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap;
}
.cd-table thead th:first-child { border-radius: 10px 0 0 0; }
.cd-table thead th:last-child  { border-radius: 0 10px 0 0; }

.cd-table tbody tr {
    transition: background .15s;
}
.cd-table tbody tr:hover { background: #F8FBFE; }
.cd-table td {
    padding: 14px;
    border-bottom: 1px solid #EEF2F7;
    vertical-align: middle;
    font-size: 13.5px;
}
.cd-table tbody tr:last-child td { border-bottom: 0; }

.cd-order-num {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-weight: 700;
    font-size: 12.5px;
    color: var(--cd-primary);
    background: var(--cd-primary-light);
    padding: 3px 9px;
    border-radius: 6px;
    border: 1px solid #BFDBFE;
    display: inline-block;
    text-decoration: none;
}
.cd-order-num:hover { background: #fff; }

.cd-price {
    font-weight: 800;
    color: #047857;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 13.5px;
}

/* Status pill */
.cd-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 11px 4px 8px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    line-height: 1.4;
    border: 1px solid transparent;
    text-transform: capitalize;
}
.cd-pill::before {
    content: "";
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
}
.cd-pill.pending, .cd-pill.cod_pending { background: #FEF3C7; color: #92400E; border-color: #FDE68A; }
.cd-pill.confirmed, .cd-pill.processing { background: #DBEAFE; color: #1E40AF; border-color: #BFDBFE; }
.cd-pill.packed { background: #E0E7FF; color: #4338CA; border-color: #C7D2FE; }
.cd-pill.shipped, .cd-pill.out_for_delivery { background: #E0E7FF; color: #4338CA; border-color: #C7D2FE; }
.cd-pill.delivered, .cd-pill.paid, .cd-pill.completed { background: #D1FAE5; color: #065F46; border-color: #A7F3D0; }
.cd-pill.cancelled, .cd-pill.failed { background: #FEE2E2; color: #991B1B; border-color: #FECACA; }
.cd-pill.refunded { background: #F1F5F9; color: #475569; border-color: #CBD5E1; }

/* ============ EMPTY STATE ============ */
.cd-empty {
    text-align: center;
    padding: 40px 20px;
}
.cd-empty-icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    margin: 0 auto 14px;
    background: linear-gradient(135deg, rgba(46,109,180,.08), rgba(38,166,154,.08));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: var(--cd-primary);
}
.cd-empty h4 {
    font-size: 15px;
    font-weight: 700;
    margin: 0 0 6px;
    color: var(--cd-ink);
}
.cd-empty p {
    color: var(--cd-muted);
    margin: 0;
    font-size: 13px;
}

/* ============ SIDEBAR (left column) ============ */
.cd-sidebar-card {
    padding: 22px 20px;
}

.cd-sidebar-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid var(--cd-line);
}
.cd-sidebar-stat {
    padding: 12px;
    border-radius: 10px;
    text-align: center;
}
.cd-sidebar-stat.orders { background: linear-gradient(135deg, #EFF6FF, #DBEAFE); }
.cd-sidebar-stat.spent  { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); }
.cd-sidebar-stat strong {
    display: block;
    font-size: 18px;
    font-weight: 800;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    letter-spacing: -.02em;
    line-height: 1;
    margin-bottom: 4px;
}
.cd-sidebar-stat.orders strong { color: #1E40AF; }
.cd-sidebar-stat.spent  strong { color: #047857; }
.cd-sidebar-stat span {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: var(--cd-slate);
}

/* Quick actions */
.cd-quick-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 16px;
}
.cd-action-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 14px;
    border-radius: 11px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: all .2s;
    border: 1.5px solid var(--cd-line);
    background: #fff;
    color: var(--cd-ink);
    cursor: pointer;
    text-align: left;
}
.cd-action-btn i {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #fff;
    flex-shrink: 0;
}
.cd-action-btn.primary i   { background: linear-gradient(135deg, #2E6DB4, #1E4C82); }
.cd-action-btn.success i   { background: linear-gradient(135deg, #10B981, #059669); }
.cd-action-btn.violet i    { background: linear-gradient(135deg, #8B5CF6, #6D28D9); }
.cd-action-btn:hover {
    border-color: var(--cd-primary);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px -6px rgba(46,109,180,.28);
}

/* ============ ADDRESS CARD ============ */
.cd-address {
    padding: 14px;
    border-radius: 12px;
    background: linear-gradient(135deg, #F8FAFD, #EEF4FA);
    border: 1px solid #E1EAF4;
    font-size: 13px;
    line-height: 1.6;
    color: var(--cd-slate);
    margin-bottom: 10px;
    position: relative;
}
.cd-address:last-child { margin-bottom: 0; }
.cd-address-head {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-weight: 700;
    color: var(--cd-ink);
    font-size: 12.5px;
}
.cd-address-head i { color: var(--cd-primary); }
.cd-address-head .badge {
    margin-left: auto;
    padding: 2px 8px;
    border-radius: 99px;
    background: var(--cd-gradient);
    color: #fff;
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
}

/* ============ RESPONSIVE ============ */
@media (max-width: 991.98px) {
    .cd-kpis { grid-template-columns: repeat(2, 1fr); }
    .cd-grid { grid-template-columns: 1fr; }
    .cd-hero { padding: 18px 20px; }
    .cd-hero-avatar { width: 56px; height: 56px; font-size: 20px; }
}
@media (max-width: 575.98px) {
    .cd-hero {
        padding: 16px 18px;
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    .cd-hero-actions { width: 100%; }
    .cd-hero-actions .cd-btn { flex: 1; justify-content: center; }
    .cd-hero-info h1 { font-size: 17px; }
    .cd-kpis { gap: 8px; }
    .cd-kpi { padding: 12px 14px; gap: 10px; }
    .cd-kpi-value { font-size: 18px; }
    .cd-kpi-icon { width: 36px; height: 36px; font-size: 15px; }
    .cd-tabs { padding: 4px; }
    .cd-tab { padding: 8px 11px; font-size: 12px; }
    .cd-tab-content { padding: 14px; }
    .cd-card-head { padding: 13px 14px; }
    .cd-card-body { padding: 14px; }
    .cd-row { padding: 10px 0; font-size: 13px; }
}
@media (prefers-reduced-motion: reduce) {
    .cd * { transition: none !important; animation: none !important; }
}
</style>

<div class="cd">

    {{-- HERO --}}
    <div class="cd-hero">
        <div class="cd-hero-left">
            <div class="cd-hero-avatar">
                <span>{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                <img src="{{ $avatar }}" alt="" onerror="this.remove()">
                <span class="status {{ $customer->status }}"></span>
            </div>
            <div class="cd-hero-info">
                <h1>{{ $customer->name }}</h1>
                <div class="cd-hero-meta">
                    <span class="code">#C-{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="sep"></span>
                    <span class="status-pill">{{ ucfirst($customer->status) }}</span>
                    <span class="sep"></span>
                    <span><i class="bi bi-calendar3"></i> Member since {{ $memberSince->format('M Y') }}</span>
                </div>
            </div>
        </div>
        <div class="cd-hero-actions">
            @if($customer->email)
                <a href="mailto:{{ $customer->email }}" class="cd-btn cd-btn-primary">
                    <i class="bi bi-envelope-fill"></i> Email
                </a>
            @endif
            <a href="{{ route('admin.customers') }}" class="cd-btn cd-btn-glass">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- KPI ROW --}}
    <div class="cd-kpis">
        <div class="cd-kpi cd-kpi-orders">
            <div class="cd-kpi-icon"><i class="bi bi-bag-check-fill"></i></div>
            <div class="cd-kpi-body">
                <div class="cd-kpi-value">{{ number_format($totalOrders) }}</div>
                <div class="cd-kpi-label">Total Orders</div>
            </div>
        </div>
        <div class="cd-kpi cd-kpi-spent">
            <div class="cd-kpi-icon"><i class="bi bi-currency-rupee"></i></div>
            <div class="cd-kpi-body">
                <div class="cd-kpi-value">₹{{ number_format($totalSpent, 0) }}</div>
                <div class="cd-kpi-label">Total Spent</div>
            </div>
        </div>
        <div class="cd-kpi cd-kpi-avg">
            <div class="cd-kpi-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="cd-kpi-body">
                <div class="cd-kpi-value">₹{{ number_format($avgOrderValue, 0) }}</div>
                <div class="cd-kpi-label">Avg. Order Value</div>
            </div>
        </div>
        <div class="cd-kpi cd-kpi-days">
            <div class="cd-kpi-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="cd-kpi-body">
                <div class="cd-kpi-value">{{ number_format($daysSinceJoin) }}</div>
                <div class="cd-kpi-label">Days as Member</div>
            </div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="cd-grid">

        {{-- ============ LEFT SIDEBAR ============ --}}
        <div>

            {{-- Contact Info --}}
            <div class="cd-card" data-accent="blue">
                <div class="cd-card-head">
                    <i class="bi bi-person-badge-fill"></i>
                    <h5>Contact Information</h5>
                </div>
                <div class="cd-card-body">
                    <div class="cd-rows">
                        @if($customer->email)
                            <div class="cd-row">
                                <span class="cd-row-label"><i class="bi bi-envelope-fill"></i>Email</span>
                                <span class="cd-row-value">
                                    <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                                </span>
                            </div>
                        @endif
                        @if($customer->phone)
                            <div class="cd-row">
                                <span class="cd-row-label"><i class="bi bi-telephone-fill"></i>Phone</span>
                                <span class="cd-row-value">
                                    <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>
                                </span>
                            </div>
                        @endif
                        @if($customer->gender)
                            <div class="cd-row">
                                <span class="cd-row-label"><i class="bi bi-gender-ambiguous"></i>Gender</span>
                                <span class="cd-row-value">{{ ucfirst($customer->gender) }}</span>
                            </div>
                        @endif
                        @if($customer->dob)
                            <div class="cd-row">
                                <span class="cd-row-label"><i class="bi bi-cake2"></i>Date of Birth</span>
                                <span class="cd-row-value">{{ $customer->dob->format('d M Y') }}</span>
                            </div>
                        @endif
                        <div class="cd-row">
                            <span class="cd-row-label"><i class="bi bi-calendar-check"></i>Joined</span>
                            <span class="cd-row-value">{{ $memberSince->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="cd-sidebar-stats">
                        <div class="cd-sidebar-stat orders">
                            <strong>{{ number_format($totalOrders) }}</strong>
                            <span>Orders</span>
                        </div>
                        <div class="cd-sidebar-stat spent">
                            <strong>₹{{ number_format($totalSpent / 1000, 1) }}k</strong>
                            <span>Spent</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Addresses --}}
            @if($customer->addresses && $customer->addresses->count())
                <div class="cd-card" data-accent="teal">
                    <div class="cd-card-head">
                        <i class="bi bi-geo-alt-fill"></i>
                        <h5>Addresses</h5>
                        <span class="badge">{{ $customer->addresses->count() }}</span>
                    </div>
                    <div class="cd-card-body">
                        @foreach($customer->addresses as $addr)
                            <div class="cd-address">
                                <div class="cd-address-head">
                                    <i class="bi bi-house-door-fill"></i>
                                    {{ $addr->label ?? 'Address' }}
                                    @if($addr->is_default)
                                        <span class="badge">Default</span>
                                    @endif
                                </div>
                                <div>
                                    {{ $addr->address_line1 }}<br>
                                    @if($addr->address_line2) {{ $addr->address_line2 }}<br>@endif
                                    {{ $addr->city }}, {{ $addr->state }} — {{ $addr->pincode }}<br>
                                    {{ $addr->country }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Quick Actions --}}
            <div class="cd-card" data-accent="violet">
                <div class="cd-card-head">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <h5>Quick Actions</h5>
                </div>
                <div class="cd-card-body">
                    <div class="cd-quick-actions" style="margin-top:0">
                        @if($customer->email)
                            <a href="mailto:{{ $customer->email }}" class="cd-action-btn primary">
                                <i class="bi bi-envelope-fill"></i>
                                Send Email
                            </a>
                        @endif
                        @if($customer->phone)
                            <a href="tel:{{ $customer->phone }}" class="cd-action-btn success">
                                <i class="bi bi-telephone-fill"></i>
                                Call Customer
                            </a>
                        @endif
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone ?? '') }}" target="_blank" class="cd-action-btn violet">
                            <i class="bi bi-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============ RIGHT MAIN ============ --}}
        <div>

            {{-- Tabs Card --}}
            <div class="cd-card" data-accent="blue">
                <div class="cd-tabs">
                    <button class="cd-tab active" data-tab="orders">
                        <i class="bi bi-bag-check"></i> Orders
                        <span class="count">{{ $customer->orders->count() }}</span>
                    </button>
                    <button class="cd-tab" data-tab="reviews">
                        <i class="bi bi-star"></i> Reviews
                        @if(method_exists($customer, 'reviews'))
                            <span class="count">{{ $customer->reviews->count() }}</span>
                        @endif
                    </button>
                </div>

                {{-- Orders Tab --}}
                <div class="cd-tab-content" data-panel="orders">
                    @if($customer->orders->count())
                        <div style="overflow-x:auto">
                            <table class="cd-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($customer->orders as $o)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $o) }}" class="cd-order-num">
                                                {{ $o->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $o->created_at->format('d M Y') }}</td>
                                        <td>{{ $o->items->count() ?? 0 }} item{{ $o->items->count() != 1 ? 's' : '' }}</td>
                                        <td><span class="cd-price">₹{{ number_format($o->grand_total, 0) }}</span></td>
                                        <td>
                                            <span class="cd-pill {{ $o->payment_status }}">
                                                {{ ucfirst(str_replace('_', ' ', $o->payment_status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="cd-pill {{ $o->status }}">
                                                {{ ucfirst(str_replace('_', ' ', $o->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="cd-empty">
                            <div class="cd-empty-icon"><i class="bi bi-bag-x"></i></div>
                            <h4>No Orders Yet</h4>
                            <p>This customer hasn't placed any orders.</p>
                        </div>
                    @endif
                </div>

                {{-- Reviews Tab --}}
                <div class="cd-tab-content" data-panel="reviews" style="display:none">
                    @if(method_exists($customer, 'reviews') && $customer->reviews->count())
                        <div class="cd-empty">
                            <div class="cd-empty-icon"><i class="bi bi-star-fill"></i></div>
                            <h4>{{ $customer->reviews->count() }} Reviews</h4>
                            <p>Review details coming soon.</p>
                        </div>
                    @else
                        <div class="cd-empty">
                            <div class="cd-empty-icon"><i class="bi bi-star"></i></div>
                            <h4>No Reviews Yet</h4>
                            <p>This customer hasn't written any product reviews.</p>
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
    // Tab switching
    $('.cd-tab').on('click', function () {
        const tab = $(this).data('tab');
        $('.cd-tab').removeClass('active');
        $(this).addClass('active');
        $('.cd-tab-content').hide();
        $('.cd-tab-content[data-panel="' + tab + '"]').show();
    });
});
</script>
@endpush
