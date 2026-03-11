@extends('layouts.main')

@section('role_badge', 'Admin')

@section('sidebar_nav')
<a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a class="nav-link {{ request()->routeIs('admin.agents.index') ? 'active' : '' }}" href="{{ route('admin.agents.index') }}">
    <i class="bi bi-person-workspace"></i> Agents
</a>
<a class="nav-link {{ request()->routeIs('admin.agents.pending') ? 'active' : '' }}" href="{{ route('admin.agents.pending') }}">
    <i class="bi bi-person-plus"></i> Pending Applications
</a>
<a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
    <i class="bi bi-people"></i> Customers
</a>
<a class="nav-link {{ request()->routeIs('admin.recipients.*') ? 'active' : '' }}" href="{{ route('admin.recipients.index') }}">
    <i class="bi bi-person-lines-fill"></i> Recipients
</a>
<a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
    <i class="bi bi-journal-text"></i> Transactions
</a>
<a class="nav-link {{ request()->routeIs('admin.compliance.*') ? 'active' : '' }}" href="{{ route('admin.compliance.index') }}">
    <i class="bi bi-shield-lock"></i> Compliance
</a>
<a class="nav-link {{ request()->routeIs('admin.wallets.*') ? 'active' : '' }}" href="{{ route('admin.wallets.index') }}">
    <i class="bi bi-wallet-fill"></i> Agent Wallets
</a>
@endsection
