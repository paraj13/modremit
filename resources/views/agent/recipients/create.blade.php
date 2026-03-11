@extends('layouts.agent')

@section('page_title', 'Add Recipient')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Recipient Bank/UPI Details</h5>
                <small class="text-muted">For Customer: {{ $customer->name }}</small>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('agent.recipients.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Recipient Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="account_number" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">IFSC Code (for Banks)</label>
                            <input type="text" name="ifsc_code" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UPI ID (Optional)</label>
                            <input type="text" name="upi_id" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control" value="India" required>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('agent.customers.show', $customer->id) }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Save Recipient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
