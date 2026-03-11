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

<div class="table-premium-container">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h5 class="mb-0 fw-bold text-brand-dark">Live Exchange Rates</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-premium">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th width="180px" class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fxRates as $index => $fx)
                <tr>
                    <td>{{ $fxRates->firstItem() + $index }}</td>
                    <td class="fw-bold text-brand-dark">{{ $fx->from_currency }}</td>
                    <td class="fw-bold text-brand-dark">{{ $fx->to_currency }}</td>
                    <td class="text-brand-dark fw-bold">{{ number_format($fx->rate, 4) }}</td>
                    <td>
                        <span class="badge {{ $fx->is_active ? 'bg-brand-mint text-brand-dark' : 'bg-danger text-white' }} px-3">
                            {{ $fx->is_active ? 'ACTIVE' : 'INACTIVE' }}
                        </span>
                    </td>
                    <td><span class="text-muted small">{{ $fx->updated_at->format('M d, Y H:i') }}</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button data-id="{{ $fx->id }}" class="btn btn-sm btn-outline-dark rounded-3 px-3 editFx">Edit</button>
                            <button data-id="{{ $fx->id }}" class="btn btn-sm btn-outline-danger rounded-3 px-3 deleteFx">Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">No FX rates found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 px-3">
        {{ $fxRates->links() }}
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
          $('#saveBtn').val("edit-fx");
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
              location.reload(); // Simple reload for standard table
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
                    location.reload();
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
