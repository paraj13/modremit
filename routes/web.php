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
    Route::post('agents/{agent}/refresh-kyc', [Admin\AgentController::class, 'refreshKyc'])->name('agents.refresh-kyc');

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

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Customer Portal Routes
Route::prefix('customer')->name('customer.')->group(function () {
    // Auth (guest)
    Route::get('/login', [App\Http\Controllers\Customer\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Customer\Auth\LoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Customer\Auth\LoginController::class, 'logout'])->name('logout');
    Route::get('/register', [App\Http\Controllers\Customer\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Customer\Auth\RegisterController::class, 'register']);

    // Password Reset
    Route::get('/password/reset', [App\Http\Controllers\Customer\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\Customer\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [App\Http\Controllers\Customer\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [App\Http\Controllers\Customer\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

    // KYC Required page (only requires customer auth)
    Route::middleware('auth:customer')->group(function () {
        Route::get('/kyc-required', [App\Http\Controllers\Customer\KycController::class, 'required'])->name('kyc.required');
    });

    // KYC-gated routes (requires auth + KYC approved)
    Route::middleware(['auth:customer', 'customer.kyc'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/transfers/create', [App\Http\Controllers\Customer\TransferController::class, 'create'])->name('transfers.create');
        Route::post('/transfers/quote', [App\Http\Controllers\Customer\TransferController::class, 'getQuote'])->name('transfers.quote');
        Route::post('/transfers', [App\Http\Controllers\Customer\TransferController::class, 'store'])->name('transfers.store');

        Route::get('/transactions', [App\Http\Controllers\Customer\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{id}', [App\Http\Controllers\Customer\TransactionController::class, 'show'])->name('transactions.show');

        Route::get('/recipients', [App\Http\Controllers\Customer\RecipientController::class, 'index'])->name('recipients.index');
        Route::get('/recipients/create', [App\Http\Controllers\Customer\RecipientController::class, 'create'])->name('recipients.create');
        Route::get('/recipients/{id}', [App\Http\Controllers\Customer\RecipientController::class, 'show'])->name('recipients.show');
        Route::post('/recipients', [App\Http\Controllers\Customer\RecipientController::class, 'store'])->name('recipients.store');
    });
});


