@extends('layouts.main')

@section('role_badge', 'Admin')

@section('sidebar_nav')
<a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a class="nav-link {{ request()->routeIs('admin.agents.*') ? 'active' : '' }}" href="{{ route('admin.agents.index') }}">
    <i class="bi bi-people"></i> Agents
</a>
<a class="nav-link {{ request()->routeIs('admin.compliance.*') ? 'active' : '' }}" href="{{ route('admin.compliance.index') }}">
    <i class="bi bi-shield-check"></i> Compliance
</a>
<a class="nav-link {{ request()->routeIs('admin.fx.*') ? 'active' : '' }}" href="{{ route('admin.fx.index') }}">
    <i class="bi bi-currency-exchange"></i> FX Quotes
</a>
@endsection
