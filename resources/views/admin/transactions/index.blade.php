@extends('layouts.admin')

@section('page_title', 'All Transactions')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Platform Transaction History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle transaction-table w-100">
                <thead class="bg-light">
                    <tr>
                        <th width="50px">No</th>
                        <th>Agent</th>
                        <th>Customer</th>
                        <th>Recipient</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>Status</th>
                        <th>Date</th>
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
    var table = $('.transaction-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.transactions.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'agent', name: 'agent.name'},
            {data: 'customer', name: 'customer.name'},
            {data: 'recipient', name: 'recipient.name'},
            {data: 'amount', name: 'chf_amount'},
            {data: 'rate', name: 'rate'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at', render: function(data){
                return new Date(data).toLocaleString();
            }},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            searchPlaceholder: "Search transactions...",
            search: ""
        }
    });

    $('.dataTables_filter input').addClass('form-control form-control-sm shadow-none border-0 bg-light px-3 py-2').css('width', '250px');
  });
</script>
@endpush
