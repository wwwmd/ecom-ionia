<?php

use App\Http\Controllers\Agent\AgentDashboardController;
use App\Http\Controllers\Agent\AgentLeadController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
Route::get('/leads', [AgentLeadController::class, 'index'])->name('leads');
Route::get('/leads/{lead}', [AgentLeadController::class, 'show'])->name('leads.show');

Route::get('/followups', fn () => view('agent.followups.index'))->name('followups');
Route::get('/customers', fn () => view('agent.customers.index'))->name('customers');
Route::get('/orders', fn () => view('agent.orders.index'))->name('orders');
Route::get('/commission', fn () => view('agent.commission.index'))->name('commission');
Route::get('/profile', fn () => view('agent.profile'))->name('profile');
