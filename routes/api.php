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
| Worker API Routes
|--------------------------------------------------------------------------
| Routes for static PC worker to communicate with cPanel
*/
Route::prefix('worker')->name('api.worker.')->group(function () {
    // Task progress updates
    Route::post('/tasks/{taskId}/progress', [WorkerController::class, 'updateProgress'])->name('progress');
    Route::post('/tasks/{taskId}/complete', [WorkerController::class, 'completeTask'])->name('complete');

    // Worker heartbeat and status
    Route::post('/heartbeat', [WorkerController::class, 'heartbeat'])->name('heartbeat');
    Route::get('/status/{workerId}', [WorkerController::class, 'getWorkerStatus'])->name('status');
    Route::get('/workers', [WorkerController::class, 'getAllWorkers'])->name('workers');
});

/*
|--------------------------------------------------------------------------
| Claude Server API Routes
|--------------------------------------------------------------------------
| These routes serve as the API endpoints for remote admin panels
| Protected by API token authentication
*/
// Route::middleware(['api.token'])->group(function () {

    // Admin Management API
    Route::prefix('admins')->name('api.admins.')->group(function () {
        Route::get('/', [App\Http\Controllers\API\AdminController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\API\AdminController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\API\AdminController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\API\AdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\API\AdminController::class, 'destroy'])->name('destroy');
        Route::post('/search', [App\Http\Controllers\API\AdminController::class, 'search'])->name('search');
    });

    // Project Management API
    Route::prefix('projects')->name('api.projects.')->group(function () {
        Route::get('/', [App\Http\Controllers\API\ProjectController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\API\ProjectController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\API\ProjectController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\API\ProjectController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\API\ProjectController::class, 'destroy'])->name('destroy');
        Route::post('/search', [App\Http\Controllers\API\ProjectController::class, 'search'])->name('search');
    });

    // Account Management API
    Route::prefix('accounts')->name('api.accounts.')->group(function () {
        Route::get('/', [App\Http\Controllers\API\AccountController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\API\AccountController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\API\AccountController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\API\AccountController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\API\AccountController::class, 'destroy'])->name('destroy');
        Route::post('/search', [App\Http\Controllers\API\AccountController::class, 'search'])->name('search');
    });

    // Post/Posting Management API
    Route::prefix('posts')->name('api.posts.')->group(function () {
        Route::get('/', [App\Http\Controllers\API\PostController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\API\PostController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\API\PostController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\API\PostController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\API\PostController::class, 'destroy'])->name('destroy');
        Route::post('/search', [App\Http\Controllers\API\PostController::class, 'search'])->name('search');
    });

// });
