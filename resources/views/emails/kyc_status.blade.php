@extends('emails.layout')

@section('content')
    <h2 style="margin-top: 0;">Identity Verification Update</h2>
    <p>Hello {{ $user->name }},</p>
    
    @if($status === 'approved')
        <div style="background-color: #d4edda; color: #155724; padding: 20px; border-radius: 12px; margin: 20px 0; border: 1px solid #c3e6cb;">
            <p style="margin: 0; font-weight: bold;"><i class="bi bi-check-circle-fill me-2"></i> Verified Successfully!</p>
            <p style="margin: 10px 0 0 0; font-size: 14px;">Your identity verification (KYC) has been reviewed and approved. You can now use all features of the Modremit platform without restrictions.</p>
        </div>
        <div style="text-align: center;">
            <a href="{{ route('customer.dashboard') }}" class="button">Go to Dashboard</a>
        </div>
    @else
        <div style="background-color: #f8d7da; color: #721c24; padding: 20px; border-radius: 12px; margin: 20px 0; border: 1px solid #f5c6cb;">
            <p style="margin: 0; font-weight: bold;"><i class="bi bi-exclamation-triangle-fill me-2"></i> Action Required</p>
            <p style="margin: 10px 0 0 0; font-size: 14px;">Unfortunately, your identity verification could not be approved at this time.</p>
            @if($reason)
                <p style="margin: 10px 0 0 0; font-weight: bold;">Reason:</p>
                <p style="margin: 5px 0 0 0; font-style: italic;">"{{ $reason }}"</p>
            @endif
        </div>
        <p>Please log in to your account and re-submit your documents or contact support for further clarification.</p>
        <div style="text-align: center;">
            <a href="{{ route('customer.login') }}" class="button">Log In to Re-submit</a>
        </div>
    @endif

    <p style="margin-top: 30px; font-size: 14px; color: #64748b;">If you have any questions, our support team is here to help.</p>
@endsection
