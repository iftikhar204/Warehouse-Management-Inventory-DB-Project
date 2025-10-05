@extends('layouts.app')

@section('title', 'Add New Employee')

@section('content')
    <div class="container-fluid px-4">

        <div class="d-flex justify-content-between align-items-center border-bottom mb-4 pb-2">
            <h1 class="h4 fw-bold text-primary d-flex align-items-center">
                <i class="bi bi-person-plus-fill me-2"></i> Add New Employee
            </h1>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form action="{{ route('employees.store') }}" method="POST">
                    @csrf

                    {{-- Basic Info --}}
                    <h5 class="text-primary mb-3"><i class="bi bi-info-circle me-1"></i> Basic Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="F_Name" class="form-control" value="{{ old('F_Name') }}" required>
                            @error('F_Name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="L_Name" class="form-control" value="{{ old('L_Name') }}" required>
                            @error('L_Name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="DoB" class="form-control" value="{{ old('DoB') }}">
                            @error('DoB')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Address --}}
                    <h5 class="text-primary mb-3"><i class="bi bi-geo-alt me-1"></i> Address Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="street_address" class="form-control"
                                value="{{ old('street_address') }}" required>
                            @error('street_address')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State/Province</label>
                            <input type="text" name="state_province" class="form-control"
                                value="{{ old('state_province') }}">
                            @error('state_province')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" value="{{ old('country') }}"
                                required>
                            @error('country')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Employment Info --}}
                    <h5 class="text-primary mb-3"><i class="bi bi-briefcase me-1"></i> Employment Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Hire Date</label>
                            <input type="date" name="hire_date" class="form-control" value="{{ old('hire_date') }}"
                                required>
                            @error('hire_date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept }}"
                                        {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Salary</label>
                            <input type="number" step="0.01" name="salary" class="form-control"
                                value="{{ old('salary') }}">
                            @error('salary')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Role Section (leave as is, for now it's well structured) --}}
                    <h5 class="text-primary mb-3"><i class="bi bi-person-badge me-1"></i> Employee Role</h5>
                    {{-- Leave role-specific fields and JS logic as-is, just align spacing --}}
                    {{-- ... keep your radio buttons and dynamic sections here ... --}}

                    <h5 class="text-primary mb-3"><i class="bi bi-telephone me-1"></i> Contact Information</h5>

                    <div id="contactFields">
                        @if (old('contacts'))
                            @foreach (old('contacts') as $index => $contact)
                                <div class="row g-3 mb-3 contact-row">
                                    <div class="col-md-4">
                                        <label class="form-label">Contact Type</label>
                                        <select class="form-select" name="contacts[{{ $index }}][contact_type]"
                                            required>
                                            <option value="phone"
                                                {{ $contact['contact_type'] == 'phone' ? 'selected' : '' }}>Phone</option>
                                            <option value="mobile"
                                                {{ $contact['contact_type'] == 'mobile' ? 'selected' : '' }}>Mobile
                                            </option>
                                            <option value="email"
                                                {{ $contact['contact_type'] == 'email' ? 'selected' : '' }}>Email</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contact Value</label>
                                        <input type="text" class="form-control"
                                            name="contacts[{{ $index }}][contact_value]"
                                            value="{{ $contact['contact_value'] }}" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger remove-contact w-100">
                                            <i class="bi bi-trash me-1"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row g-3 mb-3 contact-row">
                                <div class="col-md-4">
                                    <label class="form-label">Contact Type</label>
                                    <select class="form-select" name="contacts[0][contact_type]" required>
                                        <option value="phone">Phone</option>
                                        <option value="mobile">Mobile</option>
                                        <option value="email">Email</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact Value</label>
                                    <input type="text" class="form-control" name="contacts[0][contact_value]"
                                        required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-contact w-100"
                                        style="display: none;">
                                        <i class="bi bi-trash me-1"></i> Remove
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <button type="button" id="addContact" class="btn btn-outline-primary btn-sm mb-3">
                        <i class="bi bi-plus-circle me-1"></i> Add Another Contact
                    </button>


                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Save Employee
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary px-4">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script>
$(document).ready(function () {
    let contactCount = {{ count(old('contacts', [])) > 0 ? count(old('contacts')) : 1 }};

    $('#addContact').click(function () {
        const newRow = `
        <div class="row g-3 mb-3 contact-row">
            <div class="col-md-4">
                <label class="form-label">Contact Type</label>
                <select class="form-select" name="contacts[${contactCount}][contact_type]" required>
                    <option value="phone">Phone</option>
                    <option value="mobile">Mobile</option>
                    <option value="email">Email</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Contact Value</label>
                <input type="text" class="form-control" name="contacts[${contactCount}][contact_value]" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-contact w-100">
                    <i class="bi bi-trash me-1"></i> Remove
                </button>
            </div>
        </div>`;
        $('#contactFields').append(newRow);
        contactCount++;
        updateRemoveButtonsVisibility();
    });

    $(document).on('click', '.remove-contact', function () {
        $(this).closest('.contact-row').remove();
        updateRemoveButtonsVisibility();
    });

    function updateRemoveButtonsVisibility() {
        $('.remove-contact').toggle($('.contact-row').length > 1);
    }

    updateRemoveButtonsVisibility();
});
</script>
@endsection

