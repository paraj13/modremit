@extends('layouts.agent')

@section('page_title', 'Agent Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card bg-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted">Total Transfers</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                </div>
                <div class="stat-icon text-primary">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted">CHF Received</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['total_chf'], 2) }}</h3>
                </div>
                <div class="stat-icon text-success">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted">INR Sent</h6>
                    <h3 class="fw-bold mb-0">₹ {{ number_format($stats['total_inr'], 2) }}</h3>
                </div>
                <div class="stat-icon text-info">
                    <i class="bi bi-currency-rupee"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted">Wallet Balance</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($wallet->chf_balance, 2) }} CHF</h3>
                </div>
                <div class="stat-icon text-warning">
                    <i class="bi bi-bank"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Recent Actions</h5>
                <a href="{{ route('agent.transfers.create') }}" class="btn btn-primary btn-sm">New Transfer</a>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <img src="https://img.icons8.com/clouds/100/000000/empty-box.png" alt="No data" class="mb-3">
                    <p class="text-muted">Ready to process your first remittance?</p>
                    <a href="{{ route('agent.customers.create') }}" class="btn btn-outline-primary shadow-sm">Add First Customer</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Total Customers</span>
                    <span class="fw-bold">{{ $customerCount }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Completed Transfers</span>
                    <span class="text-success fw-bold">{{ $stats['completed'] }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Failed Transfers</span>
                    <span class="text-danger fw-bold">{{ $stats['failed'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
