{{-- resources/views/settings/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Application Settings')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h1 class="h3 fw-bold text-primary d-flex align-items-center">
            <i class="bi bi-gear-fill me-2"></i> Application Settings
        </h1>
    </div>

    <!-- Toast Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Please fix the following errors:
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Settings Form Card -->
    <div class="card shadow-sm border-0 animate__animated animate__fadeInUp">
        <div class="card-header bg-white border-bottom-0">
            <h5 class="mb-0 fw-semibold text-dark"><i class="bi bi-sliders me-2 text-secondary"></i> General Settings</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                {{-- If using PUT method, uncomment below --}}
                {{-- @method('PUT') --}}

                <div class="mb-3">
                    <label for="company_name" class="form-label fw-semibold">Company Name</label>
                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                        id="company_name" name="company_name"
                        value="{{ old('company_name', $settings['company_name']) }}"
                        placeholder="Enter your company name">
                    @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="default_currency" class="form-label fw-semibold">Default Currency</label>
                    <input type="text" class="form-control @error('default_currency') is-invalid @enderror"
                        id="default_currency" name="default_currency"
                        value="{{ old('default_currency', $settings['default_currency']) }}"
                        placeholder="E.g. USD, EUR, PKR">
                    @error('default_currency')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input @error('enable_email_notifications') is-invalid @enderror"
                        type="checkbox" id="enable_email_notifications" name="enable_email_notifications" value="1"
                        {{ old('enable_email_notifications', $settings['enable_email_notifications']) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="enable_email_notifications">
                        Enable Email Notifications
                    </label>
                    @error('enable_email_notifications')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="bi bi-save-fill me-1"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
