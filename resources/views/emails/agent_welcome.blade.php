@extends('emails.layout')

@section('content')
    <h2 class="mt-0">Welcome to Modremit Agent Portal!</h2>
    <p>Hello {{ $agent->name }},</p>
    <p>Your agent account has been created successfully. You can now log in to the portal using your email and the temporary password provided below.</p>
    
    <div class="alert alert-info">
        <p class="mb-0"><strong>Email:</strong> {{ $agent->email }}</p>
        <p class="mb-0"><strong>Temporary Password:</strong> {{ $password }}</p>
    </div>

    <p>For security reasons, we recommend that you change your password after your first login.</p>

    <div class="text-center">
        <a href="{{ route('login') }}" class="button button-dark">Login to Portal</a>
    </div>

    <p class="mt-30 text-muted">If you have any questions or need assistance, please contact our support team.</p>
@endsection
