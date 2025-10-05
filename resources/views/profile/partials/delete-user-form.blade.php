{{-- resources/views/partials/delete-user-form.blade.php --}}
<section class="card shadow-sm border mb-4">
    <div class="card-header bg-white border-bottom">
        <h5 class="text-danger fw-bold mb-1">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Delete Account
        </h5>
        <p class="text-muted mb-0">
            Once your account is deleted, all resources and data will be <strong>permanently erased</strong>.
            Please confirm your password to proceed. <br>
            <span class="text-danger fw-semibold">This action cannot be undone.</span>
        </p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <div class="mb-3">
                <label for="password_delete" class="form-label fw-semibold">
                    Confirm Password
                </label>
                <input
                    type="password"
                    id="password_delete"
                    name="password"
                    class="form-control @error('password', 'deleteUser') is-invalid @enderror"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                >
                <div class="form-text">To confirm account deletion, enter your current password.</div>

                @error('password', 'deleteUser')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-danger px-4">
                    <i class="bi bi-trash me-1"></i> Delete Account
                </button>
            </div>
        </form>
    </div>
</section>
