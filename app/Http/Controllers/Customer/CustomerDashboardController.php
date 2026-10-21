<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Order::where('user_id', $user->id);

        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'completed' => (clone $query)->where('status', 'delivered')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
        ];

        $recentOrders = (clone $query)->latest()->take(5)->get();

        return view('customer.dashboard', compact('stats', 'recentOrders'));
    }
}
