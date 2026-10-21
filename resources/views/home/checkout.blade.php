@extends('layouts.frontend')
@section('title', 'Checkout — Ionia Premium Store')

@section('content')
<div class="co-page-wrapper">
    <div class="container">

        <!-- BREADCRUMBS & TOP TITLE -->
        <!-- <div class="co-header-area">
            <nav class="co-breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <span class="co-breadcrumb-separator"><i class="fa-solid fa-chevron-right"></i></span>
                <a href="{{ url('/cart') }}">Shopping Cart</a>
                <span class="co-breadcrumb-separator"><i class="fa-solid fa-chevron-right"></i></span>
                <span class="co-breadcrumb-active">Checkout</span>
            </nav>

            <div class="co-title-row">
                <div>
                    <h1 class="co-page-title">Checkout</h1>
                    <p class="co-page-subtitle">Please enter your billing and delivery details to complete your order.</p>
                </div>
                <!-- Trust Step Pill -->
                <!-- <div class="co-step-indicator">
                    <div class="co-step-item co-step-done">
                        <span class="co-step-num"><i class="fa-solid fa-check"></i></span>
                        <span class="co-step-name">Cart</span>
                    </div>
                    <div class="co-step-line"></div>
                    <div class="co-step-item co-step-active">
                        <span class="co-step-num">2</span>
                        <span class="co-step-name">Checkout</span>
                    </div>
                    <div class="co-step-line"></div>
                    <div class="co-step-item">
                        <span class="co-step-num">3</span>
                        <span class="co-step-name">Confirmation</span>
                    </div>
                </div> -->
            <!-- </div>
        </div> --> 

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 co-alert" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
            @csrf
            <input type="hidden" name="cart_id" value="{{ $cartItems->first()?->cart_id }}">
            <div class="row g-4 co-main-row">

                <!-- ================= LEFT COLUMN: FORM DETAILS ================= -->
                <div class="col-lg-7">
                    <div class="co-form-left">

                        <!-- BILLING DETAILS SECTION -->
                        <div class="co-card-box">
                            <div class="co-card-heading-wrap">
                                <span class="co-card-icon"><i class="fa-solid fa-address-card"></i></span>
                                <div>
                                    <h3 class="co-section-title">Billing Details</h3>
                                    <p class="co-section-sub">Where should we invoice your purchase?</p>
                                </div>
                            </div>

                            <!-- Row 1: Full Name & Mobile -->
                            <div class="row g-3 co-form-group">
                                <div class="col-md-6">
                                    <label class="co-label" for="billingName">Full Name <span class="text-danger">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa-regular fa-user co-field-icon"></i>
                                        <input type="text" name="name" id="billingName" class="co-input @error('name') co-is-invalid @enderror" value="{{ old('name', auth()->user()?->name ?? '') }}" placeholder="Enter full name">
                                    </div>
                                    @error('name')
                                        <div class="co-error-text">{{ $message }}</div>
                                    @else
                                        <div class="co-error-text client-error" id="nameError" style="display:none;">The Full Name field is required.</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="co-label" for="billingPhone">Mobile Number <span class="text-danger">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa-solid fa-phone co-field-icon"></i>
                                        <input type="text" name="phone" id="billingPhone" class="co-input @error('phone') co-is-invalid @enderror" value="{{ old('phone', auth()->user()?->phone ?? '') }}" placeholder="10-digit mobile number">
                                    </div>
                                    @error('phone')
                                        <div class="co-error-text">{{ $message }}</div>
                                    @else
                                        <div class="co-error-text client-error" id="phoneError" style="display:none;">The Mobile field is required.</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Row 2: Email Address -->
                            <div class="co-form-group">
                                <label class="co-label" for="billingEmail">Email Address <span class="text-danger">*</span></label>
                                <div class="co-input-wrap">
                                    <i class="fa-regular fa-envelope co-field-icon"></i>
                                    <input type="email" name="email" id="billingEmail" class="co-input @error('email') co-is-invalid @enderror" value="{{ old('email', auth()->user()?->email ?? '') }}" placeholder="name@example.com">
                                </div>
                                @error('email')
                                    <div class="co-error-text">{{ $message }}</div>
                                @else
                                    <div class="co-error-text client-error" id="emailError" style="display:none;">The Email field is required.</div>
                                @enderror
                            </div>

                            <!-- Row 3: Street Address -->
                            <div class="co-form-group">
                                <label class="co-label" for="billingAddress">Street Address <span class="text-danger">*</span></label>
                                <div class="co-input-wrap">
                                    <i class="fa-solid fa-location-dot co-field-icon"></i>
                                    <input type="text" name="address" id="billingAddress" class="co-input @error('address') co-is-invalid @enderror" value="{{ old('address') }}" placeholder="Flat / House no, Building name, Street">
                                </div>
                                @error('address')
                                    <div class="co-error-text">{{ $message }}</div>
                                @else
                                    <div class="co-error-text client-error" id="addressError" style="display:none;">The Address field is required.</div>
                                @enderror
                            </div>

                            <!-- Row 4: Country & State -->
                            <div class="row g-3 co-form-group">
                                <div class="col-md-6">
                                    <label class="co-label" for="billingCountry">Country <span class="text-danger">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa-solid fa-globe co-field-icon"></i>
                                        <select name="country" id="billingCountry" class="co-select @error('country') co-is-invalid @enderror">
                                            <option value="India" selected>India</option>
                                        </select>
                                    </div>
                                    @error('country')
                                        <div class="co-error-text">{{ $message }}</div>
                                    @else
                                        <div class="co-error-text client-error" id="countryError" style="display:none;">The Country field is required.</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="co-label" for="billingState">State <span class="text-danger">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa-solid fa-map co-field-icon"></i>
                                        <select name="state" id="billingState" class="co-select @error('state') co-is-invalid @enderror">
                                            <option value="">Select State</option>
                                            @foreach(['Andhra Pradesh', 'Assam', 'Bihar', 'Chandigarh', 'Chhattisgarh', 'Delhi', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Odisha', 'Punjab', 'Rajasthan', 'Tamil Nadu', 'Telangana', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'] as $st)
                                                <option value="{{ $st }}" {{ old('state') == $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('state')
                                        <div class="co-error-text">{{ $message }}</div>
                                    @else
                                        <div class="co-error-text client-error" id="stateError" style="display:none;">The State field is required.</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Row 5: City & Post Code -->
                            <div class="row g-3 co-form-group">
                                <div class="col-md-6">
                                    <label class="co-label" for="billingCity">City <span class="text-danger">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa-solid fa-city co-field-icon"></i>
                                        <input type="text" name="city" id="billingCity" class="co-input @error('city') co-is-invalid @enderror" value="{{ old('city') }}" placeholder="City">
                                    </div>
                                    @error('city')
                                        <div class="co-error-text">{{ $message }}</div>
                                    @else
                                        <div class="co-error-text client-error" id="cityError" style="display:none;">The City field is required.</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="co-label" for="billingPincode">Post Code / PIN <span class="text-danger">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa-solid fa-envelopes-bulk co-field-icon"></i>
                                        <input type="text" name="pincode" id="billingPincode" class="co-input @error('pincode') co-is-invalid @enderror" value="{{ old('pincode') }}" placeholder="6-digit PIN code">
                                    </div>
                                    @error('pincode')
                                        <div class="co-error-text">{{ $message }}</div>
                                    @else
                                        <div class="co-error-text client-error" id="pincodeError" style="display:none;">The Pin Code field is required.</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SHIPPING DETAILS SECTION -->
                        <div class="co-card-box co-shipping-card-box">
                            <div class="co-card-heading-wrap">
                                <span class="co-card-icon"><i class="fa-solid fa-truck-fast"></i></span>
                                <div>
                                    <h3 class="co-section-title">Shipping Details</h3>
                                    <p class="co-section-sub">Where should we deliver your order?</p>
                                </div>
                            </div>

                            <!-- Same address toggle card -->
                            <div class="co-same-address-card">
                                <label class="co-checkbox-label" for="sameAsBilling">
                                    <input type="checkbox" id="sameAsBilling" name="same_as_billing" value="1" {{ old('same_as_billing', '1') ? 'checked' : '' }} class="co-checkbox-input">
                                    <div class="co-checkbox-content">
                                        <span class="co-check-title">Shipping address is the same as Billing address</span>
                                        <span class="co-check-desc">Check this to quickly use your billing address for delivery.</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Shipping Fields Container -->
                            <div id="shippingFieldsContainer" class="co-shipping-fields">
                                <!-- Name -->
                                <div class="co-form-group">
                                    <label class="co-label" for="shipName">Recipient Name</label>
                                    <div class="co-input-wrap">
                                        <i class="fa-regular fa-user co-field-icon"></i>
                                        <input type="text" name="shipping_name" id="shipName" class="co-input" value="{{ old('shipping_name') }}" placeholder="Full recipient name">
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="co-form-group">
                                    <label class="co-label" for="shipAddress">Delivery Address</label>
                                    <div class="co-input-wrap">
                                        <i class="fa-solid fa-location-dot co-field-icon"></i>
                                        <input type="text" name="shipping_address" id="shipAddress" class="co-input" value="{{ old('shipping_address') }}" placeholder="Street address / landmark">
                                    </div>
                                    <div class="co-error-text client-error" id="shipAddressError" style="display:none;">The Address field is required.</div>
                                </div>

                                <!-- Country & State -->
                                <div class="row g-3 co-form-group">
                                    <div class="col-md-6">
                                        <label class="co-label" for="shipCountry">Country</label>
                                        <div class="co-input-wrap">
                                            <i class="fa-solid fa-globe co-field-icon"></i>
                                            <select name="shipping_country" id="shipCountry" class="co-select">
                                                <option value="India" selected>India</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="co-label" for="shipState">State</label>
                                        <div class="co-input-wrap">
                                            <i class="fa-solid fa-map co-field-icon"></i>
                                            <select name="shipping_state" id="shipState" class="co-select">
                                                <option value="">Select State</option>
                                                @foreach(['Andhra Pradesh', 'Assam', 'Bihar', 'Chandigarh', 'Chhattisgarh', 'Delhi', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Odisha', 'Punjab', 'Rajasthan', 'Tamil Nadu', 'Telangana', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'] as $st)
                                                    <option value="{{ $st }}" {{ old('shipping_state') == $st ? 'selected' : '' }}>{{ $st }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- City & Post Code -->
                                <div class="row g-3 co-form-group">
                                    <div class="col-md-6">
                                        <label class="co-label" for="shipCity">City</label>
                                        <div class="co-input-wrap">
                                            <i class="fa-solid fa-city co-field-icon"></i>
                                            <input type="text" name="shipping_city" id="shipCity" class="co-input" value="{{ old('shipping_city') }}" placeholder="City">
                                        </div>
                                        <div class="co-error-text client-error" id="shipCityError" style="display:none;">The City field is required.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="co-label" for="shipPincode">Post Code / PIN</label>
                                        <div class="co-input-wrap">
                                            <i class="fa-solid fa-envelopes-bulk co-field-icon"></i>
                                            <input type="text" name="shipping_pincode" id="shipPincode" class="co-input" value="{{ old('shipping_pincode') }}" placeholder="PIN code">
                                        </div>
                                        <div class="co-error-text client-error" id="shipPincodeError" style="display:none;">The Pin Code field is required.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CREATE ACCOUNT CHECKBOX (GUESTS ONLY) -->
                        @guest
                        <div class="co-card-box co-account-card-box">
                            <label class="co-checkbox-label" for="createAccountCheck">
                                <input type="checkbox" id="createAccountCheck" name="create_account" value="1" {{ old('create_account') ? 'checked' : '' }} class="co-checkbox-input">
                                <div class="co-checkbox-content">
                                    <span class="co-check-title"><i class="fa-solid fa-user-plus me-1 text-danger"></i> Create an account with this order?</span>
                                    <span class="co-check-desc">Save your details for faster checkout and track warranty & maintenance history.</span>
                                </div>
                            </label>

                            <!-- Password Fields (Toggled by Checkbox) -->
                            <div id="passwordFieldsWrap" class="co-password-wrap" style="{{ old('create_account') ? 'display:block;' : 'display:none;' }}">
                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label class="co-label" for="coPassword">Create Password <span class="text-danger">*</span></label>
                                        <div class="co-input-wrap">
                                            <i class="fa-solid fa-key co-field-icon"></i>
                                            <input type="password" name="password" id="coPassword" class="co-input @error('password') co-is-invalid @enderror" placeholder="Min. 6 characters">
                                        </div>
                                        @error('password')
                                            <div class="co-error-text">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="co-label" for="coPasswordConfirm">Confirm Password <span class="text-danger">*</span></label>
                                        <div class="co-input-wrap">
                                            <i class="fa-solid fa-shield-halved co-field-icon"></i>
                                            <input type="password" name="password_confirmation" id="coPasswordConfirm" class="co-input" placeholder="Re-enter password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endguest

                        <!-- PAYMENT METHOD SELECTION -->
                        <!-- <div class="co-card-box co-payment-box">
                            <div class="co-card-heading-wrap">
                                <span class="co-card-icon"><i class="fa-solid fa-credit-card"></i></span>
                                <div>
                                    <h3 class="co-section-title">Payment Method</h3>
                                    <p class="co-section-sub">Select your preferred payment mode</p>
                                </div>
                            </div>

                            <div class="co-payment-options">
                                <label class="co-payment-option-card">
                                    <input type="radio" name="payment_method" value="cod" checked class="co-payment-radio">
                                    <div class="co-payment-option-inner">
                                        <div class="co-payment-icon-box">
                                            <i class="fa-solid fa-hand-holding-dollar"></i>
                                        </div>
                                        <div class="co-payment-text">
                                            <div class="co-payment-title">Cash on Delivery (COD)</div>
                                            <div class="co-payment-desc">Pay easily by cash or UPI at the time of doorstep delivery</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="co-payment-option-card">
                                    <input type="radio" name="payment_method" value="online" class="co-payment-radio">
                                    <div class="co-payment-option-inner">
                                        <div class="co-payment-icon-box">
                                            <i class="fa-solid fa-bolt"></i>
                                        </div>
                                        <div class="co-payment-text">
                                            <div class="co-payment-title">Online Payment (UPI / Cards / Net Banking)</div>
                                            <div class="co-payment-desc">Instant confirmation via secure payment gateway</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div> -->

                    </div>
                </div>

                <!-- ================= RIGHT COLUMN: ORDER SUMMARY ================= -->
                <div class="col-lg-5">
                    <div class="co-order-card">
                        <!-- Header -->
                        <div class="co-card-header">
                            <h4 class="co-summary-title">
                                <i class="fa-solid fa-receipt me-2" style="color: #c8372d;"></i>
                                Order Summary
                            </h4>
                            <span class="co-summary-count-badge">{{ $count ?? $cartItems->sum('quantity') }} items</span>
                        </div>

                        <!-- Item list -->
                        <div class="co-card-items">
                            @forelse($cartItems as $item)
                            @php
                                $prod = $item->product;
                                $thumb = $prod ? \App\Models\Product::resolveImageUrl($prod->thumbnail) : null;
                                $pName = $prod ? $prod->name : 'Product';
                            @endphp
                            <div class="co-card-item">
                                <div class="co-item-left">
                                    <div class="co-item-thumb">
                                        @if($thumb)
                                            <img src="{{ $thumb }}" alt="{{ $pName }}">
                                        @else
                                            <div class="co-thumb-fallback"><i class="fa-solid fa-image"></i></div>
                                        @endif
                                        <span class="co-item-qty-badge">{{ $item->quantity }}</span>
                                    </div>
                                    <div class="co-item-info">
                                        <div class="co-item-name" title="{{ $pName }}">{{ $pName }}</div>
                                        <div class="co-item-unit">Unit: ₹{{ number_format($item->price, 2) }}</div>
                                    </div>
                                </div>
                                <div class="co-item-price">
                                    ₹ {{ number_format($item->price * $item->quantity) }}
                                </div>
                            </div>
                            @empty
                            <div class="co-card-empty">
                                <i class="fa-solid fa-bag-shopping mb-2"></i>
                                <p class="mb-0">No products in cart</p>
                            </div>
                            @endforelse
                        </div>

                        <!-- Summary Breakdown -->
                        <div class="co-card-summary">
                            <!-- Subtotal -->
                            <div class="co-summary-row">
                                <span class="co-sum-lbl">Subtotal</span>
                                <span class="co-sum-val">₹ {{ number_format($subtotal, 2) }}</span>
                            </div>

                            <!-- Discount -->
                            @if(!empty($discount) && $discount > 0)
                            <div class="co-summary-row">
                                <span class="co-sum-lbl">Discount</span>
                                <span class="co-sum-val text-success font-weight-bold">- ₹ {{ number_format($discount, 2) }}</span>
                            </div>
                            @endif

                            <!-- Shipping -->
                            <div class="co-summary-row">
                                <span class="co-sum-lbl">Express Delivery</span>
                                <span class="co-sum-val">₹ {{ number_format($shipping ?? 100, 2) }}</span>
                            </div>

                            <!-- GST -->
                            <div class="co-summary-row">
                                <span class="co-sum-lbl">Estimated GST</span>
                                <span class="co-sum-val">₹ {{ number_format($tax, 2) }}</span>
                            </div>

                            <!-- Total Payable -->
                            <div class="co-summary-row co-row-total">
                                <div>
                                    <span class="co-sum-lbl co-bold">Total Payable</span>
                                    <div class="co-tax-note">All taxes & duties included</div>
                                </div>
                                <span class="co-sum-val co-bold-total">₹ {{ number_format($grandTotal, 2) }}</span>
                            </div>
                        </div>

                        <!-- Place Order Button -->
                        <div class="co-btn-wrap">
                            <button type="submit" class="co-btn-place-order" id="placeOrderBtn">
                                <span><i class="fa-solid fa-lock me-2"></i> Place Order Now</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>

                       
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

<!-- ============================================================
     THEME CSS STYLES FOR CHECKOUT PAGE
     ============================================================ -->
<style>
/* Page Wrapper & Base Theme Tokens */
.co-page-wrapper {
    padding-top: 100px;
    padding-bottom: 75px;
    background: #f8fafd;
    min-height: 85vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #173a46;
}

/* Breadcrumbs Area */
.co-header-area {
    margin-bottom: 30px;
}
.co-breadcrumbs {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #5e7279;
    margin-bottom: 12px;
}
.co-breadcrumbs a {
    color: #5e7279;
    text-decoration: none;
    transition: color 0.18s ease;
}
.co-breadcrumbs a:hover {
    color: #c8372d;
}
.co-breadcrumb-separator {
    font-size: 10px;
    color: #94a3b8;
}
.co-breadcrumb-active {
    color: #173a46;
    font-weight: 600;
}

/* Page Title Row */
.co-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e2e8f0;
}
.co-page-title {
    font-size: 28px;
    font-weight: 700;
    color: #173a46;
    margin: 0 0 4px 0;
    letter-spacing: -0.4px;
}
.co-page-subtitle {
    font-size: 14px;
    color: #5e7279;
    margin: 0;
}

/* Step Indicator */
.co-step-indicator {
    display: inline-flex;
    align-items: center;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 30px;
    padding: 6px 18px;
    box-shadow: 0 2px 6px rgba(23, 58, 70, 0.04);
}
.co-step-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12.5px;
    font-weight: 600;
    color: #64748b;
}
.co-step-num {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
}
.co-step-done .co-step-num {
    background: #dcfce7;
    color: #166534;
}
.co-step-active {
    color: #c8372d;
}
.co-step-active .co-step-num {
    background: #c8372d;
    color: #ffffff;
}
.co-step-line {
    width: 22px;
    height: 1px;
    background: #e2e8f0;
    margin: 0 10px;
}

/* Card Boxes */
.co-card-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 28px 30px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px -2px rgba(23, 58, 70, 0.05);
}
.co-card-heading-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
}
.co-card-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: #fef2f2;
    color: #c8372d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    flex-shrink: 0;
}
.co-section-title {
    font-size: 19px;
    font-weight: 700;
    color: #173a46;
    margin: 0;
    letter-spacing: -0.2px;
}
.co-section-sub {
    font-size: 12.5px;
    color: #64748b;
    margin: 2px 0 0;
}

