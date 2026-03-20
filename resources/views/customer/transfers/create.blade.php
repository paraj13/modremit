@extends('layouts.customer')

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
                        <h4 class="fw-bold mb-0 text-white">Send Money Worldwide</h4>
                        <p class="text-brand-lime small mb-0 opacity-75">Fast, secure, and transparent transfers from CHF</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0 bg-white">
                <form id="customerTransferForm" action="{{ route('customer.transfers.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-4 p-md-5 border-bottom">
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge-premium me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%;">1</span>
                            <h5 class="fw-bold mb-0 text-brand-dark">Select Recipient</h5>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold small text-muted mb-0">CHOOSE BENEFICIARY</label>
                                    <a href="{{ route('customer.recipients.create') }}" class="text-decoration-none text-primary small fw-bold">
                                        <i class="bi bi-plus-circle"></i> Add New Recipient
                                    </a>
                                </div>
                                @if($recipients->isEmpty())
                                    <div class="alert bg-light border-0 rounded-4 p-3 small">
                                        <i class="bi bi-info-circle me-1"></i> You don't have any recipients yet. Please add one first.
                                    </div>
                                @else
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-person-check text-muted"></i></span>
                                        <select name="recipient_id" id="recipient_id" class="form-select bg-light border-0 shadow-none px-3 py-2">
                                            <option value="">Select recipient...</option>
                                            @foreach($recipients as $recipient)
                                                <option value="{{ $recipient->id }}" {{ old('recipient_id') == $recipient->id ? 'selected' : '' }}>
                                                    {{ $recipient->name }} ({{ $recipient->bank_name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="recipient_id-error-container"></div>
                                @endif
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
                                    <input type="number" name="chf_amount" id="chf_amount" class="form-control border-0 shadow-none ps-0" step="0.01" placeholder="0.00" value="{{ old('chf_amount', 100) }}">
                                    <select name="target_currency" id="target_currency" class="form-select border-0 bg-light fw-bold" style="max-width: 120px;">
                                        @foreach(\App\Constants\CountryCurrency::CURRENCIES as $c)
                                            @continue($c['code'] === 'CHF')
                                            <option value="{{ $c['code'] }}" {{ old('target_currency', 'INR') == $c['code'] ? 'selected' : '' }}>
                                                {{ $c['code'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="get_quote_btn" class="btn btn-brand rounded-0 px-4">Get Quote</button>
                                </div>
                                <div id="chf_amount-error-container"></div>
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
                                            <span class="text-muted">Fee:</span>
                                            <span class="fw-bold text-danger"><span id="quote_fee">0.00</span> CHF</span>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-2">
                                            <span class="text-muted">Exchange Rate:</span>
                                            <span class="fw-bold text-brand-dark">1 CHF = <span id="quote_rate">0.00</span> <span class="selected_target_currency">INR</span></span>
                                        </div>
                                        <div class="d-flex justify-content-between pt-2 border-top">
                                            <span class="fw-bold text-brand-dark">Total:</span>
                                            <span class="fw-bold text-brand-dark"><span id="quote_total">0.00</span> CHF</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2 x-small text-muted d-flex justify-content-between opacity-50">
                                        <span>Quote ID: <span id="quote_id_text"></span></span>
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
                            <label class="form-label fw-bold small text-muted">PURPOSE / NOTES</label>
                            <textarea name="notes" class="form-control bg-light border-0 shadow-none px-3 py-3 rounded-4" rows="3" placeholder="e.g. Family maintenance, gift..."></textarea>
                        </div>

                        <div class="d-grid pt-2">
                            <button type="submit" id="submit_btn" class="btn btn-brand btn-lg py-3 shadow-none" disabled>
                                 <i class="bi bi-send-check-fill me-2"></i> Confirm and Send Now
                            </button>
                        </div>
                        <p class="text-center mt-3 text-muted small">
                            <i class="bi bi-shield-lock-fill me-1"></i> Your transaction is protected by 256-bit encryption.
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
    const recipientSelect = document.getElementById('recipient_id');
    const getQuoteBtn = document.getElementById('get_quote_btn');
    const chfAmountInput = document.getElementById('chf_amount');
    const submitBtn = document.getElementById('submit_btn');
    const transferForm = $("#customerTransferForm");

    if ($.validator) {
        transferForm.validate({
            rules: {
                recipient_id: { required: true },
                chf_amount: { required: true, number: true, min: 10 }
            },
            messages: {
                recipient_id: "Please select a recipient",
                chf_amount: {
                    required: "Please enter a send amount",
                    min: "Minimum transfer amount is 10 CHF"
                }
            },
            errorPlacement: function(error, element) {
                const id = element.attr('id');
                const container = $(`#${id}-error-container`);
                if (container.length) {
                    error.appendTo(container);
                } else {
                    error.insertAfter(element.closest('.input-group'));
                }
            },
            submitHandler: function(form) {
                showLoader($(form).find('button[type="submit"]')[0]);
                form.submit();
            }
        });
    }

    getQuoteBtn.addEventListener('click', function() {
        const amount = chfAmountInput.value;
        const targetCurrency = document.getElementById('target_currency').value;
        
        if (!amount || amount < 10) {
            transferForm.valid();
            return;
        }

        getQuoteBtn.classList.add('btn-loading');
        getQuoteBtn.disabled = true;

        $.ajax({
            url: '{{ route("customer.transfers.quote") }}',
            method: 'POST',
            data: { 
                _token: '{{ csrf_token() }}',
                chf_amount: amount,
                target_currency: targetCurrency
            },
            success: function(data) {
                const symbols = { 'INR': '₹', 'EUR': '€', 'USD': '$', 'GBP': '£', 'PHP': '₱', 'PKR': 'Rs' };
                const symbol = symbols[data.to] || data.to;

                $('.selected_target_currency').text(symbol);
                $('#quote_rate').text(data.rate);
                $('#quote_target_amount').text(parseFloat(data.target_amount).toLocaleString('en-US', {maximumFractionDigits: 2}));
                $('#quote_send_amount').text(parseFloat(data.send_amount).toLocaleString('en-US', {minimumFractionDigits: 2}));
                $('#quote_fee').text(parseFloat(data.fee).toLocaleString('en-US', {minimumFractionDigits: 2}));
                $('#quote_total').text(parseFloat(data.amount).toLocaleString('en-US', {minimumFractionDigits: 2}));
                $('#quote_id_text').text(data.id);
                
                let quoteIdInput = $('#quote_id_input');
                if (!quoteIdInput.length) {
                    quoteIdInput = $('<input type="hidden" name="quote_id" id="quote_id_input">');
                    transferForm.append(quoteIdInput);
                }
                quoteIdInput.val(data.id);

                $('#quote_display').fadeIn();
                submitBtn.disabled = false;
            },
            error: function() {
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Failed to fetch quote.', showConfirmButton: false, timer: 3000 });
            },
            complete: function() {
                getQuoteBtn.classList.remove('btn-loading');
                getQuoteBtn.disabled = false;
            }
        });
    });
});
</script>
@endpush
