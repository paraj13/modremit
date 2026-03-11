<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Agent;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('agents', Admin\AgentController::class);
    Route::post('agents/{agent}/toggle', [Admin\AgentController::class, 'toggleStatus'])->name('agents.toggle');

    Route::get('/compliance', [Admin\ComplianceController::class, 'index'])->name('compliance.index');
    Route::get('/compliance/{id}', [Admin\ComplianceController::class, 'show'])->name('compliance.show');
    Route::post('/compliance/{id}/review', [Admin\ComplianceController::class, 'review'])->name('compliance.review');

    Route::get('/fx-records', function() {
        return view('admin.fx.index'); // To be implemented or simple log
    })->name('fx.index');
});

// Agent Routes
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [Agent\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', Agent\CustomerController::class);
    Route::post('customers/{id}/refresh-kyc', [Agent\CustomerController::class, 'refreshKyc'])->name('customers.refresh-kyc');

    Route::get('recipients/create', [Agent\RecipientController::class, 'create'])->name('recipients.create');
    Route::post('recipients', [Agent\RecipientController::class, 'store'])->name('recipients.store');
    Route::get('recipients/{id}/edit', [Agent\RecipientController::class, 'edit'])->name('recipients.edit');
    Route::put('recipients/{id}', [Agent\RecipientController::class, 'update'])->name('recipients.update');

    Route::get('/transfers/create', [Agent\TransferController::class, 'create'])->name('transfers.create');
    Route::post('/transfers/quote', [Agent\TransferController::class, 'getQuote'])->name('transfers.quote');
    Route::post('/transfers', [Agent\TransferController::class, 'store'])->name('transfers.store');

    Route::get('/transactions', [Agent\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [Agent\TransactionController::class, 'show'])->name('transactions.show');
});
