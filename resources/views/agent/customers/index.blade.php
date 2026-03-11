@extends('layouts.agent')

@section('page_title', 'Customer Management')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-end">
        <a href="{{ route('agent.customers.create') }}" class="btn btn-brand">
            <i class="bi bi-person-plus-fill me-2"></i> Add New Customer
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm card-premium">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="mb-0 fw-bold text-brand-dark">My Registered Customers</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle customer-table w-100">
                <thead class="bg-light">
                    <tr>
                        <th width="50px">No</th>
                        <th>Name</th>
                        <th>Contact info</th>
                        <th>KYC Status</th>
                        <th>Recipients</th>
                        <th width="220px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
  $(function () {
    var table = $('.customer-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('agent.customers.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name', className: 'fw-bold text-brand-dark'},
            {data: 'contact', name: 'email'},
            {data: 'kyc_status', name: 'kyc_status'},
            {data: 'recipients_count', name: 'recipients_count', searchable: false, render: function(data){
                return '<span class="badge bg-brand-mint text-brand-dark border-0 px-3">' + data + ' Recipients</span>';
            }},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            searchPlaceholder: "Search customers...",
            search: ""
        }
    });

    $('.dataTables_filter input').addClass('form-control form-control-sm shadow-none border-0 bg-light px-3 py-2').css('width', '250px');
  });
</script>
@endpush
