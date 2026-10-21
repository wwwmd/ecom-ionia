<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\WishlistController;
use Illuminate\Support\Facades\Route;

/* Frontend */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('products', [HomeController::class, 'products'])->name('products');
Route::get('about-us', [HomeController::class, 'about'])->name('about-us');
Route::get('contact-us', [HomeController::class, 'contact'])->name('contact-us');
Route::get('product-details/{slug?}', [HomeController::class, 'producDetail'])->name('product-details');
Route::get('product/{slug}', [HomeController::class, 'producDetail'])->name('product.slug');
Route::get('checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buy-now');
Route::post('/buy-now', [CartController::class, 'buyNow'])->name('buy-now');

/* Auth - SINGLE LOGIN */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/* AJAX */
Route::prefix('api')->group(function () {
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::post('/cart/buy-now', [CartController::class, 'buyNow']);
    Route::post('/cart/update', [CartController::class, 'update']);
    Route::post('/cart/remove', [CartController::class, 'remove']);
    Route::get('/cart/count', [CartController::class, 'count']);
    Route::get('/cart/data', [CartController::class, 'data']);
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon']);

    Route::middleware('auth')->group(function () {
        Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);
        Route::get('/wishlist/count', [WishlistController::class, 'count']);
    });
});

/* Checkout */
Route::post('/checkout/place', [CheckoutController::class, 'place'])->name('checkout.place');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});

/* Customer */
Route::middleware(['auth', 'active', 'role:customer'])
    ->prefix('customer')->name('customer.')
    ->group(base_path('routes/customer.php'));

/* Admin */
Route::middleware(['auth', 'active', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(base_path('routes/admin.php'));

/* Agent */
Route::middleware(['auth', 'active', 'role:agent'])
    ->prefix('agent')->name('agent.')
    ->group(base_path('routes/agent.php'));
