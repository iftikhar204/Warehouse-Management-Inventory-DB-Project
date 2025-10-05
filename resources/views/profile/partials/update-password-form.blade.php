{{-- resources/views/partials/update-password-form.blade.php --}}

<div class="card shadow-sm border mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0 text-primary fw-bold">
            <i class="bi bi-shield-lock-fill me-2"></i> Update Password
        </h5>
    </div>

    <div class="card-body">
        <p class="text-muted mb-4">
            Ensure your account is using a strong password with a mix of letters, numbers, and symbols to stay secure.
        </p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password"
                       class="form-control @error('current_password') is-invalid @enderror"
                       id="current_password"
                       name="current_password"
                       required
                       autocomplete="current-password"
                       autofocus>
                @error('current_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- New Password -->
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       required
                       autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password"
                       class="form-control"
                       id="password_confirmation"
                       name="password_confirmation"
                       required
                       autocomplete="new-password">
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Save
                </button>
            </div>

            <!-- Success Message -->
            @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Your password has been updated successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </form>
    </div>
</div>
