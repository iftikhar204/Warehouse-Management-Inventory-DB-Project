@extends('layouts.app')

@section('title', 'Add New Warehouse')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center pt-4 pb-3 mb-4 border-bottom border-2 border-secondary">
        <h1 class="h1 display-5 fw-bold text-dark mb-0">
            <i class="bi bi-plus-square me-2 text-primary"></i> Create New Warehouse
        </h1>
    </div>

    {{-- Success and Error Alerts (Mimicking Edit Template) --}}
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

    {{-- Main Form Card --}}
    <div class="card shadow-lg mb-5 rounded-3">
        <div class="card-body p-4">
            {{-- Form for creating a new warehouse --}}
            <form action="{{ route('warehouses.store') }}" method="POST">
                @csrf

                {{-- Section: General Warehouse Information --}}
                <section class="mb-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 mb-4 text-primary border-bottom pb-2">General Warehouse Information</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="warehouseName" class="form-label">Warehouse Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('Name') is-invalid @enderror"
                                id="warehouseName" name="Name" value="{{ old('Name') }}" required
                                aria-describedby="nameHelp">
                            @error('Name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="nameHelp" class="form-text">Enter a unique and descriptive name for the warehouse.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="maxCapacity" class="form-label">Max Capacity (sq ft) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-lg @error('Max_Capacity') is-invalid @enderror"
                                id="maxCapacity" name="Max_Capacity" value="{{ old('Max_Capacity') }}" required min="0"
                                aria-describedby="capacityHelp">
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
                        <select class="form-select form-select-lg @error('operational_status') is-invalid @enderror"
                            id="operationalStatus" name="operational_status" required>
                            <option value="">Select Status</option> {{-- Added a default 'Select Status' option --}}
                            <option value="active" {{ old('operational_status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ old('operational_status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="closed" {{ old('operational_status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('operational_status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </section>

                <hr class="my-5 border-secondary border-3 opacity-75">

                {{-- Section: Address Information --}}
                <section class="mb-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 mb-4 text-primary border-bottom pb-2">Address Information</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="streetAddress" class="form-label">Street Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('street_address') is-invalid @enderror"
                                id="streetAddress" name="street_address" value="{{ old('street_address') }}" required>
                            @error('street_address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('city') is-invalid @enderror"
                                id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="stateProvince" class="form-label">State/Province</label>
                            <input type="text" class="form-control form-control-lg @error('state_province') is-invalid @enderror"
                                id="stateProvince" name="state_province" value="{{ old('state_province') }}">
                            @error('state_province')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="postalCode" class="form-label">Postal Code</label>
                            <input type="text" class="form-control form-control-lg @error('postal_code') is-invalid @enderror"
                                id="postalCode" name="postal_code" value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('country') is-invalid @enderror"
                                id="country" name="country" value="{{ old('country') }}" required>
                            @error('country')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </section>

                <hr class="my-5 border-secondary border-3 opacity-75">

                {{-- Section: Contact Information (Dynamic) --}}
                <section class="mb-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 mb-4 text-primary border-bottom pb-2">Contact Information</h2>
                    <div id="contactFields">
                        {{-- Default first contact field, always present for new warehouses --}}
                        <div class="row g-3 mb-3 contact-row">
                            <div class="col-md-4">
                                <label for="contactType_0" class="form-label visually-hidden">Contact Type</label>
                                <select class="form-select @error('contacts.0.contact_type') is-invalid @enderror"
                                    id="contactType_0" name="contacts[0][contact_type]" required>
                                    <option value="">Select Type</option>
                                    <option value="phone" {{ old('contacts.0.contact_type') == 'phone' ? 'selected' : '' }}>Phone</option>
                                    <option value="mobile" {{ old('contacts.0.contact_type') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                                    <option value="fax" {{ old('contacts.0.contact_type') == 'fax' ? 'selected' : '' }}>Fax</option>
                                    <option value="email" {{ old('contacts.0.contact_type') == 'email' ? 'selected' : '' }}>Email</option>
                                </select>
                                @error('contacts.0.contact_type')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contactValue_0" class="form-label visually-hidden">Contact Value</label>
                                <input type="text" class="form-control @error('contacts.0.contact_value') is-invalid @enderror"
                                    id="contactValue_0" name="contacts[0][contact_value]" value="{{ old('contacts.0.contact_value') }}"
                                    required>
                                @error('contacts.0.contact_value')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                {{-- The first contact's remove button will be hidden by JS if it's the only one --}}
                                <button type="button" class="btn btn-outline-danger remove-contact" title="Remove Contact">
                                    <i class="bi bi-x-circle"></i> <span class="d-none d-md-inline">Remove</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addContact" class="btn btn-sm btn-outline-primary mt-3">
                        <i class="bi bi-plus-circle me-1"></i> Add Another Contact
                    </button>
                </section>

                <hr class="my-5 border-secondary border-3 opacity-75">

                {{-- Section: Warehouse Sections (Dynamic) --}}
                <section class="mb-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 mb-4 text-primary border-bottom pb-2">Warehouse Sections</h2>
                    <div id="sectionFields">
                        {{-- Default first section, always present for new warehouses --}}
                        <div class="card mb-4 section-row shadow-sm">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-3">
                                <h6 class="mb-0">Section 1</h6>
                                {{-- The first section's remove button will be hidden by JS if it's the only one --}}
                                <button type="button" class="btn btn-sm btn-danger remove-section" title="Remove Section">
                                    <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Remove</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="sectionName_0" class="form-label">Section Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('sections.0.section_name') is-invalid @enderror"
                                            id="sectionName_0" name="sections[0][section_name]" value="{{ old('sections.0.section_name') }}"
                                            required>
                                        @error('sections.0.section_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sectionCapacity_0" class="form-label">Section Capacity (sq ft) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('sections.0.s_Capacity') is-invalid @enderror"
                                            id="sectionCapacity_0" name="sections[0][s_Capacity]" value="{{ old('sections.0.s_Capacity') }}"
                                            required min="0">
                                        @error('sections.0.s_Capacity')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="temperatureZone_0" class="form-label">Temperature Zone <span class="text-danger">*</span></label>
                                    <select class="form-select @error('sections.0.temperature_zone') is-invalid @enderror"
                                        id="temperatureZone_0" name="sections[0][temperature_zone]" required>
                                        <option value="">Select Zone</option>
                                        <option value="ambient" {{ old('sections.0.temperature_zone') == 'ambient' ? 'selected' : '' }}>Ambient</option>
                                        <option value="chilled" {{ old('sections.0.temperature_zone') == 'chilled' ? 'selected' : '' }}>Chilled</option>
                                        <option value="frozen" {{ old('sections.0.temperature_zone') == 'frozen' ? 'selected' : '' }}>Frozen</option>
                                        <option value="controlled" {{ old('sections.0.temperature_zone') == 'controlled' ? 'selected' : '' }}>Controlled</option>
                                    </select>
                                    @error('sections.0.temperature_zone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <h6 class="mt-4 mb-3 text-secondary border-bottom pb-1">Subsections for Section 1</h6>
                                <div class="subsections-container">
                                    {{-- Default first subsection for new sections --}}
                                    <div class="row g-3 mb-3 subsection-row">
                                        <div class="col-md-5">
                                            <label for="subsectionName_0_0" class="form-label visually-hidden">Subsection Name</label>
                                            <input type="text" class="form-control @error('sections.0.subsections.0.subsection_name') is-invalid @enderror"
                                                id="subsectionName_0_0" name="sections[0][subsections][0][subsection_name]"
                                                value="{{ old('sections.0.subsections.0.subsection_name') }}" required>
                                            @error('sections.0.subsections.0.subsection_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-5">
                                            <label for="subsectionCapacity_0_0" class="form-label visually-hidden">Subsection Capacity</label>
                                            <input type="number" class="form-control @error('sections.0.subsections.0.s_Capacity') is-invalid @enderror"
                                                id="subsectionCapacity_0_0" name="sections[0][subsections][0][s_Capacity]"
                                                value="{{ old('sections.0.subsections.0.s_Capacity') }}" required min="0">
                                            @error('sections.0.subsections.0.s_Capacity')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            {{-- The first subsection's remove button will be hidden by JS if it's the only one --}}
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-subsection"
                                                title="Remove Subsection">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-info add-subsection mt-3">
                                    <i class="bi bi-plus-circle me-1"></i> Add Subsection to This Section
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addSection" class="btn btn-sm btn-outline-success mt-3">
                        <i class="bi bi-plus-circle me-1"></i> Add Another Section
                    </button>
                </section>

                <hr class="my-5 border-primary border-3 opacity-75">

                {{-- Form Actions --}}
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-1"></i> Create Warehouse
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
            // Initialize counters for dynamic fields.
            // For a 'create' form, these start at 1 since the first (index 0) is already in the HTML.
            let contactCounter = $('#contactFields .contact-row').length;
            let sectionCounter = $('#sectionFields .section-row').length;
            let subsectionCounters = {}; // Stores subsection counts per section index

            // Initialize subsection counters for existing sections (only 0 for create)
            $('#sectionFields .section-row').each(function(index) {
                subsectionCounters[index] = $(this).find('.subsection-row').length;
            });


            /**
             * Re-indexes all dynamic form fields (contacts, sections, subsections)
             * to maintain sequential array indexing for Laravel's input handling.
             * Also updates displayed section numbers and element IDs/labels.
             */
            function reindexFormFields() {
                // Re-index contacts
                $('#contactFields .contact-row').each(function(contactHtmlIndex) {
                    const contactRow = $(this);
                    // Update 'name' attributes for inputs and selects
                    contactRow.find('input, select').each(function() {
                        const currentName = $(this).attr('name');
                        if (currentName) {
                            const newName = currentName.replace(/contacts\[\d+\]/, `contacts[${contactHtmlIndex}]`);
                            $(this).attr('name', newName);
                        }
                    });

                    // Update IDs and labels for accessibility (if they exist)
                    contactRow.find('select[id^="contactType_"]').attr('id', `contactType_${contactHtmlIndex}`).prev('label').attr('for', `contactType_${contactHtmlIndex}`);
                    contactRow.find('input[id^="contactValue_"]').attr('id', `contactValue_${contactHtmlIndex}`).prev('label').attr('for', `contactValue_${contactHtmlIndex}`);
                });
                contactCounter = $('#contactFields .contact-row').length; // Keep global counter accurate

                // Re-index sections
                $('#sectionFields .section-row').each(function(sectionHtmlIndex) {
                    const sectionCard = $(this);
                    // Update data attribute for quick lookup
                    sectionCard.attr('data-section-index', sectionHtmlIndex);
                    // Update section header text (e.g., "Section 1", "Section 2")
                    sectionCard.find('.card-header h6').text(`Section ${sectionHtmlIndex + 1}`);

                    // Update 'name' attributes for section inputs and selects
                    sectionCard.find('input, select').each(function() {
                        const currentName = $(this).attr('name');
                        if (currentName) {
                            // This regex replaces the section index while preserving the subsection index if present
                            const newName = currentName.replace(/sections\[\d+\]/, `sections[${sectionHtmlIndex}]`);
                            $(this).attr('name', newName);
                        }
                    });

                    // Update IDs and labels for accessibility within the section
                    sectionCard.find('input[id^="sectionName_"]').attr('id', `sectionName_${sectionHtmlIndex}`).prev('label').attr('for', `sectionName_${sectionHtmlIndex}`);
                    sectionCard.find('input[id^="sectionCapacity_"]').attr('id', `sectionCapacity_${sectionHtmlIndex}`).prev('label').attr('for', `sectionCapacity_${sectionHtmlIndex}`);
                    sectionCard.find('select[id^="temperatureZone_"]').attr('id', `temperatureZone_${sectionHtmlIndex}`).prev('label').attr('for', `temperatureZone_${sectionHtmlIndex}`);


                    // Re-index subsections within this specific section
                    const subsectionsContainer = sectionCard.find('.subsections-container');
                    subsectionsContainer.find('h6').text(`Subsections for Section ${sectionHtmlIndex + 1}`); // Update subsection header
                    subsectionsContainer.find('.subsection-row').each(function(subsectionHtmlIndex) {
                        const subsectionRow = $(this);
                        // Update 'name' attributes for subsection inputs
                        subsectionRow.find('input').each(function() {
                            const currentName = $(this).attr('name');
                            if (currentName) {
                                // This regex specifically targets the subsection index part
                                const newName = currentName
                                    .replace(/sections\[\d+\]\[subsections\]\[\d+\]/,
                                        `sections[${sectionHtmlIndex}][subsections][${subsectionHtmlIndex}]`);
                                $(this).attr('name', newName);
                            }
                        });

                        // Update IDs and labels for accessibility within the subsection
                        subsectionRow.find('input[id^="subsectionName_"]').attr('id', `subsectionName_${sectionHtmlIndex}_${subsectionHtmlIndex}`).prev('label').attr('for', `subsectionName_${sectionHtmlIndex}_${subsectionHtmlIndex}`);
                        subsectionRow.find('input[id^="subsectionCapacity_"]').attr('id', `subsectionCapacity_${sectionHtmlIndex}_${subsectionHtmlIndex}`).prev('label').attr('for', `subsectionCapacity_${sectionHtmlIndex}_${subsectionHtmlIndex}`);
                    });
                    subsectionCounters[sectionHtmlIndex] = subsectionsContainer.find('.subsection-row').length;
                });
                sectionCounter = $('#sectionFields .section-row').length; // Keep global counter accurate
            }

            /**
             * Shows/hides remove buttons based on the count of items in a container.
             * If only one item exists, its remove button is hidden.
             * @param {jQuery} containerElement The parent container (e.g., #contactFields, .subsections-container).
             * @param {string} rowSelector The selector for each dynamic row (e.g., '.contact-row').
             * @param {string} buttonSelector The selector for the remove buttons (e.g., '.remove-contact').
             */
            function toggleRemoveButtons(containerElement, rowSelector, buttonSelector) {
                const itemCount = containerElement.find(rowSelector).length;
                if (itemCount > 1) {
                    containerElement.find(buttonSelector).show();
                } else {
                    containerElement.find(buttonSelector).hide();
                }
            }

            // --- Event Listeners for Dynamic Fields ---

            // Add Contact
            $('#addContact').on('click', function() {
                const newContactRow = `
                    <div class="row g-3 mb-3 contact-row">
                        <div class="col-md-4">
                            <label for="contactType_${contactCounter}" class="form-label visually-hidden">Contact Type</label>
                            <select class="form-select" id="contactType_${contactCounter}" name="contacts[${contactCounter}][contact_type]" required>
                                <option value="">Select Type</option>
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

                $('#contactFields').append(newContactRow);
                contactCounter++; // Increment for the next one
                toggleRemoveButtons($('#contactFields'), '.contact-row', '.remove-contact');
                // No need to call reindexFormFields() here for contacts, as the counter is always sequential
            });

            // Remove Contact (delegated event)
            $(document).on('click', '.remove-contact', function() {
                $(this).closest('.contact-row').remove();
                reindexFormFields(); // Re-index all fields after removal to fix array keys
                toggleRemoveButtons($('#contactFields'), '.contact-row', '.remove-contact');
            });

            // Add Section
            $('#addSection').on('click', function() {
                const currentSectionIdx = sectionCounter; // Use current index for the new section
                subsectionCounters[currentSectionIdx] = 1; // Initialize subsection counter for this new section

                const newSectionHtml = `
                    <div class="card mb-4 section-row shadow-sm">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-3">
                            <h6 class="mb-0">Section ${currentSectionIdx + 1}</h6>
                            <button type="button" class="btn btn-sm btn-danger remove-section" title="Remove Section">
                                <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Remove</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="sectionName_${currentSectionIdx}" class="form-label">Section Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="sectionName_${currentSectionIdx}" name="sections[${currentSectionIdx}][section_name]" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="sectionCapacity_${currentSectionIdx}" class="form-label">Section Capacity (sq ft) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="sectionCapacity_${currentSectionIdx}" name="sections[${currentSectionIdx}][s_Capacity]" required min="0">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="temperatureZone_${currentSectionIdx}" class="form-label">Temperature Zone <span class="text-danger">*</span></label>
                                <select class="form-select" id="temperatureZone_${currentSectionIdx}" name="sections[${currentSectionIdx}][temperature_zone]" required>
                                    <option value="">Select Zone</option>
                                    <option value="ambient">Ambient</option>
                                    <option value="chilled">Chilled</option>
                                    <option value="frozen">Frozen</option>
                                    <option value="controlled">Controlled</option>
                                </select>
                            </div>

                            <h6 class="mt-4 mb-3 text-secondary border-bottom pb-1">Subsections for Section ${currentSectionIdx + 1}</h6>
                            <div class="subsections-container">
                                {{-- Default first subsection for newly added sections --}}
                                <div class="row g-3 mb-3 subsection-row">
                                    <div class="col-md-5">
                                        <label for="subsectionName_${currentSectionIdx}_0" class="form-label visually-hidden">Subsection Name</label>
                                        <input type="text" class="form-control" id="subsectionName_${currentSectionIdx}_0" name="sections[${currentSectionIdx}][subsections][0][subsection_name]" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="subsectionCapacity_${currentSectionIdx}_0" class="form-label visually-hidden">Subsection Capacity</label>
                                        <input type="number" class="form-control" id="subsectionCapacity_${currentSectionIdx}_0" name="sections[${currentSectionIdx}][subsections][0][s_Capacity]" required min="0">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-subsection" title="Remove Subsection">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-info add-subsection mt-3">
                                <i class="bi bi-plus-circle me-1"></i> Add Subsection to This Section
                            </button>
                        </div>
                    </div>`;

                $('#sectionFields').append(newSectionHtml);
                sectionCounter++; // Increment for the next one
                toggleRemoveButtons($('#sectionFields'), '.section-row', '.remove-section');
                // The newly added section will have its own subsections-container, so toggle its button
                toggleRemoveButtons($(`[data-section-index="${currentSectionIdx}"]`).find('.subsections-container'), '.subsection-row', '.remove-subsection');
                // No need to call reindexFormFields() here for sections as the counter is sequential
            });

            // Remove Section (delegated event)
            $(document).on('click', '.remove-section', function() {
                $(this).closest('.section-row').remove();
                reindexFormFields(); // Re-index all fields after removal to fix array keys
                toggleRemoveButtons($('#sectionFields'), '.section-row', '.remove-section');
            });

            // Add Subsection (delegated event)
            $(document).on('click', '.add-subsection', function() {
                const parentSectionCard = $(this).closest('.section-row');
                const sectionIdx = parentSectionCard.attr('data-section-index'); // Get the data-section-index
                const subsectionsContainer = parentSectionCard.find('.subsections-container');
                const currentSubsectionCount = subsectionsContainer.find('.subsection-row').length;

                const newSubsectionRow = `
                    <div class="row g-3 mb-3 subsection-row">
                        <div class="col-md-5">
                            <label for="subsectionName_${sectionIdx}_${currentSubsectionCount}" class="form-label visually-hidden">Subsection Name</label>
                            <input type="text" class="form-control" id="subsectionName_${sectionIdx}_${currentSubsectionCount}" name="sections[${sectionIdx}][subsections][${currentSubsectionCount}][subsection_name]" required>
                        </div>
                        <div class="col-md-5">
                            <label for="subsectionCapacity_${sectionIdx}_${currentSubsectionCount}" class="form-label visually-hidden">Subsection Capacity</label>
                            <input type="number" class="form-control" id="subsectionCapacity_${sectionIdx}_${currentSubsectionCount}" name="sections[${sectionIdx}][subsections][${currentSubsectionCount}][s_Capacity]" required min="0">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-subsection" title="Remove Subsection">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    </div>`;

                subsectionsContainer.append(newSubsectionRow);
                subsectionCounters[sectionIdx]++; // Increment for this specific section's subsection counter
                toggleRemoveButtons(subsectionsContainer, '.subsection-row', '.remove-subsection');
            });

            // Remove Subsection (delegated event)
            $(document).on('click', '.remove-subsection', function() {
                const parentSectionCard = $(this).closest('.section-row');
                const sectionIdx = parentSectionCard.attr('data-section-index');
                const subsectionsContainer = parentSectionCard.find('.subsections-container');

                $(this).closest('.subsection-row').remove();
                reindexFormFields(); // Re-index all fields after removal to fix array keys
                toggleRemoveButtons(subsectionsContainer, '.subsection-row', '.remove-subsection');
                subsectionCounters[sectionIdx]--; // Decrement for this specific section's subsection counter
            });

            // --- Initial calls on page load ---
            reindexFormFields(); // Call initially to set correct data-section-index attributes
            toggleRemoveButtons($('#contactFields'), '.contact-row', '.remove-contact');
            toggleRemoveButtons($('#sectionFields'), '.section-row', '.remove-section');
            // Loop through each existing section to handle its subsections' remove buttons
            $('#sectionFields .section-row').each(function() {
                toggleRemoveButtons($(this).find('.subsections-container'), '.subsection-row', '.remove-subsection');
            });
        });
    </script>
@endsection
