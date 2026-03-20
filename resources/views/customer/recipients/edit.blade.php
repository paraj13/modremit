@extends('layouts.customer')

@section('page_title', 'Edit Beneficiary')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-brand-dark py-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-brand-lime rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-pencil-square text-brand-dark h4 mb-0"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-white">Edit Beneficiary</h4>
                        <p class="text-brand-lime small mb-0 opacity-75">Update details for {{ $recipient->name }}</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('customer.recipients.update', $recipient->id) }}" method="POST" id="recipientEditForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4 mb-4">
                        {{-- Full Name --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Recipient Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-0 px-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name', $recipient->name) }}" placeholder="Receiver's name" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address <span class="text-muted opacity-50">(Optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-0 px-3 py-2 @error('email') is-invalid @enderror" value="{{ old('email', $recipient->email) }}" placeholder="For notifications">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-brand-dark mb-0 border-bottom pb-2">Account Details</h6>
                        </div>

                        {{-- Country --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Destination Country</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-geo-alt text-muted"></i></span>
                                <select name="country" class="form-select bg-light border-0 px-3 py-2 @error('country') is-invalid @enderror">
                                    <option value="">Select country...</option>
                                    @foreach(\App\Constants\CountryCurrency::COUNTRIES as $c)
                                        <option value="{{ $c['name'] }}" {{ old('country', $recipient->country) == $c['name'] ? 'selected' : '' }}>
                                            {{ $c['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Bank Name --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Bank Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-bank text-muted"></i></span>
                                <input type="text" name="bank_name" class="form-control bg-light border-0 px-3 py-2 @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $recipient->bank_name) }}" placeholder="e.g. HDFC Bank" required>
                                @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Account Number --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Account Number / IBAN</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-hash text-muted"></i></span>
                                <input type="text" name="account_number" class="form-control bg-light border-0 px-3 py-2 @error('account_number') is-invalid @enderror" value="{{ old('account_number', $recipient->account_number) }}" placeholder="Account no" required>
                                @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- IFSC Code --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">IFSC Code <span class="text-muted opacity-50">(Optional)</span></label>
                            <input type="text" name="ifsc_code" class="form-control bg-light border-0 px-3 py-2" value="{{ old('ifsc_code', $recipient->ifsc_code) }}" placeholder="For India">
                        </div>

                        {{-- UPI ID --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">UPI ID <span class="text-muted opacity-50">(Optional)</span></label>
                            <input type="text" name="upi_id" class="form-control bg-light border-0 px-3 py-2" value="{{ old('upi_id', $recipient->upi_id) }}" placeholder="username@upi">
                        </div>

                         {{-- IBAN --}}
                         <div class="col-md-4">
                            <label class="form-label small fw-bold">IBAN <span class="text-muted opacity-50">(Optional)</span></label>
                            <input type="text" name="iban" class="form-control bg-light border-0 px-3 py-2" value="{{ old('iban', $recipient->iban) }}" placeholder="For Europe">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('customer.recipients.index') }}" class="btn btn-light px-5 py-2 rounded-pill">Cancel</a>
                        <button type="submit" class="btn btn-brand px-5 py-2 rounded-pill fw-bold">Update Beneficiary</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    window.initGlobalValidation('recipientEditForm', {
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
