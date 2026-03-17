@extends('layouts.agent')

@section('title', 'My Wallet')

@section('content')
<div class="row g-4">
    <!-- Wallet Balance Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4 bg-brand-dark text-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="bg-brand-light text-brand-dark rounded-3 d-flex align-items-center justify-content-center me-3"
                    style="width: 48px; height: 48px;">
                    <i class="bi bi-send-fill" style="font-size: 1.4rem;"></i>
                </div>
                    <span class="badge bg-brand-lime text-brand-dark rounded-pill px-3 py-2 d-inline-flex align-items-center">Active Wallet</span>
                </div>
                <h6 class="text-brand-lime opacity-75 small fw-bold uppercase mb-2">AVAILABLE BALANCE</h6>
                <h2 class="display-6 fw-bold mb-4">CHF {{ number_format($wallet->chf_balance, 2) }}</h2>
                <div class="d-grid">
                    <a href="{{ route('agent.wallet.topup') }}" class="btn bg-brand-light rounded-3 py-3 fw-bold">
                        <i class="bi bi-plus-circle me-2"></i> Request Top-up
                    </a>
                </div>
            </div>
            <div class="card-footer bg-white border-0 p-4">
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <p class="text-muted small mb-1">TOTAL RECEIVED</p>
                        <h6 class="fw-bold mb-0">CHF {{ number_format($wallet->total_received, 2) }}</h6>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">COMMISSIONS</p>
                        <h6 class="fw-bold mb-0">CHF {{ number_format($wallet->total_commission, 2) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-4 px-4 overflow-hidden">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-brand-dark">Transaction History</h5>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg ms-5">
                            <li><a class="dropdown-item" href="#">All Types</a></li>
                            <li><a class="dropdown-item" href="#">Deposits</a></li>
                            <li><a class="dropdown-item" href="#">Transfers</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3 small text-muted text-uppercase">Date</th>
                                <th class="border-0 py-3 small text-muted text-uppercase">Description</th>
                                <th class="border-0 py-3 small text-muted text-uppercase">Type</th>
                                <th class="border-0 py-3 small text-muted text-uppercase text-end">Amount</th>
                                <th class="border-0 px-4 py-3 small text-muted text-uppercase text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $txn)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="x-small text-muted">{{ $txn->created_at->format('M d, Y') }}</div>
                                    <div class="small fw-bold">{{ $txn->created_at->format('H:i') }}</div>
                                </td>
                                <td>
                                    <div class="small fw-bold text-brand-dark">{{ $txn->description }}</div>
                                    @if($txn->reference_id)
                                    <div class="x-small text-muted">Ref: {{ $txn->reference_type }} #{{ $txn->reference_id }}</div>
                                    @endif
                                </td>
                                <td>
                                    @php 
                                        $typeClass = [
                                            'deposit' => 'bg-success-subtle text-success',
                                            'transfer' => 'bg-info-subtle text-info',
                                            'withdrawal' => 'bg-danger-subtle text-danger',
                                            'commission' => 'bg-brand-lime bg-opacity-25 text-brand-dark',
                                        ][$txn->type] ?? 'bg-light text-muted';
                                    @endphp
                                    <span class="badge {{ $typeClass }} rounded-pill px-3 py-2 small fw-bold">
                                        {{ ucfirst($txn->type) }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold {{ $txn->amount > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $txn->amount > 0 ? '+' : '' }}{{ number_format($txn->amount, 2) }} {{ $txn->currency }}
                                </td>
                                <td class="px-4 text-center">
                                    <span class="badge bg-{{ $txn->status === 'completed' ? 'success' : ($txn->status === 'pending' ? 'warning' : 'danger') }} rounded-pill p-1">
                                        <i class="bi bi-{{ $txn->status === 'completed' ? 'check-circle-fill' : ($txn->status === 'pending' ? 'clock-fill' : 'x-circle-fill') }}"></i>
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No wallet transactions found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($transactions->hasPages())
            <div class="card-footer bg-white border-0 py-4 px-4">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
