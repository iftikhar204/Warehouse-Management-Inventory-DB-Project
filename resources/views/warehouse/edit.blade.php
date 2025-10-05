@extends('layouts.app')

@section('title', 'Edit Warehouse')

@section('content')
    <div class="d-flex justify-content-between align-items-center pt-4 pb-3 mb-4 border-bottom border-2 border-secondary">
        <h1 class="h1 display-5 fw-bold text-dark mb-0">
            <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Warehouse: {{ $warehouse->Name }}
        </h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg mb-5 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('warehouses.update', $warehouse->warehouse_ID) }}" method="POST">
                @csrf
                @method('PUT')

                <section class="mb-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 mb-4 text-primary border-bottom pb-2">General Warehouse Information</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="warehouseName" class="form-label">Warehouse Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('Name') is-invalid @enderror" id="warehouseName" name="Name"
                                value="{{ old('Name', $warehouse->Name) }}" required aria-describedby="nameHelp">
                            @error('Name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="nameHelp" class="form-text">Enter a unique and descriptive name for the warehouse.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="maxCapacity" class="form-label">Max Capacity (sq ft) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-lg @error('Max_Capacity') is-invalid @enderror" id="maxCapacity" name="Max_Capacity"
                                value="{{ old('Max_Capacity', $warehouse->Max_Capacity) }}" required min="0" aria-describedby="capacityHelp">
                            @error('Max_Capacity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="capacityHelp" class="form-text">Specify the maximum storage capacity in square feet.</div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <label for="operationalStatus" class="form-label">Operational Status <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg @error('operational_status') is-invalid @enderror" id="operationalStatus" name="operational_status" required>
                            <option value="active" {{ old('operational_status', $warehouse->operational_status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ old('operational_status', $warehouse->operational_status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="closed" {{ old('operational_status', $warehouse->operational_status) == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('operational_status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </section>

                <hr class="my-5 border-secondary border-3 opacity-75">

                <section class="mb-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 mb-4 text-primary border-bottom pb-2">Address Information</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="streetAddress" class="form-label">Street Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('street_address') is-invalid @enderror" id="streetAddress" name="street_address"
                                value="{{ old('street_address', $warehouse->address->street_address) }}" required>
                            @error('street_address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('city') is-invalid @enderror" id="city" name="city"
                                value="{{ old('city', $warehouse->address->city) }}" required>
                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="stateProvince" class="form-label">State/Province</label>
                            <input type="text" class="form-control form-control-lg @error('state_province') is-invalid @enderror" id="stateProvince" name="state_province"
                                value="{{ old('state_province', $warehouse->address->state_province) }}">
                            @error('state_province')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="postalCode" class="form-label">Postal Code</label>
                            <input type="text" class="form-control form-control-lg @error('postal_code') is-invalid @enderror" id="postalCode" name="postal_code"
                                value="{{ old('postal_code', $warehouse->address->postal_code) }}">
                            @error('postal_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('country') is-invalid @enderror" id="country" name="country"
                                value="{{ old('country', $warehouse->address->country) }}" required>
                            @error('country')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </section>

                <hr class="my-5 border-secondary border-3 opacity-75">

                <section class="mb-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 mb-4 text-primary border-bottom pb-2">Contact Information</h2>
                    <div id="contactFields">
                        @forelse ($warehouse->contacts as $contact)
                            <div class="row g-3 mb-3 contact-row" data-contact-id="{{ $contact->contact_ID }}">
                                <input type="hidden" name="contacts[{{ $loop->index }}][contact_ID]" value="{{ $contact->contact_ID }}">
                                <div class="col-md-4">
                                    <label for="contactType_{{ $loop->index }}" class="form-label visually-hidden">Contact Type</label>
                                    <select class="form-select @error("contacts.{$loop->index}.contact_type") is-invalid @enderror" id="contactType_{{ $loop->index }}" name="contacts[{{ $loop->index }}][contact_type]" required>
                                        <option value="phone" {{ old("contacts.{$loop->index}.contact_type", $contact->contact_type) == 'phone' ? 'selected' : '' }}>Phone</option>
                                        <option value="mobile" {{ old("contacts.{$loop->index}.contact_type", $contact->contact_type) == 'mobile' ? 'selected' : '' }}>Mobile</option>
                                        <option value="fax" {{ old("contacts.{$loop->index}.contact_type", $contact->contact_type) == 'fax' ? 'selected' : '' }}>Fax</option>
                                        <option value="email" {{ old("contacts.{$loop->index}.contact_type", $contact->contact_type) == 'email' ? 'selected' : '' }}>Email</option>
                                    </select>
                                    @error("contacts.{$loop->index}.contact_type")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="contactValue_{{ $loop->index }}" class="form-label visually-hidden">Contact Value</label>
                                    <input type="text" class="form-control @error("contacts.{$loop->index}.contact_value") is-invalid @enderror" id="contactValue_{{ $loop->index }}"
                                        name="contacts[{{ $loop->index }}][contact_value]"
                                        value="{{ old("contacts.{$loop->index}.contact_value", $contact->contact_value) }}" required>
                                    @error("contacts.{$loop->index}.contact_value")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger remove-contact" title="Remove Contact">
                                        <i class="bi bi-x-circle"></i> <span class="d-none d-md-inline">Remove</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No contacts added yet. Add one below.</p>
                        @endforelse
                    </div>

                    <button type="button" id="addContact" class="btn btn-sm btn-outline-primary mt-3">
                        <i class="bi bi-plus-circle me-1"></i> Add Another Contact
                    </button>
                </section>

                <hr class="my-5 border-secondary border-3 opacity-75">

                <section class="mb-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 mb-4 text-primary border-bottom pb-2">Warehouse Sections</h2>
                    <div id="sectionFields">
                        @forelse ($warehouse->sections as $section)
                            <div class="card mb-4 section-row shadow-sm">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-3">
                                    <h6 class="mb-0">Section {{ $loop->index + 1 }}</h6>
                                    <button type="button" class="btn btn-sm btn-danger remove-section" title="Remove Section">
                                        <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Remove</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="sections[{{ $loop->index }}][section_ID]" value="{{ $section->section_ID }}">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label for="sectionName_{{ $loop->index }}" class="form-label">Section Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error("sections.{$loop->index}.section_name") is-invalid @enderror" id="sectionName_{{ $loop->index }}"
                                                name="sections[{{ $loop->index }}][section_name]"
                                                value="{{ old("sections.{$loop->index}.section_name", $section->section_name) }}" required>
                                            @error("sections.{$loop->index}.section_name")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="sectionCapacity_{{ $loop->index }}" class="form-label">Section Capacity (sq ft) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error("sections.{$loop->index}.s_Capacity") is-invalid @enderror" id="sectionCapacity_{{ $loop->index }}"
                                                name="sections[{{ $loop->index }}][s_Capacity]"
                                                value="{{ old("sections.{$loop->index}.s_Capacity", $section->s_Capacity) }}" required min="0">
                                            @error("sections.{$loop->index}.s_Capacity")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="temperatureZone_{{ $loop->index }}" class="form-label">Temperature Zone <span class="text-danger">*</span></label>
                                        <select class="form-select @error("sections.{$loop->index}.temperature_zone") is-invalid @enderror" id="temperatureZone_{{ $loop->index }}"
                                            name="sections[{{ $loop->index }}][temperature_zone]" required>
                                            <option value="">Select Zone</option>
                                            <option value="ambient" {{ old("sections.{$loop->index}.temperature_zone", $section->temperature_zone) == 'ambient' ? 'selected' : '' }}>Ambient</option>
                                            <option value="chilled" {{ old("sections.{$loop->index}.temperature_zone", $section->temperature_zone) == 'chilled' ? 'selected' : '' }}>Chilled</option>
                                            <option value="frozen" {{ old("sections.{$loop->index}.temperature_zone", $section->temperature_zone) == 'frozen' ? 'selected' : '' }}>Frozen</option>
                                            <option value="controlled" {{ old("sections.{$loop->index}.temperature_zone", $section->temperature_zone) == 'controlled' ? 'selected' : '' }}>Controlled</option>
                                        </select>
                                        @error("sections.{$loop->index}.temperature_zone")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <h6 class="mt-4 mb-3 text-secondary border-bottom pb-1">Subsections for Section {{ $loop->index + 1 }}</h6>
                                    <div class="subsections-container">
                                        @forelse ($section->subsections as $subsection)
                                            <div class="row g-3 mb-3 subsection-row" data-subsection-id="{{ $subsection->Sub_Section_ID }}">
                                                <input type="hidden" name="sections[{{ $loop->parent->index }}][subsections][{{ $loop->index }}][subsection_ID]"
                                                    value="{{ $subsection->Sub_Section_ID }}">
                                                <div class="col-md-5">
                                                    <label for="subsectionName_{{ $loop->parent->index }}_{{ $loop->index }}" class="form-label visually-hidden">Subsection Name</label>
                                                    <input type="text" class="form-control @error("sections.{$loop->parent->index}.subsections.{$loop->index}.subsection_name") is-invalid @enderror"
                                                        id="subsectionName_{{ $loop->parent->index }}_{{ $loop->index }}"
                                                        name="sections[{{ $loop->parent->index }}][subsections][{{ $loop->index }}][subsection_name]"
                                                        value="{{ old("sections.{$loop->parent->index}.subsections.{$loop->index}.subsection_name", $subsection->subsection_name) }}" required>
                                                    @error("sections.{$loop->parent->index}.subsections.{$loop->index}.subsection_name")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="subsectionCapacity_{{ $loop->parent->index }}_{{ $loop->index }}" class="form-label visually-hidden">Subsection Capacity</label>
                                                    <input type="number" class="form-control @error("sections.{$loop->parent->index}.subsections.{$loop->index}.s_Capacity") is-invalid @enderror"
                                                        id="subsectionCapacity_{{ $loop->parent->index }}_{{ $loop->index }}"
                                                        name="sections[{{ $loop->parent->index }}][subsections][{{ $loop->index }}][s_Capacity]"
                                                        value="{{ old("sections.{$loop->parent->index}.subsections.{$loop->index}.s_Capacity", $subsection->s_Capacity) }}" required min="0">
                                                    @error("sections.{$loop->parent->index}.subsections.{$loop->index}.s_Capacity")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-subsection" title="Remove Subsection">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">No subsections added for this section. Add one below.</p>
                                        @endforelse
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-info add-subsection mt-3">
                                        <i class="bi bi-plus-circle me-1"></i> Add Subsection to This Section
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No sections defined for this warehouse. Add one below.</p>
                        @endforelse
                    </div>

                    <button type="button" id="addSection" class="btn btn-sm btn-outline-success mt-3">
                        <i class="bi bi-plus-circle me-1"></i> Add Another Section
                    </button>
                </section>

                <hr class="my-5 border-primary border-3 opacity-75">

               <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-1"></i> Update Warehouse
                    </button>
                    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let contactCounter = {{ count($warehouse->contacts) }};
            let sectionCounter = {{ count($warehouse->sections) }};
            let subsectionCounters = {};
            @foreach ($warehouse->sections as $index => $section)
                subsectionCounters[{{ $index }}] = {{ count($section->subsections) }};
            @endforeach

            function reindexFormFields() {
                $('#contactFields .contact-row').each(function(contactHtmlIndex) {
                    const contactRow = $(this);
                    contactRow.find('input[name^="contacts["][name$="][contact_ID]"]').attr('name', `contacts[${contactHtmlIndex}][contact_ID]`);
                    contactRow.find('select[name^="contacts["][name$="][contact_type]"]').attr('name', `contacts[${contactHtmlIndex}][contact_type]`);
                    contactRow.find('input[name^="contacts["][name$="][contact_value]"]').attr('name', `contacts[${contactHtmlIndex}][contact_value]`);

                    const oldContactTypeId = contactRow.find('select[id^="contactType_"]').attr('id');
                    const newContactTypeId = `contactType_${contactHtmlIndex}`;
                    contactRow.find(`label[for="${oldContactTypeId}"]`).attr('for', newContactTypeId);
                    contactRow.find('select[id^="contactType_"]').attr('id', newContactTypeId);

                    const oldContactValueId = contactRow.find('input[id^="contactValue_"]').attr('id');
                    const newContactValueId = `contactValue_${contactHtmlIndex}`;
                    contactRow.find(`label[for="${oldContactValueId}"]`).attr('for', newContactValueId);
                    contactRow.find('input[id^="contactValue_"]').attr('id', newContactValueId);
                });

                $('#sectionFields .section-row').each(function(sectionHtmlIndex) {
                    const sectionCard = $(this);
                    sectionCard.find('.card-header h6').text(`Section ${sectionHtmlIndex + 1}`);

                    sectionCard.find('input[name$="][section_ID]"]').attr('name', `sections[${sectionHtmlIndex}][section_ID]`);
                    sectionCard.find('input[name$="][section_name]"]').attr('name', `sections[${sectionHtmlIndex}][section_name]`);
                    sectionCard.find('input[name$="][s_Capacity]"]').attr('name', `sections[${sectionHtmlIndex}][s_Capacity]`);
                    sectionCard.find('select[name$="][temperature_zone]"]').attr('name', `sections[${sectionHtmlIndex}][temperature_zone]`);

                    const oldSecNameId = sectionCard.find('input[id^="sectionName_"]').attr('id');
                    const newSecNameId = `sectionName_${sectionHtmlIndex}`;
                    sectionCard.find(`label[for="${oldSecNameId}"]`).attr('for', newSecNameId);
                    sectionCard.find('input[id^="sectionName_"]').attr('id', newSecNameId);

                    const oldSecCapacityId = sectionCard.find('input[id^="sectionCapacity_"]').attr('id');
                    const newSecCapacityId = `sectionCapacity_${sectionHtmlIndex}`;
                    sectionCard.find(`label[for="${oldSecCapacityId}"]`).attr('for', newSecCapacityId);
                    sectionCard.find('input[id^="sectionCapacity_"]').attr('id', newSecCapacityId);

                    const oldTempZoneId = sectionCard.find('select[id^="temperatureZone_"]').attr('id');
                    const newTempZoneId = `temperatureZone_${sectionHtmlIndex}`;
                    sectionCard.find(`label[for="${oldTempZoneId}"]`).attr('for', newTempZoneId);
                    sectionCard.find('select[id^="temperatureZone_"]').attr('id', newTempZoneId);

                    const subsectionsContainer = sectionCard.find('.subsections-container');
                    subsectionsContainer.find('.subsection-row').each(function(subsectionHtmlIndex) {
                        const subsectionRow = $(this);
                        subsectionRow.find('input[name$="][subsection_ID]"]').attr('name', `sections[${sectionHtmlIndex}][subsections][${subsectionHtmlIndex}][subsection_ID]`);
                        subsectionRow.find('input[name$="][subsection_name]"]').attr('name', `sections[${sectionHtmlIndex}][subsections][${subsectionHtmlIndex}][subsection_name]`);
                        subsectionRow.find('input[name$="][s_Capacity]"]').attr('name', `sections[${sectionHtmlIndex}][subsections][${subsectionHtmlIndex}][s_Capacity]`);

                        const oldSubNameId = subsectionRow.find('input[id^="subsectionName_"]').attr('id');
                        const newSubNameId = `subsectionName_${sectionHtmlIndex}_${subsectionHtmlIndex}`;
                        subsectionRow.find(`label[for="${oldSubNameId}"]`).attr('for', newSubNameId);
                        subsectionRow.find('input[id^="subsectionName_"]').attr('id', newSubNameId);

                        const oldSubCapacityId = subsectionRow.find('input[id^="subsectionCapacity_"]').attr('id');
                        const newSubCapacityId = `subsectionCapacity_${sectionHtmlIndex}_${subsectionHtmlIndex}`;
                        subsectionRow.find(`label[for="${oldSubCapacityId}"]`).attr('for', newSubCapacityId);
                        subsectionRow.find('input[id^="subsectionCapacity_"]').attr('id', newSubCapacityId);
                    });
                    subsectionCounters[sectionHtmlIndex] = subsectionsContainer.find('.subsection-row').length;
                });
            }

            function toggleRemoveButtons(containerElement, rowSelector, buttonSelector) {
                const itemCount = containerElement.find(rowSelector).length;
                if (itemCount > 1) {
                    containerElement.find(buttonSelector).show();
                } else {
                    containerElement.find(buttonSelector).hide();
                }
            }

            $('#addContact').click(function() {
                const newRow = `
                <div class="row g-3 mb-3 contact-row new-contact-row">
                    <div class="col-md-4">
                        <label for="contactType_${contactCounter}" class="form-label visually-hidden">Contact Type</label>
                        <select class="form-select" id="contactType_${contactCounter}" name="contacts[${contactCounter}][contact_type]" required>
                            <option value="phone">Phone</option>
                            <option value="mobile">Mobile</option>
                            <option value="fax">Fax</option>
                            <option value="email">Email</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="contactValue_${contactCounter}" class="form-label visually-hidden">Contact Value</label>
                        <input type="text" class="form-control" id="contactValue_${contactCounter}" name="contacts[${contactCounter}][contact_value]" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger remove-contact" title="Remove Contact">
                            <i class="bi bi-x-circle"></i> <span class="d-none d-md-inline">Remove</span>
                        </button>
                    </div>
                </div>`;

                $('#contactFields').append(newRow);
                contactCounter++;
                toggleRemoveButtons($('#contactFields'), '.contact-row', '.remove-contact');
                reindexFormFields();
            });

            $(document).on('click', '.remove-contact', function() {
                $(this).closest('.contact-row').remove();
                reindexFormFields();
                toggleRemoveButtons($('#contactFields'), '.contact-row', '.remove-contact');
            });

            toggleRemoveButtons($('#contactFields'), '.contact-row', '.remove-contact');


            $('#addSection').click(function() {
                const newSectionHtml = `
                <div class="card mb-4 section-row new-section-row shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-3">
                        <h6 class="mb-0">Section ${sectionCounter + 1}</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-section" title="Remove Section">
                            <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Remove</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="sectionName_${sectionCounter}" class="form-label">Section Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sectionName_${sectionCounter}" name="sections[${sectionCounter}][section_name]" required>
                            </div>
                            <div class="col-md-6">
                                <label for="sectionCapacity_${sectionCounter}" class="form-label">Section Capacity (sq ft) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="sectionCapacity_${sectionCounter}" name="sections[${sectionCounter}][s_Capacity]" required min="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="temperatureZone_${sectionCounter}" class="form-label">Temperature Zone <span class="text-danger">*</span></label>
                            <select class="form-select" id="temperatureZone_${sectionCounter}" name="sections[${sectionCounter}][temperature_zone]" required>
                                <option value="">Select Zone</option>
                                <option value="ambient">Ambient</option>
                                <option value="chilled">Chilled</option>
                                <option value="frozen">Frozen</option>
                                <option value="controlled">Controlled</option>
                            </select>
                        </div>

                        <h6 class="mt-4 mb-3 text-secondary border-bottom pb-1">Subsections for Section ${sectionCounter + 1}</h6>
                        <div class="subsections-container">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-info add-subsection mt-3">
                            <i class="bi bi-plus-circle me-1"></i> Add Subsection to This Section
                        </button>
                    </div>
                </div>`;

                $('#sectionFields').append(newSectionHtml);
                subsectionCounters[sectionCounter] = 0;
                sectionCounter++;
                toggleRemoveButtons($('#sectionFields'), '.section-row', '.remove-section');
                reindexFormFields();
            });

            $(document).on('click', '.remove-section', function() {
                $(this).closest('.section-row').remove();
                reindexFormFields();
                toggleRemoveButtons($('#sectionFields'), '.section-row', '.remove-section');
            });

            toggleRemoveButtons($('#sectionFields'), '.section-row', '.remove-section');

            $(document).on('click', '.add-subsection', function() {
                const sectionCard = $(this).closest('.section-row');
                const sectionIndex = sectionCard.index('.section-row');
                let currentSubsectionCount = subsectionCounters[sectionIndex] || 0;

                const newSubsectionHtml = `
                    <div class="row g-3 mb-3 subsection-row new-subsection-row">
                        <div class="col-md-5">
                            <label for="subsectionName_${sectionIndex}_${currentSubsectionCount}" class="form-label visually-hidden">Subsection Name</label>
                            <input type="text" class="form-control" id="subsectionName_${sectionIndex}_${currentSubsectionCount}"
                                name="sections[${sectionIndex}][subsections][${currentSubsectionCount}][subsection_name]" required>
                        </div>
                        <div class="col-md-5">
                            <label for="subsectionCapacity_${sectionIndex}_${currentSubsectionCount}" class="form-label visually-hidden">Subsection Capacity</label>
                            <input type="number" class="form-control" id="subsectionCapacity_${sectionIndex}_${currentSubsectionCount}"
                                name="sections[${sectionIndex}][subsections][${currentSubsectionCount}][s_Capacity]" required min="0">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-subsection" title="Remove Subsection">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    </div>`;

                sectionCard.find('.subsections-container').append(newSubsectionHtml);
                subsectionCounters[sectionIndex]++;
                toggleRemoveButtons(sectionCard.find('.subsections-container'), '.subsection-row', '.remove-subsection');
                reindexFormFields();
            });

            $(document).on('click', '.remove-subsection', function() {
                const subsectionRow = $(this).closest('.subsection-row');
                const sectionCard = subsectionRow.closest('.section-row');
                subsectionRow.remove();
                reindexFormFields();
                toggleRemoveButtons(sectionCard.find('.subsections-container'), '.subsection-row', '.remove-subsection');
            });

            reindexFormFields();
            $('#sectionFields .section-row').each(function() {
                toggleRemoveButtons($(this).find('.subsections-container'), '.subsection-row', '.remove-subsection');
            });
        });
    </script>
@endsection
