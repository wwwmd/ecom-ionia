@extends('layouts.frontend')
@section('title', 'Profile Settings — Ionia')

@section('content')
<div class="container ioncu-profile-page py-5">
    <div class="row g-4">

        {{-- ==================== SIDEBAR ==================== --}}
        <aside class="col-lg-3">
            <div class="ioncu-account-nav">
                <div class="ioncu-account-user">
                    <div class="ioncu-au-avatar">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="ioncu-au-info">
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->email }}</small>
                    </div>
                </div>
                <a href="{{ url('/customer/dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ url('/customer/orders') }}"><i class="bi bi-box-seam"></i> My Orders</a>
                <a href="{{ url('/wishlist') }}"><i class="bi bi-heart"></i> Wishlist</a>
                <a href="{{ url('/customer/profile') }}" class="active"><i class="bi bi-gear"></i> Profile</a>
                 <form action="{{ url('/logout') }}" method="POST" class="ioncu-logout-form">
                    @csrf
                    <button class="ioncu-logout-link"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </aside>

        {{-- ==================== MAIN ==================== --}}
        <div class="col-lg-9">
            <h1 class="ioncu-page-title border-bottom-0">Profile Settings</h1>

            @if(session('success'))
                <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger py-2 mb-3">{{ session('error') }}</div>
            @endif

            {{-- ============================================= --}}
            {{-- PROFILE IMAGE + INFO --}}
            {{-- ============================================= --}}
            <div class="ioncu-card mb-4">
                <h5 class="ioncu-card-title">Profile Information</h5>

                <form method="POST" action="{{ url('/customer/profile/update') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="ioncu-avatar-upload">
                        <div class="ioncu-avatar-preview" id="ioncuAvatarPreview">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" id="ioncuAvatarImg">
                            @else
                                <span id="ioncuAvatarLetter">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="ioncu-avatar-actions">
                            <label for="avatarInput" class="ioncu-avatar-btn">
                                <i class="bi bi-camera"></i> Change Photo
                            </label>
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" hidden>
                            <small class="ioncu-avatar-hint">JPG, PNG or WEBP · Max 2MB</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="ioncu-label">Full Name</label>
                            <input type="text" name="name" class="ioncu-input" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="ioncu-label">Email</label>
                            <input type="email" name="email" class="ioncu-input" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="ioncu-label">Phone</label>
                            <input type="text" name="phone" class="ioncu-input" value="{{ old('phone', auth()->user()->phone ?? '') }}">
                            @error('phone')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="ioncu-label">Member Since</label>
                            <input type="text" class="ioncu-input" value="{{ auth()->user()->created_at->format('d M Y') }}" readonly>
                        </div>
                    </div>

                    <button type="submit" class="ioncu-submit mt-4">
                        <span>SAVE CHANGES</span>
                    </button>
                </form>
            </div>

            {{-- ============================================= --}}
            {{-- ADDRESSES --}}
            {{-- ============================================= --}}
            <div class="ioncu-card mb-4">
                <div class="ioncu-card-head">
                    <h5 class="ioncu-card-title mb-0">Delivery Addresses</h5>
                    <button type="button" class="ioncu-btn-outline" data-bs-toggle="collapse" data-bs-target="#addAddressForm">
                        <i class="bi bi-plus-lg"></i> Add New
                    </button>
                </div>

                {{-- Saved Addresses List --}}
                @if(isset($addresses) && $addresses->count())
                    <div class="ioncu-address-list">
                        @foreach($addresses as $addr)
                            <div class="ioncu-address-item">
                                <div class="ioncu-address-info">
                                    <strong>{{ $addr->first_name }} {{ $addr->last_name }}</strong>
                                    @if($addr->is_default)
                                        <span class="ioncu-badge">Default</span>
                                    @endif
                                    <p>
                                        {{ $addr->address }}@if($addr->address2), {{ $addr->address2 }}@endif<br>
                                        {{ $addr->city }}, {{ $addr->state }} - {{ $addr->pincode }}<br>
                                        {{ $addr->country }} · {{ $addr->phone }}
                                    </p>
                                </div>
                                <div class="ioncu-address-actions">
                                    <form method="POST" action="{{ url('/customer/address/' . $addr->id . '/default') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="ioncu-icon-btn" title="Make default">
                                            <i class="bi bi-star"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ url('/customer/address/' . $addr->id) }}" class="d-inline" onsubmit="return confirm('Delete this address?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ioncu-icon-btn danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="ioncu-empty">No addresses saved yet. Add your first one below.</p>
                @endif

                {{-- Add New Address Form --}}
                <div class="collapse mt-3" id="addAddressForm">
                    <form method="POST" action="{{ url('/customer/address') }}" class="ioncu-address-form">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="ioncu-label">First Name</label>
                                <input type="text" name="first_name" class="ioncu-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="ioncu-label">Last Name</label>
                                <input type="text" name="last_name" class="ioncu-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="ioncu-label">Phone</label>
                                <input type="text" name="phone" class="ioncu-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="ioncu-label">Country</label>
                                <select name="country" class="ioncu-input" required>
                                    <option value="India" selected>India</option>
                                    <option value="USA">United States</option>
                                    <option value="UK">United Kingdom</option>
                                    <option value="UAE">United Arab Emirates</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="ioncu-label">Address</label>
                                <input type="text" name="address" class="ioncu-input" required>
                            </div>
                            <div class="col-12">
                                <label class="ioncu-label">Apartment, suite, etc. (optional)</label>
                                <input type="text" name="address2" class="ioncu-input">
                            </div>
                            <div class="col-md-4">
                                <label class="ioncu-label">City</label>
                                <input type="text" name="city" class="ioncu-input" required>
                            </div>
                            <div class="col-md-4">
                                <label class="ioncu-label">State</label>
                                <select name="state" class="ioncu-input" required>
                                    <option value="">Select</option>
                                    <option value="MH">Maharashtra</option>
                                    <option value="DL">Delhi</option>
                                    <option value="KA">Karnataka</option>
                                    <option value="TN">Tamil Nadu</option>
                                    <option value="GJ">Gujarat</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="ioncu-label">PIN Code</label>
                                <input type="text" name="pincode" class="ioncu-input" required>
                            </div>
                            <div class="col-12">
                                <label class="ioncu-check">
                                    <input type="checkbox" name="is_default" value="1">
                                    <span>Set as default address</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="ioncu-submit mt-3">
                            <span>SAVE ADDRESS</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- CHANGE PASSWORD --}}
            {{-- ============================================= --}}
            <div class="ioncu-card">
                <h5 class="ioncu-card-title">Change Password</h5>

                <form method="POST" action="{{ url('/customer/profile/password') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="ioncu-label">Current Password</label>
                            <input type="password" name="current_password" class="ioncu-input" required>
                            @error('current_password')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="ioncu-label">New Password</label>
                            <input type="password" name="password" class="ioncu-input" required>
                            @error('password')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="ioncu-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="ioncu-input" required>
                        </div>
                    </div>

                    <button type="submit" class="ioncu-submit mt-4">
                        <span>UPDATE PASSWORD</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ============================================================
       PROFILE PAGE — IONIA PREMIUM THEME
       ============================================================ */

    .ioncu-profile-page {
        font-family: 'Segoe UI', Roboto, sans-serif;
    }

    /* ============================================================
       SIDEBAR NAV
       ============================================================ */
    .ioncu-account-nav {
        background: #ffffff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 18px 14px;
        position: sticky;
        top: 24px;
        box-shadow: 0 8px 24px -12px rgba(0, 0, 0, 0.06);
    }

    .ioncu-account-user {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 6px 8px 18px;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 12px;
    }

    .ioncu-au-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d95a0a, #c8372d);
        color: #ffffff;
        font-size: 1.15rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
        box-shadow: 0 6px 14px -6px rgba(217, 90, 10, 0.55);
    }

    .ioncu-au-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ioncu-au-info {
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .ioncu-au-info strong {
        font-size: 0.9rem;
        color: #2b2b2b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ioncu-au-info small {
        font-size: 0.75rem;
        color: #8a8a8a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ioncu-account-nav a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        font-size: 0.88rem;
        font-weight: 500;
        color: #4a4a4a;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 4px;
    }

    .ioncu-account-nav a i {
        font-size: 1rem;
        color: #b0b0b0;
        transition: color 0.2s ease;
    }

    .ioncu-account-nav a:hover {
        background: rgba(217, 90, 10, 0.06);
        color: #d95a0a;
    }

    .ioncu-account-nav a:hover i {
        color: #d95a0a;
    }

    .ioncu-account-nav a.active {
        background: linear-gradient(135deg, #d95a0a, #c8372d);
        color: #ffffff;
        box-shadow: 0 8px 18px -8px rgba(217, 90, 10, 0.55);
    }

    .ioncu-account-nav a.active i {
        color: #ffffff;
    }

    /* ============================================================
       PAGE TITLE
       ============================================================ */
    .ioncu-page-title {
        font-family: 'Georgia', 'Times New Roman', serif;
        font-size: 26px;
        font-weight: 400;
        color: #2b2b2b;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 26px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f0f0f0;
    }

    /* ============================================================
       CARD
       ============================================================ */
    .ioncu-card {
        background: #ffffff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 26px 24px;
        box-shadow: 0 8px 24px -12px rgba(0, 0, 0, 0.06);
        transition: box-shadow 0.3s ease;
    }

    .ioncu-card:hover {
        box-shadow: 0 12px 32px -14px rgba(0, 0, 0, 0.09);
    }

    .ioncu-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f0f0f0;
        flex-wrap: wrap;
        gap: 10px;
    }

    .ioncu-card-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2b2b2b;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        margin-bottom: 22px;
    }

    .ioncu-card-head .ioncu-card-title {
        margin-bottom: 0;
    }

    /* ============================================================
       AVATAR UPLOAD
       ============================================================ */
    .ioncu-avatar-upload {
        display: flex;
        align-items: center;
        gap: 24px;
        padding-bottom: 24px;
        margin-bottom: 24px;
        border-bottom: 1px solid #f0f0f0;
    }

    .ioncu-avatar-preview {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d95a0a, #c8372d);
        color: #ffffff;
        font-size: 2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 12px 26px -10px rgba(217, 90, 10, 0.55);
        border: 3px solid #ffffff;
        outline: 1px solid #ececec;
    }

    .ioncu-avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ioncu-avatar-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .ioncu-avatar-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border: 1.5px solid #d95a0a;
        color: #d95a0a;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.25s ease;
        text-transform: uppercase;
    }

    .ioncu-avatar-btn:hover {
        background: #d95a0a;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 10px 20px -8px rgba(217, 90, 10, 0.55);
    }

    .ioncu-avatar-btn i {
        font-size: 1rem;
    }

    .ioncu-avatar-hint {
        font-size: 0.75rem;
        color: #b0b0b0;
    }

    /* ============================================================
       FORM LABEL / INPUT
       ============================================================ */
    .ioncu-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #8a8a8a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .ioncu-input {
        width: 100%;
        height: 46px;
        padding: 0 14px;
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #2b2b2b;
        background: #ffffff;
        transition: all 0.25s ease;
        outline: none;
        font-family: inherit;
    }

    .ioncu-input:focus {
        border-color: #d95a0a;
        box-shadow: 0 0 0 4px rgba(217, 90, 10, 0.10);
    }

    .ioncu-input[readonly] {
        background: #fafafa;
        color: #8a8a8a;
        cursor: not-allowed;
    }

    select.ioncu-input {
        appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23888' stroke-width='2'><polyline points='6 9 12 15 18 9'/></svg>");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 40px;
    }

    /* ============================================================
       BUTTONS
       ============================================================ */
    .ioncu-submit {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-width: 130px;
        height: 48px;
        padding: 0 28px;
        border: 0;
        font-family: 'Inter', system-ui, sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: #ffffff;
        background: #d95a0a;
        cursor: pointer;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 14px 30px -12px rgba(217, 90, 10, 0.7);
        transition: background .3s ease, letter-spacing .3s ease, box-shadow .3s ease, transform .3s ease;
    }

    .ioncu-submit:hover {
        background: #c8372d;
        letter-spacing: .20em;
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -14px rgba(200, 55, 45, 0.85);
    }

    .ioncu-submit:active {
        transform: translateY(0);
    }

    .ioncu-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -130%;
        width: 100%;
        height: 100%;
        background: linear-gradient(100deg, transparent 25%, rgba(255,255,255,0.30) 50%, transparent 75%);
        transition: left 0.75s cubic-bezier(0.2, 0.9, 0.3, 1);
        z-index: 2;
        pointer-events: none;
    }

    .ioncu-submit:hover::before {
        left: 130%;
    }

    .ioncu-submit span {
        position: relative;
        z-index: 3;
    }

    .ioncu-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: transparent;
        border: 1.5px solid #d95a0a;
        color: #d95a0a;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .ioncu-btn-outline:hover {
        background: #d95a0a;
        color: #ffffff;
        box-shadow: 0 10px 20px -8px rgba(217, 90, 10, 0.55);
    }

    /* ============================================================
       ADDRESSES
       ============================================================ */
    .ioncu-address-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .ioncu-address-item {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 18px;
        border: 1.5px solid #ececec;
        border-radius: 10px;
        transition: all 0.25s ease;
        flex-wrap: wrap;
    }

    .ioncu-address-item:hover {
        border-color: rgba(217, 90, 10, 0.4);
        box-shadow: 0 10px 24px -12px rgba(217, 90, 10, 0.25);
    }

    .ioncu-address-info {
        flex: 1;
        min-width: 200px;
    }

    .ioncu-address-info strong {
        font-size: 0.9rem;
        color: #2b2b2b;
        display: inline-block;
        margin-right: 8px;
    }

    .ioncu-address-info p {
        font-size: 0.83rem;
        color: #6a6a6a;
        line-height: 1.6;
        margin: 6px 0 0;
    }

    .ioncu-badge {
        display: inline-block;
        padding: 2px 9px;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #ffffff;
        background: linear-gradient(135deg, #d95a0a, #c8372d);
        border-radius: 20px;
        vertical-align: middle;
        box-shadow: 0 4px 10px -4px rgba(217, 90, 10, 0.6);
    }

    .ioncu-address-actions {
        display: flex;
        gap: 6px;
    }

    .ioncu-icon-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #e0e0e0;
        background: #ffffff;
        border-radius: 8px;
        color: #8a8a8a;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }

    .ioncu-icon-btn:hover {
        border-color: #d95a0a;
        color: #d95a0a;
        background: rgba(217, 90, 10, 0.05);
    }

    .ioncu-icon-btn.danger:hover {
        border-color: #c8372d;
        color: #c8372d;
        background: rgba(200, 55, 45, 0.05);
    }

    .ioncu-empty {
        font-size: 0.85rem;
        color: #b0b0b0;
        padding: 10px 0;
        text-align: center;
        font-style: italic;
    }

    .ioncu-address-form {
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    /* ============================================================
       CHECKBOX
       ============================================================ */
    .ioncu-check {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
        color: #666;
        cursor: pointer;
        user-select: none;
    }

    .ioncu-check input {
        width: 16px;
        height: 16px;
        accent-color: #d95a0a;
        cursor: pointer;
    }

    /* ============================================================
       ALERTS
       ============================================================ */
    .alert {
        border-radius: 8px;
        font-size: 0.9rem;
        border: none;
        padding: 12px 16px;
    }

    .alert-danger {
        background: rgba(200, 55, 45, 0.07);
        color: #c8372d;
        border-left: 3px solid #c8372d;
    }

    .alert-success {
        background: rgba(38, 166, 154, 0.07);
        color: #1e7a70;
        border-left: 3px solid #26a69a;
    }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 991px) {
        .ioncu-account-nav {
            position: static;
            margin-bottom: 20px;
        }

        .ioncu-page-title {
            font-size: 22px;
        }
    }

    @media (max-width: 575px) {
        .ioncu-card {
            padding: 20px 16px;
        }

        .ioncu-avatar-upload {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .ioncu-page-title {
            font-size: 20px;
            letter-spacing: 1px;
        }

        .ioncu-address-item {
            flex-direction: column;
        }

        .ioncu-address-actions {
            align-self: flex-end;
        }
    }
    .ioncu-profile-page{
        padding-top: 50px;
    }
    .ioncu-account-nav {
 margin-top: 100px;

    }
    .ioncu-card {
       margin-top: 58px;
 margin-bottom: 40px
    }

     .ioncu-logout-form {
        margin-top: 10px;
        padding-top: 14px;
        border-top: 1px solid #f0f0f0;
    }
     .ioncu-logout-link {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 11px 14px;
        font-size: 0.88rem;
        font-weight: 500;
        color: #c8372d;
        background: transparent;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .ioncu-logout-link i {
        font-size: 1rem;
    }

    .ioncu-logout-link:hover {
        background: rgba(200, 55, 45, 0.06);
    }

</style>
@endpush
@push('scripts')


<script>
    /* ============================================================
       AVATAR PREVIEW
       ============================================================ */
    (function () {
        const input = document.getElementById('avatarInput');
        const preview = document.getElementById('ioncuAvatarPreview');
        if (!input || !preview) return;

        input.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                alert('Image must be smaller than 2MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (ev) {
                // Replace contents with the new image
                preview.innerHTML = '<img src="' + ev.target.result + '" alt="Avatar">';
            };
            reader.readAsDataURL(file);
        });
    })();

    (() => {
      const header = document.getElementById('owHeader');
      const menuButton = document.getElementById('owMenuButton');
      const drawer = document.getElementById('owSideDrawer');
      const overlay = document.getElementById('owDrawerOverlay');
      const closeButton = document.getElementById('owDrawerClose');
      let lastFocus = null;

      function owOpenDrawer() {
        lastFocus = document.activeElement;
        header.classList.add('ow-menu-open');
        drawer.classList.add('ow-visible');
        overlay.classList.add('ow-visible');
        drawer.setAttribute('aria-hidden', 'false');
        overlay.setAttribute('aria-hidden', 'false');
        menuButton.setAttribute('aria-expanded', 'true');
        menuButton.setAttribute('aria-label', 'Close menu');
        document.body.classList.add('ow-body-locked');
        closeButton.focus();
      }
      function owCloseDrawer() {
        header.classList.remove('ow-menu-open');
        drawer.classList.remove('ow-visible');
        overlay.classList.remove('ow-visible');
        drawer.setAttribute('aria-hidden', 'true');
        overlay.setAttribute('aria-hidden', 'true');
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.setAttribute('aria-label', 'Open menu');
        document.body.classList.remove('ow-body-locked');
        if (lastFocus) lastFocus.focus();
      }

      menuButton.addEventListener('click', () => drawer.classList.contains('ow-visible') ? owCloseDrawer() : owOpenDrawer());
      closeButton.addEventListener('click', owCloseDrawer);
      overlay.addEventListener('click', owCloseDrawer);
      drawer.querySelectorAll('a').forEach(link => link.addEventListener('click', owCloseDrawer));
      document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && drawer.classList.contains('ow-visible')) owCloseDrawer();
      });
      document.getElementById('owNewsletterForm').addEventListener('submit', event => {
        event.preventDefault();
        document.getElementById('owNewsletterMessage').textContent = 'Thanks! Connect this form to your newsletter service to collect subscriptions.';
        event.target.reset();
      });
    })();

        (function() {
      const slider = document.getElementById('heroSlider');
      const slides = Array.from(document.querySelectorAll('.blume-hero-slide'));
      const dots = Array.from(document.querySelectorAll('.blume-hero-dot'));
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const dotsContainer = document.getElementById('dotsContainer');

      let currentIndex = 0;
      let slideInterval = null;
      const SLIDE_DURATION = 6000; // ms, matches dot animation

      // ---- helper: go to slide ----
      function goToSlide(index) {
        // wrap around
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;

        // update slides
        slides.forEach((slide, i) => {
          slide.classList.toggle('blume-hero-active', i === index);
        });

        // update dots
        dots.forEach((dot, i) => {
          dot.classList.toggle('blume-hero-active', i === index);
        });

        currentIndex = index;
        resetTimer();
      }

      // ---- next / prev ----
      function nextSlide() {
        goToSlide(currentIndex + 1);
      }

      function prevSlide() {
        goToSlide(currentIndex - 1);
      }

      // ---- autoplay timer ----
      function startTimer() {
        if (slideInterval) clearInterval(slideInterval);
        slideInterval = setInterval(() => {
          nextSlide();
        }, SLIDE_DURATION);
      }

      function resetTimer() {
        if (slideInterval) {
          clearInterval(slideInterval);
          slideInterval = null;
        }
        // only restart if not paused (we don't have pause now, but keep logic)
        startTimer();
      }

      // pause on hover (optional, nice UX)
      slider.addEventListener('mouseenter', () => {
        slider.classList.add('is-paused');
        if (slideInterval) {
          clearInterval(slideInterval);
          slideInterval = null;
        }
      });
      slider.addEventListener('mouseleave', () => {
        slider.classList.remove('is-paused');
        startTimer();
      });

      // ---- event listeners ----
      prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        prevSlide();
      });

      nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        nextSlide();
      });

      dots.forEach((dot, idx) => {
        dot.addEventListener('click', (e) => {
          e.preventDefault();
          goToSlide(idx);
        });
      });

      // keyboard navigation
      document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
          prevSlide();
        } else if (e.key === 'ArrowRight') {
          nextSlide();
        }
      });

      // ---- init ----
      // ensure first slide is active (already is in markup, but just in case)
      goToSlide(0);
      // start autoplay
      startTimer();

      // Cleanup on page unload (optional)
      window.addEventListener('beforeunload', () => {
        if (slideInterval) clearInterval(slideInterval);
      });
    })();
</script>
@endpush
