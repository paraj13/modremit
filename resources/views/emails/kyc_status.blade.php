@extends('emails.layout')

@section('content')
    <h2 class="mt-0">Identity Verification Update</h2>
    <p>Hello {{ $user->name }},</p>
    
    @if($status === 'approved')
        <div class="alert alert-success">
            <p class="mb-0 fw-bold">Verified Successfully!</p>
            <p class="mt-10 fs-14">Your identity verification (KYC) has been reviewed and approved. You can now use all features of the Modremit platform without restrictions.</p>
        </div>
        <div class="text-center">
            <a href="{{ route('customer.dashboard') }}" class="button">Go to Dashboard</a>
        </div>
    @else
        <div class="alert alert-danger">
            <p class="mb-0 fw-bold">Action Required</p>
            <p class="mt-10 fs-14">Unfortunately, your identity verification could not be approved at this time.</p>
            @if($reason)
                <p class="mt-10 fw-bold">Reason:</p>
                <p class="mt-5 italic">"{{ $reason }}"</p>
            @endif
        </div>
        <p>Please log in to your account and re-submit your documents or contact support for further clarification.</p>
        <div class="text-center">
            <a href="{{ route('customer.login') }}" class="button button-dark">Log In to Re-submit</a>
        </div>
    @endif

    <p class="mt-30 text-muted">If you have any questions, our support team is here to help.</p>
@endsection
