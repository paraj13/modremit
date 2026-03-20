@extends('layouts.customer')

@section('page_title', 'Transaction History')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-brand-dark">All Transactions</h5>
                <a href="{{ route('customer.transfers.create') }}" class="btn btn-brand btn-sm px-4 rounded-pill">
                    <i class="bi bi-plus-lg me-1"></i> New Transfer
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle table-premium mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 small fw-bold text-muted">DATE</th>
                            <th class="border-0 py-3 small fw-bold text-muted">RECIPIENT</th>
                            <th class="border-0 py-3 small fw-bold text-muted text-end">SEND AMOUNT</th>
                            <th class="border-0 py-3 small fw-bold text-muted text-end">TARGET AMOUNT</th>
                            <th class="border-0 py-3 small fw-bold text-muted text-center">STATUS</th>
                            <th class="border-0 py-3 small fw-bold text-muted text-center pe-4">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $txn)
                        <tr>
                            <td class="px-4 py-3 small text-muted">
                                <div class="fw-bold">{{ $txn->created_at->format('M d, Y') }}</div>
                                <div class="x-small">{{ $txn->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-brand-dark small">{{ $txn->recipient->name ?? 'N/A' }}</div>
                                <div class="x-small text-muted">{{ $txn->recipient->bank_name ?? '' }}</div>
                            </td>
                            <td class="text-end fw-bold text-brand-dark">{{ number_format($txn->chf_amount, 2) }} CHF</td>
                            <td class="text-end fw-bold text-success">{{ number_format($txn->target_amount, 2) }} {{ $txn->target_currency }}</td>
                            <td class="text-center">
                                <span class="status-pill status-{{ $txn->status }}">{{ strtoupper($txn->status) }}</span>
                            </td>
                            <td class="text-center pe-3">
                                <a href="{{ route('customer.transactions.show', $txn->id) }}" class="btn btn-sm btn-brand-outline rounded-pill px-3">Details</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-journal-x fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                <p class="text-muted mb-0">No transactions found. <a href="{{ route('customer.transfers.create') }}" class="fw-bold text-brand-dark">Send money now</a></p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transactions->hasPages())
            <div class="card-footer bg-white border-0 py-3 px-4">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
