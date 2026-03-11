@extends('layouts.agent')

@section('page_title', 'Transaction History')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Recent Remittances</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Transaction ID</th>
                        <th>Customer / Recipient</th>
                        <th>CHF Amount</th>
                        <th>INR Amount</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                    <tr>
                        <td class="small">{{ $txn->created_at->format('M d, H:i') }}</td>
                        <td><span class="font-monospace text-muted">{{ str_pad($txn->id, 8, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <div class="fw-bold">{{ $txn->customer->name }}</div>
                            <div class="small text-muted">To: {{ $txn->recipient->name }}</div>
                        </td>
                        <td>{{ number_format($txn->chf_amount, 2) }}</td>
                        <td>₹ {{ number_format($txn->inr_amount, 2) }}</td>
                        <td>
                            @php
                                $statusClass = match($txn->status) {
                                    'completed' => 'bg-success',
                                    'failed' => 'bg-danger',
                                    'processing' => 'bg-info text-dark',
                                    default => 'bg-warning text-dark'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($txn->status) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('agent.transactions.show', $txn->id) }}" class="btn btn-sm btn-link">Details</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
