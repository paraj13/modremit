@extends('layouts.customer')

@section('page_title', 'Dashboard')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-6">
        <h5 class="fw-bold text-brand-dark mb-0">Account Overview</h5>
        <p class="text-muted small mb-0">Welcome back, {{ auth()->guard('customer')->user()->name }}</p>
    </div>
    <div class="col-6 text-end">
        <a href="{{ route('customer.transfers.create') }}" class="btn btn-brand rounded-pill px-4">
            <i class="bi bi-send-fill me-2"></i> Send Money
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary me-3">
                    <i class="bi bi-send-fill fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold text-uppercase">Total Sent</h6>
            </div>
            <h3 class="fw-bold mb-0 text-brand-dark">CHF {{ number_format($totalSent, 2) }}</h3>
            <p class="text-muted small mt-2 mb-0">Lifetime transfers</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-warning bg-opacity-10 p-2 rounded-3 text-warning me-3">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold text-uppercase">Pending</h6>
            </div>
            <h3 class="fw-bold mb-0 text-brand-dark">{{ $pendingTx }}</h3>
            <p class="text-muted small mt-2 mb-0">Processing transactions</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success me-3">
                    <i class="bi bi-shield-check fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold text-uppercase">KYC Status</h6>
            </div>
            <h3 class="fw-bold mb-0 text-brand-dark">
                <span class="badge bg-{{ auth()->guard('customer')->user()->kyc_badge }} rounded-pill px-3">{{ strtoupper(auth()->guard('customer')->user()->kyc_status) }}</span>
            </h3>
            <p class="text-muted small mt-2 mb-0">Identity verification</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-brand-dark">Recent Activity</h5>
                <a href="{{ route('customer.transactions.index') }}" class="btn btn-sm btn-brand-outline px-3 rounded-pill">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 small fw-bold text-muted">DATE</th>
                            <th class="border-0 py-3 small fw-bold text-muted">RECIPIENT</th>
                            <th class="border-0 py-3 small fw-bold text-muted text-end">AMOUNT</th>
                            <th class="border-0 py-3 small fw-bold text-muted text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $txn)
                        <tr>
                            <td class="px-4 py-3 small text-muted">{{ $txn->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="fw-bold text-brand-dark small">{{ $txn->recipient->name ?? 'N/A' }}</div>
                                <div class="x-small text-muted">{{ $txn->recipient->bank_name ?? '' }}</div>
                            </td>
                            <td class="text-end fw-bold text-brand-dark">{{ number_format($txn->chf_amount, 2) }} CHF</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $txn->status_badge }} rounded-pill px-3 py-1 small">{{ strtoupper($txn->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-inboxes fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                <p class="text-muted mb-0">No transactions yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-brand-dark text-white">
            <h5 class="fw-bold mb-4">Quick Actions</h5>
            <div class="d-grid gap-3">
                <a href="{{ route('customer.transfers.create') }}" class="btn btn-brand btn-lg py-3 fw-bold rounded-4">
                    <i class="bi bi-send-fill me-2"></i> Send Money
                </a>
                <a href="{{ route('customer.recipients.create') }}" class="btn btn-outline-light py-3 fw-bold rounded-4">
                    <i class="bi bi-person-plus-fill me-2"></i> Add Recipient
                </a>
                <a href="{{ route('customer.transactions.index') }}" class="btn btn-white btn-opacity-10 text-white py-3 fw-bold rounded-4 btn-glass">
                    <i class="bi bi-journal-text me-2"></i> Transactions
                </a>
            </div>
            
            <div class="mt-auto pt-5">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 small">
                    <h6 class="fw-bold mb-2">Need Help?</h6>
                    <p class="mb-0 text-white-50">Contact our support team if you have any questions about your transfers.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
