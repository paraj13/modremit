@extends('layouts.customer')

@section('page_title', 'My Recipients')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h5 class="fw-bold text-brand-dark mb-1">Saved Beneficiaries</h5>
        <p class="text-muted small mb-0">Manage people and accounts you send money to regularly.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('customer.recipients.create') }}" class="btn btn-brand rounded-pill px-4">
            <i class="bi bi-plus-lg me-1"></i> Add New Recipient
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-premium mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 small fw-bold text-muted">NAME & BANK</th>
                            <th class="border-0 py-3 small fw-bold text-muted">ACCOUNT DETAILS</th>
                            <th class="border-0 py-3 small fw-bold text-muted">COUNTRY</th>
                            <th class="border-0 py-3 small fw-bold text-muted text-center pe-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recipients as $r)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-brand-dark small">{{ $r->name }}</div>
                                <div class="x-small text-muted">{{ $r->bank_name }}</div>
                            </td>
                            <td>
                                <div class="small fw-bold">No: {{ $r->account_number }}</div>
                                @if($r->ifsc_code) <div class="x-small text-muted">IFSC: {{ $r->ifsc_code }}</div> @endif
                                @if($r->iban) <div class="x-small text-muted">IBAN: {{ $r->iban }}</div> @endif
                            </td>
                            <td>
                                <span class="fi fi-{{ strtolower(\App\Constants\CountryCurrency::getFlagByCountry($r->country)) }} me-1"></span>
                                <span class="small">{{ $r->country }}</span>
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('customer.transfers.create', ['recipient_id' => $r->id]) }}" class="btn btn-sm btn-brand rounded-pill px-3 shadow-sm">Send</a>
                                    <a href="{{ route('customer.recipients.edit', $r->id) }}" class="btn btn-sm btn-brand rounded-pill px-3 shadow-sm">Edit</a>
                                    <a href="{{ route('customer.recipients.show', $r->id) }}" class="btn btn-sm btn-brand rounded-pill px-3 shadow-sm">View</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-people fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                <p class="text-muted mb-0">No recipients found. <a href="{{ route('customer.recipients.create') }}" class="fw-bold text-brand-dark">Add your first recipient</a>.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
