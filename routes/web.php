<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Product Activity Routes
|--------------------------------------------------------------------------
*/

// Create product
Route::get(
    '/create-product',
    [ProductController::class, 'create']
)->name('product.create');

// Update product
Route::get(
    '/update-product',
    [ProductController::class, 'update']
)->name('product.update');

// Delete product
Route::get(
    '/delete-product',
    [ProductController::class, 'delete']
)->name('product.delete');

/*
|--------------------------------------------------------------------------
| Activity Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/activity-dashboard',
    [ProductController::class, 'dashboard']
)->name('activity.dashboard');

/*
|--------------------------------------------------------------------------
| Activity Logs
|--------------------------------------------------------------------------
*/

// Logs with search and filters
Route::get(
    '/logs',
    [ProductController::class, 'logs']
)->name('activity.logs');

// CSV export
Route::get(
    '/logs/export',
    [ProductController::class, 'exportLogs']
)->name('activity.export');

// JSON export
Route::get(
    '/logs/export-json',
    [ProductController::class, 'exportJson']
)->name('activity.export.json');

/*
|--------------------------------------------------------------------------
| Activity Log Management
|--------------------------------------------------------------------------
*/

// Delete one activity log
Route::delete(
    '/logs/{id}',
    [ProductController::class, 'destroyLog']
)->name('activity.destroy');

// Clear all logs
Route::delete(
    '/logs',
    [ProductController::class, 'clearLogs']
)->name('activity.clear');

// Rollback activity state
Route::post(
    '/logs/{id}/rollback',
    [ProductController::class, 'rollbackLog']
)->name('activity.rollback');

// Auto Prune Old Logs
Route::post(
    '/logs/prune',
    [ProductController::class, 'pruneLogs']
)->name('activity.prune');

// Export Archive Zip / CSV
Route::get(
    '/logs/export-archive',
    [ProductController::class, 'exportArchive']
)->name('activity.export-archive');

/*
|--------------------------------------------------------------------------
| Activity Details
|--------------------------------------------------------------------------
|
| Keep this route after the delete route so that
| /logs/{id} continues to work correctly.
|
*/

Route::get(
    '/logs/{id}',
    [ProductController::class, 'showLog']
)->name('activity.show');