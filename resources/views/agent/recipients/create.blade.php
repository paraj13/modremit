@extends('layouts.agent')

@section('page_title', 'Add New Recipient')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-4 border-0 text-center">
                <h4 class="fw-bold mb-0">Add Beneficiary</h4>
                <p class="text-muted small mb-0">For Customer: <span class="text-primary fw-bold">{{ $customer->name }}</span></p>
            </div>
            <div class="card-body p-5 pt-2">
                <form action="{{ route('agent.recipients.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">RECIPIENT FULL NAME</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-person-fill text-muted"></i></span>
                            <input type="text" name="name" class="form-control bg-light border-0 shadow-none @error('name') is-invalid @enderror" placeholder="Receiver Name" required>
                        </div>
                        @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small text-muted">BANK NAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-bank text-muted"></i></span>
                                <input type="text" name="bank_name" class="form-control bg-light border-0 shadow-none @error('bank_name') is-invalid @enderror" placeholder="State Bank of India" required>
                            </div>
                            @error('bank_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small text-muted">ACCOUNT NUMBER</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-credit-card-2-front text-muted"></i></span>
                                <input type="text" name="account_number" class="form-control bg-light border-0 shadow-none @error('account_number') is-invalid @enderror" placeholder="00000000000" required>
                            </div>
                            @error('account_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small text-muted">IFSC CODE</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-hash text-muted"></i></span>
                                <input type="text" name="ifsc_code" class="form-control bg-light border-0 shadow-none @error('ifsc_code') is-invalid @enderror" placeholder="SBIN0001234">
                            </div>
                            @error('ifsc_code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small text-muted">UPI ID (OPTIONAL)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-qr-code text-muted"></i></span>
                                <input type="text" name="upi_id" class="form-control bg-light border-0 shadow-none @error('upi_id') is-invalid @enderror" placeholder="username@upi">
                            </div>
                            @error('upi_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">DESTINATION COUNTRY</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-globe text-muted"></i></span>
                            <input type="text" name="country" class="form-control bg-light border-0 shadow-none" value="India" readonly required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 pt-2">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                             <i class="bi bi-plus-circle-fill me-2"></i> Save Recipient
                        </button>
                        <a href="{{ route('agent.customers.show', $customer->id) }}" class="btn btn-light rounded-pill py-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
