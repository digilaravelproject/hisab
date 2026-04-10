<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PublicContentController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\CacheController;
use App\Http\Controllers\Api\BankAccountController;
use App\Http\Controllers\Api\SetuWebhookController;
/*
|--------------------------------------------------------------------------
| Public Routes (Authentication required nahin)
|--------------------------------------------------------------------------
*/

Route::post('/setu/webhook', [SetuWebhookController::class, 'handle']);
Route::prefix('v1')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('send-otp',    [AuthController::class, 'sendOtp']);
        Route::post('verify-otp',  [AuthController::class, 'verifyOtp']);
    });

    /*
    |----------------------------------------------------------------------
    | Protected Routes (Sanctum Token required)
    |----------------------------------------------------------------------
    */


    // Public static content
    Route::get('static/privacy-policy',    [PublicContentController::class, 'privacyPolicy']);
    Route::get('static/terms',             [PublicContentController::class, 'termsAndConditions']);
    Route::get('static/faqs',              [PublicContentController::class, 'faqs']);
    Route::post('contact-us',              [PublicContentController::class, 'contactUs']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('bank-accounts',        [BankAccountController::class, 'index']);
        Route::post('bank-accounts',       [BankAccountController::class, 'store']);
        Route::put('bank-accounts/{id}',   [BankAccountController::class, 'update']);
        Route::delete('bank-accounts/{id}', [BankAccountController::class, 'destroy']);

        Route::get('profile',         [ProfileController::class, 'show']);
        Route::post('profile/update', [ProfileController::class, 'update']);
        Route::post('profile/user-types', [ProfileController::class, 'updateUserTypes']);
        // Auth
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Transactions
        Route::prefix('transactions')->group(function () {
            Route::get('/',              [TransactionController::class, 'index']);
            Route::post('/',             [TransactionController::class, 'store']);
            Route::get('/summary',       [TransactionController::class, 'summary']);
            Route::get('/dashboard',     [TransactionController::class, 'dashboard']);
            Route::get('/download/csv',  [ReportController::class, 'downloadTransactionsCsv']);
            Route::get('/download/pdf',  [ReportController::class, 'downloadTransactionsPdf']);
            Route::get('/{id}',          [TransactionController::class, 'show']);
            Route::put('/{transaction}',        [TransactionController::class, 'update']);
            Route::delete('/{transaction}',       [TransactionController::class, 'destroy']);
            Route::post('/{transaction}/receipt', [TransactionController::class, 'uploadReceipt']);
            Route::patch('/{transaction}/categorize', [TransactionController::class, 'categorize']);
        });

        // Businesses
        Route::apiResource('businesses', BusinessController::class);

        // Categories
        Route::apiResource('categories', CategoryController::class);

        // Budgets
        Route::prefix('budgets')->group(function () {
            Route::get('/',         [BudgetController::class, 'index']);
            Route::post('/',        [BudgetController::class, 'store']);
            Route::put('/{id}',     [BudgetController::class, 'update']);
            Route::get('/weekly',   [BudgetController::class, 'weeklyStatus']);
        });

        // Bills
        Route::prefix('bills')->group(function () {
            Route::get('/',         [BillController::class, 'index']);
            Route::post('/',        [BillController::class, 'store']);
            Route::get('/{id}',     [BillController::class, 'show']);
            Route::delete('/{id}',  [BillController::class, 'destroy']);
        });

        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/monthly',        [ReportController::class, 'monthly']);
            Route::get('/yearly',         [ReportController::class, 'yearly']);
            Route::get('/comparison',     [ReportController::class, 'comparison']);
            Route::get('/export/pdf',     [ReportController::class, 'exportPdf']);
            Route::get('/export/excel',   [ReportController::class, 'exportExcel']);
        });

        // Settings
        Route::prefix('settings')->group(function () {
            Route::get('/',                       [SettingsController::class, 'show']);
            Route::post('notifications',          [SettingsController::class, 'toggleNotifications']);
            Route::post('pin',                    [SettingsController::class, 'setPin']);
            Route::post('biometric',              [SettingsController::class, 'toggleBiometric']);
            Route::post('daily-reminder',         [SettingsController::class, 'setDailyReminder']);
            Route::post('weekly-budget-limit',    [SettingsController::class, 'setWeeklyBudgetLimit']);
            Route::post('monthly-budget-limit',   [SettingsController::class, 'setMonthlyBudgetLimit']);
        });

        // Cache
        Route::prefix('cache')->group(function () {
            Route::post('clear',        [CacheController::class, 'clear']);
            Route::post('clear-views',  [CacheController::class, 'clearViews']);
            Route::post('clear-routes', [CacheController::class, 'clearRoutes']);
            Route::post('clear-config', [CacheController::class, 'clearConfig']);
            Route::post('clear-all',    [CacheController::class, 'clearAll']);
        });
    });
});
