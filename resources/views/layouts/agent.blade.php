@extends('layouts.main')

@section('role_badge', 'Agent')

@section('sidebar_nav')
<a class="nav-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}" href="{{ route('agent.dashboard') }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a class="nav-link {{ request()->routeIs('agent.customers.*') ? 'active' : '' }}" href="{{ route('agent.customers.index') }}">
    <i class="bi bi-person-lines-fill"></i> Customers
</a>
<a class="nav-link {{ request()->routeIs('agent.transfers.*') ? 'active' : '' }}" href="{{ route('agent.transfers.create') }}">
    <i class="bi bi-send-plus"></i> New Transfer
</a>
<a class="nav-link {{ request()->routeIs('agent.transactions.*') ? 'active' : '' }}" href="{{ route('agent.transactions.index') }}">
    <i class="bi bi-clock-history"></i> History
</a>
<a class="nav-link {{ request()->routeIs('agent.wallet.*') ? 'active' : '' }}" href="{{ route('agent.wallet.index') }}">
    <i class="bi bi-wallet2"></i> My Wallet
</a>
@endsection
