<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Admin Auth (guest / unauthenticated)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])        ->name('login.submit');

    Route::post('logout', [LoginController::class, 'logout'])
        ->middleware('auth:admin')
        ->name('logout');
});


/*
|--------------------------------------------------------------------------
| Admin Authenticated Routes
|--------------------------------------------------------------------------
*/
    // Dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->middleware('auth:admin')->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {



    /*
    |----------------------------------------------------------------------
    | Categories
    |----------------------------------------------------------------------
    */
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('trashed',             [CategoryController::class, 'trashed'])    ->name('trashed');
        Route::patch('{id}/restore',      [CategoryController::class, 'restore'])    ->name('restore');
        Route::delete('{id}/force-delete',[CategoryController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('categories', CategoryController::class)->except(['show']);

    /*
    |----------------------------------------------------------------------
    | Products
    |----------------------------------------------------------------------
    */
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('trashed',             [AdminProductController::class, 'trashed'])    ->name('trashed');
        Route::patch('{id}/restore',      [AdminProductController::class, 'restore'])    ->name('restore');
        Route::delete('{id}/force-delete',[AdminProductController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('products', AdminProductController::class);

    /*
    |----------------------------------------------------------------------
    | Tags
    |----------------------------------------------------------------------
    */
    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('trashed',             [TagController::class, 'trashed'])    ->name('trashed');
        Route::patch('{id}/restore',      [TagController::class, 'restore'])    ->name('restore');
        Route::delete('{id}/force-delete',[TagController::class, 'forceDelete'])->name('forceDelete');
        Route::delete('empty-trash',      [TagController::class, 'emptyTrash']) ->name('emptyTrash');
    });
    Route::resource('tags', TagController::class)->except(['show']);

    /*
    |----------------------------------------------------------------------
    | Orders
    |----------------------------------------------------------------------
    */
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('trashed',             [OrderController::class, 'trashed'])    ->name('trashed');
        Route::patch('{id}/restore',      [OrderController::class, 'restore'])    ->name('restore');
        Route::delete('{id}/force-delete',[OrderController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('orders', OrderController::class);

    /*
    |----------------------------------------------------------------------
    | Payments
    |----------------------------------------------------------------------
    */
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('trashed',             [AdminPaymentController::class, 'trashed'])    ->name('trashed');
        Route::patch('{id}/restore',      [AdminPaymentController::class, 'restore'])    ->name('restore');
        Route::delete('{id}/force-delete',[AdminPaymentController::class, 'forceDelete'])->name('force-delete');

        Route::get('/',           [AdminPaymentController::class, 'index'])  ->name('index');
        Route::get('{payment}',   [AdminPaymentController::class, 'show'])   ->name('show');
        Route::put('{payment}',   [AdminPaymentController::class, 'update']) ->name('update');
        Route::delete('{payment}',[AdminPaymentController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Customers
    |----------------------------------------------------------------------
    */
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('trashed',             [CustomerController::class, 'trashed'])    ->name('trashed');
        Route::patch('{id}/restore',      [CustomerController::class, 'restore'])    ->name('restore');
        Route::delete('{id}/force-delete',[CustomerController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('customers', CustomerController::class);

    /*
    |----------------------------------------------------------------------
    | Notifications
    |----------------------------------------------------------------------
    */
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',          [NotificationController::class, 'index'])  ->name('index');
        Route::post('read-all',  [NotificationController::class, 'readAll'])->name('readAll');
        Route::post('read-one',  [NotificationController::class, 'readOne'])->name('readOne');
    });

    /*
    |----------------------------------------------------------------------
    | Profile
    |----------------------------------------------------------------------
    */
    Route::get('profile', [ProfileController::class, 'edit'])  ->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});