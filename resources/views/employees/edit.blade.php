@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="container-fluid px-4">

    {{-- Success Toast --}}
    @if(session('success'))
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div class="toast align-items-center text-bg-success border-0 shadow fade show" role="alert" aria-live="assertive">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold text-primary d-flex align-items-center">
            <i class="bi bi-pencil-square me-2"></i> Edit Employee: {{ $employee->F_Name }} {{ $employee->L_Name }}
        </h1>
    </div>

    {{-- Employee Form --}}
    <form action="{{ route('employees.update', $employee->Emp_ID) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="deleted_contact_ids" id="deletedContactIds">

        {{-- Basic Info --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light fw-semibold">
                <i class="bi bi-person-badge me-1"></i> Basic Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="F_Name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="F_Name" name="F_Name" value="{{ old('F_Name', $employee->F_Name) }}" required>
                        @error('F_Name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="L_Name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="L_Name" name="L_Name" value="{{ old('L_Name', $employee->L_Name) }}" required>
                        @error('L_Name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="DoB" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="DoB" name="DoB" value="{{ old('DoB', optional($employee->DoB)->format('Y-m-d')) }}">
                        @error('DoB') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Address Info --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light fw-semibold">
                <i class="bi bi-geo-alt me-1"></i> Address Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="street_address" class="form-label">Street Address</label>
                        <input type="text" class="form-control" id="street_address" name="street_address" value="{{ old('street_address', optional($employee->address)->street_address) }}" required>
                        @error('street_address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', optional($employee->address)->city) }}" required>
                        @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="state_province" class="form-label">State/Province</label>
                        <input type="text" class="form-control" id="state_province" name="state_province" value="{{ old('state_province', optional($employee->address)->state_province) }}">
                        @error('state_province') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', optional($employee->address)->postal_code) }}">
                        @error('postal_code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" value="{{ old('country', optional($employee->address)->country) }}" required>
                        @error('country') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Employment Info --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light fw-semibold">
                <i class="bi bi-briefcase me-1"></i> Employment Details
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="hire_date" class="form-label">Hire Date</label>
                        <input type="date" class="form-control" id="hire_date" name="hire_date" value="{{ old('hire_date', optional($employee->hire_date)->format('Y-m-d')) }}" required>
                        @error('hire_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $employee->position) }}" required>
                        @error('position') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" id="department" name="department" required>
                            <option value="">Select Department</option>
                            @foreach(['Warehouse', 'Inventory', 'Shipping', 'Procurement', 'Management'] as $dept)
                                <option value="{{ $dept }}" {{ old('department', $employee->department) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                        @error('department') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="salary" class="form-label">Salary</label>
                        <input type="number" class="form-control" id="salary" name="salary" step="0.01" value="{{ old('salary', $employee->salary) }}">
                        @error('salary') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light fw-semibold">
                <i class="bi bi-telephone me-1"></i> Contact Information
            </div>
            <div class="card-body" id="contactFields">
                @foreach($employee->contactNumbers as $index => $contact)
                <div class="row g-3 align-items-end contact-row mb-3">
                    <input type="hidden" name="contacts[{{ $index }}][Contact_ID]" value="{{ $contact->Contact_ID }}">
                    <div class="col-md-4">
                        <label class="form-label">Contact Type</label>
                        <select name="contacts[{{ $index }}][contact_type]" class="form-select" required>
                            @foreach(['phone', 'mobile', 'email'] as $type)
                                <option value="{{ $type }}" {{ old("contacts.$index.contact_type", $contact->contact_type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Value</label>
                        <input type="text" name="contacts[{{ $index }}][contact_value]" class="form-control"
                            value="{{ old("contacts.$index.contact_value", $contact->contact_value) }}" required>
                        @error("contacts.$index.contact_value") <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-outline-danger remove-contact" data-contact-id="{{ $contact->Contact_ID }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach

                <button type="button" id="addContact" class="btn btn-outline-primary mt-2">
                    <i class="bi bi-plus-circle me-1"></i> Add Another Contact
                </button>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="text-end mt-4">
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary shadow">
                <i class="bi bi-save me-1"></i> Update Employee
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        let contactCount = $('#contactFields .contact-row').length;
        let deletedContactIds = [];

        $('#addContact').click(function () {
            const newRow = `
            <div class="row g-3 align-items-end contact-row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Contact Type</label>
                    <select name="contacts[${contactCount}][contact_type]" class="form-select" required>
                        <option value="phone">Phone</option>
                        <option value="mobile">Mobile</option>
                        <option value="email">Email</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact Value</label>
                    <input type="text" name="contacts[${contactCount}][contact_value]" class="form-control" required>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-outline-danger remove-contact">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
            $('#contactFields').append(newRow);
            contactCount++;
        });

        $(document).on('click', '.remove-contact', function () {
            const contactId = $(this).data('contact-id');
            if (contactId) {
                deletedContactIds.push(contactId);
                $('#deletedContactIds').val(deletedContactIds.join(','));
            }
            $(this).closest('.contact-row').remove();
        });
    });
</script>
@endsection
