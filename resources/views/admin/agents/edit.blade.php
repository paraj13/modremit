@extends('layouts.admin')

@section('page_title', 'Edit Agent')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-brand-dark py-4 border-0 text-center">
                <h3 class="fw-bold mb-0 text-white"><i class="bi bi-person-gear me-2"></i>Edit Agent Account</h3>
                <p class="text-brand-lime small mb-0 opacity-75 mt-1">Editing: <strong>{{ $agent->name }}</strong></p>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('admin.agents.update', $agent->id) }}" method="POST" id="agentEditForm">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">FULL NAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control bg-light border-0 shadow-none @error('name') is-invalid @enderror"
                                    value="{{ old('name', $agent->name) }}" placeholder="Agent's full name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">EMAIL ADDRESS</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control bg-light border-0 shadow-none @error('email') is-invalid @enderror"
                                    value="{{ old('email', $agent->email) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">PHONE NUMBER</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-telephone text-muted"></i></span>
                                <input type="text" name="phone" id="phone" class="form-control bg-light border-0 shadow-none @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $agent->phone) }}" placeholder="+41 XX XXX XX XX">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NEW PASSWORD <span class="opacity-50">(leave blank to keep current)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-0 shadow-none @error('password') is-invalid @enderror"
                                    placeholder="Leave blank to keep current">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-5 pt-3 border-top justify-content-end">
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-light px-5 py-2 rounded-pill">Cancel</a>
                        <button type="submit" class="btn btn-brand px-5 py-2 rounded-pill fw-bold">
                            <i class="bi bi-check-circle me-2"></i> Update Agent
                        </button>
                    </div>
                </form>

@push('scripts')
<script>
$(document).ready(function() {
    window.initGlobalValidation('agentEditForm', {
        name: { required: true, minlength: 2 },
        email: { required: true, email: true },
        phone: { required: true, phoneFormat: true },
        password: { minlength: 6 }
    }, {
        name: "Full name is required",
        email: {
            required: "Email address is required",
            email: "Please enter a valid email"
        },
        phone: "Valid phone number is required",
        password: {
            minlength: "Password must be at least 6 characters"
        }
    });
});
</script>
@endpush
            </div>
        </div>
    </div>
</div>
@endsection
