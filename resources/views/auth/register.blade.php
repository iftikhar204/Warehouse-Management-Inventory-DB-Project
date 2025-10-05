@extends('layouts.app')

@section('title', 'Register - WMS')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                     <i class=" text-primary bi bi-box-seam me-2 fs-1"></i>
                    <h3 class="mt-2 fw-bold">Create Account</h3>
                    <p class="text-muted">Join Warehouse Management System</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autofocus placeholder="Full Name">
                        <label for="name"><i class="bi bi-person-fill me-1"></i> Full Name</label>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required placeholder="Email">
                        <label for="email"><i class="bi bi-envelope-fill me-1"></i> Email</label>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password"
                            required placeholder="Password">
                        <label for="password"><i class="bi bi-lock-fill me-1"></i> Password</label>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input id="password_confirmation" type="password" class="form-control"
                            name="password_confirmation" required placeholder="Confirm Password">
                        <label for="password_confirmation"><i class="bi bi-lock-fill me-1"></i> Confirm Password</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-person-plus-fill me-1"></i> Register
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
