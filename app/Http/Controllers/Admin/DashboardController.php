<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'revenue' => Order::whereIn('status', ['delivered','shipped','processing'])->sum('grand_total'),
            'orders' => Order::count(),
            'customers' => User::withRole('customer')->count(),
            'leads' => Lead::whereIn('status', ['new','contacted','interested','negotiation'])->count(),
            'today' => Order::whereDate('created_at', today())->sum('grand_total'),
            'pending' => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'agents' => User::withRole('agent')->count(),
            'products' => Product::count(),
            'low_stock' => Product::lowStock()->count(),
            'followups' => LeadFollowup::whereDate('scheduled_at', today())->where('status', 'pending')->count(),
        ];

        $recentOrders = Order::latest()->take(5)->get();
        $recentLeads = Lead::latest()->take(4)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentLeads'));
    }
}
