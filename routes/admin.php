<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ReportController;

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
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/',              [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('index');
            Route::get('/uncategorized', [\App\Http\Controllers\Admin\TransactionController::class, 'uncategorized'])->name('uncategorized');
        });

        // Businesses
        Route::resource('businesses', \App\Http\Controllers\Admin\BusinessController::class)
            ->names('businesses');

        // Categories
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)
            ->names('categories');

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

        // Settings & Logs
        Route::get('settings', fn() => view('admin.settings.index'))->name('settings');
        Route::get('logs',     fn() => view('admin.logs.index'))->name('logs');
    });
});
