@extends('layouts.agent')

@section('page_title', 'Transaction #'.str_pad($transaction->id, 8, '0', STR_PAD_LEFT))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4 border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-4 border-bottom-0">
                <h5 class="mb-0 fw-bold border-start border-4 border-brand-mint ps-3">Transfer Details</h5>
            </div>
            <div class="card-body p-4 pt-1">
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="text-muted small fw-bold text-uppercase mb-2">Sender</label>
                        <div class="h5 fw-bold mb-1">{{ $transaction->customer->name }}</div>
                        <div class="text-muted small">{{ $transaction->customer->email }}</div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-bold text-uppercase mb-2">Recipient</label>
                        <div class="h5 fw-bold mb-1">{{ $transaction->recipient->name }}</div>
                        <div class="text-muted small">{{ $transaction->recipient->bank_name }}</div>
                        <div class="text-muted small">A/C: {{ $transaction->recipient->account_number }}</div>
                    </div>
                </div>

                <div class="bg-light rounded-4 p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <label class="text-muted small fw-bold text-uppercase mb-3">Financial Breakdown</label>
                            <div>
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted">Amount Sent:</span>
                                    <span class="fw-bold">{{ number_format($transaction->send_amount, 2) }} CHF</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted">Commission:</span>
                                    <span class="fw-bold">{{ number_format($transaction->commission, 2) }} CHF</span>
                                </div>
                                <div class="d-flex justify-content-between pt-3 mt-2 border-top">
                                    <span class="fw-bold text-brand-dark">Total Paid:</span>
                                    <span class="fw-bold text-brand-dark h5 mb-0">{{ number_format($transaction->chf_amount, 2) }} CHF</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-center text-muted py-3 py-md-0">
                            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                <i class="bi bi-arrow-right fs-5 text-brand-dark"></i>
                            </div>
                            <div class="small mt-2 fw-bold">@ {{ $transaction->rate }}</div>
                        </div>
                        <div class="col-md-5 text-md-end">
                            <label class="text-muted small fw-bold text-uppercase mb-3">Recipient Receives</label>
                            <div class="h2 fw-bold text-brand-mint mb-0">{{ number_format($transaction->target_amount, 2) }} <span class="h6 text-muted">{{ $transaction->target_currency }}</span></div>
                        </div>
                    </div>
                </div>
                
                @if($transaction->notes)
                <div class="bg-white border rounded-4 p-3">
                    <label class="text-muted small fw-bold text-uppercase mb-2">Internal Notes</label>
                    <p class="mb-0 small">{{ $transaction->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <label class="text-muted small fw-bold text-uppercase d-block mb-4 text-center">Transfer Progress</label>
                <x-transaction-timeline :transaction="$transaction" />
            </div>
        </div>
        
        @if($transaction->payment_ref)
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <label class="text-muted small fw-bold text-uppercase d-block mb-2">Wise Reference</label>
                <div class="font-monospace fw-bold text-brand-dark">{{ $transaction->payment_ref }}</div>
            </div>
        </div>
        @endif
        
        <div class="d-grid mt-3">
            <a href="{{ route('agent.transactions.index') }}" class="btn btn-light py-3 rounded-pill fw-bold text-muted border text-decoration-none">
                <i class="bi bi-arrow-left me-2"></i> Back to History
            </a>
        </div>
    </div>
</div>
@endsection
