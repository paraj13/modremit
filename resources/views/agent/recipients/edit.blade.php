@extends('layouts.agent')

@section('page_title', 'Edit Recipient')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Update Recipient Details</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('agent.recipients.update', $recipient->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Recipient Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $recipient->name }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" value="{{ $recipient->bank_name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="account_number" class="form-control" value="{{ $recipient->account_number }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">IFSC Code</label>
                            <input type="text" name="ifsc_code" class="form-control" value="{{ $recipient->ifsc_code }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UPI ID</label>
                            <input type="text" name="upi_id" class="form-control" value="{{ $recipient->upi_id }}">
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <a href="{{ route('agent.customers.show', $recipient->customer_id) }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Recipient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
