@extends('layouts.admin')
@section('title', 'Edit Product')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('admin.products') }}">Products</a>
    <i class="bi bi-chevron-right"></i><span>Edit</span>
@endsection

@section('content')

@php
    $imgUrl = fn($path) => \App\Models\Product::resolveImageUrl($path);
    $thumbUrl = $imgUrl($product->thumbnail);
@endphp

<style>
/* ============================================================
   PREMIUM EDIT PRODUCT FORM
   ============================================================ */
.pf {
    --pf-primary: #2E6DB4;
    --pf-primary-dark: #1E4C82;
    --pf-primary-light: #E8F1FB;
    --pf-accent: #26A69A;
    --pf-accent-dark: #00796B;
    --pf-accent-light: #D1FAE5;
    --pf-ink: #0F1E33;
    --pf-slate: #5A6C8C;
    --pf-muted: #8B9BB4;
    --pf-line: #E3E9F2;
    --pf-bg: #F5F8FC;
    --pf-card: #FFFFFF;
    --pf-emerald: #10B981;
    --pf-emerald-bg: #ECFDF5;
    --pf-amber: #F59E0B;
    --pf-amber-bg: #FFFBEB;
    --pf-rose: #EF4444;
    --pf-rose-bg: #FEF2F2;
    --pf-violet: #8B5CF6;
    --pf-violet-bg: #F5F3FF;
    --pf-gradient: linear-gradient(135deg, #2E6DB4 0%, #26A69A 100%);
    --pf-gradient-soft: linear-gradient(135deg, rgba(46,109,180,.06) 0%, rgba(38,166,154,.06) 100%);

    color: var(--pf-ink);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    -webkit-font-smoothing: antialiased;
    padding-bottom: 24px;
}
.pf * { box-sizing: border-box; }

/* ============ HERO ============ */
.pf-head {
    position: relative;
    overflow: hidden;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    padding: 22px 26px;
    margin-bottom: 20px;
    border-radius: 18px;
    background: linear-gradient(135deg, #0F1E33 0%, #1E4C82 50%, #2E6DB4 100%);
    color: #fff;
    box-shadow: 0 10px 30px rgba(46,109,180,.22);
    min-height: 96px;
}
.pf-head::before {
    content: ""; position: absolute;
    right: -60px; top: -80px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(38,166,154,.42), transparent 65%);
    pointer-events: none;
}
.pf-head::after {
    content: ""; position: absolute;
    left: -60px; bottom: -80px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(46,109,180,.5), transparent 65%);
    pointer-events: none;
}
.pf-head > * { position: relative; z-index: 1; }
.pf-head-left { display: flex; align-items: center; gap: 16px; min-width: 0; }
.pf-head-badge {
    width: 46px; height: 46px; border-radius: 12px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #26A69A; flex-shrink: 0;
}
.pf-head h1 {
    font-size: 20px; font-weight: 800;
    margin: 0 0 2px; color: #fff;
    letter-spacing: -.02em; line-height: 1.15;
}
.pf-head p {
    margin: 0; color: rgba(255,255,255,.68);
    font-size: 13px; line-height: 1.3;
}
.pf-head p strong { color: #fff; font-weight: 700; }

.pf-head-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.pf-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border: none; border-radius: 99px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.24);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff; text-decoration: none;
    font-weight: 600; font-size: 13px;
    transition: all .22s;
}
.pf-back:hover {
    background: rgba(255,255,255,.24);
    color: #fff;
    transform: translateY(-2px);
}

