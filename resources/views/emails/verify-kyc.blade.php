@extends('emails.layout')

@section('content')
    <h2 style="margin-top: 0;">Hello {{ $customer->name }},</h2>
    
    <p>To comply with financial regulations and ensure the security of your account, we require you to verify your identity.</p>
    
    <p>Please click the button below to start the secure KYC verification process. It should only take a few minutes.</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $verificationLink }}" class="button">Verify Your Identity</a>
    </div>

    <div style="background-color: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-top: 30px;">
        <h4 style="margin-top: 0; color: #142472; font-size: 16px;">Why is this required?</h4>
        <p style="margin-bottom: 0; font-size: 14px; color: #64748b;">Identity verification helps us prevent fraud and maintain a safe environment for all our customers.</p>
    </div>

    <p style="margin-top: 30px;">If you have any questions, please contact our support team.</p>

    <p style="margin-top: 30px; font-size: 14px; color: #64748b;">
        Regards,<br>
        <strong>The {{ config('app.name') }} Team</strong>
    </p>
@endsection
