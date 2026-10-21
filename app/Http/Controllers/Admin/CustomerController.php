<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::withRole('customer')
            ->with('customerProfile')
            ->latest()
            ->paginate(25);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        abort_unless($customer->isCustomer(), 404);

        $customer->load([
            'orders' => fn ($q) => $q->latest()->take(10),
            'addresses',
            'reviews',
        ]);

        return view('admin.customers.show', compact('customer'));
    }
}