/* ============ CARD ============ */
.pf-card {
    background: var(--pf-card);
    border: 1px solid var(--pf-line);
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 16px;
    box-shadow: 0 1px 3px rgba(15,30,51,.04);
    transition: all .25s;
    position: relative;
    overflow: hidden;
}
.pf-card:hover {
    box-shadow: 0 6px 20px rgba(15,30,51,.06);
    border-color: #D8E1EC;
}
.pf-card::before {
    content: ""; position: absolute;
    top: 0; left: 0; bottom: 0;
    width: 3px;
    background: var(--pf-accent-color, var(--pf-gradient));
    opacity: .85;
}
.pf-card[data-accent="blue"] { --pf-accent-color: linear-gradient(180deg, #2E6DB4, #1E4C82); }
.pf-card[data-accent="teal"] { --pf-accent-color: linear-gradient(180deg, #26A69A, #00796B); }
.pf-card[data-accent="violet"] { --pf-accent-color: linear-gradient(180deg, #8B5CF6, #6D28D9); }
.pf-card[data-accent="amber"] { --pf-accent-color: linear-gradient(180deg, #F59E0B, #D97706); }
.pf-card[data-accent="emerald"] { --pf-accent-color: linear-gradient(180deg, #10B981, #059669); }
.pf-card[data-accent="rose"] { --pf-accent-color: linear-gradient(180deg, #F87171, #DC2626); }

.pf-card-t {
    display: flex; align-items: center; gap: 10px;
    font-size: 15px; font-weight: 700; margin: 0 0 18px;
    color: var(--pf-ink);
}
.pf-card-t i {
    width: 34px; height: 34px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 10px;
    font-size: 15px;
    color: #fff;
    background: var(--pf-accent-color, var(--pf-gradient));
    box-shadow: 0 3px 10px rgba(46,109,180,.22);
}
.pf-card-t .pf-sp { margin-left: auto; }

/* ============ LABELS + INPUTS ============ */
.pf label.pf-l {
    display: block; font-size: 12px;
    font-weight: 700; color: var(--pf-slate);
    margin-bottom: 7px;
    letter-spacing: .02em;
    text-transform: uppercase;
}
.pf-input, .pf-select, .pf textarea.pf-input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--pf-line);
    border-radius: 10px;
    background: #FAFCFD;
    color: var(--pf-ink);
    font-size: 14px;
    font-family: inherit;
    transition: all .2s;
    appearance: none; -webkit-appearance: none;
}
.pf-input::placeholder { color: var(--pf-muted); }
.pf-input:hover, .pf-select:hover { border-color: #CBD5E1; }
.pf-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none' stroke='%235A6C8C' stroke-width='2'%3E%3Cpath d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 38px;
    cursor: pointer;
}
.pf-input:focus, .pf-select:focus, .pf textarea.pf-input:focus {
    outline: none;
    border-color: var(--pf-primary);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(46,109,180,.12);
}
.pf-addon { display: flex; }
.pf-addon span {
    display: flex; align-items: center;
    padding: 0 13px;
    border: 1.5px solid var(--pf-line);
    border-right: 0;
    border-radius: 10px 0 0 10px;
    background: linear-gradient(135deg, var(--pf-primary-light), #F5F8FC);
    color: var(--pf-primary);
    font-weight: 700;
    font-size: 14px;
}
.pf-addon .pf-input { border-radius: 0 10px 10px 0; }
.pf-addon:focus-within span { border-color: var(--pf-primary); }

.pf-final {
    display: flex; justify-content: space-between; align-items: center;
    gap: 10px; margin-top: 18px;
    padding: 14px 18px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
    border: 1px solid #A7F3D0;
    font-size: 13px; color: #047857;
    font-weight: 600;
}
.pf-final b {
    font-size: 20px;
    color: #047857;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    letter-spacing: -.02em;
}

/* ============ SWITCH ============ */
.pf-switch {
    display: flex; align-items: center; gap: 10px;
    min-height: 44px; cursor: pointer;
    font-size: 14px; margin: 0; font-weight: 500;
}
.pf-switch input { position: absolute; opacity: 0; pointer-events: none; }
.pf-switch .tr {
    width: 44px; height: 24px; border-radius: 99px;
    background: #CFD6E4;
    position: relative;
    transition: background .2s;
    flex: none;
}
.pf-switch .tr::after {
    content: ""; position: absolute;
    top: 3px; left: 3px;
    width: 18px; height: 18px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,.25);
    transition: transform .2s;
}
.pf-switch input:checked + .tr { background: linear-gradient(135deg, #26A69A, #00796B); }
.pf-switch input:checked + .tr::after { transform: translateX(20px); }
.pf-switch input:focus-visible + .tr { box-shadow: 0 0 0 4px rgba(38,166,154,.2); }

/* ============ CHIPS ============ */
.pf-chip { display: inline-flex; margin: 0; cursor: pointer; }
.pf-chip input { position: absolute; opacity: 0; pointer-events: none; }
.pf-chip span {
    padding: 8px 16px;
    border: 1.5px solid var(--pf-line);
    border-radius: 99px;
    font-size: 13px;
    background: #FAFCFD;
    color: var(--pf-slate);
    font-weight: 600;
    transition: all .18s;
    user-select: none;
}
.pf-chip input:checked + span {
    background: var(--pf-gradient);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 3px 10px rgba(46,109,180,.28);
}
.pf-chip input:focus-visible + span { box-shadow: 0 0 0 4px rgba(46,109,180,.2); }
.pf-chips { display: flex; flex-wrap: wrap; gap: 8px; }
.pf-attr-name {
    font-size: 13px; font-weight: 700;
    margin-bottom: 8px;
    color: var(--pf-ink);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.pf-attr-name::before {
    content: ""; width: 4px; height: 14px;
    border-radius: 2px;
    background: var(--pf-gradient);
}

/* ============ SPEC ROWS ============ */
.pf-spec {
    display: grid;
    grid-template-columns: 1fr 1.3fr 40px;
    gap: 10px;
    margin-bottom: 10px;
}
.pf-icon-btn {
    width: 40px; height: 44px;
    border: 1.5px solid var(--pf-line);
    border-radius: 10px;
    background: #fff;
    color: var(--pf-rose);
    cursor: pointer;
    transition: all .18s;
    display: flex; align-items: center; justify-content: center;
}
.pf-icon-btn:hover {
    background: var(--pf-rose-bg);
    border-color: #FCA5A5;
    transform: translateY(-1px);
}
.pf-add {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 15px;
    border: 1.5px dashed var(--pf-primary);
    border-radius: 99px;
    background: var(--pf-primary-light);
    color: var(--pf-primary);
    font-weight: 700; font-size: 12.5px;
    cursor: pointer;
    transition: all .2s;
}
.pf-add:hover {
    background: var(--pf-gradient);
    color: #fff;
    border-color: transparent;
    border-style: solid;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(46,109,180,.28);
}

/* ============ UPLOAD DROP ZONES ============ */
.pf-drop {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 6px; text-align: center;
    padding: 24px 16px;
    border: 2px dashed #C5CDE0;
    border-radius: 14px;
    background: linear-gradient(135deg, #FAFCFD, #F5F8FC);
    cursor: pointer;
    transition: all .22s;
    margin: 0;
}
.pf-drop:hover, .pf-drop.drag {
    border-color: var(--pf-primary);
    background: linear-gradient(135deg, #F0F7FF, #E8F1FB);
    transform: translateY(-1px);
}
.pf-drop i {
    font-size: 26px;
    color: #fff;
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    background: var(--pf-gradient);
    box-shadow: 0 6px 18px rgba(46,109,180,.28);
    margin-bottom: 4px;
}
.pf-drop strong { font-size: 13.5px; font-weight: 700; color: var(--pf-ink); }
.pf-drop small { color: var(--pf-muted); font-size: 11.5px; }
.pf-err {
    color: var(--pf-rose);
    font-size: 12.5px;
    font-weight: 600;
    margin-top: 8px;
    display: none;
}

/* ============ THUMB PREVIEW ============ */
.pf-thumb {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid var(--pf-line);
    box-shadow: 0 4px 12px rgba(15,30,51,.06);
    margin-bottom: 10px;
}
.pf-thumb img { display: block; width: 100%; aspect-ratio: 1/1; object-fit: cover; }
.pf-thumb .badge {
    position: absolute; top: 8px; left: 8px;
    padding: 3px 10px; border-radius: 99px;
    background: rgba(16,185,129,.95);
    color: #fff;
    font-size: 10px; font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    box-shadow: 0 2px 6px rgba(0,0,0,.15);
    backdrop-filter: blur(6px);
}
.pf-thumb .badge.pending { background: rgba(46,109,180,.95); }

/* ============ GALLERY GRID ============ */
.pf-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(84px, 1fr));
    gap: 10px;
    margin-top: 12px;
}
.pf-grid .it {
    position: relative;
    aspect-ratio: 1/1;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--pf-line);
    transition: all .22s;
    box-shadow: 0 2px 6px rgba(15,30,51,.04);
    background: linear-gradient(135deg, #F5F8FC, #E8F1FB);
}
.pf-grid .it:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15,30,51,.1);
    border-color: var(--pf-primary);
}
.pf-grid img { width: 100%; height: 100%; object-fit: cover; display: block; }
.pf-grid .it .pf-x {
    position: absolute;
    top: 5px; right: 5px;
    width: 24px; height: 24px;
    border: 0; border-radius: 50%;
    background: rgba(15,20,40,.78);
    color: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px;
    transition: all .18s;
    backdrop-filter: blur(6px);
}
.pf-grid .it .pf-x:hover {
    background: var(--pf-rose);
    transform: scale(1.12);
}
/* Saved image variant */
.pf-grid .it.saved {
    border-color: #A7F3D0;
    border-width: 2px;
}
.pf-grid .it.saved::before {
    content: "✓ Saved";
    position: absolute;
    top: 4px; left: 4px;
    padding: 2px 7px;
    border-radius: 99px;
    background: rgba(16,185,129,.95);
    color: #fff;
    font-size: 8px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .3px;
    z-index: 2;
    box-shadow: 0 1px 4px rgba(0,0,0,.15);
    backdrop-filter: blur(4px);
}
.pf-grid .it.removed {
    opacity: .35;
    filter: grayscale(1);
}
.pf-grid .it.removed::before {
    content: "Removed";
    background: rgba(239,68,68,.95);
}
.pf-count {
    font-size: 11.5px;
    color: var(--pf-primary);
    font-weight: 700;
    background: var(--pf-primary-light);
    padding: 3px 10px;
    border-radius: 99px;
}

/* ============ LIVE PREVIEW ============ */
.pf-live {
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--pf-line);
    background: #fff;
    box-shadow: 0 4px 16px rgba(15,30,51,.06);
    transition: all .25s;
}
.pf-live:hover { box-shadow: 0 8px 24px rgba(15,30,51,.1); }
.pf-live .im {
    aspect-ratio: 4/3;
    background: linear-gradient(135deg, #F5F8FC, #E8F1FB);
    display: flex; align-items: center; justify-content: center;
    color: #B3BCCF;
    font-size: 40px;
    position: relative;
    overflow: hidden;
}
.pf-live .im img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .35s;
}
.pf-live:hover .im img { transform: scale(1.04); }
.pf-live .bd { padding: 16px; }
.pf-live .nm {
    font-weight: 700;
    margin-bottom: 6px;
    font-size: 14px;
    color: var(--pf-ink);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.pf-live .pr {
    display: flex;
    align-items: baseline;
    gap: 8px;
    flex-wrap: wrap;
}
.pf-live .pr b {
    font-size: 18px;
    color: #047857;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    letter-spacing: -.02em;
    font-weight: 800;
}
.pf-live .pr s { color: var(--pf-muted); font-size: 13px; font-weight: 500; }
.pf-live .pr em {
    font-style: normal;
    font-size: 11px;
    font-weight: 700;
    color: #047857;
    background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
    padding: 3px 9px;
    border-radius: 99px;
    border: 1px solid #A7F3D0;
}

/* ============ BUTTONS ============ */
.pf-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 14px 18px;
    border: 0;
    border-radius: 12px;
    background: var(--pf-gradient);
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all .25s;
    box-shadow: 0 6px 20px rgba(46,109,180,.32);
    position: relative;
    overflow: hidden;
}
.pf-btn::before {
    content: "";
    position: absolute; inset: 0;
    background: linear-gradient(135deg, #1E4C82, #00796B);
    opacity: 0;
    transition: opacity .25s;
}
.pf-btn span, .pf-btn i { position: relative; z-index: 1; }
.pf-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(46,109,180,.42);
}
.pf-btn:hover::before { opacity: 1; }
.pf-btn[disabled] { opacity: .7; cursor: wait; transform: none; }

.pf-btn-danger {
    background: linear-gradient(135deg, #F87171, #DC2626);
    box-shadow: 0 6px 20px rgba(239,68,68,.28);
}
.pf-btn-danger::before {
    background: linear-gradient(135deg, #DC2626, #B91C1C);
}
.pf-btn-danger:hover { box-shadow: 0 10px 28px rgba(239,68,68,.4); }

.pf-cancel {
    display: flex;
    justify-content: center;
    margin-top: 10px;
    padding: 12px;
    border-radius: 12px;
    border: 1.5px solid var(--pf-line);
    color: var(--pf-slate);
    text-decoration: none;
    font-weight: 600;
    font-size: 13.5px;
    transition: all .2s;
    background: #fff;
}
.pf-cancel:hover {
    border-color: var(--pf-primary);
    color: var(--pf-primary);
    background: var(--pf-primary-light);
}

.pf-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--pf-line), transparent);
    margin: 14px 0;
}

/* ============ FLAGS ============ */
.pf-flags {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.pf-flags .pf-chip { display: flex; }
.pf-flags .pf-chip span {
    width: 100%;
    text-align: center;
    border-radius: 10px;
    padding: 9px 12px;
    font-size: 12.5px;
}

/* ============ INFO STRIP ============ */
.pf-info-strip {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 12px;
    background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
    border: 1px solid #BFDBFE;
    color: #1E40AF;
    font-size: 12.5px;
    font-weight: 600;
    margin-bottom: 16px;
}
.pf-info-strip i { font-size: 16px; }

/* ============ RESPONSIVE ============ */
@media (min-width: 992px) {
    .pf-side { position: sticky; top: 76px; }
    .pf-mbar { display: none; }
}
@media (max-width: 991.98px) {
    .pf-desk-actions { display: none; }
    .pf { padding-bottom: 84px; }
    .pf-mbar {
        position: fixed; left: 0; right: 0; bottom: 0;
        z-index: 1030;
        display: flex; gap: 10px;
        padding: 10px 14px calc(10px + env(safe-area-inset-bottom));
        background: #fff;
        border-top: 1px solid var(--pf-line);
        box-shadow: 0 -6px 20px rgba(20,30,60,.08);
    }
    .pf-mbar .pf-btn { flex: 1; }
    .pf-mbar .pf-cancel { margin: 0; width: auto; padding: 0 18px; }
}
@media (max-width: 575.98px) {
    .pf-head { padding: 16px 18px; min-height: 82px; }
    .pf-head h1 { font-size: 17px; }
    .pf-head p { font-size: 12px; }
    .pf-head-badge { width: 40px; height: 40px; font-size: 18px; }
    .pf-card { padding: 16px; border-radius: 14px; }
    .pf-head-actions .pf-back { padding: 8px 14px; font-size: 12px; }
    .pf-spec { grid-template-columns: 1fr 40px; }
    .pf-spec .v { grid-column: 1/2; grid-row: 2; }
    .pf-spec .pf-icon-btn { grid-column: 2; grid-row: 1/3; height: 100%; }
    .pf-final b { font-size: 17px; }
    .pf-drop i { width: 44px; height: 44px; font-size: 22px; }
}
@media (prefers-reduced-motion: reduce) {
    .pf * { transition: none !important; }
}
</style>

<div class="pf">

    {{-- HERO --}}
    <div class="pf-head">
        <div class="pf-head-left">
            <div class="pf-head-badge"><i class="bi bi-pencil-square"></i></div>
            <div>
                <h1>Edit Product</h1>
                <p><strong>{{ \Illuminate\Support\Str::limit($product->name, 50) }}</strong> · SKU: {{ $product->sku }}</p>
            </div>
        </div>
        <div class="pf-head-actions">
            <a href="{{ route('admin.products.show', $product) }}" class="pf-back">
                <i class="bi bi-eye"></i> Preview
            </a>
            <a href="{{ route('admin.products') }}" class="pf-back">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="border-radius:12px">
            <strong>Fix these errors:</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        @method('PUT')

        <div class="row g-3">
            {{-- ================= LEFT ================= --}}
            <div class="col-lg-8">

                {{-- Info strip --}}
                <div class="pf-info-strip">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Editing product #{{ $product->id }} · Created {{ $product->created_at->format('d M Y') }}</span>
                </div>

                {{-- Basic Info --}}
                <div class="pf-card" data-accent="blue">
                    <h5 class="pf-card-t"><i class="bi bi-info-circle-fill"></i>Basic Information</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="pf-l">Product Name *</label>
                            <input type="text" name="name" id="fName" class="pf-input" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="pf-l">SKU</label>
                            <input type="text" name="sku" class="pf-input" value="{{ old('sku', $product->sku) }}">
                        </div>
                        <div class="col-md-4 col-6">
                            <label class="pf-l">Product Type *</label>
                            <select name="type" id="productType" class="pf-select" required>
                                <option value="simple" @selected(old('type', $product->type) == 'simple')>Simple</option>
                                <option value="variable" @selected(old('type', $product->type) == 'variable')>Variable</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-6">
                            <label class="pf-l">Status *</label>
                            <select name="status" class="pf-select" required>
                                <option value="active" @selected(old('status', $product->status) == 'active')>Active</option>
                                <option value="inactive" @selected(old('status', $product->status) == 'inactive')>Inactive</option>
                                <option value="draft" @selected(old('status', $product->status) == 'draft')>Draft</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="pf-l">Short Description</label>
                            <textarea name="short_description" class="pf-input" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="pf-l">Full Description</label>
                            <textarea name="description" class="pf-input" rows="5">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="pf-card" data-accent="emerald">
                    <h5 class="pf-card-t"><i class="bi bi-currency-rupee"></i>Pricing</h5>
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            <label class="pf-l">Regular Price *</label>
                            <div class="pf-addon"><span>₹</span><input type="number" step="0.01" min="0" name="price" id="fPrice" class="pf-input" value="{{ old('price', $product->price) }}" required></div>
                        </div>
                        <div class="col-md-4 col-6">
                            <label class="pf-l">Compare Price</label>
                            <div class="pf-addon"><span>₹</span><input type="number" step="0.01" min="0" name="compare_price" id="fCompare" class="pf-input" value="{{ old('compare_price', $product->compare_price) }}"></div>
                        </div>
                        <div class="col-md-4 col-6">
                            <label class="pf-l">Cost Price</label>
                            <div class="pf-addon"><span>₹</span><input type="number" step="0.01" min="0" name="cost_price" class="pf-input" value="{{ old('cost_price', $product->cost_price) }}"></div>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="pf-l">Discount Type</label>
                            <select name="discount_type" id="fDType" class="pf-select">
                                @foreach(['none','fixed','percent'] as $t)
                                    <option value="{{ $t }}" @selected(old('discount_type', $product->discount_type) == $t)>{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-6">
                            <label class="pf-l">Discount Value</label>
                            <input type="number" step="0.01" min="0" name="discount_value" id="fDVal" class="pf-input" value="{{ old('discount_value', $product->discount_value) }}">
                        </div>
                        <div class="col-md-4 col-6">
                            <label class="pf-l">Tax Rate (%)</label>
                            <input type="number" step="0.01" min="0" name="tax_rate" class="pf-input" value="{{ old('tax_rate', $product->tax_rate) }}">
                        </div>
                    </div>
                    <div class="pf-final">
                        <span>Selling price after discount</span>
                        <b id="finalPrice">₹0.00</b>
                    </div>
                </div>

                {{-- Inventory --}}
                <div class="pf-card" data-accent="violet">
                    <h5 class="pf-card-t"><i class="bi bi-boxes"></i>Inventory</h5>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4 col-6">
                            <label class="pf-l">Stock *</label>
                            <input type="number" min="0" name="stock" class="pf-input" value="{{ old('stock', $product->stock) }}" required>
                        </div>
                        <div class="col-md-4 col-6">
                            <label class="pf-l">Low Stock Alert</label>
                            <input type="number" min="0" name="low_stock_alert" class="pf-input" value="{{ old('low_stock_alert', $product->low_stock_alert) }}">
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="pf-switch">
                                <input type="checkbox" name="manage_stock" value="1" @checked(old('manage_stock', $product->manage_stock))>
                                <span class="tr"></span> Manage Stock
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Shipping --}}
                <div class="pf-card" data-accent="amber">
                    <h5 class="pf-card-t"><i class="bi bi-truck"></i>Shipping</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-6"><label class="pf-l">Weight (kg)</label><input type="number" step="0.01" min="0" name="weight" class="pf-input" value="{{ old('weight', $product->weight) }}"></div>
                        <div class="col-md-3 col-6"><label class="pf-l">Length (cm)</label><input type="number" step="0.01" min="0" name="length" class="pf-input" value="{{ old('length', $product->length) }}"></div>
                        <div class="col-md-3 col-6"><label class="pf-l">Width (cm)</label><input type="number" step="0.01" min="0" name="width" class="pf-input" value="{{ old('width', $product->width) }}"></div>
                        <div class="col-md-3 col-6"><label class="pf-l">Height (cm)</label><input type="number" step="0.01" min="0" name="height" class="pf-input" value="{{ old('height', $product->height) }}"></div>
                    </div>
                </div>

                {{-- Specifications --}}
                <div class="pf-card" data-accent="teal">
                    <h5 class="pf-card-t">
                        <i class="bi bi-list-check"></i>Specifications
                        <button type="button" class="pf-add pf-sp" id="addSpec"><i class="bi bi-plus-lg"></i> Add</button>
                    </h5>
                    <div id="specsWrap">
                        @php
                            $specs = old('specifications', $product->specifications ?? []);
                            $sIdx = 0;
                        @endphp
                        @forelse($specs as $key => $value)
                            @php
                                $k = is_array($value) ? ($value['key'] ?? '') : $key;
                                $v = is_array($value) ? ($value['value'] ?? '') : $value;
                            @endphp
                            <div class="pf-spec">
                                <input type="text" name="specifications[{{ $sIdx }}][key]" class="pf-input" placeholder="Key" value="{{ $k }}">
                                <input type="text" name="specifications[{{ $sIdx }}][value]" class="pf-input v" placeholder="Value" value="{{ $v }}">
                                <button type="button" class="pf-icon-btn remove-spec" aria-label="Remove"><i class="bi bi-trash"></i></button>
                            </div>
                            @php $sIdx++; @endphp
                        @empty
                            <div class="pf-spec">
                                <input type="text" name="specifications[0][key]" class="pf-input" placeholder="Key">
                                <input type="text" name="specifications[0][value]" class="pf-input v" placeholder="Value">
                                <button type="button" class="pf-icon-btn remove-spec" aria-label="Remove"><i class="bi bi-trash"></i></button>
                            </div>
                            @php $sIdx = 1; @endphp
                        @endforelse
                    </div>
                </div>

                {{-- Attributes --}}
                <div class="pf-card" data-accent="blue">
                    <h5 class="pf-card-t"><i class="bi bi-sliders"></i>Attributes</h5>
                    @php $selectedAttrValues = $product->attributeValues->pluck('id')->toArray(); @endphp
                    @forelse($attributes as $attr)
                        <div class="mb-3">
                            <div class="pf-attr-name">{{ $attr->name }}</div>
                            <div class="pf-chips">
                                @foreach($attr->values as $val)
                                    <label class="pf-chip">
                                        <input type="checkbox" name="attributes[{{ $attr->id }}][]" value="{{ $val->id }}"
                                            @checked(in_array($val->id, old('attributes.' . $attr->id, $selectedAttrValues)))>
                                        <span>{{ $val->value }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No attributes yet.</p>
                    @endforelse
                </div>

                {{-- SEO --}}
                <div class="pf-card" data-accent="teal">
                    <h5 class="pf-card-t"><i class="bi bi-search"></i>SEO</h5>
                    <div class="row g-3">
                        <div class="col-12"><label class="pf-l">Meta Title</label><input type="text" name="meta_title" class="pf-input" value="{{ old('meta_title', $product->meta_title) }}"></div>
                        <div class="col-12"><label class="pf-l">Meta Description</label><textarea name="meta_description" class="pf-input" rows="2">{{ old('meta_description', $product->meta_description) }}</textarea></div>
                    </div>
                </div>
            </div>

            {{-- ================= RIGHT ================= --}}
            <div class="col-lg-4">
                <div class="pf-side">

                    {{-- Live Preview --}}
                    <div class="pf-card" data-accent="blue">
                        <h5 class="pf-card-t"><i class="bi bi-eye-fill"></i>Live Preview</h5>
                        <div class="pf-live">
                            <div class="im">
                                @if($thumbUrl)
                                    <img id="lpImg" src="{{ $thumbUrl }}" alt="">
                                    <i class="bi bi-image" id="lpIcon" style="display:none"></i>
                                @else
                                    <i class="bi bi-image" id="lpIcon"></i>
                                    <img id="lpImg" alt="" style="display:none">
                                @endif
                            </div>
                            <div class="bd">
                                <div class="nm" id="lpName">{{ $product->name }}</div>
                                <div class="pr">
                                    <b id="lpPrice">₹{{ number_format($product->price, 2) }}</b>
                                    <s id="lpCompare">{{ $product->compare_price ? '₹'.number_format($product->compare_price, 2) : '' }}</s>
                                    <em id="lpOff" style="display:none"></em>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="pf-card" data-accent="rose">
                        <h5 class="pf-card-t"><i class="bi bi-image-fill"></i>Thumbnail</h5>

                        @if($thumbUrl)
                            <div class="pf-thumb">
                                <span class="badge">Current</span>
                                <img id="thumbImg" src="{{ $thumbUrl }}" alt="">
                            </div>
                        @endif

                        <label class="pf-drop" id="thumbDrop" for="thumbInput">
                            <i class="bi bi-cloud-upload-fill"></i>
                            <strong>{{ $thumbUrl ? 'Replace image' : 'Click or drop image' }}</strong>
                            <small>PNG, JPG, WEBP · max 2MB</small>
                        </label>
                        <input type="file" name="thumbnail" id="thumbInput" accept="image/*" hidden>
                        <div class="pf-err" id="thumbErr"></div>
                        <div id="thumbNewPreview"></div>
                    </div>

                    {{-- Gallery --}}
                    <div class="pf-card" data-accent="violet">
                        <h5 class="pf-card-t">
                            <i class="bi bi-images"></i>Gallery
                            <span class="pf-count pf-sp" id="galleryCount">{{ $product->images->count() }} / 8</span>
                        </h5>

                        @if($product->images->count())
                            <div class="pf-grid" id="existingGallery">
                                @foreach($product->images as $img)
                                    @php $imgPath = $imgUrl($img->path); @endphp
                                    <div class="it saved" data-img-id="{{ $img->id }}">
                                        <img src="{{ $imgPath }}" alt="">
                                        <button type="button" class="pf-x toggle-remove-img" data-id="{{ $img->id }}" title="Remove image">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                        <input type="checkbox" name="remove_images[]" value="{{ $img->id }}" style="display:none" class="remove-img-checkbox">
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted" style="display:block;margin-top:8px;font-size:11.5px">
                                <i class="bi bi-info-circle"></i> Click ✕ to mark for removal
                            </small>
                        @endif

                        <label class="pf-drop" id="galleryDrop" for="galleryInput" style="margin-top:12px">
                            <i class="bi bi-images"></i>
                            <strong>Add up to {{ 8 - $product->images->count() }} more</strong>
                            <small>Select multiple · max 2MB each</small>
                        </label>
                        <input type="file" name="gallery[]" id="galleryInput" accept="image/*" multiple hidden>
                        <div class="pf-err" id="galleryErr"></div>
                        <div class="pf-grid" id="galleryPreview"></div>
                    </div>

                    {{-- Organization --}}
                    <div class="pf-card" data-accent="amber">
                        <h5 class="pf-card-t"><i class="bi bi-tags-fill"></i>Organization</h5>
                        <div class="mb-3">
                            <label class="pf-l">Category *</label>
                            <select name="category_id" class="pf-select" required>
                                @foreach($categories as $cat)
                                    <optgroup label="{{ $cat->name }}">
                                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                                        @foreach($cat->children as $child)
                                            <option value="{{ $child->id }}" @selected(old('category_id', $product->category_id) == $child->id)>— {{ $child->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="pf-l">Brand</label>
                            <select name="brand_id" class="pf-select">
                                <option value="">-- Select --</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" @selected(old('brand_id', $product->brand_id) == $b->id)>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Flags --}}
                    <div class="pf-card" data-accent="rose">
                        <h5 class="pf-card-t"><i class="bi bi-star-fill"></i>Product Flags</h5>
                        <div class="pf-flags">
                            <label class="pf-chip"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))><span>★ Featured</span></label>
                            <label class="pf-chip"><input type="checkbox" name="is_trending" value="1" @checked(old('is_trending', $product->is_trending))><span>🔥 Trending</span></label>
                            <label class="pf-chip"><input type="checkbox" name="is_new_arrival" value="1" @checked(old('is_new_arrival', $product->is_new_arrival))><span>✦ New</span></label>
                            <label class="pf-chip"><input type="checkbox" name="is_best_seller" value="1" @checked(old('is_best_seller', $product->is_best_seller))><span>◆ Best Seller</span></label>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pf-card pf-desk-actions" data-accent="blue">
                        <button type="submit" class="pf-btn js-save">
                            <i class="bi bi-check2-circle"></i> <span>Update Product</span>
                        </button>
                        <a href="{{ route('admin.products') }}" class="pf-cancel">Cancel</a>

                        <div class="pf-divider"></div>

                        <button type="button" class="pf-btn pf-btn-danger" id="deleteProduct">
                            <i class="bi bi-trash-fill"></i> <span>Delete Product</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile sticky bar --}}
        <div class="pf-mbar">
            <a href="{{ route('admin.products') }}" class="pf-cancel">Cancel</a>
            <button type="submit" class="pf-btn js-save">
                <i class="bi bi-check2-circle"></i> <span>Update</span>
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const $ = s => document.querySelector(s);
    const MAX_SIZE = 2 * 1024 * 1024, MAX_GALLERY = 8;
    const inr = n => '₹' + (Number(n) || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const kb = b => b > 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.round(b / 1024) + ' KB';
    const showErr = (el, msg) => { el.textContent = msg; el.style.display = msg ? 'block' : 'none'; };

    /* ---------- Live preview ---------- */
    function refreshLive() {
        const price = parseFloat($('#fPrice').value) || 0;
        const compare = parseFloat($('#fCompare').value) || 0;
        const type = $('#fDType').value;
        const dv = parseFloat($('#fDVal').value) || 0;
        let final = price;
        if (type === 'fixed') final = price - dv;
        if (type === 'percent') final = price - (price * dv / 100);
        final = Math.max(final, 0);

        $('#finalPrice').textContent = inr(final);
        $('#lpPrice').textContent = inr(final);
        $('#lpName').textContent = $('#fName').value.trim() || 'Product name';

        const base = compare > final ? compare : (final < price ? price : 0);
        $('#lpCompare').textContent = base ? inr(base) : '';
        const off = $('#lpOff');
        if (base > 0) { off.textContent = Math.round((1 - final / base) * 100) + '% off'; off.style.display = ''; }
        else off.style.display = 'none';
    }
    ['#fName', '#fPrice', '#fCompare', '#fDType', '#fDVal'].forEach(s => {
        $(s).addEventListener('input', refreshLive);
        $(s).addEventListener('change', refreshLive);
    });
    refreshLive();

    /* ---------- Drag helpers ---------- */
    function dragStyle(zone, onDrop) {
        ['dragenter', 'dragover'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.add('drag'); }));
        ['dragleave', 'drop'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.remove('drag'); }));
        zone.addEventListener('drop', e => onDrop([...e.dataTransfer.files]));
    }

    /* ---------- Thumbnail replacement ---------- */
    const tIn = $('#thumbInput');
    let tURL = null;

    function setThumb(file) {
        showErr($('#thumbErr'), '');
        if (!file) return;
        if (!file.type.startsWith('image/')) { showErr($('#thumbErr'), 'Only image files are allowed.'); return; }
        if (file.size > MAX_SIZE) { showErr($('#thumbErr'), 'Image is ' + kb(file.size) + '. Max 2 MB allowed.'); return; }

        const dt = new DataTransfer(); dt.items.add(file); tIn.files = dt.files;
        if (tURL) URL.revokeObjectURL(tURL);
        tURL = URL.createObjectURL(file);

        $('#thumbNewPreview').innerHTML =
            '<div class="pf-thumb" style="margin-top:10px">' +
            '<span class="badge pending">New</span>' +
            '<img src="' + tURL + '" alt="New thumbnail">' +
            '</div>';

        // Update live preview too
        const lpImg = $('#lpImg');
        if (lpImg) { lpImg.src = tURL; lpImg.style.display = 'block'; $('#lpIcon').style.display = 'none'; }
    }
    tIn.addEventListener('change', () => setThumb(tIn.files[0]));
    dragStyle($('#thumbDrop'), files => setThumb(files[0]));

    /* ---------- Existing images removal ---------- */
    document.querySelectorAll('.toggle-remove-img').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const wrap = this.closest('.it');
            const cb = wrap.querySelector('.remove-img-checkbox');
            cb.checked = !cb.checked;
            wrap.classList.toggle('removed', cb.checked);
            this.innerHTML = cb.checked ? '<i class="bi bi-arrow-counterclockwise"></i>' : '<i class="bi bi-x-lg"></i>';
            this.title = cb.checked ? 'Undo remove' : 'Remove image';
        });
    });

    /* ---------- New gallery ---------- */
    const gIn = $('#galleryInput'), gGrid = $('#galleryPreview');
    let gFiles = [], gURLs = [];
    const existingCount = {{ $product->images->count() }};

    function addGallery(files) {
        let msg = '';
        files.forEach(f => {
            if (!f.type.startsWith('image/')) { msg = 'Only image files are allowed.'; return; }
            if (f.size > MAX_SIZE) { msg = f.name + ' is over 2 MB and was skipped.'; return; }
            if (gFiles.some(x => x.name === f.name && x.size === f.size && x.lastModified === f.lastModified)) return;
            if (existingCount + gFiles.length >= MAX_GALLERY) { msg = 'Maximum ' + MAX_GALLERY + ' images allowed total.'; return; }
            gFiles.push(f);
        });
        showErr($('#galleryErr'), msg);
        syncGallery();
    }
    function syncGallery() {
        const dt = new DataTransfer();
        gFiles.forEach(f => dt.items.add(f));
        gIn.files = dt.files;

        gURLs.forEach(u => URL.revokeObjectURL(u)); gURLs = [];
        gGrid.innerHTML = '';
        gFiles.forEach((f, i) => {
            const url = URL.createObjectURL(f); gURLs.push(url);
            const d = document.createElement('div');
            d.className = 'it';
            d.style.borderColor = '#A7F3D0';
            d.style.borderWidth = '2px';
            d.innerHTML = '<span style="position:absolute;top:4px;left:4px;padding:2px 7px;border-radius:99px;background:rgba(46,109,180,.95);color:#fff;font-size:8px;font-weight:800;text-transform:uppercase;letter-spacing:.3px;z-index:2">New</span>' +
                '<img alt="" src="' + url + '">' +
                '<button type="button" class="pf-x" aria-label="Remove" data-i="' + i + '"><i class="bi bi-x-lg"></i></button>';
            gGrid.appendChild(d);
        });
        $('#galleryCount').textContent = (existingCount + gFiles.length) + ' / ' + MAX_GALLERY;
    }
    gIn.addEventListener('change', () => addGallery([...gIn.files]));
    gGrid.addEventListener('click', e => {
        const b = e.target.closest('.pf-x'); if (!b) return;
        gFiles.splice(+b.dataset.i, 1);
        showErr($('#galleryErr'), '');
        syncGallery();
    });
    dragStyle($('#galleryDrop'), addGallery);

    /* ---------- Specifications ---------- */
    let specIdx = document.querySelectorAll('#specsWrap .pf-spec').length;
    $('#addSpec').addEventListener('click', () => {
        const row = document.createElement('div'); row.className = 'pf-spec';
        row.innerHTML =
            '<input type="text" name="specifications[' + specIdx + '][key]" class="pf-input" placeholder="Key">' +
            '<input type="text" name="specifications[' + specIdx + '][value]" class="pf-input v" placeholder="Value">' +
            '<button type="button" class="pf-icon-btn remove-spec" aria-label="Remove"><i class="bi bi-trash"></i></button>';
        $('#specsWrap').appendChild(row);
        specIdx++;
    });
    $('#specsWrap').addEventListener('click', e => {
        const b = e.target.closest('.remove-spec'); if (b) b.closest('.pf-spec').remove();
    });

    /* ---------- Delete product ---------- */
    $('#deleteProduct').addEventListener('click', function () {
        if (!window.zConfirm) {
            if (!confirm('Delete this product? This cannot be undone.')) return;
            doDelete();
            return;
        }
        window.zConfirm('Are You Sure Delete product?', '.', doDelete);
    });
    function doDelete() {
        const csrf = document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}';
        fetch('{{ route("admin.products.destroy", $product) }}', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        }).then(r => {
            if (r.ok) {
                if (window.zToast) window.zToast('Product deleted', 'success');
                setTimeout(() => window.location.href = '{{ route("admin.products") }}', 600);
            } else {
                if (window.zToast) window.zToast('Delete failed', 'error');
            }
        }).catch(() => {
            if (window.zToast) window.zToast('Delete failed', 'error');
        });
    }

    /* ---------- Submit loading state ---------- */
    $('#productForm').addEventListener('submit', function () {
        if (!this.checkValidity()) return;
        document.querySelectorAll('.js-save').forEach(b => {
            b.disabled = true;
            b.innerHTML = '<i class="bi bi-hourglass-split"></i> <span>Updating...</span>';
        });
    });
})();
</script>
@endpush
