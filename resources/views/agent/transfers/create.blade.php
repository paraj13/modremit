@extends('layouts.agent')

@section('page_title', 'New Transfer')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <form id="transferForm" action="{{ route('agent.transfers.store') }}" method="POST">
                    @csrf
                    
                    <h5 class="fw-bold mb-4 border-bottom pb-2">1. Select Recipient</h5>
                    <div class="mb-4">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" id="customer_select" class="form-select form-select-lg shadow-none" required>
                            <option value="">Choose a verified customer...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (isset($selectedCustomer) && $selectedCustomer->id == $customer->id) ? 'selected' : '' }} data-recipients='@json($customer->recipients)'>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4" id="recipient_wrapper" style="{{ isset($selectedCustomer) ? '' : 'display:none;' }}">
                        <label class="form-label">Recipient</label>
                        <select name="recipient_id" id="recipient_select" class="form-select form-select-lg shadow-none" required>
                            <option value="">Select recipient...</option>
                            @if(isset($selectedCustomer))
                                @foreach($selectedCustomer->recipients as $recipient)
                                    <option value="{{ $recipient->id }}">{{ $recipient->name }} ({{ $recipient->bank_name }})</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="mt-2" id="recipient_details"></div>
                    </div>

                    <h5 class="fw-bold mb-4 border-bottom pb-2">2. Amount & Exchange</h5>
                    <div class="row g-3 align-items-end mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Send Amount (CHF)</label>
                            <div class="input-group input-group-lg shadow-none">
                                <span class="input-group-text bg-white border-end-0">CHF</span>
                                <input type="number" name="chf_amount" id="chf_amount" class="form-control border-start-0 ps-0" step="0.01" min="10" placeholder="0.00" required>
                                <button type="button" id="get_quote_btn" class="btn btn-primary px-4">Get Quote</button>
                            </div>
                        </div>
                    </div>

                    <div id="quote_display" class="card bg-light border-0 mb-4" style="display: none;">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col">
                                    <div class="text-muted small">FX Rate</div>
                                    <div class="h4 fw-bold mb-0 text-primary">1 CHF = <span id="quote_rate">0.00</span> INR</div>
                                </div>
                                <div class="col border-start">
                                    <div class="text-muted small">Net to Send</div>
                                    <div class="h4 fw-bold mb-0">₹ <span id="quote_inr">0.00</span></div>
                                </div>
                                <div class="col border-start">
                                    <div class="text-muted small">Commission (Inc.)</div>
                                    <div class="h4 fw-bold mb-0 text-danger" id="quote_fee">0.00</div>
                                </div>
                            </div>
                            <div class="text-center mt-3 small text-muted">
                                Quote identity: <span id="quote_id" class="font-monospace"></span> | 
                                Expires in: <span id="quote_expiry"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Notes (Internal Use)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Family support, property tx..."></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" id="submit_btn" class="btn btn-primary btn-lg shadow-sm" disabled>Confirm and Send Money</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body p-4">
                <h5 class="fw-bold"><i class="bi bi-info-circle me-2"></i> Remittance Guide</h5>
                <hr class="bg-white">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex">
                        <i class="bi bi-check2-circle me-2 mt-1"></i>
                        <span>Only approved KYC customers can send transfers.</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="bi bi-check2-circle me-2 mt-1"></i>
                        <span>Quotes are valid for only 5 minutes. Fetch a fresh one if it expires.</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="bi bi-check2-circle me-2 mt-1"></i>
                        <span>Transfers to India (INR) are processed via Revolut.</span>
                    </li>
                    <li class="d-flex">
                        <i class="bi bi-check2-circle me-2 mt-1"></i>
                        <span>Commission is 2% and is deducted from the base CHF amount.</span>
                    </li>
                </ul>
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
            opt.textContent = `${r.name} (${r.bank_name})`;
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

        getQuoteBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
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
            document.getElementById('quote_inr').textContent = parseFloat(data.inr_amount).toLocaleString('en-IN');
            document.getElementById('quote_fee').textContent = data.fee + ' CHF';
            document.getElementById('quote_id').textContent = data.revolut_quote_id || 'LOCAL-DUMMY';
            document.getElementById('quote_expiry').textContent = '5:00 min';
            
            document.getElementById('quote_display').style.display = 'block';
            submitBtn.disabled = false;
        })
        .catch(error => {
            alert('Failed to fetch quote. Please try again.');
        })
        .finally(() => {
            getQuoteBtn.innerHTML = 'Get Quote';
            getQuoteBtn.disabled = false;
        });
    });
});
</script>
@endpush
