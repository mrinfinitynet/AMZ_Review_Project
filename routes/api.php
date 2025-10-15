<?php

use App\Http\Controllers\API\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Claude Server API Routes
|--------------------------------------------------------------------------
| These routes serve as the API endpoints for remote admin panels
| Protected by API token authentication
*/

// Amazon Review Accounts API
Route::prefix('amazon-review-accounts')->name('api.amazon-review-accounts.')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\AmazonReviewAccountApiController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Api\AmazonReviewAccountApiController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\Api\AmazonReviewAccountApiController::class, 'show'])->name('show');
    Route::put('/{id}', [App\Http\Controllers\Api\AmazonReviewAccountApiController::class, 'update'])->name('update');
    Route::patch('/{id}', [App\Http\Controllers\Api\AmazonReviewAccountApiController::class, 'update'])->name('patch');
    Route::delete('/{id}', [App\Http\Controllers\Api\AmazonReviewAccountApiController::class, 'destroy'])->name('destroy');

    // Bulk operations
    Route::post('/bulk-delete', [App\Http\Controllers\Api\AmazonReviewAccountApiController::class, 'bulkDelete'])->name('bulkDelete');
    Route::post('/bulk-update', [App\Http\Controllers\Api\AmazonReviewAccountApiController::class, 'bulkUpdate'])->name('bulkUpdate');
});

// Amazon Review Projects API
Route::prefix('amazon-review-projects')->name('api.amazon-review-projects.')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\AmazonReviewProjectApiController::class, 'index'])->name('index');
    Route::get('/grouped', [App\Http\Controllers\Api\AmazonReviewProjectApiController::class, 'grouped'])->name('grouped');
    Route::post('/', [App\Http\Controllers\Api\AmazonReviewProjectApiController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\Api\AmazonReviewProjectApiController::class, 'show'])->name('show');
    Route::put('/{id}', [App\Http\Controllers\Api\AmazonReviewProjectApiController::class, 'update'])->name('update');
    Route::patch('/{id}', [App\Http\Controllers\Api\AmazonReviewProjectApiController::class, 'update'])->name('patch');
    Route::delete('/{id}', [App\Http\Controllers\Api\AmazonReviewProjectApiController::class, 'destroy'])->name('destroy');

    // Special operations
    Route::post('/update-status', [App\Http\Controllers\Api\AmazonReviewProjectApiController::class, 'updateStatus'])->name('updateStatus');

    // Bulk operations
    Route::post('/bulk-delete', [App\Http\Controllers\Api\AmazonReviewProjectApiController::class, 'bulkDelete'])->name('bulkDelete');
    Route::post('/bulk-update', [App\Http\Controllers\Api\AmazonReviewProjectApiController::class, 'bulkUpdate'])->name('bulkUpdate');
});

// Amazon Review Project History API
Route::prefix('amazon-review-project-histories')->name('api.amazon-review-project-histories.')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\AmazonReviewProjectHistoryApiController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Api\AmazonReviewProjectHistoryApiController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\Api\AmazonReviewProjectHistoryApiController::class, 'show'])->name('show');
    Route::put('/{id}', [App\Http\Controllers\Api\AmazonReviewProjectHistoryApiController::class, 'update'])->name('update');
    Route::patch('/{id}', [App\Http\Controllers\Api\AmazonReviewProjectHistoryApiController::class, 'update'])->name('patch');
    Route::delete('/{id}', [App\Http\Controllers\Api\AmazonReviewProjectHistoryApiController::class, 'destroy'])->name('destroy');

    // Special operations
    Route::post('/clear', [App\Http\Controllers\Api\AmazonReviewProjectHistoryApiController::class, 'clearHistory'])->name('clearHistory');

    // Bulk operations
    Route::post('/bulk-delete', [App\Http\Controllers\Api\AmazonReviewProjectHistoryApiController::class, 'bulkDelete'])->name('bulkDelete');
    Route::post('/bulk-update', [App\Http\Controllers\Api\AmazonReviewProjectHistoryApiController::class, 'bulkUpdate'])->name('bulkUpdate');
});

