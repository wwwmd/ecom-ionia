<?php

use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/invoice', [CustomerOrderController::class, 'invoice'])->name('orders.invoice');

Route::get('/profile', fn () => view('customer.profile'))->name('profile');
Route::get('/addresses', fn () => view('customer.addresses'))->name('addresses');
Route::get('/support', fn () => view('customer.support'))->name('support');
