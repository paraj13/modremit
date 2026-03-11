@extends('layouts.admin')

@section('title', 'Manage Agent Wallets')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 py-4 px-4 overflow-hidden">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-brand-dark">Agent Wallets</h5>
            <div class="search-box">
                <input type="text" class="form-control form-control-sm rounded-pill px-3" placeholder="Search agent...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 px-4 py-3 small text-muted text-uppercase">Agent Name</th>
                        <th class="border-0 py-3 small text-muted text-uppercase">Email</th>
                        <th class="border-0 py-3 small text-muted text-uppercase text-end">CHF Balance</th>
                        <th class="border-0 py-3 small text-muted text-uppercase text-center">Last Updated</th>
                        <th class="border-0 px-4 py-3 small text-muted text-uppercase text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $agent)
                    <tr>
                        <td class="px-4 py-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-brand-lime text-brand-dark rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                    {{ substr($agent->name, 0, 1) }}
                                </div>
                                <div class="fw-bold text-brand-dark">{{ $agent->name }}</div>
                            </div>
                        </td>
                        <td><span class="text-muted small">{{ $agent->email }}</span></td>
                        <td class="text-end fw-bold text-brand-dark">
                            CHF {{ number_format($agent->wallet->chf_balance ?? 0, 2) }}
                        </td>
                        <td class="text-center x-small text-muted">
                            {{ $agent->wallet ? $agent->wallet->updated_at->diffForHumans() : 'N/A' }}
                        </td>
                        <td class="px-4 text-end">
                            <a href="{{ route('admin.wallets.credit.form', $agent->id) }}" class="btn btn-brand btn-sm rounded-pill px-3 fw-bold">
                                <i class="bi bi-plus-lg me-1"></i> Credit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No agents found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
