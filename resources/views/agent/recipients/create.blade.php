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
                <form action="{{ isset($recipient) ? route('agent.recipients.update', $recipient->id) : route('agent.recipients.store') }}" method="POST">
                    @csrf
                    @if(isset($recipient)) @method('PUT') @endif

                    {{-- Security tokens for create flow --}}
                    @if(!isset($recipient))
                        <input type="hidden" name="eid" value="{{ $eid ?? '' }}">
                        <input type="hidden" name="return_to" value="{{ request('return_to') }}">
                    @endif

                    <div class="row g-4">
                        {{-- Full Name --}}
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">RECIPIENT FULL NAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-person-fill text-muted"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-0 shadow-none @error('name') is-invalid @enderror"
                                    value="{{ old('name', $recipient->name ?? '') }}" placeholder="Receiver's full name" required>
                            </div>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Bank Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">BANK NAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-bank text-muted"></i></span>
                                <input type="text" name="bank_name" class="form-control bg-light border-0 shadow-none @error('bank_name') is-invalid @enderror"
                                    value="{{ old('bank_name', $recipient->bank_name ?? '') }}" placeholder="State Bank of India" required>
                            </div>
                            @error('bank_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Account Number --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">ACCOUNT NUMBER</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-credit-card-2-front text-muted"></i></span>
                                <input type="text" name="account_number" class="form-control bg-light border-0 shadow-none @error('account_number') is-invalid @enderror"
                                    value="{{ old('account_number', $recipient->account_number ?? '') }}" placeholder="00000000000" required>
                            </div>
                            @error('account_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- IFSC Code --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">IFSC CODE <span class="opacity-50">(optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-hash text-muted"></i></span>
                                <input type="text" name="ifsc_code" class="form-control bg-light border-0 shadow-none @error('ifsc_code') is-invalid @enderror"
                                    value="{{ old('ifsc_code', $recipient->ifsc_code ?? '') }}" placeholder="SBIN0001234">
                            </div>
                            @error('ifsc_code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- UPI ID --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">UPI ID <span class="opacity-50">(optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-qr-code text-muted"></i></span>
                                <input type="text" name="upi_id" class="form-control bg-light border-0 shadow-none @error('upi_id') is-invalid @enderror"
                                    value="{{ old('upi_id', $recipient->upi_id ?? '') }}" placeholder="username@upi">
                            </div>
                            @error('upi_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Destination Country --}}
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">DESTINATION COUNTRY</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-globe text-muted"></i></span>
                                <select name="country" class="form-select bg-light border-0 shadow-none @error('country') is-invalid @enderror" required>
                                    <option value="">Select country...</option>
                                    @foreach(\App\Constants\CountryCurrency::COUNTRIES as $c)
                                        <option value="{{ $c['name'] }}"
                                            {{ old('country', $recipient->country ?? 'India') === $c['name'] ? 'selected' : '' }}>
                                            {{ $c['name'] }} ({{ $c['currency'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('country') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-5 pt-3 border-top justify-content-end">
                        @if(isset($recipient))
                            <a href="{{ route('agent.customers.show', $recipient->customer_id) }}" class="btn btn-light px-5 py-2 rounded-pill">Cancel</a>
                        @else
                            <a href="{{ route('agent.customers.show', $customer->id) }}" class="btn btn-light px-5 py-2 rounded-pill">Cancel</a>
                        @endif
                        <button type="submit" class="btn btn-brand px-5 py-2 rounded-pill fw-bold">
                            <i class="bi {{ isset($recipient) ? 'bi-check-circle-fill' : 'bi-plus-circle-fill' }} me-2"></i>
                            {{ isset($recipient) ? 'Update Beneficiary' : 'Save Beneficiary' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
