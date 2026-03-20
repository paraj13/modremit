@extends('emails.layout')

@section('content')
    <h2 style="margin-top: 0;">Welcome to Modremit, {{ $customer->name }}!</h2>
    <p>Your account has been successfully created. You can now log in to the Modremit Customer Portal to send money, manage beneficiaries, and track your transfers in real-time.</p>
    
    <div style="background-color: #f8fafc; padding: 20px; border-radius: 12px; margin: 20px 0;">
        <p style="margin: 0; font-weight: bold; color: #142472;">Your Login Credentials:</p>
        <p style="margin: 10px 0 5px 0;"><strong>Email:</strong> {{ $customer->email }}</p>
        <p style="margin: 0;"><strong>Password:</strong> <span class="highlight">{{ $password }}</span></p>
    </div>

    <p style="color: #64748b; font-size: 14px;">Please note that you will need to complete your KYC (Know Your Customer) verification before making your first transfer. This is a standard security requirement.</p>

    <div style="text-align: center;">
        <a href="{{ route('customer.login') }}" class="button">Access My Account</a>
    </div>

    <p style="margin-top: 30px; font-size: 14px; color: #64748b;">If you didn't expect this email, please contact us immediately.</p>
@endsection
