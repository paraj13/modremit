@extends('layouts.admin')

@section('page_title', 'Edit Agent')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('admin.agents.update', $agent->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $agent->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $agent->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $agent->phone }}">
                    </div>
                    <hr class="my-4">
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="mt-4 text-end">
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Agent</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
