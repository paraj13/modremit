@extends('emails.layout')

@section('content')
    <h2 class="mt-0">Hello {{ $transaction->recipient->name }},</h2>
    <p>A new transaction has been initiated for you. Here are the details:</p>

    <div class="transaction-card">
        <div class="transaction-header">Transfer Details</div>
        
        <div class="data-row">
            <div class="data-label">Amount to Receive:</div>
            <div class="data-value amount-value">{{ number_format($transaction->target_amount, 2) }} {{ $transaction->target_currency }}</div>
        </div>

        <div class="data-row">
            <div class="data-label">Sender:</div>
            <div class="data-value">{{ $transaction->customer->name }}</div>
        </div>

        <div class="data-row">
            <div class="data-label">Reference ID:</div>
            <div class="data-value">#{{ $transaction->payment_ref ?? 'TXN-' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="data-row pb-10">
            <div class="data-label">Current Status:</div>
            <div class="data-value">
                <span class="status-badge status-badge-{{ $transaction->status }}">
                    {{ strtoupper($transaction->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="text-center mt-30">
        <a href="{{ route('transaction.track', $transaction->unique_hash) }}" class="button button-dark">Track Transaction</a>
    </div>

    <p>The funds are being processed and will be credited to your bank account soon. You don't need to take any action at this time.</p>

    <p class="mt-30 text-muted">Thank you for choosing Modremit.</p>
@endsection
