<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Product routes - Admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('products', ProductController::class);
    });

    // Sales Order routes - Both admin and salesperson
    Route::get('/sales-orders', [SalesOrderController::class, 'index'])
        ->name('sales-orders.index');
    Route::get('/sales-orders/create', [SalesOrderController::class, 'create'])
        ->name('sales-orders.create');
    Route::post('/sales-orders', [SalesOrderController::class, 'store'])
        ->name('sales-orders.store');
    Route::get('/sales-orders/{salesOrder}', [SalesOrderController::class, 'show'])
        ->name('sales-orders.show');
    Route::get('/sales-orders/{salesOrder}/pdf', [SalesOrderController::class, 'downloadPdf'])
        ->name('sales-orders.download-pdf');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
