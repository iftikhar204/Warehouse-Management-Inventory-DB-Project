@extends('layouts.app')

@section('title', 'Forgot Password - WMS')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0">
            <div class="card-body p-5 text-center">

                  <i class=" text-primary bi bi-box-seam me-2 fs-1"></i>
                <h4 class="mt-3">Reset Your Password</h4>
                <p class="text-muted">Enter your email and we'll send you a reset link.</p>

                @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                        <label for="email"><i class="bi bi-envelope-fill me-1"></i> Email</label>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-arrow-clockwise me-1"></i> Send Reset Link
                    </button>
                </form>

                <div class="mt-4">
                    <a href="{{ route('login') }}">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
