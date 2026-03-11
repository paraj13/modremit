@extends('layouts.admin')

@section('page_title', 'All Recipients')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="mb-0 fw-bold">Global Beneficiary Directory</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle recipient-table w-100">
                <thead class="bg-light">
                    <tr>
                        <th width="50px">No</th>
                        <th>Name</th>
                        <th>Customer</th>
                        <th>Agent</th>
                        <th>Bank Details</th>
                        <th>Country</th>
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
    var table = $('.recipient-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.recipients.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name', className: 'fw-bold'},
            {data: 'customer', name: 'customer.name'},
            {data: 'agent', name: 'customer.agent.name'},
            {data: 'bank_details', name: 'bank_name'},
            {data: 'country', name: 'country'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            searchPlaceholder: "Search recipients...",
            search: ""
        }
    });

    $('.dataTables_filter input').addClass('form-control form-control-sm shadow-none border-0 bg-light px-3 py-2').css('width', '250px');
  });
</script>
@endpush