/* Labels and Inputs */
.co-form-group {
    margin-bottom: 18px;
}
.co-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #173a46;
    margin-bottom: 6px;
    letter-spacing: 0.1px;
}
.co-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.co-field-icon {
    position: absolute;
    left: 14px;
    font-size: 14px;
    color: #94a3b8;
    pointer-events: none;
    transition: color 0.2s ease;
}
.co-input, .co-select {
    width: 100%;
    height: 46px;
    border: 1px solid #d5dbe1;
    border-radius: 8px;
    padding: 0 14px 0 38px;
    font-size: 14px;
    color: #173a46;
    background: #ffffff;
    outline: none;
    transition: all 0.2s ease;
}
.co-input:focus, .co-select:focus {
    border-color: #c8372d;
    box-shadow: 0 0 0 3.5px rgba(200, 55, 45, 0.12);
}
.co-input-wrap:focus-within .co-field-icon {
    color: #c8372d;
}
.co-input::placeholder {
    color: #94a3b8;
    font-size: 13.5px;
}
.co-select {
    cursor: pointer;
    appearance: auto;
}
.co-is-invalid {
    border-color: #dc2626 !important;
    background-color: #fffaf9;
}
.co-error-text {
    font-size: 12px;
    color: #dc2626;
    margin-top: 5px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Same Address Card */
.co-same-address-card, .co-account-card-box {
    background: #f8fafd;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    margin-bottom: 20px;
}
.co-checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
    margin: 0;
    width: 100%;
}
.co-checkbox-input {
    accent-color: #c8372d;
    width: 18px;
    height: 18px;
    margin-top: 2px;
    cursor: pointer;
    flex-shrink: 0;
}
.co-checkbox-content {
    display: flex;
    flex-direction: column;
}
.co-check-title {
    font-size: 14px;
    font-weight: 700;
    color: #173a46;
}
.co-check-desc {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
}

