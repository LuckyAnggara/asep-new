<?php

use App\Http\Controllers\AccountCategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountSubCategoryController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FinancialStatementController;
use App\Http\Controllers\IncomeStatementController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Models\ChartOfAccount;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('members', MemberController::class)
    ->middleware(['auth', 'verified']);

Route::resource('accounts', AccountController::class)
    ->middleware(['auth', 'verified']);
Route::resource('chart-of-accounts', ChartOfAccountController::class)
    ->middleware(['auth', 'verified']);

Route::resource('account-category', AccountCategoryController::class)
    ->middleware(['auth', 'verified']);
Route::resource('account-sub-category', AccountSubCategoryController::class)
    ->middleware(['auth', 'verified']);
Route::resource('accounting/journal-entries', JournalEntryController::class)
    ->middleware(['auth', 'verified']);

Route::resource('accounting/ledger', LedgerController::class)->only(['index'])
    ->middleware(['auth', 'verified']);
Route::resource('accounting/balance-sheet', BalanceSheetController::class)->only(['index'])
    ->middleware(['auth', 'verified']);
Route::resource('accounting/cash-flow', CashFlowController::class)->only(['index'])
    ->middleware(['auth', 'verified']);
Route::resource('accounting/income-statement', IncomeStatementController::class)->only(['index'])
    ->middleware(['auth', 'verified']);
Route::get('accounting/trial-balance', [FinancialStatementController::class, 'trialBalance'])->name('trial-balance');


Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::put('/settings/company/{id}', [SettingsController::class, 'updateCompany'])->middleware(['auth', 'verified'])->name('settings.updateCompany');
Route::put('/settings/preferences/{id}', [SettingsController::class, 'updatePreferences'])->middleware(['auth', 'verified'])->name('settings.updatePreferences');
Route::put('/settings/security/password', [SettingsController::class, 'updatePassword'])->name('user.updatePassword');

require __DIR__ . '/auth.php';
