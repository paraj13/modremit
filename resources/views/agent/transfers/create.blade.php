@extends('layouts.agent')

@section('page_title', 'New Money Transfer')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-primary py-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                        <i class="bi bi-send-fill text-white h4 mb-0"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-white">Initiate Remittance</h4>
                        <p class="text-white text-opacity-75 small mb-0">Send money securely from CHF to INR</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <form id="transferForm" action="{{ route('agent.transfers.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-4 p-md-5 border-bottom">
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">1</span>
                            <h5 class="fw-bold mb-0 text-dark">Select Beneficiary</h5>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">SENDER (CUSTOMER)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                                    <select name="customer_id" id="customer_select" class="form-select bg-light border-0 shadow-none px-3 py-2" required>
                                        <option value="">Choose a verified customer...</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ (isset($selectedCustomer) && $selectedCustomer->id == $customer->id) ? 'selected' : '' }} data-recipients='@json($customer->recipients)'>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="recipient_wrapper" style="{{ isset($selectedCustomer) ? '' : 'display:none;' }}">
                                <label class="form-label fw-bold small text-muted">RECIPIENT</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person-check text-muted"></i></span>
                                    <select name="recipient_id" id="recipient_select" class="form-select bg-light border-0 shadow-none px-3 py-2" required>
                                        <option value="">Select recipient...</option>
                                        @if(isset($selectedCustomer))
                                            @foreach($selectedCustomer->recipients as $recipient)
                                                <option value="{{ $recipient->id }}">{{ $recipient->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 p-md-5 border-bottom bg-light bg-opacity-50">
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">2</span>
                            <h5 class="fw-bold mb-0 text-dark">Amount & Exchange Rate</h5>
                        </div>

                        <div class="row g-4 align-items-end">
                            <div class="col-md-7">
                                <label class="form-label fw-bold small text-muted">SEND AMOUNT</label>
                                <div class="input-group input-group-lg bg-white rounded-3 shadow-none border overflow-hidden">
                                    <span class="input-group-text bg-white border-0 px-4 fw-bold text-dark">CHF</span>
                                    <input type="number" name="chf_amount" id="chf_amount" class="form-control border-0 shadow-none ps-0" step="0.01" min="10" placeholder="0.00" required>
                                    <button type="button" id="get_quote_btn" class="btn btn-primary px-4 fw-bold">Get Live Quote</button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div id="quote_display" class="p-3 bg-white border border-primary border-opacity-25 rounded-3" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small text-muted fw-bold">RECIPIENT GETS</span>
                                        <span class="h4 mb-0 fw-bold text-primary">₹ <span id="quote_inr">0.00</span></span>
                                    </div>
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Rate: 1 CHF = <span id="quote_rate" class="fw-bold text-dark">0.00</span> INR</span>
                                        <span class="text-muted">Fee: <span id="quote_fee" class="fw-bold text-danger">0.00</span> CHF</span>
                                    </div>
                                    <div class="mt-2 pt-2 border-top x-small text-muted d-flex justify-content-between">
                                        <span>Ref: <span id="quote_id"></span></span>
                                        <span class="text-primary fw-bold"><i class="bi bi-clock-history me-1"></i> Valid 5m</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">3</span>
                            <h5 class="fw-bold mb-0 text-dark">Finalize & Memo</h5>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">REMITTANCE PURPOSE / NOTES</label>
                            <textarea name="notes" class="form-control bg-light border-0 shadow-none px-3 py-3" rows="3" placeholder="e.g. Family maintenance, gift..."></textarea>
                        </div>

                        <div class="d-grid pt-2">
                            <button type="submit" id="submit_btn" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3 fw-bold" disabled>
                                 <i class="bi bi-lock-fill me-2"></i> Confirm and Execute Transfer
                            </button>
                        </div>
                        <p class="text-center mt-3 text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Funds will be debited from Agent Wallet immediately upon confirmation.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customer_select');
    const recipientSelect = document.getElementById('recipient_select');
    const recipientWrapper = document.getElementById('recipient_wrapper');
    const getQuoteBtn = document.getElementById('get_quote_btn');
    const chfAmountInput = document.getElementById('chf_amount');
    const submitBtn = document.getElementById('submit_btn');

    customerSelect.addEventListener('change', function() {
        const customerId = this.value;
        if (!customerId) {
            recipientWrapper.style.display = 'none';
            return;
        }

        const option = this.options[this.selectedIndex];
        const recipients = JSON.parse(option.getAttribute('data-recipients') || '[]');
        
        recipientSelect.innerHTML = '<option value="">Select recipient...</option>';
        recipients.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.id;
            opt.textContent = r.name;
            recipientSelect.appendChild(opt);
        });
        
        recipientWrapper.style.display = 'block';
    });

    getQuoteBtn.addEventListener('click', function() {
        const amount = chfAmountInput.value;
        if (!amount || amount < 10) {
            alert('Please enter a valid amount (Min 10 CHF)');
            return;
        }

        getQuoteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Fetching...';
        getQuoteBtn.disabled = true;

        fetch('{{ route("agent.transfers.quote") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ chf_amount: amount })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('quote_rate').textContent = data.rate;
            document.getElementById('quote_inr').textContent = parseFloat(data.inr_amount).toLocaleString('en-IN', {maximumFractionDigits: 2});
            document.getElementById('quote_fee').textContent = data.fee;
            document.getElementById('quote_id').textContent = data.revolut_quote_id ? data.revolut_quote_id.substring(0, 8) : 'LCL-001';
            
            document.getElementById('quote_display').style.display = 'block';
            submitBtn.disabled = false;
        })
        .catch(error => {
            alert('Failed to fetch quote. Please try again.');
        })
        .finally(() => {
            getQuoteBtn.innerHTML = 'Get Live Quote';
            getQuoteBtn.disabled = false;
        });
    });
});
</script>
@endpush