/* Payment Mode Radio Cards */
.co-payment-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.co-payment-option-card {
    display: block;
    position: relative;
    cursor: pointer;
    margin: 0;
}
.co-payment-radio {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.co-payment-option-inner {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 18px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    background: #ffffff;
    transition: all 0.22s ease;
}
.co-payment-icon-box {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #f8fafc;
    color: #173a46;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.22s ease;
}
.co-payment-title {
    font-size: 14px;
    font-weight: 700;
    color: #173a46;
}
.co-payment-desc {
    font-size: 12px;
    color: #64748b;
}
.co-payment-radio:checked + .co-payment-option-inner {
    border-color: #c8372d;
    background: #fefcfc;
    box-shadow: 0 0 0 3px rgba(200, 55, 45, 0.08);
}
.co-payment-radio:checked + .co-payment-option-inner .co-payment-icon-box {
    background: #fef2f2;
    color: #c8372d;
}

/* Order Summary Sticky Card */
.co-order-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 26px 28px;
    position: sticky;
    top: 110px;
    box-shadow: 0 8px 30px -4px rgba(23, 58, 70, 0.08);
}
.co-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 16px;
}
.co-summary-title {
    font-size: 19px;
    font-weight: 700;
    color: #173a46;
    margin: 0;
    display: flex;
    align-items: center;
}
.co-summary-count-badge {
    font-size: 12px;
    font-weight: 600;
    background: #edf3f5;
    color: #173a46;
    padding: 2px 10px;
    border-radius: 20px;
}

