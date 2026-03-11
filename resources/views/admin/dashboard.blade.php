@extends('layouts.admin')

@section('page_title', 'Admin Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card bg-primary text-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="opacity-75">Platform Volume</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['total_chf'], 0) }} CHF</h3>
                </div>
                <div class="stat-icon"><i class="bi bi-bank"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-success text-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="opacity-75">Total Commission</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['total_commission'], 2) }} CHF</h3>
                </div>
                <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-dark text-white h-100 {{ $stats['flagged'] > 0 ? 'border border-danger border-2' : '' }}">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="opacity-75">Compliance Alerts</h6>
                    <h3 class="fw-bold mb-0 text-{{ $stats['flagged'] > 0 ? 'danger' : 'white' }}">{{ $stats['flagged'] }}</h3>
                </div>
                <div class="stat-icon text-danger"><i class="bi bi-shield-exclamation"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted">Total Transfers</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                </div>
                <div class="stat-icon text-muted"><i class="bi bi-arrow-left-right"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Recent System Activity</h5>
                <a href="{{ route('admin.compliance.index') }}" class="btn btn-outline-danger btn-sm">Reviews Needed ({{ $pendingCompliance }})</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Agent</th>
                                <th>CHF</th>
                                <th>INR</th>
                                <th>Recipient</th>
                                <th>Status</th>
                                <th>Flagged</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-muted italic">
                                <td colspan="7" class="text-center py-4">Global transaction log monitor active.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
