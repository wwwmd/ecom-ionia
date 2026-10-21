<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        $order->load('items.product', 'statusHistory', 'invoice');

        return view('customer.order-show', compact('order'));
    }

    public function invoice(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        $order->load('items', 'invoice', 'user');

        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        return $pdf->download("Invoice-{$order->order_number}.pdf");
    }
}
