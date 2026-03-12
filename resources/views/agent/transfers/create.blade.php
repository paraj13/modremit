@extends('layouts.agent')

@section('page_title', 'New Money Transfer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-brand-dark py-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-brand-lime rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-send-fill text-brand-dark h4 mb-0"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-white">Initiate Remittance</h4>
                        <p class="text-brand-lime small mb-0 opacity-75">Send money securely from CHF to multiple currencies</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0 bg-white">
                <form id="transferForm" action="{{ route('agent.transfers.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-4 p-md-5 border-bottom">
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge-premium me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%;">1</span>
                            <h5 class="fw-bold mb-0 text-brand-dark">Select Beneficiary</h5>
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

                    <div class="p-4 p-md-5 border-bottom bg-gray-light bg-opacity-30">
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge-premium me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%;">2</span>
                            <h5 class="fw-bold mb-0 text-brand-dark">Amount & Exchange Rate</h5>
                        </div>

                        <div class="row g-4 align-items-end">
                            <div class="col-md-7">
                                <label class="form-label fw-bold small text-muted">SEND AMOUNT</label>
                                <div class="input-group input-group-lg bg-white rounded-3 shadow-none border overflow-hidden">
                                    <span class="input-group-text bg-white border-0 px-4 fw-bold text-brand-dark">CHF</span>
                                    <input type="number" name="chf_amount" id="chf_amount" class="form-control border-0 shadow-none ps-0" step="0.01" min="10" placeholder="0.00" required>
                                    <select name="target_currency" id="target_currency" class="form-select border-0 bg-light fw-bold" style="max-width: 120px;">
                                        <option value="INR" selected>INR</option>
                                        <option value="EUR">EUR</option>
                                        <option value="USD">USD</option>
                                        <option value="GBP">GBP</option>
                                        <option value="PKR">PKR</option>
                                        <option value="PHP">PHP</option>
                                    </select>
                                    <button type="button" id="get_quote_btn" class="btn btn-brand rounded-0 px-4">Get Live Quote</button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div id="quote_display" class="p-4 bg-white border border-brand-lime rounded-4 shadow-sm" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="small text-muted fw-bold uppercase">RECIPIENT RECEIVES</span>
                                        <span class="h3 mb-0 fw-bold text-success"><span class="selected_target_currency">₹</span> <span id="quote_target_amount">0.00</span></span>
                                    </div>
                                    <div class="space-y-2 border-top pt-3">
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span class="text-muted">Amount Sent:</span>
                                            <span class="fw-bold text-brand-dark"><span id="quote_send_amount">0.00</span> CHF</span>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span class="text-muted">Commission:</span>
                                            <span class="fw-bold text-danger"><span id="quote_fee">0.00</span> CHF</span>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-2">
                                            <span class="text-muted">Exchange Rate:</span>
                                            <span class="fw-bold text-brand-dark">1 CHF = <span id="quote_rate">0.00</span> <span class="selected_target_currency">INR</span></span>
                                        </div>
                                        <div class="d-flex justify-content-between pt-2 border-top">
                                            <span class="fw-bold text-brand-dark">Total Payable:</span>
                                            <span class="fw-bold text-brand-dark"><span id="quote_total">0.00</span> CHF</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2 x-small text-muted d-flex justify-content-between opacity-50">
                                        <span>Quote Ref: <span id="quote_id"></span></span>
                                        <span><i class="bi bi-clock-history me-1"></i> Valid for 5m</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge-premium me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%;">3</span>
                            <h5 class="fw-bold mb-0 text-brand-dark">Finalize & Memo</h5>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">REMITTANCE PURPOSE / NOTES</label>
                            <textarea name="notes" class="form-control bg-light border-0 shadow-none px-3 py-3 rounded-4" rows="3" placeholder="e.g. Family maintenance, gift..."></textarea>
                        </div>

                        <div class="d-grid pt-2">
                            <button type="submit" id="submit_btn" class="btn btn-brand btn-lg py-3 shadow-none" disabled>
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
        const targetCurrency = document.getElementById('target_currency').value;
        
        if (!amount || amount < 10) {
            alert('Please enter a valid amount (Min 10 CHF)');
            return;
        }

        getQuoteBtn.classList.add('btn-loading');
        getQuoteBtn.disabled = true;

        fetch('{{ route("agent.transfers.quote") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                chf_amount: amount,
                target_currency: targetCurrency
            })
        })
        .then(response => response.json())
        .then(data => {
            const symbols = { 'INR': '₹', 'EUR': '€', 'USD': '$', 'GBP': '£', 'PHP': '₱', 'PKR': 'Rs' };
            const symbol = symbols[data.to_currency] || data.to_currency;

            document.querySelectorAll('.selected_target_currency').forEach(el => el.textContent = symbol);
            document.getElementById('quote_rate').textContent = data.rate;
            document.getElementById('quote_target_amount').textContent = parseFloat(data.target_amount).toLocaleString('en-US', {maximumFractionDigits: 2});
            document.getElementById('quote_send_amount').textContent = parseFloat(data.send_amount).toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('quote_fee').textContent = parseFloat(data.fee).toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('quote_total').textContent = parseFloat(data.chf_amount).toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('quote_id').textContent = data.revolut_quote_id ? data.revolut_quote_id.substring(0, 8) : 'LCL-001';
            
            // Populate hidden quote_id and amount for the form
            let quoteIdInput = document.getElementById('quote_id_input');
            if (!quoteIdInput) {
                quoteIdInput = document.createElement('input');
                quoteIdInput.type = 'hidden';
                quoteIdInput.name = 'quote_id';
                quoteIdInput.id = 'quote_id_input';
                document.getElementById('transferForm').appendChild(quoteIdInput);
            }
            quoteIdInput.value = data.id;

            document.getElementById('quote_display').style.display = 'block';
            submitBtn.disabled = false;
        })
        .catch(error => {
            alert('Failed to fetch quote. Please try again.');
        })
        .finally(() => {
            getQuoteBtn.classList.remove('btn-loading');
            getQuoteBtn.disabled = false;
        });
    });
});
</script>
@endpush
