<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Agent;

Route::get('/', [App\Http\Controllers\PublicController::class, 'index'])->name('home');
Route::post('/public/quote', [App\Http\Controllers\PublicController::class, 'getQuote'])->name('public.quote');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Admin Approval Routes (Must come before resource)
    Route::get('/agents/pending', [App\Http\Controllers\Admin\AgentController::class, 'pending'])->name('agents.pending');
    Route::post('/agents/{id}/approve', [App\Http\Controllers\Admin\AgentController::class, 'approve'])->name('agents.approve');
    Route::post('/agents/{id}/reject', [App\Http\Controllers\Admin\AgentController::class, 'reject'])->name('agents.reject');

    Route::resource('agents', Admin\AgentController::class);
    Route::get('/transactions', [Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [Admin\TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/customers', [Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers/{id}/refresh-kyc', [Admin\CustomerController::class, 'refreshKyc'])->name('customers.refresh-kyc');
    Route::delete('/customers/{id}', [Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/recipients', [Admin\RecipientController::class, 'index'])->name('recipients.index');
    Route::get('/recipients/{id}', [Admin\RecipientController::class, 'show'])->name('recipients.show');
    Route::post('agents/{agent}/toggle', [Admin\AgentController::class, 'toggleStatus'])->name('agents.toggle');

    Route::get('/compliance', [Admin\ComplianceController::class, 'index'])->name('compliance.index');
    Route::get('/compliance/{id}', [Admin\ComplianceController::class, 'show'])->name('compliance.show');
    Route::post('/compliance/{id}/review', [Admin\ComplianceController::class, 'review'])->name('compliance.review');

    // Wallet Management
    Route::get('/wallets', [Admin\WalletController::class, 'index'])->name('wallets.index');
    Route::get('/wallets/{agent}/credit', [Admin\WalletController::class, 'showCreditForm'])->name('wallets.credit.form');
    Route::post('/wallets/{agent}/credit', [Admin\WalletController::class, 'credit'])->name('wallets.credit');
});

// Agent Routes
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [Agent\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', Agent\CustomerController::class);
    Route::post('customers/{id}/refresh-kyc', [Agent\CustomerController::class, 'refreshKyc'])->name('customers.refresh-kyc');

    Route::get('recipients', [Agent\RecipientController::class, 'index'])->name('recipients.index');
    Route::get('recipients/create', [Agent\RecipientController::class, 'create'])->name('recipients.create');
    Route::post('recipients', [Agent\RecipientController::class, 'store'])->name('recipients.store');
    Route::get('recipients/{id}/edit', [Agent\RecipientController::class, 'edit'])->name('recipients.edit');
    Route::put('recipients/{id}', [Agent\RecipientController::class, 'update'])->name('recipients.update');

    Route::get('/transfers/create', [Agent\TransferController::class, 'create'])->name('transfers.create');
    Route::post('/transfers/quote', [Agent\TransferController::class, 'getQuote'])->name('transfers.quote');
    Route::post('/transfers', [Agent\TransferController::class, 'store'])->name('transfers.store');

    Route::get('/transactions', [Agent\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [Agent\TransactionController::class, 'show'])->name('transactions.show');

    // Wallet
    Route::get('/wallet', [Agent\WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/topup', [Agent\WalletController::class, 'topUp'])->name('wallet.topup');
    Route::post('/wallet/checkout', [Agent\WalletController::class, 'checkout'])->name('wallet.checkout');
});

// Stripe Webhook
Route::post('/stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handle']);

// Sumsub Webhook
Route::post('/sumsub/webhook', [App\Http\Controllers\SumsubWebhookController::class, 'handle']);

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'de'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Agent Self-Registration
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

