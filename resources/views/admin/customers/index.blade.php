@extends('layouts.admin')
@section('title', 'Customers')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i><span>Customers</span>
@endsection

@section('content')

@php
    // ---- Avatar URL (uses UI Avatars if no image)
    $avatarUrl = function ($user) {
        if ($user->avatar) {
            $path = ltrim($user->avatar, '/');
            if (\Illuminate\Support\Str::startsWith($path, ['http://','https://'])) return $path;
            if (\Illuminate\Support\Str::startsWith($path, 'storage/') || file_exists(public_path($path))) return asset($path);
            return asset('storage/' . $path);
        }
        $name = urlencode($user->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=2E6DB4&color=fff&bold=true&size=128";
    };

    // ---- Status counts
    $by = \App\Models\User::withRole('customer')
        ->selectRaw('status, COUNT(*) as c')
        ->groupBy('status')
        ->pluck('c', 'status');
    $stats = [
        'active'   => (int)($by['active']   ?? 0),
        'inactive' => (int)($by['inactive'] ?? 0),
        'blocked'  => (int)($by['blocked']  ?? 0),
    ];
    $stats['total'] = array_sum($stats);
    if ($stats['total'] === 0) $stats['total'] = (int)($customers->total() ?? 0);

    $pct = fn($n) => $stats['total'] > 0 ? round(($n / $stats['total']) * 100) : 0;
    $cur = request('status');
    $cardUrl = fn($s) => route('admin.customers', array_filter(array_merge(request()->except('status', 'page'), ['status' => $s])));
    $hasFilter = request()->filled('q') || request()->filled('status');

    $statusCards = [
        ['key' => 'active',   'label' => 'Active',   'icon' => 'bi-check2-circle'],
        ['key' => 'inactive', 'label' => 'Inactive', 'icon' => 'bi-pause-circle'],
        ['key' => 'blocked',  'label' => 'Blocked',  'icon' => 'bi-slash-circle'],
    ];
@endphp

<style>
/* ============================================================
   PREMIUM CUSTOMERS PAGE
   ============================================================ */
.cp {
    --cp-primary: #2E6DB4;
    --cp-primary-dark: #1E4C82;
    --cp-primary-light: #E8F1FB;
    --cp-accent: #26A69A;
    --cp-ink: #0F1E33;
    --cp-slate: #5A6C8C;
    --cp-muted: #8B9BB4;
    --cp-line: #E3E9F2;
    --cp-bg: #F5F8FC;
    --cp-gradient: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    color: var(--cp-ink);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    -webkit-font-smoothing: antialiased;
}
.cp * { box-sizing: border-box; }

/* ============ HERO ============ */
.cp-hero {
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
.cp-hero::before {
    content: "";
    position: absolute;
    right: -60px; top: -80px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(38,166,154,.42), transparent 65%);
    pointer-events: none;
}
.cp-hero::after {
    content: "";
    position: absolute;
    left: -60px; bottom: -80px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(46,109,180,.5), transparent 65%);
    pointer-events: none;
}
.cp-hero > * { position: relative; z-index: 1; }
.cp-hero-left { display: flex; align-items: center; gap: 16px; min-width: 0; }
.cp-hero-badge {
    width: 46px; height: 46px; border-radius: 12px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #26A69A; flex-shrink: 0;
}
.cp-hero h1 {
    font-size: 20px; font-weight: 800;
    margin: 0 0 2px; color: #fff;
    letter-spacing: -.02em; line-height: 1.15;
}
.cp-hero p { margin: 0; color: rgba(255,255,255,.68); font-size: 13px; line-height: 1.3; }
.cp-hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }

