@extends('layouts.admin')

@section('page_title', 'Compliance Monitoring')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Flagged Transactions</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>Transaction</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="small">{{ $log->created_at->format('M d, H:i') }}</td>
                        <td>{{ $log->transaction->agent->name }}</td>
                        <td>
                            <div class="fw-bold">{{ number_format($log->transaction->chf_amount, 2) }} CHF</div>
                            <small class="text-muted">TX: #{{ str_pad($log->transaction->id, 6, '0', STR_PAD_LEFT) }}</small>
                        </td>
                        <td><span class="text-danger small">{{ $log->reason }}</span></td>
                        <td>
                            @if($log->reviewed_at)
                                <span class="badge bg-success">Reviewed</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending Review</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.compliance.show', $log->id) }}" class="btn btn-sm btn-primary">Review</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">No compliance flags found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
