@extends('layouts.agent')

@section('title', 'Top Up Wallet')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-brand-dark text-white p-4">
                <h5 class="fw-bold mb-0">Manual Top-Up Request</h5>
                <p class="text-brand-lime small mb-0 opacity-75">Submit a request to the admin to credit your wallet.</p>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info border-0 rounded-4 p-4 mb-4">
                    <div class="d-flex">
                        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">How it works:</h6>
                            <p class="small mb-0">After submitting this request, please transfer the funds to our company bank account. Once the payment is verified, the admin will credit your wallet balance.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('agent.wallet.checkout') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">TARGET AMOUNT (CHF)</label>
                        <div class="input-group input-group-lg bg-light rounded-3 overflow-hidden border">
                            <span class="input-group-text bg-light border-0 px-4 fw-bold text-brand-dark">CHF</span>
                            <input type="number" name="amount" class="form-control bg-light border-0 shadow-none ps-0" placeholder="0.00" min="10" step="0.01" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">PAYMENT METHOD</label>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center">
                                    <i class="bi bi-credit-card fs-4 me-2 text-primary"></i>
                                    <span class="small fw-bold">Credit Card</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-brand btn-lg rounded-3 py-3 fw-bold">
                            <i class="bi bi-credit-card-fill me-2"></i> Pay with Card (Stripe)
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('agent.wallet.index') }}" class="text-muted text-decoration-none small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Back to Wallet
            </a>
        </div>
    </div>
</div>
@endsection
