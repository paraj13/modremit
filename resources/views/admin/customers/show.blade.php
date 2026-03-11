@extends('layouts.admin')

@section('page_title', 'Customer Details')

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-0 fw-bold">Customer Info</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td class="text-muted small">Name</td><td class="fw-bold">{{ $customer->name }}</td></tr>
                    <tr><td class="text-muted small">Email</td><td class="fw-bold">{{ $customer->email }}</td></tr>
                    <tr><td class="text-muted small">Phone</td><td class="fw-bold">{{ $customer->phone }}</td></tr>
                    <tr><td class="text-muted small">Agent</td><td class="fw-bold text-primary">{{ $customer->agent->name }}</td></tr>
                    <tr><td class="text-muted small">KYC</td><td><span class="badge bg-{{ $customer->kyc_status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($customer->kyc_status) }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0 fw-bold">Recipients ({{ $customer->recipients->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light small">
                        <tr><th>Name</th><th>Bank Details</th></tr>
                    </thead>
                    <tbody>
                        @foreach($customer->recipients as $recipient)
                        <tr>
                            <td>{{ $recipient->name }}</td>
                            <td class="small">{{ $recipient->bank_name }} ({{ $recipient->account_number }})</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
