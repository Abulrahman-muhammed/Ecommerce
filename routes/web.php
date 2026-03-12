<?php

use App\Http\Controllers\Admin\{DashboardController, CategoryController , ProductController ,ProfileController};
// use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TagController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/dashboard',[DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['auth', 'verified']
], function () {

    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('trashed', [CategoryController::class, 'trashed'])->name('trashed');
        Route::patch('{id}/restore', [CategoryController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('force-delete');
    });


    Route::prefix('products')->name('products.')->group(function () {
        Route::get('trashed', [ProductController::class, 'trashed'])->name('trashed');
        Route::patch('{id}/restore', [ProductController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [ProductController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('products', ProductController::class);

    Route::resource('tags', TagController::class)->except(['show']);
    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('trashed', [TagController::class, 'trashed'])->name('trashed');
        Route::patch('{id}/restore', [TagController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [TagController::class, 'forceDelete'])->name('forceDelete');
        Route::delete('empty-trash',       [TagController::class, 'emptyTrash'])  ->name('emptyTrash');

    });
    Route::resource('orders', OrderController::class);

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('trashed', [OrderController::class, 'trashed'])->name('trashed');
        Route::patch('{id}/restore', [OrderController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [OrderController::class, 'forceDelete'])->name('force-delete');
    });
    Route::get('profile',  [ProfileController::class, 'edit'])  ->name('profile.edit');
    Route::put('profile',  [ProfileController::class, 'update'])->name('profile.update');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
