@extends('layouts.app')

@section('title', 'Reset Password - WMS')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0">
            <div class="card-body p-5 text-center">
                 <i class=" text-primary bi bi-box-seam me-2 fs-1"></i>
                <h4 class="mt-3">Set a New Password</h4>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-floating mb-3">
                        <input type="email" name="email" value="{{ old('email', $email ?? '') }}"
                            class="form-control @error('email') is-invalid @enderror" required autofocus placeholder="Email">
                        <label for="email"><i class="bi bi-envelope-fill me-1"></i> Email</label>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required placeholder="New Password">
                        <label for="password"><i class="bi bi-lock-fill me-1"></i> New Password</label>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            required placeholder="Confirm Password">
                        <label for="password_confirmation"><i class="bi bi-lock-fill me-1"></i> Confirm Password</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle-fill me-1"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
