@extends('layouts.agent')

@section('page_title', 'Transaction #'.str_pad($transaction->id, 8, '0', STR_PAD_LEFT))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Transfer Details</h5>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="text-muted small text-uppercase">Sender</label>
                        <div class="h5 fw-bold">{{ $transaction->customer->name }}</div>
                        <div class="text-muted">{{ $transaction->customer->email }}</div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small text-uppercase">Recipient</label>
                        <div class="h5 fw-bold">{{ $transaction->recipient->name }}</div>
                        <div class="text-muted">{{ $transaction->recipient->bank_name }}</div>
                        <div class="text-muted">A/C: {{ $transaction->recipient->account_number }}</div>
                    </div>
                </div>

                <hr class="my-4" style="opacity: 0.1;">

                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="text-muted small text-uppercase">Amount Sent</div>
                        <div class="h3 fw-bold">{{ number_format($transaction->chf_amount, 2) }} <span class="h6 text-muted">CHF</span></div>
                        <div class="small text-muted">Incl. Commission: {{ number_format($transaction->commission, 2) }} CHF</div>
                    </div>
                    <div class="col-md-2 text-center text-muted">
                        <i class="bi bi-arrow-right h3"></i>
                        <div class="small">@ {{ $transaction->rate }}</div>
                    </div>
                    <div class="col-md-5 text-end">
                        <div class="text-muted small text-uppercase">Recipient Receives</div>
                        <div class="h3 fw-bold text-success">{{ number_format($transaction->target_amount, 2) }} <span class="h6 text-muted">{{ $transaction->target_currency }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        @if($transaction->notes)
        <div class="card bg-light border-0">
            <div class="card-body">
                <label class="text-muted small text-uppercase">Internal Notes</label>
                <p class="mb-0">{{ $transaction->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 text-center">
                <label class="text-muted small text-uppercase d-block mb-3">Status Monitoring</label>
                @php
                    $statusIcon = match($transaction->status) {
                        'completed' => 'bi-check-circle-fill text-success',
                        'failed' => 'bi-x-circle-fill text-danger',
                        'processing' => 'bi-arrow-repeat text-info spin',
                        default => 'bi-clock-history text-warning'
                    };
                @endphp
                <i class="bi {{ $statusIcon }}" style="font-size: 4rem;"></i>
                <h3 class="fw-bold mt-3">{{ ucfirst($transaction->status) }}</h3>
                
                @if($transaction->payment_ref)
                    <div class="mt-4 p-3 bg-light rounded text-start">
                        <small class="text-muted d-block">Revolut Reference</small>
                        <span class="font-monospace fw-bold">{{ $transaction->payment_ref }}</span>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="d-grid">
            <a href="{{ route('agent.transactions.index') }}" class="btn btn-outline-secondary">Back to History</a>
        </div>
    </div>
</div>

<style>
    .spin { animation: spin 2s linear infinite; }
    @keyframes spin { 100% { transform: rotate(360deg); } }
</style>
@endsection
