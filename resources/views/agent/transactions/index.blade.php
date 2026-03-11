@extends('layouts.agent')

@section('page_title', 'Transaction History')

@section('content')
<div class="card border-0 shadow-sm card-premium">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-brand-dark">My Transactions</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle transaction-table w-100">
                <thead class="bg-light">
                    <tr>
                        <th width="50px">No</th>
                        <th>Customer</th>
                        <th>Recipient</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="120px">Action</th>
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
        ajax: "{{ route('agent.transactions.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'customer', name: 'customer.name', className: 'fw-bold'},
            {data: 'recipient', name: 'recipient.name'},
            {data: 'amount', name: 'chf_amount', className: 'text-brand-dark fw-bold'},
            {data: 'status', name: 'status', render: function(data){
                let cls = 'bg-secondary';
                if(data === 'completed') cls = 'bg-success';
                if(data === 'processing') cls = 'bg-info';
                if(data === 'failed') cls = 'bg-danger';
                return `<span class="badge ${cls} px-3">${data.toUpperCase()}</span>`;
            }},
            {data: 'created_at', name: 'created_at', render: function(data){
                return `<span class="text-muted small">${new Date(data).toLocaleDateString()}</span>`;
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
