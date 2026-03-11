@extends('layouts.admin')

@section('page_title', 'Admin Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden pt-1 bg-primary">
            <div class="card-body bg-white py-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                        <i class="bi bi-bank h4 mb-0"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1 small fw-bold">PLATFORM VOLUME</h6>
                        <h4 class="fw-bold mb-0">CHF {{ number_format($stats['total_chf'], 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden pt-1 bg-success">
            <div class="card-body bg-white py-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-3 text-success">
                        <i class="bi bi-graph-up-arrow h4 mb-0"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1 small fw-bold">TOTAL COMMISSION</h6>
                        <h4 class="fw-bold mb-0">CHF {{ number_format($stats['total_commission'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden pt-1 bg-danger text-dark">
            <div class="card-body bg-white py-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-danger bg-opacity-10 p-3 rounded-3 text-danger">
                        <i class="bi bi-shield-exclamation h4 mb-0"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1 small fw-bold">COMPLIANCE ALERTS</h6>
                        <h4 class="fw-bold mb-0 text-{{ $stats['flagged'] > 0 ? 'danger' : 'dark' }}">{{ $stats['flagged'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden pt-1 bg-dark">
            <div class="card-body bg-white py-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-dark bg-opacity-10 p-3 rounded-3 text-dark">
                        <i class="bi bi-arrow-left-right h4 mb-0"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1 small fw-bold">TOTAL TRANSFERS</h6>
                        <h4 class="fw-bold mb-0">{{ $stats['total'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Recent System Activity</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-light btn-sm px-3 rounded-pill">View All</a>
                    @if($pendingCompliance > 0)
                        <a href="{{ route('admin.compliance.index') }}" class="btn btn-outline-danger btn-sm px-3 rounded-pill">
                            <i class="bi bi-exclamation-octagon me-1"></i> Reviews Needed: {{ $pendingCompliance }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="alert alert-info border-0 rounded-3 mb-0 py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-3 h4 mb-0"></i>
                        <div>
                             <strong>Monitor platform health in real-time.</strong>
                             Use the navigation menu to manage agents, review compliance flags, and configure FX rates.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
