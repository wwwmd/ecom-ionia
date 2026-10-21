<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $service) {}

    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->payment, fn ($q, $s) => $q->where('payment_status', $s))
            ->when($request->from, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
            ->when($request->to, fn ($q, $d) => $q->whereDate('created_at', '<=', $d))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user', 'agent', 'statusHistory', 'payments', 'invoice']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,packed,shipped,out_for_delivery,delivered,cancelled,returned,refunded',
            'note' => 'nullable|string|max:255',
        ]);

        $this->service->updateStatus($order, $data['status'], $data['note'] ?? null, auth()->user());

        ActivityLog::record('orders', 'update_status', "Order {$order->order_number} → {$data['status']}");

        return response()->json(['success' => true]);
    }
}
