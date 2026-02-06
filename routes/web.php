<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

// Route to create a new product and generate activity log
Route::get('/create-product', [ProductController::class, 'create']);

// Route to update the first product and log the changes
Route::get('/update-product', [ProductController::class, 'update']);

// Route to delete the first product and record the deletion log
Route::get('/delete-product', [ProductController::class, 'delete']);

// Route to display all activity logs in a table view
Route::get('/logs', [ProductController::class, 'logs']);
