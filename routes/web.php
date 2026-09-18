<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Product Activity Routes
|--------------------------------------------------------------------------
*/

// Create a product
Route::get('/create-product', [ProductController::class, 'create'])
    ->name('product.create');

// Update the first product
Route::get('/update-product', [ProductController::class, 'update'])
    ->name('product.update');

// Delete the first product
Route::get('/delete-product', [ProductController::class, 'delete'])
    ->name('product.delete');


/*
|--------------------------------------------------------------------------
| Activity Log Routes
|--------------------------------------------------------------------------
*/

// Activity Log Dashboard
Route::get('/activity-dashboard', [ProductController::class, 'dashboard'])
    ->name('activity.dashboard');

// Activity Logs with search and filters
Route::get('/logs', [ProductController::class, 'logs'])
    ->name('activity.logs');

Route::get('/logs/export', [ProductController::class, 'exportLogs'])
    ->name('activity.export');

// Detailed Activity Log
Route::get('/logs/{id}', [ProductController::class, 'showLog'])
    ->name('activity.show');