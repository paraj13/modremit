@extends('layouts.agent')

@section('page_title', isset($recipient) ? 'Edit Beneficiary' : 'Add Beneficiary')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-brand-dark py-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-brand-lime rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi {{ isset($recipient) ? 'bi-pencil-square' : 'bi-person-plus' }} text-brand-dark h4 mb-0"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-white">{{ isset($recipient) ? 'Update Beneficiary' : 'Add Beneficiary' }}</h4>
                        <p class="text-brand-lime small mb-0 opacity-75">
                            @if(isset($recipient))
                                Editing: <strong>{{ $recipient->name }}</strong>
                            @else
                                For Customer: <strong>{{ $customer->name }}</strong>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-body p-5">
                <form action="{{ isset($recipient) ? route('agent.recipients.update', $recipient->id) : route('agent.recipients.store') }}" method="POST" id="recipientEditForm">
                    @csrf
                    @if(isset($recipient)) @method('PUT') @endif

                    {{-- Security tokens for create flow --}}
                    @if(!isset($recipient))
                        <input type="hidden" name="eid" value="{{ $eid ?? '' }}">
                        <input type="hidden" name="return_to" value="{{ request('return_to') }}">
                    @endif

                    <div class="row g-4">
                        {{-- Full Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">RECIPIENT FULL NAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-person-fill text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control bg-light border-0 shadow-none @error('name') is-invalid @enderror"
                                    value="{{ old('name', $recipient->name ?? '') }}" placeholder="Receiver's full name">
                            </div>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">EMAIL ADDRESS <span class="opacity-50">(Optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope-fill text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control bg-light border-0 shadow-none @error('email') is-invalid @enderror"
                                    value="{{ old('email', $recipient->email ?? '') }}" placeholder="For transfer notifications">
                            </div>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Bank Details Section --}}
                        <div class="col-12 mt-4">
                            <h5 class="fw-bold mb-3 text-brand-dark border-bottom pb-2">Bank Details</h5>
                        </div>

                        {{-- Bank Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">BANK NAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-bank text-muted"></i></span>
                                <input type="text" name="bank_name" id="bank_name" class="form-control bg-light border-0 shadow-none @error('bank_name') is-invalid @enderror"
                                    value="{{ old('bank_name', $recipient->bank_name ?? '') }}" placeholder="e.g. Chase, HDFC, Barclays">
                            </div>
                            @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Account Number --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">ACCOUNT NUMBER / IBAN</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-credit-card-2-front text-muted"></i></span>
                                <input type="text" name="account_number" id="account_number" class="form-control bg-light border-0 shadow-none @error('account_number') is-invalid @enderror"
                                    value="{{ old('account_number', $recipient->account_number ?? '') }}" placeholder="Standard acc no or full IBAN">
                            </div>
                            @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- IBAN (Separate field if needed) --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">IBAN <span class="opacity-50">(Optional)</span></label>
                            <input type="text" name="iban" class="form-control bg-light border-0 shadow-none"
                                value="{{ old('iban', $recipient->iban ?? '') }}" placeholder="For EUR/GBP">
                        </div>

                        {{-- SWIFT/BIC --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">SWIFT / BIC <span class="opacity-50">(Optional)</span></label>
                            <input type="text" name="swift_code" class="form-control bg-light border-0 shadow-none"
                                value="{{ old('swift_code', $recipient->swift_code ?? '') }}" placeholder="BANKXXXX">
                        </div>

                        {{-- Routing Number / ABA --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">ROUTING # / ABA <span class="opacity-50">(Optional)</span></label>
                            <input type="text" name="routing_number" class="form-control bg-light border-0 shadow-none"
                                value="{{ old('routing_number', $recipient->routing_number ?? '') }}" placeholder="For USD">
                        </div>

                        {{-- IFSC Code --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">IFSC CODE <span class="opacity-50">(Optional)</span></label>
                            <input type="text" name="ifsc_code" class="form-control bg-light border-0 shadow-none"
                                value="{{ old('ifsc_code', $recipient->ifsc_code ?? '') }}" placeholder="For INR">
                        </div>

                        {{-- Sort Code --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">SORT CODE <span class="opacity-50">(Optional)</span></label>
                            <input type="text" name="sort_code" class="form-control bg-light border-0 shadow-none"
                                value="{{ old('sort_code', $recipient->sort_code ?? '') }}" placeholder="For GBP (e.g. 102030)">
                        </div>

                        {{-- UPI ID --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">UPI ID <span class="opacity-50">(Optional)</span></label>
                            <input type="text" name="upi_id" class="form-control bg-light border-0 shadow-none"
                                value="{{ old('upi_id', $recipient->upi_id ?? '') }}" placeholder="username@upi">
                        </div>

                        {{-- Address Details Section --}}
                        <div class="col-12 mt-4">
                            <h5 class="fw-bold mb-3 text-brand-dark border-bottom pb-2">Recipient Address <span class="small fw-normal text-muted">(Required for USD, PHP, TRY)</span></h5>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">ADDRESS LINE 1</label>
                            <input type="text" name="address_line_1" class="form-control bg-light border-0 shadow-none"
                                value="{{ old('address_line_1', $recipient->address_line_1 ?? '') }}" placeholder="Street address">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">CITY</label>
                            <input type="text" name="city" class="form-control bg-light border-0 shadow-none"
                                value="{{ old('city', $recipient->city ?? '') }}" placeholder="City">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">POSTAL / ZIP CODE</label>
                            <input type="text" name="postal_code" class="form-control bg-light border-0 shadow-none"
                                value="{{ old('postal_code', $recipient->postal_code ?? '') }}" placeholder="Zip code">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">STATE / PROVINCE</label>
                            <input type="text" name="state" class="form-control bg-light border-0 shadow-none"
                                value="{{ old('state', $recipient->state ?? '') }}" placeholder="State code/name">
                        </div>

                        {{-- Destination Country --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">DESTINATION COUNTRY</label>
                            <select name="country" id="country" class="form-select bg-light border-0 shadow-none">
                                <option value="">Select country...</option>
                                @foreach(\App\Constants\CountryCurrency::COUNTRIES as $c)
                                    <option value="{{ $c['name'] }}"
                                        {{ old('country', $recipient->country ?? 'India') === $c['name'] ? 'selected' : '' }}>
                                        {{ $c['name'] }} ({{ $c['currency'] }})
                                    </option>
                                @endforeach
                            </select>
                            @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end align-items-center mt-5 pt-3 border-top gap-2">
                        
                        @if(isset($recipient))
                            <a href="{{ route('agent.customers.show', $recipient->customer_id) }}" 
                            class="btn btn-light px-4 py-2 rounded-pill me-2">
                            Cancel
                            </a>
                        @else
                            <a href="{{ route('agent.customers.show', $customer->id) }}" 
                            class="btn btn-light px-4 py-2 rounded-pill me-2">
                            Cancel
                            </a>
                        @endif

                        <button type="submit" class="btn btn-brand px-4 py-2 rounded-pill fw-bold">
                            <i class="bi {{ isset($recipient) ? 'bi-check-circle-fill' : 'bi-plus-circle-fill' }} me-2"></i>
                            {{ isset($recipient) ? 'Update Beneficiary' : 'Save Beneficiary' }}
                        </button>

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
        bank_name: { required: true },
        account_number: { required: true, minlength: 5 },
        country: { required: true }
    }, {
        name: "Please enter the recipient's full name",
        bank_name: "Bank name is required",
        account_number: "Account number or IBAN is required",
        country: "Please select destination country"
    });
});
</script>
@endpush
            </div>
        </div>
    </div>
</div>
@endsection