/* Items List */
.co-card-items {
    max-height: 290px;
    overflow-y: auto;
    margin-bottom: 18px;
    padding-right: 4px;
}
.co-card-items::-webkit-scrollbar {
    width: 4px;
}
.co-card-items::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.co-card-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #f1f3f5;
    gap: 12px;
}
.co-card-item:last-child {
    border-bottom: none;
}
.co-item-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    flex: 1;
}
.co-item-thumb {
    position: relative;
    width: 52px;
    height: 52px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 2px;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.co-item-thumb img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.co-thumb-fallback {
    font-size: 18px;
    color: #cbd5e1;
}
.co-item-qty-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 19px;
    height: 19px;
    border-radius: 50%;
    background: #173a46;
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid #ffffff;
}
.co-item-name {
    font-size: 13.5px;
    font-weight: 600;
    color: #173a46;
    line-height: 1.4;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}
.co-item-unit {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
}
.co-item-price {
    font-size: 14.5px;
    font-weight: 700;
    color: #173a46;
    white-space: nowrap;
}
.co-card-empty {
    text-align: center;
    padding: 30px 10px;
    color: #94a3b8;
    font-size: 14px;
}

/* Summary Breakdown Rows */
.co-card-summary {
    padding-top: 14px;
    border-top: 1px solid #e2e8f0;
    margin-bottom: 22px;
}
.co-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    color: #5e7279;
    margin-bottom: 11px;
}
.co-sum-lbl {
    font-weight: 500;
}
.co-sum-val {
    font-weight: 600;
    color: #173a46;
}
.co-summary-row.co-row-total {
    padding-top: 14px;
    margin-top: 12px;
    border-top: 1.5px dashed #e2e8f0;
    margin-bottom: 0;
    align-items: flex-start;
}
.co-bold {
    font-size: 16px;
    font-weight: 800;
    color: #173a46;
}
.co-tax-note {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 2px;
}
.co-bold-total {
    font-size: 22px;
    font-weight: 800;
    color: #c8372d;
    letter-spacing: -0.4px;
}

