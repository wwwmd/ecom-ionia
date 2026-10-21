<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FollowupController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/* Products */
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])
    ->name('products.destroy')
    ->withTrashed();
Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle');

/* Orders */
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

/* Customers */
 Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
 Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

/* CRM Leads */
// Route::get('/crm/leads', [LeadController::class, 'index'])->name('leads');
// Route::get('/crm/leads/create', [LeadController::class, 'create'])->name('leads.create');
// Route::post('/crm/leads', [LeadController::class, 'store'])->name('leads.store');
// Route::get('/crm/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
// Route::post('/crm/leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');

/* CRM Followups */
// Route::get('/crm/followups', [FollowupController::class, 'index'])->name('followups');
// Route::post('/crm/followups', [FollowupController::class, 'store']);
// Route::post('/crm/followups/{followup}/complete', [FollowupController::class, 'complete']);

/* Settings */
Route::get('/settings', [SettingController::class, 'index'])->name('settings');
Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

/* Logs */
Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs');
