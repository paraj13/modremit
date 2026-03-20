@extends('layouts.customer')

@section('page_title', 'Add New Recipient')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-brand-dark py-4 px-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-brand-lime rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-plus-fill text-brand-dark fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">Add Beneficiary</h5>
                        <p class="text-brand-lime small mb-0 opacity-75">Save details for faster future transfers</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('customer.recipients.store') }}" method="POST" id="recipientForm">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">FULL NAME (As per bank record)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-0 px-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">EMAIL ADDRESS (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-0 px-3 py-2 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="For notifications">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">BANK NAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-bank text-muted"></i></span>
                                <input type="text" name="bank_name" class="form-control bg-light border-0 px-3 py-2 @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}" placeholder="e.g. HDFC Bank">
                                @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">COUNTRY</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-geo-alt text-muted"></i></span>
                                <select name="country" class="form-select bg-light border-0 px-3 py-2 @error('country') is-invalid @enderror">
                                    <option value="">Select country...</option>
                                    @foreach(\App\Constants\CountryCurrency::COUNTRIES as $c)
                                        <option value="{{ $c['name'] }}" {{ old('country') == $c['name'] ? 'selected' : '' }}>
                                            {{ $c['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">ACCOUNT NUMBER / IBAN</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-hash text-muted"></i></span>
                                <input type="text" name="account_number" class="form-control bg-light border-0 px-3 py-2 @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}" placeholder="Account or IBAN">
                                @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">IFSC / SWIFT / SORT CODE</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-shield-check text-muted"></i></span>
                                <input type="text" name="ifsc_code" class="form-control bg-light border-0 px-3 py-2" value="{{ old('ifsc_code') }}" placeholder="Bank code">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">UPI ID (FOR INDIA ONLY - OPTIONAL)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-qr-code-scan text-muted"></i></span>
                                <input type="text" name="upi_id" class="form-control bg-light border-0 px-3 py-2" value="{{ old('upi_id') }}" placeholder="yourname@upi">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-5 pt-3">
                        <button type="submit" class="btn btn-brand py-3 fw-bold px-5 rounded-4 shadow-sm flex-grow-1">
                            <i class="bi bi-check-circle-fill me-2"></i> Save Recipient
                        </button>
                        <a href="{{ route('customer.recipients.index') }}" class="btn btn-light py-3 fw-bold px-4 rounded-4 text-muted border">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    window.initGlobalValidation('recipientForm', {
        name: { required: true, minlength: 3 },
        email: { email: true },
        bank_name: { required: true },
        country: { required: true },
        account_number: { required: true, minlength: 5 }
    }, {
        name: "Please enter recipient's full legal name",
        bank_name: "Please provide the bank name",
        country: "Please select a country",
        account_number: "Valid account number or IBAN is required"
    });
});
</script>
@endpush
@endsection
