@extends('emails.layout')

@section('content')
    <h2 class="mt-0">Hello {{ $transaction->recipient->name }},</h2>
    <h2 class="mt-0">Transaction Update</h2>
    <p>Hello,</p>
    <p>A new transaction has been initiated for you. Here are the details:</p>

    <div class="alert alert-info">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td class="fw-bold fs-14">Ref No:</td>
                <td class="fs-14 fw-bold">{{ $transaction->payment_ref ?? 'TXN-' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td class="fs-14">Amount:</td>
                <td class="fs-14">{{ number_format($transaction->send_amount, 2) }} CHF</td>
            </tr>
            <tr>
                <td class="fs-14">Receiver:</td>
                <td class="fs-14 fw-bold">{{ $transaction->recipient->name }}</td>
            </tr>
        </table>
    </div>

    <div class="text-center mt-30">
        <a href="{{ route('transaction.track', $transaction->unique_hash) }}" class="button button-dark">Track Transaction</a>
    </div>

    <p>The funds are being processed and will be credited to your bank account soon. You don't need to take any action at this time.</p>

    <p class="mt-30 text-muted">Thank you for choosing Modremit.</p>
@endsection
