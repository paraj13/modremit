@extends('emails.layout')

@section('content')
    <h2 style="margin-top: 0;">Hello {{ $transaction->recipient->name }},</h2>
    <p>We are pleased to inform you that a money transfer has been initiated to your account via Modremit.</p>
    
    <div style="background-color: #f8fafc; padding: 25px; border-radius: 12px; margin: 20px 0; border: 1px solid #e2e8f0;">
        <h4 style="margin-top: 0; color: #142472; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Transfer Details</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Amount to Receive:</td>
                <td style="padding: 8px 0; text-align: right; font-weight: bold; color: #28a745;">{{ number_format($transaction->target_amount, 2) }} {{ $transaction->target_currency }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Sender:</td>
                <td style="padding: 8px 0; text-align: right; font-weight: bold;">{{ $transaction->customer->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Reference ID:</td>
                <td style="padding: 8px 0; text-align: right; font-family: monospace;">#TXN-{{ str_pad($transaction->id, 8, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Current Status:</td>
                <td style="padding: 8px 0; text-align: right;"><span style="background-color: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: bold; text-transform: uppercase;">{{ $transaction->status }}</span></td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('transaction.track', $transaction->unique_hash) }}" class="button">Track Transaction</a>
    </div>

    <p>The funds are being processed and will be credited to your bank account soon. You don't need to take any action at this time.</p>

    <p style="margin-top: 30px; font-size: 14px; color: #64748b;">Thank you for using Modremit.<br><strong>The Modremit Team</strong></p>
@endsection
