@extends('emails.layout')

@section('content')
    <h2 style="margin-top: 0;">Welcome to the Team, {{ $agent->name }}!</h2>
    <p>Your agent account has been successfully created. You can now log in to the Modremit Agent Portal and start processing transfers for your customers.</p>
    
    <div style="background-color: #f8fafc; padding: 20px; border-radius: 12px; margin: 20px 0;">
        <p style="margin: 0; font-weight: bold; color: #142472;">Your Login Credentials:</p>
        <p style="margin: 10px 0 5px 0;"><strong>Email:</strong> {{ $agent->email }}</p>
        <p style="margin: 0;"><strong>Password:</strong> <span class="highlight">{{ $password }}</span></p>
    </div>

    <p>For security reasons, we recommend that you change your password after your first login.</p>

    <div style="text-align: center;">
        <a href="{{ route('login') }}" class="button">Login to Portal</a>
    </div>

    <p style="margin-top: 30px; font-size: 14px; color: #64748b;">If you have any questions or need assistance, please contact our support team.</p>
@endsection
