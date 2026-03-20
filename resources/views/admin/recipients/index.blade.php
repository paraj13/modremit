@extends('layouts.admin')

@section('page_title', 'All Recipients')

@section('content')
<div class="table-premium-container">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h5 class="mb-0 fw-bold text-brand-dark">Platform Recipient List</h5>
        <div class="search-box">
            <input type="text" class="form-control rounded-pill px-4 shadow-sm border-0 bg-white" placeholder="Search beneficiaries..." data-search-target="#recipientsTable" style="min-width: 280px;">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-premium" id="recipientsTable">
            <thead>
                <tr>
                    <th width="50px">Status</th>
                    <th>Recipient</th>
                    <th>Customer</th>
                    <th>Managing Agent</th>
                    <th>Bank Details / ID</th>
                    <th width="80px" class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recipients as $index => $recipient)
                <tr>
                    <td>
                        <span class="status-pill status-{{ $recipient->status ?? 'approved' }} px-3">
                            {{ strtoupper($recipient->status ?? 'ACTIVE') }}
                        </span>
                    </td>
                    <td class="fw-bold">{{ $recipient->name }}</td>
                    <td>{{ $recipient->customer->name ?? 'N/A' }}</td>
                    <td><span class="badge bg-light text-brand-dark border">{{ $recipient->customer->agent->name ?? 'N/A' }}</span></td>
                    <td>
                        @if($recipient->upi_id)
                            <span class="text-primary fw-bold">UPI: {{ $recipient->upi_id }}</span>
                        @else
                            <div>{{ $recipient->bank_name }}</div>
                            <small class="text-muted">{{ $recipient->account_number }}</small>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.recipients.show', $recipient->id) }}" class="btn btn-sm btn-outline-dark rounded-3 px-3">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">No recipients found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 px-3">
        {{ $recipients->links() }}
    </div>
</div>
@endsection
