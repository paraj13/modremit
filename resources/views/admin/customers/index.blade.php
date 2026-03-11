@extends('layouts.admin')

@section('page_title', 'All Customers')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="mb-0 fw-bold">Global Customer Directory</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle customer-table w-100">
                <thead class="bg-light">
                    <tr>
                        <th width="50px">No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Agent</th>
                        <th>KYC Status</th>
                        <th>Recipients</th>
                        <th width="80px">Action</th>
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
        ajax: "{{ route('admin.customers.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name', className: 'fw-bold'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'agent', name: 'agent.name'},
            {data: 'kyc_status', name: 'kyc_status'},
            {data: 'recipients_count', name: 'recipients_count', className: 'text-center'},
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
