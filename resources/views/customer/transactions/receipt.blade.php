<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $transaction->id }} - Modremit</title>
    <link href="{{ asset('vendor/css/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; color: #142472; background: #fff; }
        .receipt-container { max-width: 800px; margin: 40px auto; padding: 40px; border: 1px solid #eee; }
        .logo-box { background-color: #ffde42; border-radius: 8px; width: 48px; height: 48px; display: inline-flex; align-items: center; justify-content: center; }
        .brand-text { font-size: 24px; font-weight: 800; letter-spacing: -1px; margin-left: 10px; }
        .brand-highlight { background-color: #ffde42; color: #142472; padding: 2px 8px; border-radius: 4px; margin-left: 2px; }
        .detail-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed #eee; }
        .detail-label { color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
        .detail-value { font-weight: 700; color: #142472; }
        .header-section { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #142472; }
        .total-box { background: #f8fafc; padding: 20px; border-radius: 12px; margin-top: 30px; }
        @media print {
            .no-print { display: none !important; }
            .receipt-container { margin: 0; border: none; width: 100%; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="no-print text-center my-4">
    <button onclick="window.print()" class="btn btn-primary px-4 rounded-pill me-2">
        <i class="bi bi-printer me-1"></i> Print Receipt
    </button>
    <button onclick="downloadReceipt()" class="btn btn-success px-4 rounded-pill">
        <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
    </button>
    <p class="small text-muted mt-2">To save as PDF, select "Save as PDF" in the printer destination or use the Download button.</p>
</div>

<!-- html2pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadReceipt() {
        const element = document.querySelector('.receipt-container');
        const opt = {
            margin:       0.5,
            filename:     'Receipt_{{ str_pad($transaction->id, 8, '0', STR_PAD_LEFT) }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    }

    // Auto-download if ?download=1 is in the URL
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('download') === '1') {
            downloadReceipt();
        }
    }
</script>

<div class="receipt-container shadow-sm">
    <div class="header-section d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="logo-box">
                <span class="fs-5">&#10148;</span>
            </div>
            <span class="brand-text">MOD<span class="brand-highlight">REMIT</span></span>
        </div>
        <div class="text-end">
            <h4 class="fw-bold mb-0">TRANSACTION RECEIPT</h4>
            <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'info') }} rounded-pill px-3">
                {{ strtoupper($transaction->status) }}
            </span>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-6">
            <div class="detail-label mb-1">Receipt To</div>
            <h5 class="fw-bold mb-0">{{ $transaction->customer->name }}</h5>
            <div class="small text-muted">{{ $transaction->customer->email }}</div>
        </div>
        <div class="col-6 text-end">
            <div class="detail-label mb-1">Date & Time</div>
            <h5 class="fw-bold mb-0">{{ $transaction->created_at->format('M d, Y') }}</h5>
            <div class="small text-muted">{{ $transaction->created_at->format('H:i:s') }} UTC</div>
        </div>
    </div>

    <div class="mb-5">
        <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-3">Transfer Details</h6>
        
        <div class="detail-row">
            <span class="detail-label">Transaction Reference</span>
            <span class="detail-value">#{{ str_pad($transaction->id, 8, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Recipient Name</span>
            <span class="detail-value">{{ $transaction->recipient->name }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Bank Name</span>
            <span class="detail-value">{{ $transaction->recipient->bank_name }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Account / IBAN</span>
            <span class="detail-value">{{ $transaction->recipient->account_number }}</span>
        </div>

        @if($transaction->recipient->ifsc_code)
        <div class="detail-row">
            <span class="detail-label">IFSC Code</span>
            <span class="detail-value">{{ $transaction->recipient->ifsc_code }}</span>
        </div>
        @endif

        <div class="detail-row">
            <span class="detail-label">Destination Country</span>
            <span class="detail-value">{{ $transaction->recipient->country }}</span>
        </div>
    </div>

    <div class="total-box">
        <div class="detail-row border-0">
            <span class="detail-label">Amount Sent</span>
            <span class="detail-value">{{ number_format($transaction->chf_amount, 2) }} CHF</span>
        </div>
        <div class="detail-row border-0">
            <span class="detail-label">Exchange Rate</span>
            <span class="detail-value">1 CHF = {{ number_format($transaction->rate, 4) }} {{ $transaction->target_currency }}</span>
        </div>
        <div class="detail-row border-0">
            <span class="detail-label">Service Fee</span>
            <span class="detail-value text-danger">{{ number_format($transaction->commission, 2) }} CHF</span>
        </div>
        <hr>
        <div class="detail-row border-0 pt-2">
            <h5 class="fw-bold mb-0">Recipient Receives</h5>
            <h4 class="fw-bold text-success mb-0">{{ number_format($transaction->target_amount, 2) }} {{ $transaction->target_currency }}</h4>
        </div>
    </div>

    <div class="mt-5 pt-3 border-top x-small text-muted text-center">
        Modremit is a regulated money transfer platform. For support, please contact us at support@modremit.com.<br>
        Thank you for choosing Modremit for your global transfers.
    </div>
</div>

</body>
</html>
