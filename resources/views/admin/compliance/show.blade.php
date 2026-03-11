@extends('layouts.admin')

@section('page_title', 'Review Flagged Transaction')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Transaction Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted small">Agent</label>
                        <div>{{ $log->transaction->agent->name }}</div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small">Customer</label>
                        <div>{{ $log->transaction->customer->name }}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted small">Amount</label>
                        <div class="h4 fw-bold">{{ number_format($log->transaction->chf_amount, 2) }} CHF</div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small">Reason for Flag</label>
                        <div class="text-danger fw-bold">{{ $log->reason }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Compliance Decision</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.compliance.review', $log->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Review Notes</label>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Document the findings..."></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-5">Mark as Reviewed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