/* Place Order Button */
.co-btn-place-order {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    height: 52px;
    background: #c8372d;
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(200, 55, 45, 0.32);
    position: relative;
    overflow: hidden;
    transition: all 0.25s ease;
}
.co-btn-place-order::before {
    content: "";
    position: absolute;
    top: 0;
    left: -80%;
    width: 60%;
    height: 100%;
    background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.35) 50%, transparent);
    transform: skewX(-22deg);
    transition: left 0.6s ease;
    pointer-events: none;
}
.co-btn-place-order:hover::before {
    left: 140%;
}
.co-btn-place-order:hover {
    background: #a02a22;
    transform: translateY(-2px);
    box-shadow: 0 8px 26px rgba(200, 55, 45, 0.42);
}

/* Trust Badges under button */
.co-trust-badge-group {
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #f1f3f5;
    display: flex;
    flex-direction: column;
    gap: 9px;
}
.co-trust-item {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 12px;
    color: #5e7279;
}
.co-trust-item i {
    color: #c8372d;
    font-size: 13px;
    width: 16px;
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    .co-order-card {
        position: static;
        margin-top: 10px;
    }
}
@media (max-width: 576px) {
    .co-page-wrapper {
        padding-top: 85px;
    }
    .co-card-box {
        padding: 20px 16px;
    }
    .co-order-card {
        padding: 20px 16px;
    }
    .co-step-indicator {
        display: none;
    }
}
</style>

