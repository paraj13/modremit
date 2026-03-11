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

                <form>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">TARGET AMOUNT (CHF)</label>
                        <div class="input-group input-group-lg bg-light rounded-3 overflow-hidden border-0">
                            <span class="input-group-text bg-light border-0 px-4 fw-bold text-brand-dark">CHF</span>
                            <input type="number" class="form-control bg-light border-0 shadow-none ps-0" placeholder="0.00" min="10" step="0.01">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">DEPOSIT SLIP / PROOF (OPTIONAL)</label>
                        <input type="file" class="form-control rounded-3 border-0 bg-light p-3">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">ADDITIONAL NOTES</label>
                        <textarea class="form-control rounded-3 border-0 bg-light p-3" rows="3" placeholder="Reference number, bank name, etc."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="button" class="btn btn-brand btn-lg rounded-3 py-3 fw-bold" onclick="alert('This feature is coming soon! Please contact Admin for manual credit.')">
                            <i class="bi bi-send-fill me-2"></i> Submit Request
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
