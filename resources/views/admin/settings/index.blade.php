@extends('layouts.admin')
@section('title', 'Settings')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i><span>Settings</span>
@endsection

@section('content')

<div class="z-page-head">
    <h1>Settings</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-lg-3">
            <div class="z-card" style="padding:10px;position:sticky;top:90px">
                <button type="button" class="z-sn-item active" data-section="general" style="display:flex;align-items:center;gap:10px;width:100%;padding:12px 14px;border-radius:10px;background:var(--z-gradient);color:#fff;border:none;font-weight:600;font-size:14px;cursor:pointer;margin-bottom:4px">
                    <i class="bi bi-gear"></i> General
                </button>
                <button type="button" class="z-sn-item" data-section="ecommerce" style="display:flex;align-items:center;gap:10px;width:100%;padding:12px 14px;border-radius:10px;background:transparent;color:var(--z-navy);border:none;font-weight:500;font-size:14px;cursor:pointer;text-align:left">
                    <i class="bi bi-cart"></i> E-commerce
                </button>
                <button type="button" class="z-sn-item" data-section="seo" style="display:flex;align-items:center;gap:10px;width:100%;padding:12px 14px;border-radius:10px;background:transparent;color:var(--z-navy);border:none;font-weight:500;font-size:14px;cursor:pointer;text-align:left">
                    <i class="bi bi-search"></i> SEO
                </button>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="z-settings-panel active" data-panel="general">
                <div class="z-card mb-3">
                    <h5>General Settings</h5>
                    <div class="row g-3">
                        <div class="col-md-6"><label>Site Name</label><input type="text" name="site_name" class="z-input" value="{{ $settings['site_name'] ?? 'store Store' }}"></div>
                        <div class="col-md-6"><label>Tagline</label><input type="text" name="site_tagline" class="z-input" value="{{ $settings['site_tagline'] ?? '' }}"></div>
                        <div class="col-md-6"><label>Email</label><input type="email" name="site_email" class="z-input" value="{{ $settings['site_email'] ?? '' }}"></div>
                        <div class="col-md-6"><label>Phone</label><input type="text" name="site_phone" class="z-input" value="{{ $settings['site_phone'] ?? '' }}"></div>
                        <div class="col-12"><label>Address</label><textarea name="site_address" class="z-input" rows="2">{{ $settings['site_address'] ?? '' }}</textarea></div>
                    </div>
                </div>
            </div>

            <div class="z-settings-panel" data-panel="ecommerce" style="display:none">
                <div class="z-card mb-3">
                    <h5>E-commerce Settings</h5>
                    <div class="row g-3">
                        <div class="col-md-4"><label>Currency</label><input type="text" name="currency" class="z-input" value="{{ $settings['currency'] ?? 'INR' }}"></div>
                        <div class="col-md-4"><label>Tax Rate (%)</label><input type="number" step="0.01" name="tax_rate" class="z-input" value="{{ $settings['tax_rate'] ?? 18 }}"></div>
                        <div class="col-md-4"><label>Shipping Flat Rate</label><input type="number" step="0.01" name="shipping_flat_rate" class="z-input" value="{{ $settings['shipping_flat_rate'] ?? 49 }}"></div>
                        <div class="col-md-6"><label>Free Shipping Above</label><input type="number" step="0.01" name="free_shipping_min" class="z-input" value="{{ $settings['free_shipping_min'] ?? 999 }}"></div>
                        <div class="col-md-6"><label>Low Stock Threshold</label><input type="number" name="low_stock_threshold" class="z-input" value="{{ $settings['low_stock_threshold'] ?? 5 }}"></div>
                    </div>
                </div>
            </div>

            <div class="z-settings-panel" data-panel="seo" style="display:none">
                <div class="z-card mb-3">
                    <h5>SEO Settings</h5>
                    <div class="row g-3">
                        <div class="col-12"><label>Meta Title</label><input type="text" name="meta_title" class="z-input" value="{{ $settings['meta_title'] ?? '' }}"></div>
                        <div class="col-12"><label>Meta Description</label><textarea name="meta_description" class="z-input" rows="3">{{ $settings['meta_description'] ?? '' }}</textarea></div>
                    </div>
                </div>
            </div>

            <div class="z-card">
                <button type="submit" class="z-btn-primary"><i class="bi bi-check2"></i> Save Settings</button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
$(function () {
    $('.z-sn-item').on('click', function () {
        $('.z-sn-item').css({ background: 'transparent', color: 'var(--z-navy)', fontWeight: 500 });
        $(this).css({ background: 'var(--z-gradient)', color: '#fff', fontWeight: 600 });

        const sec = $(this).data('section');
        $('.z-settings-panel').hide();
        $('.z-settings-panel[data-panel="' + sec + '"]').show();
    });
});
</script>
@endpush
