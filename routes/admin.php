<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\ContactQueryController;

/*
|--------------------------------------------------------------------------
| Admin Routes — /admin prefix
|--------------------------------------------------------------------------
|
| Yeh routes web.php mein include karo ya Laravel 13 mein
| bootstrap/app.php mein register karo.
|
*/

Route::name('admin.')->group(function () {

    // ── Public (bina login ke) ──────────────────────────────────────────
    Route::middleware('guest:admin')->group(function () {
        Route::get('login',  [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // ── Protected (login required) ──────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::patch('users/{id}/toggle-status', [UserManagementController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Logout
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [AdminDashboardController::class, 'index']);

        // Users
        Route::resource('users', UserManagementController::class)->names([
            'index'   => 'users.index',
            'create'  => 'users.create',
            'store'   => 'users.store',
            'show'    => 'users.show',
            'edit'    => 'users.edit',
            'update'  => 'users.update',
            'destroy' => 'users.destroy',
        ]);

        // Transactions
        Route::resource('transactions', \App\Http\Controllers\Admin\TransactionController::class)
            ->names('transactions');
        Route::get('transactions/uncategorized', [\App\Http\Controllers\Admin\TransactionController::class, 'uncategorized'])->name('transactions.uncategorized');

        // Businesses
        Route::resource('businesses', \App\Http\Controllers\Admin\BusinessController::class)
            ->names('businesses');
        Route::post('businesses/{id}/toggle', [\App\Http\Controllers\Admin\BusinessController::class, 'toggle'])->name('businesses.toggle');

        // Categories
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)
            ->names('categories');
        Route::post('categories/{id}/toggle', [\App\Http\Controllers\Admin\CategoryController::class, 'toggle'])->name('categories.toggle');

        // Budgets
        Route::resource('budgets', \App\Http\Controllers\Admin\BudgetController::class)
            ->names('budgets');

        // Bills
        Route::resource('bills', \App\Http\Controllers\Admin\BillController::class)
            ->names('bills');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/monthly',  [ReportController::class, 'monthly'])->name('monthly');
            Route::get('/yearly',   [ReportController::class, 'yearly'])->name('yearly');
            Route::get('/export',   [ReportController::class, 'export'])->name('export');
        });

        // Static Pages
        Route::resource('static-pages', StaticPageController::class)->except(['show'])->names([
            'index' => 'static-pages.index',
            'create' => 'static-pages.create',
            'store' => 'static-pages.store',
            'edit' => 'static-pages.edit',
            'update' => 'static-pages.update',
            'destroy' => 'static-pages.destroy',
        ]);
        Route::post('static-pages/{id}/toggle', [StaticPageController::class, 'toggle'])->name('static-pages.toggle');

        // Contact queries
        Route::get('contact-queries', [ContactQueryController::class, 'index'])->name('contact-queries.index');
        Route::get('contact-queries/{id}', [ContactQueryController::class, 'show'])->name('contact-queries.show');
        Route::post('contact-queries/{id}/status', [ContactQueryController::class, 'updateStatus'])->name('contact-queries.updateStatus');
        Route::delete('contact-queries/{id}', [ContactQueryController::class, 'destroy'])->name('contact-queries.destroy');

        // Settings & Logs
        Route::get('settings', fn() => view('admin.settings.index'))->name('settings');
        Route::get('logs',     fn() => view('admin.logs.index'))->name('logs');
    });
});
