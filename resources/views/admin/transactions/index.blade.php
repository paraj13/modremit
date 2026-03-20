@extends('layouts.admin')

@section('page_title', 'All Transactions')

@section('content')
<div class="table-premium-container">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h5 class="mb-0 fw-bold text-brand-dark">Platform Transaction History</h5>
        <div class="search-box">
            <input type="text" class="form-control rounded-pill px-4 shadow-sm border-0 bg-white" placeholder="Search transactions..." data-search-target="#transactionsTable" style="min-width: 280px;">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-premium" id="transactionsTable">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>Agent</th>
                    <th>Customer</th>
                    <th>Recipient</th>
                    <th>Amount</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th width="80px" class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $transaction)
                <tr>
                    <td>{{ $transactions->firstItem() + $index }}</td>
                    <td class="fw-bold">{{ $transaction->agent->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->customer->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->recipient->name ?? 'N/A' }}</td>
                    <td>
                        <div class="fw-bold text-brand-dark">CHF {{ number_format($transaction->chf_amount, 2) }}</div>
                        <div class="small text-muted">S: {{ number_format($transaction->send_amount, 2) }} | C: {{ number_format($transaction->commission, 2) }}</div>
                    </td>
                    <td>{{ number_format($transaction->rate, 4) }}</td>
                    <td>
                        <span class="status-pill status-{{ $transaction->status }}">
                            {{ strtoupper($transaction->status) }}
                        </span>
                    </td>
                    <td><span class="text-muted small">{{ $transaction->created_at->format('M d, Y H:i') }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-sm btn-outline-dark rounded-3 px-3">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">No transactions found.</td>
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
