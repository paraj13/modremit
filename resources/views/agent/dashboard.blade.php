@extends('layouts.agent')

@section('page_title', 'Agent Dashboard')

@section('content')
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card card-premium p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold uppercase">Transfers</h6>
                    <h3 class="fw-bold mb-0 text-brand-dark">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-brand-mint p-3 rounded-circle text-brand-dark">
                    <i class="bi bi-arrow-left-right fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-premium p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold uppercase">CHF Received</h6>
                    <h3 class="fw-bold mb-0 text-brand-dark">{{ number_format($stats['total_chf'], 2) }}</h3>
                </div>
                <div class="bg-brand-lime p-3 rounded-circle text-brand-dark">
                    <i class="bi bi-wallet2 fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-premium p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold uppercase">Target Sent</h6>
                    <h3 class="fw-bold mb-0 text-brand-dark">{{ number_format($stats['total_target'], 2) }}</h3>
                </div>
                <div class="bg-light p-3 rounded-circle text-brand-dark border">
                    <i class="bi bi-globe fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <a href="{{ route('agent.wallet.index') }}" class="text-decoration-none">
            <div class="card card-premium p-4 h-100 bg-brand-dark text-white shadow-hover transition">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-brand-lime small fw-bold uppercase">Wallet Balance</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($wallet->chf_balance, 2) }} CHF</h3>
                    </div>
                    <div class="bg-white bg-opacity-10 p-3 rounded-circle text-brand-lime">
                        <i class="bi bi-bank fs-4"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="table-premium-container">
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <h5 class="mb-0 fw-bold text-brand-dark">Recent Activity</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.wallet.topup') }}" class="btn btn-brand-outline btn-sm px-4">Top-Up Wallet</a>
                    <a href="{{ route('agent.transfers.create') }}" class="btn btn-brand btn-sm px-4">New Transfer</a>
                </div>
            </div>
            <div class="text-center py-5">
                <div class="bg-gray-light d-inline-block p-4 rounded-circle mb-4">
                    <i class="bi bi-lightning-charge opacity-25 h1 mb-0"></i>
                </div>
                <p class="text-muted">Ready to process your first remittance?</p>
                <a href="{{ route('agent.customers.create') }}" class="btn btn-brand-outline px-4">Add First Customer</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-premium p-4 h-100">
            <h5 class="mb-4 fw-bold text-brand-dark">Quick Summary</h5>
            <div class="space-y-4">
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <span class="text-muted">Total Customers</span>
                    <span class="fw-bold text-brand-dark f-5">{{ $customerCount }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <span class="text-muted">Completed</span>
                    <span class="badge bg-brand-mint text-brand-dark px-3">{{ $stats['completed'] }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3">
                    <span class="text-muted">Failed</span>
                    <span class="badge bg-danger text-white px-3">{{ $stats['failed'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