@push('scripts')
<script>
(function() {
    // Toggle Password Fields when "Create An Account?" is checked (Guests only)
    const createAccountCheck = document.getElementById('createAccountCheck');
    const passwordWrap = document.getElementById('passwordFieldsWrap');
    if (createAccountCheck && passwordWrap) {
        createAccountCheck.addEventListener('change', function() {
            passwordWrap.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Shipping Address Same as Billing Sync
    const sameCheck = document.getElementById('sameAsBilling');
    const billingName = document.getElementById('billingName');
    const billingAddress = document.getElementById('billingAddress');
    const billingCountry = document.getElementById('billingCountry');
    const billingState = document.getElementById('billingState');
    const billingCity = document.getElementById('billingCity');
    const billingPincode = document.getElementById('billingPincode');

    const shipName = document.getElementById('shipName');
    const shipAddress = document.getElementById('shipAddress');
    const shipCountry = document.getElementById('shipCountry');
    const shipState = document.getElementById('shipState');
    const shipCity = document.getElementById('shipCity');
    const shipPincode = document.getElementById('shipPincode');
    const shippingContainer = document.getElementById('shippingFieldsContainer');

    function syncShippingWithBilling() {
        if (!sameCheck || !sameCheck.checked) return;
        if (shipName && billingName) shipName.value = billingName.value;
        if (shipAddress && billingAddress) shipAddress.value = billingAddress.value;
        if (shipCountry && billingCountry) shipCountry.value = billingCountry.value;
        if (shipState && billingState) shipState.value = billingState.value;
        if (shipCity && billingCity) shipCity.value = billingCity.value;
        if (shipPincode && billingPincode) shipPincode.value = billingPincode.value;
    }

    if (sameCheck) {
        sameCheck.addEventListener('change', function() {
            if (this.checked) {
                syncShippingWithBilling();
            }
        });
    }

    [billingName, billingAddress, billingCountry, billingState, billingCity, billingPincode].forEach(input => {
        if (input) {
            input.addEventListener('input', syncShippingWithBilling);
            input.addEventListener('change', syncShippingWithBilling);
        }
    });

    if (sameCheck && sameCheck.checked) {
        syncShippingWithBilling();
    }

    // Client-side quick validation to show red errors if fields are empty
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            let hasError = false;

            const fields = [
                { name: 'name', errorId: 'nameError' },
                { name: 'phone', errorId: 'phoneError' },
                { name: 'email', errorId: 'emailError' },
                { name: 'address', errorId: 'addressError' },
                { name: 'country', errorId: 'countryError' },
                { name: 'state', errorId: 'stateError' },
                { name: 'city', errorId: 'cityError' },
                { name: 'pincode', errorId: 'pincodeError' }
            ];

            fields.forEach(f => {
                const input = checkoutForm.querySelector(`[name="${f.name}"]`);
                const err = document.getElementById(f.errorId);
                if (input) {
                    if (!input.value.trim()) {
                        hasError = true;
                        input.classList.add('co-is-invalid');
                        if (err) err.style.display = 'block';
                    } else {
                        input.classList.remove('co-is-invalid');
                        if (err) err.style.display = 'none';
                    }
                }
            });

            if (hasError) {
                e.preventDefault();
                const firstInvalid = checkoutForm.querySelector('.co-is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
            }
        });
    }
})();
</script>
@endpush
@endsection
