@extends('layouts.admin')

@section('page_title', 'Pending Agents')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('admin.agents.index') }}" class="btn btn-brand-outline d-inline-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Back to Agents
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm card-premium">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Agent Applications</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle pending-agent-table w-100">
                <thead class="bg-light">
                    <tr>
                        <th width="50px">No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Applied Date</th>
                        <th width="180px">Actions</th>
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
    var table = $('.pending-agent-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.agents.pending') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name', className: 'fw-bold'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'created_at', name: 'created_at', render: function(data){
                return new Date(data).toLocaleDateString();
            }},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            searchPlaceholder: "Search applications...",
            search: ""
        }
    });

    $('.dataTables_filter input').addClass('form-control form-control-sm shadow-none border-0 bg-light px-3 py-2').css('width', '250px');
  });
</script>
@endpush
