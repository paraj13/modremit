@extends('layouts.admin')

@section('page_title', 'Edit Customer')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-premium shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0 text-brand-dark"><i class="bi bi-pencil-square me-2 text-primary"></i> Update Customer Information</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" id="editCustomerForm">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">FULL NAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-person text-brand-dark"></i></span>
                                <input type="text" name="name" class="form-control form-control-premium @error('name') is-invalid @enderror" value="{{ old('name', $customer->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">EMAIL ADDRESS</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-brand-dark"></i></span>
                                <input type="email" name="email" class="form-control form-control-premium @error('email') is-invalid @enderror" value="{{ old('email', $customer->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">PHONE NUMBER</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-phone text-brand-dark"></i></span>
                                <input type="text" name="phone" class="form-control form-control-premium @error('phone') is-invalid @enderror" value="{{ old('phone', $customer->phone) }}" required>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">ASSIGNED AGENT</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-person-workspace text-brand-dark"></i></span>
                                <select name="agent_id" class="form-select form-control-premium @error('agent_id') is-invalid @enderror">
                                    <option value="">None (Self-Registered)</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('agent_id', $customer->agent_id) == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->name }} ({{ $agent->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-2">
                        <button type="submit" class="btn btn-brand px-5 py-3 fw-bold">
                            <i class="bi bi-check-circle me-2"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-brand-outline px-5 py-3 fw-bold">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    window.initGlobalValidation('editCustomerForm', {
        name: { required: true, minlength: 2 },
        email: { required: true, email: true },
        phone: { required: true, phoneFormat: true }
    });
});
</script>
@endpush
@endsection