// Clients API
Route::prefix('clients')->name('api.clients.')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\ClientApiController::class, 'index'])->name('index');
    Route::get('/active', [App\Http\Controllers\Api\ClientApiController::class, 'active'])->name('active');
    Route::post('/', [App\Http\Controllers\Api\ClientApiController::class, 'store'])->name('store');
    Route::get('/by-code/{code}', [App\Http\Controllers\Api\ClientApiController::class, 'showByCode'])->name('showByCode');
    Route::get('/by-key/{key}', [App\Http\Controllers\Api\ClientApiController::class, 'showByKey'])->name('showByKey');
    Route::get('/{id}', [App\Http\Controllers\Api\ClientApiController::class, 'show'])->name('show');
    Route::put('/{id}', [App\Http\Controllers\Api\ClientApiController::class, 'update'])->name('update');
    Route::patch('/{id}', [App\Http\Controllers\Api\ClientApiController::class, 'update'])->name('patch');
    Route::delete('/{id}', [App\Http\Controllers\Api\ClientApiController::class, 'destroy'])->name('destroy');

    // Special operations
    Route::post('/verify-key', [App\Http\Controllers\Api\ClientApiController::class, 'verifyKey'])->name('verifyKey');
    Route::post('/{id}/generate-key', [App\Http\Controllers\Api\ClientApiController::class, 'generateKey'])->name('generateKey');
    Route::post('/{id}/remove-key', [App\Http\Controllers\Api\ClientApiController::class, 'removeKey'])->name('removeKey');
    Route::post('/{id}/toggle-status', [App\Http\Controllers\Api\ClientApiController::class, 'toggleStatus'])->name('toggleStatus');
    Route::post('/{id}/track-access', [App\Http\Controllers\Api\ClientApiController::class, 'trackAccess'])->name('trackAccess');
});

/*
|--------------------------------------------------------------------------
| Universal Service API Routes
|--------------------------------------------------------------------------
| Dynamic API that works with any table
| Single endpoint to CRUD any data from any allowed table
*/

// Get available tables
Route::get('/service/tables', [App\Http\Controllers\Api\UniversalServiceController::class, 'tables'])->name('api.service.tables');

// Service configuration and testing
Route::get('/service/config', [App\Http\Controllers\Api\UniversalServiceController::class, 'config'])->name('api.service.config');
Route::get('/service/test-connection', [App\Http\Controllers\Api\UniversalServiceController::class, 'testConnection'])->name('api.service.testConnection');

// Table operations
Route::prefix('service/{table}')->name('api.service.')->group(function () {
    // Get table structure
    Route::get('/structure', [App\Http\Controllers\Api\UniversalServiceController::class, 'structure'])->name('structure');

    // Get count
    Route::get('/count', [App\Http\Controllers\Api\UniversalServiceController::class, 'count'])->name('count');

    // Custom query
    Route::post('/query', [App\Http\Controllers\Api\UniversalServiceController::class, 'customQuery'])->name('query');

    // Bulk operations
    Route::post('/bulk-delete', [App\Http\Controllers\Api\UniversalServiceController::class, 'bulkDelete'])->name('bulkDelete');
    Route::post('/bulk-update', [App\Http\Controllers\Api\UniversalServiceController::class, 'bulkUpdate'])->name('bulkUpdate');

    // Standard CRUD
    Route::get('/', [App\Http\Controllers\Api\UniversalServiceController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Api\UniversalServiceController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\Api\UniversalServiceController::class, 'show'])->name('show');
    Route::put('/{id}', [App\Http\Controllers\Api\UniversalServiceController::class, 'update'])->name('update');
    Route::patch('/{id}', [App\Http\Controllers\Api\UniversalServiceController::class, 'update'])->name('patch');
    Route::delete('/{id}', [App\Http\Controllers\Api\UniversalServiceController::class, 'destroy'])->name('destroy');
});
