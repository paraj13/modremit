@extends('emails.layout')

@section('content')
    <h2 class="mt-0">Complete Your Verification</h2>
    <p>Hello {{ $user->name }},</p>
    <p>To ensure the security of our platform and comply with financial regulations, we need you to complete a quick identity verification (KYC).</p>
    
    <div class="alert alert-info text-center">
        <p class="fw-bold highlight">Please click the button below to start your verification process securely via our partner Sumsub:</p>
        <a href="{{ $kycLink }}" class="button button-dark">Start Verification</a>
    </div>

    <p class="text-muted">You will need a valid government-issued ID (Passport, ID Card, or Driving License) and a few minutes to complete the process.</p>

    <p class="mt-30 text-muted">This link will expire soon. If you face any issues, please contact our support team.</p>
@endsection
