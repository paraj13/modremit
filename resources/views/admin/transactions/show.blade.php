@extends('layouts.admin')

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
            <div class="card-body p-4 text-center">
                <label class="text-muted small fw-bold text-uppercase d-block mb-4">Status Monitoring</label>
                @php
                    $statusConfig = match($transaction->status) {
                        'completed' => ['icon' => 'bi-check-circle-fill', 'color' => 'var(--brand-mint)', 'text' => 'text-brand-dark'],
                        'failed' => ['icon' => 'bi-x-circle-fill', 'color' => '#dc3545', 'text' => 'text-white'],
                        'processing' => ['icon' => 'bi-arrow-repeat spin', 'color' => '#0dcaf0', 'text' => 'text-white'],
                        default => ['icon' => 'bi-clock-history', 'color' => '#ffc107', 'text' => 'text-dark']
                    };
                @endphp
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 80px; height: 80px; background-color: {{ $statusConfig['color'] }}; color: {{ $statusConfig['color'] == 'var(--brand-mint)' ? 'var(--brand-dark)' : 'white' }};">
                    <i class="bi {{ $statusConfig['icon'] }}" style="font-size: 2.5rem;"></i>
                </div>
                <h4 class="fw-bold mb-1">{{ strtoupper($transaction->status) }}</h4>
                <div class="text-muted small mb-4">{{ $transaction->created_at->format('M d, Y - H:i A') }}</div>
            </div>
        </div>
        
        @if($transaction->wise_transfer_id || $transaction->wise_response)
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="mb-0 fw-bold border-start border-3 border-brand-dark ps-2">Wise Transfer Details</h6>
            </div>
            <div class="card-body p-4 pt-0">
                @if($transaction->wise_transfer_id)
                <div class="mb-3">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Transfer ID</label>
                    <div class="font-monospace fw-bold">{{ $transaction->wise_transfer_id }}</div>
                </div>
                @endif
                
                @if($transaction->wise_status)
                <div class="mb-3">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Wise Status</label>
                    <span class="badge bg-{{ $transaction->wise_status == 'completed' ? 'success' : ($transaction->wise_status == 'failed' ? 'danger' : 'info') }} rounded-pill px-3">
                        {{ strtoupper($transaction->wise_status) }}
                    </span>
                </div>
                @endif
                
                @if($transaction->wise_response)
                <div>
                    <label class="text-muted small fw-bold text-uppercase d-block mb-2">API Response</label>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill w-100 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#wiseResponse" aria-expanded="false" aria-controls="wiseResponse">
                        <i class="bi bi-code-slash me-1"></i> View Raw JSON
                    </button>
                    <div class="collapse" id="wiseResponse">
                        <div class="bg-dark text-light p-3 rounded-3 mt-2 small overflow-auto" style="max-height: 300px;">
                            <pre class="mb-0"><code class="language-json">{{ json_encode($transaction->wise_response, JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="d-grid">
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-light py-3 rounded-pill fw-bold text-muted border text-decoration-none">
                <i class="bi bi-arrow-left me-2"></i> Back to History
            </a>
        </div>
    </div>
</div>

<style>
    .spin { animation: spin 2s linear infinite; }
    @keyframes spin { 100% { transform: rotate(360deg); } }
</style>
@endsection
