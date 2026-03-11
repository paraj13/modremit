@extends('layouts.admin')

@section('title', 'Credit Agent Wallet')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-brand-dark text-white p-4">
                <h5 class="fw-bold mb-0">Credit Agent Wallet</h5>
                <p class="text-brand-lime small mb-0 opacity-75">Manually add funds to {{ $agent->name }}'s wallet.</p>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                    <div class="avatar bg-brand-lime text-brand-dark rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px;">
                        {{ substr($agent->name, 0, 1) }}
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-brand-dark">{{ $agent->name }}</h6>
                        <p class="x-small text-muted mb-0">Current balance: <strong>CHF {{ number_format($agent->wallet->chf_balance ?? 0, 2) }}</strong></p>
                    </div>
                </div>

                <form action="{{ route('admin.wallets.credit', $agent->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">CREDIT AMOUNT (CHF)</label>
                        <div class="input-group input-group-lg bg-light rounded-3 overflow-hidden border">
                            <span class="input-group-text bg-light border-0 px-4 fw-bold text-brand-dark">CHF</span>
                            <input type="number" name="amount" class="form-control bg-light border-0 shadow-none ps-0" placeholder="0.00" min="0.01" step="0.01" required autofocus>
                        </div>
                        @error('amount') <div class="text-danger x-small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">DESCRIPTION / REFERENCE</label>
                        <input type="text" name="description" class="form-control rounded-3 border bg-light p-3" placeholder="e.g. Bank Deposit Verified" required>
                        @error('description') <div class="text-danger x-small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-brand btn-lg rounded-3 py-3 fw-bold">
                            <i class="bi bi-shield-check me-2"></i> Approve & Credit Wallet
                        </button>
                        <a href="{{ route('admin.wallets.index') }}" class="btn btn-light btn-lg rounded-3 py-3 text-muted fw-bold">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
