@extends('layouts.admin')

@section('page_title', 'Compliance Monitoring')

@section('content')
<div class="table-premium-container">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h5 class="mb-0 fw-bold text-brand-dark">Flagged Transactions for Review</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-premium">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>Agent</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Flagged Date</th>
                    <th width="120px" class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                <tr>
                    <td>{{ $logs->firstItem() + $index }}</td>
                    <td class="fw-bold">{{ $log->transaction->agent->name ?? 'N/A' }}</td>
                    <td>{{ $log->transaction->customer->name ?? 'N/A' }}</td>
                    <td class="text-brand-dark fw-bold">CHF {{ number_format($log->transaction->chf_amount, 2) }}</td>
                    <td>
                        <span class="status-pill status-{{ $log->status }}">
                            {{ strtoupper($log->status) }}
                        </span>
                    </td>
                    <td><span class="text-muted small">{{ $log->created_at->format('M d, Y H:i') }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('admin.compliance.show', $log->id) }}" class="btn btn-sm btn-outline-dark rounded-3 px-3">Review</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">No compliance logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 px-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
