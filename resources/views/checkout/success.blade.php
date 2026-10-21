@extends('layouts.frontend')
@section('title', 'Order Confirmed — store Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="z-card text-center">
                <div style="width:80px;height:80px;border-radius:50%;background:var(--z-gradient);margin:0 auto 20px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:38px">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h2 style="font-size:26px;margin-bottom:8px">Order Placed Successfully! 🎉</h2>
                <p class="text-muted">Thank you for shopping with store.</p>

                <div class="z-card" style="background:var(--z-light);text-align:left;margin-top:20px">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Number</span>
                        <strong>{{ $order->order_number }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Amount</span>
                        <strong>₹{{ number_format($order->grand_total) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Payment Method</span>
                        <strong>{{ strtoupper($order->payment_method) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Status</span>
                        <span class="z-status z-status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ url('/customer/orders/'.$order->id) }}" class="z-btn-primary w-100">
                        View Order <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ url('/products') }}" class="z-btn-ghost w-100 justify-content-center">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
