@extends('layouts.admin')

@section('page_title', 'FX Rate Management')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-end">
        <button class="btn btn-brand d-inline-flex align-items-center" id="createNewFx">
            <i class="bi bi-plus-lg me-2"></i> Add New FX Rate
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm card-premium">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-brand-dark">Live Exchange Rates</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle data-table w-100">
                <thead class="bg-light">
                    <tr>
                        <th width="50px">No</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Rate</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th width="180px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="ajaxModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-brand-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="modelHeading"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="fxForm" name="fxForm" class="form-horizontal">
                   <input type="hidden" name="fx_id" id="fx_id">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-brand-dark">From Currency</label>
                        <input type="text" name="from_currency" id="from_currency" class="form-control form-control-premium" value="CHF" maxlength="3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-brand-dark">To Currency</label>
                        <input type="text" name="to_currency" id="to_currency" class="form-control form-control-premium" value="INR" maxlength="3" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-brand-dark">Rate</label>
                        <input type="number" step="0.000001" name="rate" id="rate" class="form-control form-control-premium" placeholder="Enter Rate" required>
                    </div>
                    <div class="text-end pt-2">
                        <button type="button" class="btn btn-light me-2 px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-brand px-5 py-2" id="saveBtn">Save Rate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
  $(function () {
    
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.fx.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'from_currency', name: 'from_currency', className: 'fw-bold text-brand-dark'},
            {data: 'to_currency', name: 'to_currency', className: 'fw-bold text-brand-dark'},
            {data: 'rate', name: 'rate', className: 'text-primary fw-bold'},
            {data: 'is_active', name: 'is_active', render: function(data){
                return data ? '<span class="badge bg-success-subtle text-success border border-success-subtle px-3">Active</span>' : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3">Inactive</span>';
            }},
            {data: 'updated_at', name: 'updated_at', render: function(data){
                return '<span class="text-muted small">' + new Date(data).toLocaleString() + '</span>';
            }},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            searchPlaceholder: "Search currencies...",
            search: ""
        }
    });

    $('.dataTables_filter input').addClass('form-control form-control-sm shadow-none border-0 bg-light px-3 py-2').css('width', '250px');
     
    $('#createNewFx').click(function () {
        $('#saveBtn').val("create-fx");
        $('#fx_id').val('');
        $('#fxForm').trigger("reset");
        $('#modelHeading').html("Add New FX Rate");
        $('#ajaxModal').modal('show');
    });
    
    $('body').on('click', '.editFx', function () {
      var fx_id = $(this).data('id');
      $.get("{{ route('admin.fx.index') }}" +'/' + fx_id +'/edit', function (data) {
          $('#modelHeading').html("Edit FX Rate");
          $('#saveBtn').val("edit-user");
          $('#ajaxModal').modal('show');
          $('#fx_id').val(data.id);
          $('#from_currency').val(data.from_currency);
          $('#to_currency').val(data.to_currency);
          $('#rate').val(data.rate);
      })
    });
    
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
    
        $.ajax({
          data: $('#fxForm').serialize(),
          url: "{{ route('admin.fx.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              $('#fxForm').trigger("reset");
              $('#ajaxModal').modal('hide');
              table.draw();
              $('#saveBtn').html('Save Rate');
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Rate');
          }
      });
    });
    
    $('body').on('click', '.deleteFx', function () {
        var fx_id = $(this).data('id');
        if(confirm("Are you sure you want to delete this exchange rate? This action cannot be undone.")){
            $.ajax({
                type: "DELETE",
                url: "{{ route('admin.fx.index') }}"+'/'+fx_id,
                success: function (data) {
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });
     
  });
</script>
@endpush
