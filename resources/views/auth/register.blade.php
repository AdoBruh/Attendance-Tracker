@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="page-title mb-1">Register</h3>
                <p class="text-muted">Create your account first before using the tracker.</p>
                <form method="POST" action="/register">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" minlength="4" required>
                    </div>
                    <button class="btn btn-success w-100">Create Account</button>
                </form>
                <p class="mt-3 mb-0">Already registered? <a href="/login">Login here</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
