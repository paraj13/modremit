@extends('layouts.guest')

@section('page_title', 'Track Transaction #' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-brand-dark mb-1">Track Your Transfer</h2>
                    <p class="text-muted mb-0">Real-time status of transaction #{{ str_pad($transaction->id, 8, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-{{ $transaction->status_badge }} rounded-pill px-4 py-2 fs-6 shadow-sm">
                        {{ strtoupper($transaction->status) }}
                    </span>
                </div>
            </div>

            <div class="row g-4">
                <!-- Transaction Details -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="mb-0 fw-bold border-start border-4 border-brand-mint ps-3">Transfer Summary</h5>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Sender</label>
                                    <div class="h6 fw-bold mb-0 text-brand-dark">{{ $transaction->customer->name }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Recipient</label>
                                    <div class="h6 fw-bold mb-0 text-brand-dark">{{ $transaction->recipient->name }}</div>
                                    <div class="text-muted small">{{ $transaction->recipient->bank_name }}</div>
                                </div>
                            </div>

                            <div class="bg-light rounded-4 p-4">
                                <div class="row align-items-center">
                                    <div class="col-12 col-md-5">
                                        <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Sending Amount</label>
                                        <div class="h4 fw-bold text-brand-dark mb-0">
                                            {{ number_format($transaction->send_amount, 2) }} <span class="h6 text-muted">CHF</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2 text-center py-2 py-md-0">
                                        <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                            <i class="bi bi-arrow-right fs-5 text-brand-dark"></i>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-5 text-md-end">
                                        <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Recipient Receives</label>
                                        <div class="h3 fw-bold text-brand-mint mb-0">
                                            {{ number_format($transaction->target_amount, 2) }} <span class="h6 text-muted">{{ $transaction->target_currency }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Exchange Rate:</span>
                                    <span class="fw-bold">1 CHF = {{ number_format($transaction->rate, 4) }} {{ $transaction->target_currency }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Reference ID:</span>
                                    <span class="font-monospace fw-bold">#{{ $transaction->payment_ref ?? 'TXN-' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Last Updated:</span>
                                    <span>{{ $transaction->updated_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 bg-brand-dark text-white p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-brand-mint rounded-circle p-3 me-4">
                                <i class="bi bi-shield-check fs-2 text-brand-dark"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Your transfer is secure</h5>
                                <p class="mb-0 text-white-50 small">Modremit uses industry-standard encryption to protect your financial data and ensure safe delivery.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-bottom-0 text-center">
                            <h5 class="mb-0 fw-bold">Live Tracking</h5>
                        </div>
                        <div class="card-body p-4">
                            <x-transaction-timeline :transaction="$transaction" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-brand-dark { background-color: #142472 !important; }
    .text-brand-dark { color: #142472 !important; }
    .bg-brand-mint { background-color: #ffde42 !important; }
    .text-brand-mint { color: #ffde42 !important; }
    .border-brand-mint { border-color: #ffde42 !important; }
</style>
@endsection