.cp-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    padding: 9px 18px; border: none; border-radius: 99px;
    background: #fff; color: var(--cp-primary-dark);
    font-weight: 700; font-size: 13px; text-decoration: none; cursor: pointer;
    transition: all .22s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 3px 10px rgba(0,0,0,.12); white-space: nowrap;
}
.cp-btn:hover { color: var(--cp-primary-dark); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.18); }
.cp-btn i { font-size: 14px; }
.cp-btn-primary {
    background: var(--cp-gradient); color: #fff;
    box-shadow: 0 4px 14px rgba(46,109,180,.32);
}
.cp-btn-primary:hover {
    background: linear-gradient(135deg, #1E4C82 0%, #00796B 100%);
    color: #fff; box-shadow: 0 8px 22px rgba(46,109,180,.42);
}
.cp-btn-ghost {
    background: #fff; color: var(--cp-slate);
    border: 1.5px solid var(--cp-line); box-shadow: none;
}
.cp-btn-ghost:hover {
    border-color: var(--cp-primary); color: var(--cp-primary);
    background: var(--cp-primary-light); box-shadow: none;
}

/* ============ STAT CARDS ============ */
.cp-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}
.cp-stat {
    position: relative;
    padding: 14px 16px;
    border-radius: 14px;
    background: #fff;
    border: 1px solid var(--cp-line);
    text-decoration: none;
    color: var(--cp-ink);
    overflow: hidden;
    transition: all .25s;
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 78px;
}
.cp-stat:hover {
    color: var(--cp-ink);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px -6px rgba(15,30,51,.1);
    border-color: transparent;
}
.cp-stat::before {
    content: "";
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--cp-accent-c, var(--cp-primary));
    transition: width .25s;
}
.cp-stat:hover::before { width: 5px; }

.cp-stat-icon {
    width: 40px; height: 40px;
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #fff;
    background: var(--cp-accent-gradient, var(--cp-gradient));
    box-shadow: 0 3px 10px var(--cp-accent-shadow, rgba(46,109,180,.24));
    flex-shrink: 0;
}
.cp-stat-body { flex: 1; min-width: 0; }
.cp-stat-value {
    font-size: 22px; font-weight: 800;
    line-height: 1; letter-spacing: -.03em;
    color: var(--cp-ink);
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    margin-bottom: 2px;
}
.cp-stat-label { font-size: 12px; font-weight: 600; color: var(--cp-ink); line-height: 1.2; }
.cp-stat-pct {
    position: absolute;
    top: 10px; right: 12px;
    padding: 2px 8px;
    border-radius: 99px;
    background: var(--cp-accent-bg, var(--cp-primary-light));
    color: var(--cp-accent-c, var(--cp-primary));
    font-size: 10px; font-weight: 700;
    line-height: 1.6;
}

