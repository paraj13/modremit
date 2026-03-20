@extends('emails.layout')

@section('content')
    <h2 style="margin-top: 0;">Complete Your Verification</h2>
    <p>Hello {{ $user->name }},</p>
    <p>To ensure the security of our platform and comply with financial regulations, we need you to complete a quick identity verification (KYC).</p>
    
    <div style="background-color: #eef2ff; padding: 25px; border-radius: 12px; margin: 20px 0; border: 1px solid #e0e7ff; text-align: center;">
        <p style="margin-bottom: 20px; color: #142472; font-weight: bold;">Please click the button below to start your verification process securely via our partner Sumsub:</p>
        <a href="{{ $kycLink }}" class="button" style="background-color: #142472; color: #D3FF8A;">Start Verification</a>
    </div>

    <p style="font-size: 14px; color: #64748b;">You will need a valid government-issued ID (Passport, ID Card, or Driving License) and a few minutes to complete the process.</p>

    <p style="margin-top: 30px; font-size: 14px; color: #64748b;">This link will expire soon. If you face any issues, please contact our support team.</p>
@endsection
