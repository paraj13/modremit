@extends('layouts.agent')

@section('page_title', 'Add New Customer')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Customer Information</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('agent.customers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="alert alert-info py-2 d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <small>Saving will automatically initiate KYC verification via Sumsub.</small>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('agent.customers.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Create Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
