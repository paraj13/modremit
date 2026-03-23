@extends('emails.layout')

@section('content')
    <h2 class="mt-0">Verify Your Identity</h2>
    <p>Hello {{ $customer->name }},</p>
    <p>To ensure the security of your account and comply with financial regulations, we need you to verify your identity.</p>
    <p>Please click the button below to start the secure KYC verification process. It should only take a few minutes.</p>
    
    <div class="text-center mt-30">
        <a href="{{ $verificationLink }}" class="button button-dark">Verify Your Identity</a>
    </div>

    <div class="alert alert-info mt-30">
        <p class="mb-0 fw-bold fs-14">Why verification is required?</p>
        <ul class="fs-14">
            <li>To protect your account from unauthorized access</li>
            <li>To comply with Anti-Money Laundering (AML) regulations</li>
            <li>To enable higher transfer limits</li>
        </ul>
    </div>

    <p class="text-muted small mt-30">The verification link is valid for 24 hours. For security reasons, do not share this link with anyone.</p>
@endsection
