@extends('layouts.app')

@section('title', 'Confirm Password - WMS')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0 p-5 text-center">
             <i class=" text-primary bi bi-box-seam me-2 fs-1"></i>
            <h4 class="mt-3">Confirm Password</h4>
            <p class="text-muted">Please confirm your password before continuing.</p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="form-floating mb-3">
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror" name="password"
                        required placeholder="Password">
                    <label for="password"><i class="bi bi-lock-fill me-1"></i> Password</label>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-shield-lock-fill me-1"></i> Confirm Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
