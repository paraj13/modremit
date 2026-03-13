@extends('layouts.admin')

@section('page_title', 'Create Agent')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-brand-dark py-4 border-0 text-center">
                <h3 class="fw-bold mb-0 text-white"><i class="bi bi-person-plus me-2"></i>Create New Agent Account</h3>
                <p class="text-brand-lime small mb-0 opacity-75 mt-1">Fill in the details below to register a new platform agent. A default password will be assigned automatically.</p>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('admin.agents.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">FULL NAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-0 shadow-none @error('name') is-invalid @enderror" placeholder="Agent's full name" required>
                            </div>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">EMAIL ADDRESS</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-0 shadow-none @error('email') is-invalid @enderror" placeholder="agent@example.com" required>
                            </div>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">PHONE NUMBER</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-telephone text-muted"></i></span>
                                <input type="text" name="phone" class="form-control bg-light border-0 shadow-none @error('phone') is-invalid @enderror" placeholder="+41 XX XXX XX XX">
                            </div>
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">DEFAULT PASSWORD</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-0 shadow-none" value="12345678" readonly>
                            </div>
                            <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Agent should change this on first login.</small>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-5 pt-3 border-top justify-content-end">
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-light px-5 py-2 rounded-pill">Cancel</a>
                        <button type="submit" class="btn btn-brand px-5 py-2 rounded-pill fw-bold">
                            <i class="bi bi-person-check me-2"></i> Create Agent
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