.cp-stat-total {
    --cp-accent-c: #0F1E33;
    --cp-accent-bg: #E9EDF2;
    --cp-accent-gradient: linear-gradient(135deg, #35567F, #0F1E33);
    --cp-accent-shadow: rgba(15,30,51,.22);
}
.cp-stat-active {
    --cp-accent-c: #00796B;
    --cp-accent-bg: #ECFDF5;
    --cp-accent-gradient: linear-gradient(135deg, #26A69A, #00796B);
    --cp-accent-shadow: rgba(38,166,154,.28);
}
.cp-stat-inactive {
    --cp-accent-c: #64748B;
    --cp-accent-bg: #F1F5F9;
    --cp-accent-gradient: linear-gradient(135deg, #5A6C8C, #3A4A53);
    --cp-accent-shadow: rgba(90,108,140,.24);
}
.cp-stat-blocked {
    --cp-accent-c: #B91C1C;
    --cp-accent-bg: #FEF2F2;
    --cp-accent-gradient: linear-gradient(135deg, #F87171, #DC2626);
    --cp-accent-shadow: rgba(239,68,68,.28);
}
.cp-stat.is-active {
    border-color: var(--cp-accent-c);
    box-shadow: 0 0 0 3px var(--cp-accent-bg), 0 1px 3px rgba(15,30,51,.04);
}

/* ============ FILTERS ============ */
.cp-card {
    background: #fff;
    border: 1px solid var(--cp-line);
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(15,30,51,.04);
    overflow: hidden;
}
.cp-filters {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(0, 1fr) auto;
    gap: 12px;
    align-items: end;
    padding: 16px;
    margin-bottom: 14px;
}
.cp-fi { min-width: 0; }
.cp-fi label {
    display: block;
    font-size: 11px; font-weight: 700;
    color: var(--cp-slate);
    margin-bottom: 6px;
    letter-spacing: .02em;
    text-transform: uppercase;
}
.cp-input {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--cp-line);
    border-radius: 10px;
    background: #FAFCFD;
    color: var(--cp-ink);
    font-size: 13px;
    font-family: inherit;
    transition: all .2s;
    appearance: none; -webkit-appearance: none;
}
.cp-input::placeholder { color: var(--cp-muted); }
.cp-input:hover { border-color: #CBD5E1; }
.cp-input:focus {
    outline: none;
    border-color: var(--cp-primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(46,109,180,.1);
}
select.cp-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none' stroke='%235A6C8C' stroke-width='2'%3E%3Cpath d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 34px;
    cursor: pointer;
}
.cp-fi-search { position: relative; }
.cp-fi-search .cp-input { padding-left: 38px; }
.cp-fi-search::before {
    content: "\F52A";
    font-family: "bootstrap-icons";
    position: absolute;
    left: 13px; bottom: 10px;
    font-size: 14px;
    color: var(--cp-muted);
    pointer-events: none;
}
.cp-fbtn { display: flex; gap: 6px; }

/* ============ LIST HEADER ============ */
.cp-listhead {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-bottom: 1px solid var(--cp-line);
    flex-wrap: wrap;
}
.cp-listhead-count { font-size: 12.5px; color: var(--cp-slate); font-weight: 500; }
.cp-listhead-count strong { color: var(--cp-ink); font-weight: 700; }

/* ============ TABLE ============ */
.cp-tablewrap { overflow-x: auto; }
.cp-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    min-width: 900px;
}
.cp-table thead th {
    padding: 13px 18px;
    font-size: 10.5px;
    font-weight: 800;
    color: var(--cp-slate);
    text-align: left;
    background: linear-gradient(180deg, #F7FAFC 0%, #EEF3F7 100%);
    border-bottom: 2px solid var(--cp-line);
    text-transform: uppercase;
    letter-spacing: .8px;
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 2;
}
.cp-table thead th:first-child { padding-left: 22px; }
.cp-table thead th:last-child { padding-right: 22px; text-align: right; }

.cp-table tbody tr {
    position: relative;
    transition: background .18s;
    background: #fff;
}

.cp-table tbody tr:hover::before { width: 4px; }
.cp-table tbody tr:hover { background: linear-gradient(90deg, #F5FBFF 0%, #FAFCFE 100%); }

.cp-table td {
    padding: 16px 18px;
    border-bottom: 1px solid #EEF2F7;
    vertical-align: middle;
    font-size: 13.5px;
    color: var(--cp-ink);
    position: relative;
}
.cp-table td:first-child { padding-left: 22px; }
.cp-table td:last-child { padding-right: 22px; }
.cp-table tbody tr:last-child td { border-bottom: 0; }

/* Customer cell */
.cp-user {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 240px;
}
.cp-avatar {
    position: relative;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    background: linear-gradient(135deg, #2E6DB4, #26A69A);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: 17px;
    box-shadow: 0 3px 10px rgba(46,109,180,.22);
    transition: all .25s;
}
.cp-table tbody tr:hover .cp-avatar {
    box-shadow: 0 6px 16px rgba(46,109,180,.35);
    transform: scale(1.04);
}
.cp-avatar img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cp-avatar-status {
    position: absolute;
    bottom: -1px;
    right: -1px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    z-index: 2;
}
.cp-avatar-status.active { background: #10B981; }
.cp-avatar-status.inactive { background: #94A3B8; }
.cp-avatar-status.blocked { background: #DC2626; }

.cp-user-info { min-width: 0; flex: 1; }
.cp-user-name {
    display: block;
    font-weight: 700;
    font-size: 13.5px;
    color: var(--cp-ink);
    line-height: 1.35;
    margin-bottom: 3px;
    text-decoration: none;
    transition: color .15s;
}
.cp-table tbody tr:hover .cp-user-name { color: var(--cp-primary); }
.cp-user-id {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10.5px;
    font-weight: 700;
    color: var(--cp-slate);
    background: #F1F5F9;
    padding: 2px 8px;
    border-radius: 5px;
    border: 1px solid #E2E8F0;
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
}

/* Contact cell */
.cp-contact {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 200px;
}
.cp-contact-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: var(--cp-ink);
}
.cp-contact-item i {
    width: 14px;
    font-size: 12px;
    color: var(--cp-muted);
}
.cp-contact-item.email { color: #2E6DB4; font-weight: 500; }
.cp-contact-item.phone { color: var(--cp-slate); }

/* Orders count */
.cp-orders {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 11px;
    border-radius: 99px;
    background: #EFF6FF;
    color: #1E40AF;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid #BFDBFE;
}
.cp-orders i { font-size: 11px; }
.cp-orders.empty {
    background: #F1F5F9;
    color: var(--cp-slate);
    border-color: #E2E8F0;
}

/* Total spent */
.cp-spent {
    display: inline-flex;
    align-items: baseline;
    gap: 2px;
    font-size: 14px;
    font-weight: 800;
    color: #047857;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    letter-spacing: -.02em;
}
.cp-spent .currency { font-size: 12px; opacity: .75; }
.cp-spent.zero { color: var(--cp-muted); font-weight: 600; }

/* Status pill */
.cp-status {
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
}
.cp-status::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    box-shadow: 0 0 0 3px currentColor;
    animation: cp-pulse 2s infinite;
}
.cp-status-active {
    background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
    color: #047857;
    border-color: #A7F3D0;
}
.cp-status-inactive {
    background: linear-gradient(135deg, #F1F5F9, #E2E8F0);
    color: #475569;
    border-color: #CBD5E1;
}
.cp-status-blocked {
    background: linear-gradient(135deg, #FEF2F2, #FEE2E2);
    color: #B91C1C;
    border-color: #FECACA;
}
@keyframes cp-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .5; }
}

/* Joined date */
.cp-date {
    display: flex;
    flex-direction: column;
    gap: 2px;
    font-size: 12.5px;
}
.cp-date-main { font-weight: 600; color: var(--cp-ink); }
.cp-date-sub { font-size: 11px; color: var(--cp-muted); }

/* Actions */
.cp-acts { display: flex; gap: 6px; justify-content: flex-end; }
.cp-act {
    width: 34px;
    height: 34px;
    border: 1.5px solid var(--cp-line);
    border-radius: 9px;
    background: #fff;
    color: var(--cp-slate);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    cursor: pointer;
    transition: all .22s cubic-bezier(.4,0,.2,1);
    font-size: 14px;
    padding: 0;
}
.cp-act i { transition: transform .22s; }
.cp-act:hover i { transform: scale(1.15); }

.cp-act-view:hover {
    border-color: #2E6DB4;
    color: #fff;
    background: linear-gradient(135deg, #2E6DB4, #1E4C82);
    box-shadow: 0 4px 12px rgba(46,109,180,.35);
}
.cp-act-edit:hover {
    border-color: #26A69A;
    color: #fff;
    background: linear-gradient(135deg, #26A69A, #00796B);
    box-shadow: 0 4px 12px rgba(38,166,154,.35);
}
.cp-act-mail:hover {
    border-color: #8B5CF6;
    color: #fff;
    background: linear-gradient(135deg, #8B5CF6, #6D28D9);
    box-shadow: 0 4px 12px rgba(139,92,246,.35);
}

/* ============ EMPTY STATE ============ */
.cp-empty {
    text-align: center;
    padding: 60px 24px;
}
.cp-empty-icon {
    width: 72px; height: 72px;
    border-radius: 20px;
    margin: 0 auto 16px;
    background: linear-gradient(135deg, rgba(46,109,180,.08), rgba(38,166,154,.08));
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; color: var(--cp-primary);
}
.cp-empty h3 { font-size: 17px; font-weight: 700; margin: 0 0 6px; }
.cp-empty p { color: var(--cp-muted); margin: 0 0 20px; font-size: 13px; }

/* ============ PAGINATION ============ */
.cp-pager {
    padding: 16px;
    border-top: 1px solid var(--cp-line);
    display: flex;
    justify-content: center;
    background: #FAFCFD;
}
.cp-pager .pagination { margin: 0; gap: 3px; }
.cp-pager .page-link {
    border: none;
    color: var(--cp-slate);
    font-weight: 600;
    border-radius: 8px;
    padding: 7px 12px;
    font-size: 12.5px;
    transition: all .15s;
    background: #fff;
}
.cp-pager .page-link:hover { background: var(--cp-primary-light); color: var(--cp-primary); }
.cp-pager .page-item.active .page-link {
    background: var(--cp-gradient);
    color: #fff;
    box-shadow: 0 3px 10px rgba(46,109,180,.28);
}
.cp-pager .page-item.disabled .page-link { color: #CBD5E1; background: transparent; }

/* ============ RESPONSIVE ============ */
@media (max-width: 1199.98px) {
    .cp-stats { grid-template-columns: repeat(2, 1fr); }
    .cp-filters { grid-template-columns: 1fr 1fr; }
    .cp-fi-search { grid-column: 1 / -1; }
    .cp-fbtn { grid-column: 1 / -1; }
    .cp-fbtn .cp-btn { flex: 1; }
}
@media (max-width: 767.98px) {
    .cp-hero { padding: 16px 18px; min-height: 82px; }
    .cp-hero h1 { font-size: 17px; }
    .cp-hero p { font-size: 12px; }
    .cp-hero-badge { width: 40px; height: 40px; font-size: 18px; }
    .cp-stat { padding: 12px 14px; min-height: 70px; }
    .cp-stat-value { font-size: 19px; }
    .cp-stat-icon { width: 36px; height: 36px; font-size: 16px; }
    .cp-table thead th, .cp-table td { padding: 12px 14px; }
}
@media (max-width: 575.98px) {
    .cp-hero { padding: 14px 16px; min-height: 76px; }
    .cp-hero h1 { font-size: 16px; }
    .cp-stats { gap: 8px; }
    .cp-stat { padding: 10px 12px; min-height: 64px; gap: 8px; }
    .cp-stat-value { font-size: 17px; }
    .cp-stat-icon { width: 32px; height: 32px; font-size: 14px; }
    .cp-filters { grid-template-columns: 1fr; }
    .cp-table { min-width: auto; }
}
@media (prefers-reduced-motion: reduce) {
    .cp * { transition: none !important; animation: none !important; }
}
</style>

<div class="cp">

    {{-- HERO --}}
    <div class="cp-hero">
        <div class="cp-hero-left">
            <div class="cp-hero-badge"><i class="bi bi-people-fill"></i></div>
            <div>
                <h1>Customers</h1>
                <p>{{ number_format($customers->total()) }} registered customers</p>
            </div>
        </div>
        <div class="cp-hero-actions">
            <a href="{{ route('admin.customers') }}" class="cp-btn cp-btn-ghost">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </a>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="cp-stats">
        <a href="{{ $cardUrl(null) }}" class="cp-stat cp-stat-total {{ !$cur ? 'is-active' : '' }}">
            <div class="cp-stat-icon"><i class="bi bi-people"></i></div>
            <div class="cp-stat-body">
                <div class="cp-stat-value">{{ number_format($stats['total']) }}</div>
                <div class="cp-stat-label">All Customers</div>
            </div>
        </a>
        @foreach($statusCards as $sc)
            @php $n = (int)($stats[$sc['key']] ?? 0); @endphp
            <a href="{{ $cardUrl($sc['key']) }}"
               class="cp-stat cp-stat-{{ $sc['key'] }} {{ $cur === $sc['key'] ? 'is-active' : '' }}">
                <div class="cp-stat-icon"><i class="bi {{ $sc['icon'] }}"></i></div>
                <div class="cp-stat-body">
                    <div class="cp-stat-value">{{ number_format($n) }}</div>
                    <div class="cp-stat-label">{{ $sc['label'] }}</div>
                </div>
                <span class="cp-stat-pct">{{ $pct($n) }}%</span>
            </a>
        @endforeach
    </div>

    {{-- FILTERS --}}
    <form method="GET" class="cp-card cp-filters">
        <div class="cp-fi cp-fi-search">
            <label>Search</label>
            <input type="text" name="q" class="cp-input" value="{{ request('q') }}" placeholder="Search by name, email or phone...">
        </div>
        <div class="cp-fi">
            <label>Status</label>
            <select name="status" class="cp-input">
                <option value="">All Status</option>
                <option value="active" @selected($cur == 'active')>Active</option>
                <option value="inactive" @selected($cur == 'inactive')>Inactive</option>
                <option value="blocked" @selected($cur == 'blocked')>Blocked</option>
            </select>
        </div>
        <div class="cp-fi cp-fbtn">
            <button type="submit" class="cp-btn cp-btn-primary"><i class="bi bi-funnel"></i> Filter</button>
            @if($hasFilter)
                <a href="{{ route('admin.customers') }}" class="cp-btn cp-btn-ghost"><i class="bi bi-arrow-clockwise"></i> Reset</a>
            @endif
        </div>
    </form>

    {{-- TABLE --}}
    <div class="cp-card">
        <div class="cp-listhead">
            <div class="cp-listhead-count">
                Showing <strong>{{ $customers->firstItem() ?? 0 }}–{{ $customers->lastItem() ?? 0 }}</strong>
                of <strong>{{ $customers->total() }}</strong> customers
            </div>
        </div>

        @if($customers->count())
            <div class="cp-tablewrap">
                <table class="cp-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th style="width:130px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $c)
                        @php
                            $avatar = $avatarUrl($c);
                            $orders = (int) ($c->customerProfile->total_orders ?? 0);
                            $spent  = (float) ($c->customerProfile->total_spent ?? 0);
                        @endphp
                        <tr>
                            <td>
                                <div class="cp-user">
                                    <div class="cp-avatar">
                                        <span>{{ strtoupper(substr($c->name, 0, 1)) }}</span>
                                        <img src="{{ $avatar }}" alt="" onerror="this.remove()">
                                        <span class="cp-avatar-status {{ $c->status }}"></span>
                                    </div>
                                    <div class="cp-user-info">
                                        <a href="{{ route('admin.customers.show', $c) }}" class="cp-user-name">
                                            {{ $c->name }}
                                        </a>
                                        <span class="cp-user-id">
                                            <i class="bi bi-hash"></i>{{ str_pad($c->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="cp-contact">
                                    @if($c->email)
                                        <div class="cp-contact-item email">
                                            <i class="bi bi-envelope-fill"></i>
                                            <span>{{ $c->email }}</span>
                                        </div>
                                    @endif
                                    @if($c->phone)
                                        <div class="cp-contact-item phone">
                                            <i class="bi bi-telephone-fill"></i>
                                            <span>{{ $c->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="cp-orders {{ $orders === 0 ? 'empty' : '' }}">
                                    <i class="bi bi-bag-check-fill"></i>
                                    {{ $orders }}
                                </span>
                            </td>
                            <td>
                                <span class="cp-spent {{ $spent == 0 ? 'zero' : '' }}">
                                    <span class="currency">₹</span>{{ number_format($spent, 0) }}
                                </span>
                            </td>
                            <td>
                                <span class="cp-status cp-status-{{ $c->status }}">
                                    {{ ucfirst($c->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="cp-date">
                                    <span class="cp-date-main">{{ $c->created_at->format('d M Y') }}</span>
                                    <span class="cp-date-sub">{{ $c->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="cp-acts">
                                    <a href="{{ route('admin.customers.show', $c) }}" class="cp-act cp-act-view" title="View Profile">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if($c->email)
                                        <a href="mailto:{{ $c->email }}" class="cp-act cp-act-mail" title="Send Email">
                                            <i class="bi bi-envelope-fill"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="cp-empty">
                <div class="cp-empty-icon"><i class="bi bi-people"></i></div>
                <h3>{{ $hasFilter ? 'No customers found' : 'No customers yet' }}</h3>
                <p>{{ $hasFilter ? 'Try changing filters or search query.' : 'Customers will appear here once they register.' }}</p>
                @if($hasFilter)
                    <a href="{{ route('admin.customers') }}" class="cp-btn cp-btn-primary">
                        <i class="bi bi-arrow-clockwise"></i> Clear Filters
                    </a>
                @endif
            </div>
        @endif

        @if($customers->hasPages())
            <div class="cp-pager">{{ $customers->withQueryString()->links() }}</div>
        @endif
    </div>
</div>

@endsection
