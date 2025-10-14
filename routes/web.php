<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AmazonReviewController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
===================================================
                  ADMIN
===================================================
*/
// Authentication Routes
Route::controller(AccountController::class)->group(function() {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'loginSubmit')->name('loginSubmit');
    Route::get('/logout', 'logoutSubmit')->name('logoutSubmit');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    // Dashboard
    Route::controller(DashboardController::class)->name("dashboard.")->group(function() {
        Route::get('/', 'dashboard')->name('index');

        // AJAX endpoints for instant loading
        Route::get('/ajax/statistics', 'getStatistics')->name('ajax.statistics');
        Route::get('/ajax/project-status', 'getProjectStatus')->name('ajax.projectStatus');
        Route::get('/ajax/accounts-by-type', 'getAccountsByType')->name('ajax.accountsByType');
        Route::get('/ajax/recent-activities', 'getRecentActivities')->name('ajax.recentActivities');
        Route::get('/ajax/reviews-per-day', 'getReviewsPerDay')->name('ajax.reviewsPerDay');
    });

    // Amazon Review Management
    Route::controller(AmazonReviewController::class)->name("review.")->prefix('review')->group(function() {
        // Accounts
        Route::any('/accounts', 'accounts')->name('accounts');
        Route::any('/accounts-add', 'accountsAdd')->name('accountsAdd');
        Route::any('/accounts-edit/{account}', 'accountsEdit')->name('accountsEdit');
        Route::any('/accounts-delete', 'accountsDelete')->name('accountsDelete');
        Route::any('/accounts-add-cart', 'accountsAddCart')->name('accountsAddCart');

        // Projects
        Route::get('/projects', 'projects')->name('projects'); // Redirects to pending
        Route::get('/projects-pending', 'projectsPending')->name('projectsPending');
        Route::get('/projects-archive', 'projectsArchive')->name('projectsArchive');
        Route::any('/projects-add', 'projectsAdd')->name('projectsAdd');
        Route::any('/projects-edit/{account}', 'projectsEdit')->name('projectsEdit');
        Route::any('/projects-delete', 'projectsDelete')->name('projectsDelete');
        Route::any('/stop-review/{project_id}', 'stopReview')->name('stopReview');
        Route::any('/find-project', 'findProject')->name('findProject');

        // Submit & Review
        Route::get('/submit', 'submit')->name('submit');
        Route::get('/start-review', 'startReview')->name('startReview');
        Route::get('/clear-history', 'clearHistory')->name('clearHistory');
        Route::get('/check-review/{review_id}', 'checkReview')->name('checkReview');
        Route::get('/check-account/{account_id}', 'checkAccount')->name('checkAccount');
        Route::post('/update-project/{id}', 'updateProject')->name('updateProject');

        // Book Data Fetching (Educational Purpose)
        Route::get('/test-book-fetch', function() {
            return view('admin.pages.reviews.test-book-fetch');
        })->name('testBookFetch');
        Route::post('/fetch-book-data', 'fetchBookData')->name('fetchBookData');
    });

    // Client Management Routes
    Route::controller(ClientController::class)->name("clients.")->prefix('clients')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{client}/edit', 'edit')->name('edit');
        Route::put('/{client}', 'update')->name('update');
        Route::delete('/{client}', 'destroy')->name('destroy');
        Route::post('/{client}/toggle-status', 'toggleStatus')->name('toggleStatus');
    });

    // Task Management Routes
    Route::controller(\App\Http\Controllers\Admin\TaskController::class)->name("task.")->prefix('task')->group(function() {
        Route::post('/start-review', 'startReview')->name('startReview');
        Route::get('/status/{taskId}', 'getTaskStatus')->name('status');
        Route::get('/review/{reviewId}/status', 'getReviewTaskStatus')->name('reviewStatus');
        Route::post('/retry/{taskId}', 'retryTask')->name('retry');
    });
});
