@extends('layout')

@section('content')
<div class="mb-3">
    <h2 class="page-title mb-0">User Profile</h2>
    <p class="text-muted mb-0">Update your account information.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="/profile" enctype="multipart/form-data" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-12">
                @if (auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="rounded-circle mb-3" width="110" height="110" style="object-fit: cover;" alt="Profile picture">
                @else
                    <div class="profile-avatar mb-3" style="width: 110px; height: 110px; font-size: 2.5rem;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', auth()->user()->address) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <button class="btn btn-success">Update Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
