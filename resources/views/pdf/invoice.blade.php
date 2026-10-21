<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
    .header { border-bottom: 3px solid #2E6DB4; padding-bottom: 15px; margin-bottom: 20px; }
    .brand { font-size: 26px; font-weight: bold; color: #2E6DB4; }
    .brand span { color: #26A69A; }
    .tagline { color: #666; font-size: 11px; }
    .invoice-title { text-align: right; }
    .invoice-title h2 { color: #2E6DB4; margin: 0; font-size: 22px; }
    table { width: 100%; border-collapse: collapse; margin: 15px 0; }
    th, td { padding: 8px; text-align: left; border-bottom: 1px solid #E3E9F2; }
    th { background: #F5F8FC; font-size: 11px; text-transform: uppercase; color: #555; }
    .text-right { text-align: right; }
    .totals td { padding: 6px 8px; }
    .grand { font-weight: bold; color: #2E6DB4; font-size: 14px; border-top: 2px solid #2E6DB4; }
    .footer { margin-top: 30px; text-align: center; color: #888; font-size: 10px; border-top: 1px solid #ddd; padding-top: 10px; }
</style>
</head>
<body>

<div class="header">
    <table>
        <tr>
            <td>
                <div class="brand">store<span>Store</span></div>
                <div class="tagline">Think Beyond... Do Beyond</div>
                <div class="tagline">hello@store.com · +91 99999 99999</div>
            </td>
            <td class="invoice-title">
                <h2>INVOICE</h2>
                <div><strong>#{{ $order->order_number }}</strong></div>
                <div>{{ $order->created_at->format('d M Y') }}</div>
            </td>
        </tr>
    </table>
</div>

<table>
    <tr>
        <td width="50%">
            <strong>Bill To:</strong><br>
            {{ $order->customer_name }}<br>
            {{ $order->customer_email }}<br>
            {{ $order->customer_phone }}
        </td>
        <td width="50%">
            <strong>Ship To:</strong><br>
            @if($order->shipping_address)
                {{ $order->shipping_address['line1'] ?? '' }}<br>
                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}<br>
                {{ $order->shipping_address['pincode'] ?? '' }}
            @endif
        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Price</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td class="text-right">{{ $item->quantity }}</td>
            <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
            <td class="text-right">₹{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table class="totals" style="width: 40%; margin-left: auto">
    <tr><td>Subtotal</td><td class="text-right">₹{{ number_format($order->subtotal, 2) }}</td></tr>
    <tr><td>Discount</td><td class="text-right">−₹{{ number_format($order->discount, 2) }}</td></tr>
    <tr><td>Tax</td><td class="text-right">₹{{ number_format($order->tax, 2) }}</td></tr>
    <tr><td>Shipping</td><td class="text-right">₹{{ number_format($order->shipping_cost, 2) }}</td></tr>
    <tr class="grand"><td>Grand Total</td><td class="text-right">₹{{ number_format($order->grand_total, 2) }}</td></tr>
</table>

<div class="footer">
    Thank you for your business! · store Technologies · www.store.com
</div>

</body>
</html>
