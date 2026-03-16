@extends('layouts.agent')

@section('page_title', 'Transaction History')

@section('content')
<div class="table-premium-container">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h5 class="mb-0 fw-bold text-brand-dark">My Transactions</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-premium">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>Customer</th>
                    <th>Recipient</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th width="120px" class="text-end">Action</th>
                </tr> 
            </thead>
            <tbody>
                @forelse($transactions as $index => $transaction)
                <tr>
                    <td>{{ $transactions->firstItem() + $index }}</td>
                    <td class="fw-bold text-brand-dark">{{ $transaction->customer->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->recipient->name ?? 'N/A' }}</td>
                    <td>
                        <div class="fw-bold text-brand-dark">CHF {{ number_format($transaction->chf_amount, 2) }}</div>
                        <div class="small text-muted">Send: {{ number_format($transaction->send_amount, 2) }} | Comm: {{ number_format($transaction->commission, 2) }}</div>
                    </td>
                    <td>
                        @php
                            $badgeClass = match($transaction->status) {
                                'completed'  => 'bg-brand-mint text-brand-dark',
                                'processing' => 'bg-info text-white',
                                'failed'     => 'bg-danger text-white',
                                default      => 'bg-warning text-dark',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} px-3">
                            {{ strtoupper($transaction->status) }}
                        </span>
                    </td>
                    <td><span class="text-muted small">{{ $transaction->created_at->format('M d, Y') }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('agent.transactions.show', $transaction->id) }}" class="btn btn-sm btn-outline-dark rounded-3 px-3">Details</a>
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
    <div class="mt-4 px-3">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
