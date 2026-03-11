@extends('layouts.admin')

@section('page_title', 'Recipient Details')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-0 fw-bold">Beneficiary Profile</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td class="text-muted small">Name</td><td class="fw-bold">{{ $recipient->name }}</td></tr>
                    <tr><td class="text-muted small">Bank</td><td class="fw-bold">{{ $recipient->bank_name }}</td></tr>
                    <tr><td class="text-muted small">AC Number</td><td class="fw-bold font-monospace">{{ $recipient->account_number }}</td></tr>
                    <tr><td class="text-muted small">IFSC / UPI</td><td class="fw-bold">{{ $recipient->ifsc_code ?? $recipient->upi_id ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted small">Country</td><td class="fw-bold">{{ $recipient->country }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-0 fw-bold">Associated Customer & Agent</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td class="text-muted small">Sender Name</td><td class="fw-bold text-primary">{{ $recipient->customer->name }}</td></tr>
                    <tr><td class="text-muted small">Managed By</td><td class="fw-bold text-success">{{ $recipient->customer->agent->name }}</td></tr>
                </table>
                <a href="{{ route('admin.customers.show', $recipient->customer_id) }}" class="btn btn-sm btn-outline-primary w-100 mt-2 rounded-pill">View Sender Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection
