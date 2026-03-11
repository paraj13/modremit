@extends('layouts.admin')

@section('page_title', 'Compliance Monitoring')

@section('content')
<div class="card border-0 shadow-sm card-premium">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-brand-dark">Flagged Transactions for Review</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle compliance-table w-100">
                <thead class="bg-light">
                    <tr>
                        <th width="50px">No</th>
                        <th>Agent</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Flagged Date</th>
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
    var table = $('.compliance-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.compliance.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'agent', name: 'transaction.agent.name', className: 'fw-bold'},
            {data: 'customer', name: 'transaction.customer.name'},
            {data: 'amount', name: 'transaction.chf_amount', className: 'text-brand-dark fw-bold'},
            {data: 'status', name: 'status', render: function(data){
                let cls = 'bg-secondary';
                if(data === 'pending') cls = 'bg-warning text-dark';
                if(data === 'cleared') cls = 'bg-success';
                if(data === 'escalated') cls = 'bg-danger';
                return `<span class="badge ${cls} px-3">${data.toUpperCase()}</span>`;
            }},
            {data: 'created_at', name: 'created_at', render: function(data){
                return `<span class="text-muted small">${new Date(data).toLocaleString()}</span>`;
            }},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            searchPlaceholder: "Search logs...",
            search: ""
        }
    });

    $('.dataTables_filter input').addClass('form-control form-control-sm shadow-none border-0 bg-light px-3 py-2').css('width', '250px');
  });
</script>
@endpush
