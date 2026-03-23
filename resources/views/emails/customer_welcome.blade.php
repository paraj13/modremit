@extends('emails.layout')

@section('content')
    <h2 class="mt-0">Welcome to Modremit!</h2>
    <p>Hello {{ $customer->name }},</p>
    <p>Thank you for choosing Modremit for your international money transfers. We're excited to have you on board.</p>
    
    <p class="text-muted">Please note that you will need to complete your KYC (Know Your Customer) verification before making your first transfer. This is a standard security requirement.</p>

    <div class="text-center">
        <a href="{{ route('customer.login') }}" class="button button-dark">Access My Account</a>
    </div>

    <p class="mt-30 text-muted">If you didn't expect this email, please contact us immediately.</p>
@endsection
