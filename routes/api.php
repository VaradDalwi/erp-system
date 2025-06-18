<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductApiController;
use App\Http\Controllers\API\SalesOrderApiController;

Route::middleware('auth:sanctum')->group(function () {
    // Product endpoints
    Route::get('/products', [ProductApiController::class, 'index']);

    // Sales Order endpoints
    Route::post('/sales-orders', [SalesOrderApiController::class, 'store']);
    Route::get('/sales-orders/{id}', [SalesOrderApiController::class, 'show']);
});
