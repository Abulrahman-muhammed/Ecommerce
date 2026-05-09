<?php

use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\ProductController as FrontProductController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\OrderController as FrontOrderController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
*/
Route::prefix('front')->name('front.')->group(function () {

    // Products
    Route::get('products',              [FrontProductController::class, 'index'])->name('products.index');
    Route::get('products/{product:slug}', [FrontProductController::class, 'show']) ->name('products.show');

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/',                  [CartController::class, 'index'])  ->name('index');
        Route::post('/add/{product}',    [CartController::class, 'store'])  ->name('store');
        Route::post('/update/{product}', [CartController::class, 'update']) ->name('update');
        Route::delete('/remove/{product}',[CartController::class, 'destroy'])->name('destroy');
        Route::delete('/clear',          [CartController::class, 'clear'])  ->name('clear');
    });

    // Payment callbacks (Stripe)
    Route::prefix('orders/{order}/payment')->name('orders.payment.')->group(function () {
        Route::get('success', [PaymentController::class, 'success'])->name('success');
        Route::get('cancel',  [PaymentController::class, 'cancel']) ->name('cancel');
    });

    // Checkout & Orders
    Route::get('checkout',      [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout',     [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('orders/{order}',[FrontOrderController::class, 'show'])->name('orders.show');

    // Social Login (Socialite)
    Route::prefix('socialite')->name('socialite.')->controller(SocialiteController::class)->group(function () {
        Route::get('/{provider}/login',    'redirect')->name('login');
        Route::get('/{provider}/redirect', 'callback')->name('redirect');
    });
});

require __DIR__.'/admin.php';
require __DIR__ . '/auth.php';