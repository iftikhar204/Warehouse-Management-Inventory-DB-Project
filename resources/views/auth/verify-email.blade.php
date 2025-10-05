@extends('layouts.app')

@section('title', 'Verify Email - WMS')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6">
        <div class="card shadow border-0 text-center p-5">
             <i class=" text-primary bi bi-box-seam me-2 fs-1"></i>
            <h4 class="mt-3">Verify Your Email</h4>

            @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success mt-3">
                A verification link has been sent to your email address.
            </div>
            @endif

            <p class="mt-3 text-muted">Please click the link in your email to activate your account.</p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="btn btn-info mt-2">
                    <i class="bi bi-send-fill me-1"></i> Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button class="btn btn-link">Logout</button>
            </form>
        </div>
    </div>
</div>
@endsection
