@extends('layouts.agent')

@section('page_title', 'Edit Customer')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Update Customer Details</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('agent.customers.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}" required>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <a href="{{ route('agent.customers.show', $customer->id) }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
