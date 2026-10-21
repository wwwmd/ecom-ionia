@extends('layouts.frontend')
@section('title', 'Checkout — store Store')

@section('content')
<div class="container py-4">
    <h1 class="z-page-title">Checkout</h1>

    <div class="z-steps">
        <div class="z-step active" data-step="1"><span>1</span> Information</div>
        <div class="z-step" data-step="2"><span>2</span> Shipping</div>
        <div class="z-step" data-step="3"><span>3</span> Payment</div>
        <div class="z-step" data-step="4"><span>4</span> Review</div>
    </div>

    <form id="checkoutForm" action="{{ url('/checkout/place') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">

                <div class="z-step-panel active" data-step="1">
                    <div class="z-card">
                        <h5>Customer Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Full Name *</label>
                                <input type="text" name="name" class="z-input" value="{{ auth()->user()->name ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Email *</label>
                                <input type="email" name="email" class="z-input" value="{{ auth()->user()->email ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Phone *</label>
                                <input type="tel" name="phone" class="z-input" value="{{ auth()->user()->phone ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="z-step-panel" data-step="2">
                    <div class="z-card">
                        <h5>Shipping Address</h5>
                        <div class="row g-3">
                            <div class="col-12"><label>Address Line 1 *</label><input type="text" name="address1" class="z-input" required></div>
                            <div class="col-12"><label>Address Line 2</label><input type="text" name="address2" class="z-input"></div>
                            <div class="col-md-6"><label>City *</label><input type="text" name="city" class="z-input" required></div>
                            <div class="col-md-6"><label>State *</label><input type="text" name="state" class="z-input" required></div>
                            <div class="col-md-6"><label>Pincode *</label><input type="text" name="pincode" class="z-input" required></div>
                            <div class="col-md-6"><label>Country *</label><input type="text" name="country" class="z-input" value="India" required></div>
                        </div>

                        <hr>
                        <h5 class="mt-3">Delivery Method</h5>
                        <label class="z-radio">
                            <input type="radio" name="shipping_method" value="standard" checked>
                            <span><strong>Standard</strong> — 3-5 days (₹49)</span>
                        </label>
                        <label class="z-radio">
                            <input type="radio" name="shipping_method" value="express">
                            <span><strong>Express</strong> — 1-2 days (₹99)</span>
                        </label>
                    </div>
                </div>

                <div class="z-step-panel" data-step="3">
                    <div class="z-card">
                        <h5>Payment Method</h5>
                        <label class="z-radio">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <span><strong>Cash on Delivery</strong> — Pay when delivered</span>
                        </label>
                        <label class="z-radio">
                            <input type="radio" name="payment_method" value="upi">
                            <span><strong>UPI</strong> — Pay via UPI app</span>
                        </label>
                        <label class="z-radio">
                            <input type="radio" name="payment_method" value="card">
                            <span><strong>Card</strong> — Credit / Debit</span>
                        </label>
                        <label class="z-radio">
                            <input type="radio" name="payment_method" value="netbanking">
                            <span><strong>Net Banking</strong></span>
                        </label>
                    </div>
                </div>

                <div class="z-step-panel" data-step="4">
                    <div class="z-card">
                        <h5>Review Your Order</h5>
                        <div id="reviewSummary"></div>
                    </div>
                </div>

                <div class="z-step-nav">
                    <button type="button" class="z-btn-ghost" id="prevStep" style="display:none">Back</button>
                    <button type="button" class="z-btn-primary" id="nextStep">Continue</button>
                    <button type="submit" class="z-btn-primary d-none" id="placeOrder">Place Order</button>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="z-summary">
                    <h5>Summary</h5>
                    <div class="z-summary-row"><span>Subtotal</span><span>₹{{ number_format($subtotal) }}</span></div>
                    <div class="z-summary-row"><span>Shipping</span><span>₹{{ number_format($shipping) }}</span></div>
                    <div class="z-summary-row"><span>Tax</span><span>₹{{ number_format($tax) }}</span></div>
                    <hr>
                    <div class="z-summary-row z-total"><span>Total</span><span>₹{{ number_format($grandTotal) }}</span></div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    let currentStep = 1;
    const totalSteps = 4;

    function renderStep() {
        $('.z-step-panel').removeClass('active');
        $('.z-step-panel[data-step="' + currentStep + '"]').addClass('active');
        $('.z-step').each(function () {
            const n = +$(this).data('step');
            $(this).toggleClass('active', n === currentStep).toggleClass('completed', n < currentStep);
        });
        $('#prevStep').toggle(currentStep > 1);
        $('#nextStep').toggle(currentStep < totalSteps);
        $('#placeOrder').toggleClass('d-none', currentStep !== totalSteps);
        if (currentStep === totalSteps) buildReview();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
        let valid = true;
        $('.z-step-panel[data-step="' + step + '"] [required]').each(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid').css('border-color', '#DC2626');
                valid = false;
            } else {
                $(this).removeClass('is-invalid').css('border-color', '');
            }
        });
        if (!valid) zToast('Please fill all required fields', 'error');
        return valid;
    }

    function buildReview() {
        const d = new FormData(document.getElementById('checkoutForm'));
        let html = '<div style="padding:12px 0;border-bottom:1px solid var(--z-border)"><strong>Contact</strong><br>' + (d.get('name') || '') + ' · ' + (d.get('email') || '') + ' · ' + (d.get('phone') || '') + '</div>';
        html += '<div style="padding:12px 0;border-bottom:1px solid var(--z-border)"><strong>Ship to</strong><br>' + [d.get('address1'), d.get('address2'), d.get
