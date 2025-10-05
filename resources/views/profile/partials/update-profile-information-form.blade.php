{{-- resources/views/partials/update-profile-information-form.blade.php --}}

<div class="card shadow-sm border mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0 text-primary fw-bold">
            <i class="bi bi-person-lines-fill me-2"></i> Profile Information
        </h5>
    </div>

    <div class="card-body">
        <p class="text-muted mb-4">
            Update your account's profile details and email address below.
        </p>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Name</label>
                <input type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       id="name"
                       name="name"
                       value="{{ old('name', $user->name ?? '') }}"
                       required
                       autofocus>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ old('email', $user->email ?? '') }}"
                       required>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit -->
            <div class="d-flex justify-content-end align-items-center gap-3">
                @if (session('status') === 'profile-updated')
                    <div class="text-success fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Saved successfully.
                    </div>
                @endif

                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>
