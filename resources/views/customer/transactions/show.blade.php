@extends('layouts.customer')

@section('page_title', 'Transaction Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-brand-dark py-4 px-4 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-brand-lime rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-receipt text-brand-dark h4 mb-0"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-white">Transfer Receipt</h5>
                            <p class="text-brand-lime small mb-0 opacity-75">Transaction ID: #{{ $transaction->id }}</p>
                        </div>
                    </div>
                    <span class="badge bg-{{ $transaction->status_badge }} rounded-pill px-4 py-2 fs-6 shadow-sm border border-white border-opacity-10">{{ strtoupper($transaction->status) }}</span>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="row g-4 border-bottom pb-4 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-2 d-block text-uppercase">RECIPIENT</label>
                        <h4 class="fw-bold text-brand-dark mb-1">{{ $transaction->recipient->name ?? 'Unknown' }}</h4>
                        <div class="text-muted">
                            <i class="bi bi-bank me-1 small"></i> {{ $transaction->recipient->bank_name ?? '—' }}<br>
                            <i class="bi bi-hash me-1 small"></i> {{ $transaction->recipient->account_number ?? '—' }}
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <label class="text-muted small fw-bold mb-2 d-block text-uppercase">TRANSFER DATE</label>
                        <h5 class="fw-bold text-brand-dark mb-1">{{ $transaction->created_at->format('M d, Y') }}</h5>
                        <div class="text-muted">{{ $transaction->created_at->format('H:i:s') }} (UTC)</div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="p-4 rounded-4 bg-light border border-opacity-10 border-dark">
                            <div class="row gy-3">
                                <div class="col-md-6 d-flex justify-content-between">
                                    <span class="text-muted small fw-bold">Amount Sent:</span>
                                    <span class="fw-bold text-brand-dark">{{ number_format($transaction->chf_amount, 2) }} CHF</span>
                                </div>
                                <div class="col-md-6 d-flex justify-content-between border-start-md px-md-4">
                                    <span class="text-muted small fw-bold">Exchange Rate:</span>
                                    <span class="fw-bold text-brand-dark">1 CHF = {{ number_format($transaction->rate, 4) }} {{ $transaction->target_currency }}</span>
                                </div>
                                <div class="col-md-6 d-flex justify-content-between pt-2 border-top">
                                    <span class="text-muted small fw-bold">Service Fee:</span>
                                    <span class="fw-bold text-danger">{{ number_format($transaction->commission, 2) }} CHF</span>
                                </div>
                                <div class="col-md-6 d-flex justify-content-between pt-2 border-top border-start-md px-md-4">
                                    <span class="text-success small fw-bold">Recipient Receives:</span>
                                    <span class="h4 fw-bold text-success mb-0">{{ number_format($transaction->target_amount, 2) }} {{ $transaction->target_currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($transaction->notes)
                    <div class="col-md-12">
                        <label class="text-muted small fw-bold mb-2 d-block text-uppercase">REMITTANCE NOTES</label>
                        <div class="p-3 rounded-4 bg-light border-start border-4 border-brand-lime">
                            <p class="mb-0 small text-muted italic">"{{ $transaction->notes }}"</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-5 pt-3 d-flex gap-3">
                    <a href="{{ route('customer.transactions.index') }}" class="btn btn-outline-dark px-4 py-2 rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Back to History
                    </a>
                    <button onclick="window.print()" class="btn btn-brand px-4 py-2 rounded-pill">
                        <i class="bi bi-printer me-1"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold text-brand-dark mb-4">Transfer Progress</h6>
            <x-transaction-timeline :transaction="$transaction" />
        </div>
    </div>
</div>
@endsection
