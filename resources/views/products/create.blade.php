@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center pt-4 pb-3 mb-4 border-bottom border-2 border-secondary">
        <h1 class="h1 display-5 fw-bold text-dark mb-0">
            <i class="bi bi-box-seam me-2 text-primary"></i> Add New Product
        </h1>
    </div>

    {{-- Success and Error Alerts (Consistent with Edit Template) --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Form Card --}}
    <div class="card shadow-lg mb-5 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Section: General Product Information --}}
                <section class="mb-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 mb-4 text-primary border-bottom pb-2">General Product Information</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="Product_Name" class="form-label">Product Name <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control form-control-lg @error('Product_Name') is-invalid @enderror"
                                id="Product_Name" name="Product_Name" value="{{ old('Product_Name') }}"
                                aria-describedby="productNameHelp">
                            @error('Product_Name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="productNameHelp" class="form-text">Enter the official name of the product.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="barcode" class="form-label">Barcode <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                                    id="barcode" name="barcode" value="{{ old('barcode') }}"
                                    aria-describedby="barcodeHelp">
                                <button class="btn btn-outline-secondary" type="button" id="generateBarcode"
                                    title="Generate New Barcode">
                                    <i class="bi bi-upc-scan me-1"></i> Generate
                                </button>
                            </div>
                            @error('barcode')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="barcodeHelp" class="form-text">Unique identifier for the product, or click 'Generate'
                                to create one.</div>
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <label for="Type_Name" class="form-label">Product Type <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control form-control-lg @error('Type_Name') is-invalid @enderror" id="Type_Name"
                                name="Type_Name" value="{{ old('Type_Name') }}"
                                placeholder="e.g., Electronics, Food, Apparel">
                            @error('Type_Name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="Product_Date" class="form-label">Product Release Date <span
                                    class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control form-control-lg @error('Product_Date') is-invalid @enderror"
                                id="Product_Date" name="Product_Date" value="{{ old('Product_Date') }}">
                            @error('Product_Date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <label for="weight" class="form-label">Weight (kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01"
                                class="form-control form-control-lg @error('weight') is-invalid @enderror" id="weight"
                                name="weight" value="{{ old('weight') }}" min="0">
                            @error('weight')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="dimensions" class="form-label">Dimensions (LxWxH in cm) <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control form-control-lg @error('dimensions') is-invalid @enderror"
                                id="dimensions" name="dimensions" value="{{ old('dimensions') }}"
                                placeholder="e.g., 10x5x2">
                            @error('dimensions')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control form-control-lg @error('image') is-invalid @enderror"
                                id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <label for="description" class="form-label">Description <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                    <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>
                                        Out of Stock</option>
                                    <option value="discontinued" {{ old('status') == 'discontinued' ? 'selected' : '' }}>
                                        Discontinued</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01"
                                    class="form-control @error('price') is-invalid @enderror" id="price"
                                    name="price" value="{{ old('price') }}" min="0">
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </section>

                <hr class="my-5 border-secondary border-3 opacity-75">

                {{-- Section: Initial Stock Information --}}
                <section class="mb-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 mb-4 text-primary border-bottom pb-2">Initial Stock Information</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">Initial Quantity <span
                                    class="text-danger">*</span></label>
                            <input type="number"
                                class="form-control form-control-lg @error('quantity') is-invalid @enderror"
                                id="quantity" name="quantity" value="{{ old('quantity', 0) }}" min="0">
                            @error('quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="batch_number" class="form-label">Batch Number <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control form-control-lg @error('batch_number') is-invalid @enderror"
                                id="batch_number" name="batch_number" value="{{ old('batch_number') }}">
                            @error('batch_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="Sub_section_ID" class="form-label">Storage Location <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-select-lg @error('Sub_section_ID') is-invalid @enderror"
                                id="Sub_section_ID" name="Sub_section_ID">
                                <option value="">Select Location</option>
                                @foreach ($allWarehouses as $warehouse)
                                    <optgroup label="Warehouse: {{ $warehouse->Name }}">
                                        @forelse ($warehouse->sections as $section)
                                    <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Section: {{ $section->section_name }}">
                                        @forelse ($section->subsections as $subsection)
                                            <option value="{{ $subsection->Sub_Section_ID }}"
                                                {{ old('Sub_section_ID') == $subsection->Sub_Section_ID ? 'selected' : '' }}>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subsection:
                                                {{ $subsection->subsection_name }} (Cap: {{ $subsection->s_Capacity }} sq
                                                ft)
                                            </option>
                                        @empty
                                            <option disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No subsections
                                                available</option>
                                        @endforelse
                                    </optgroup>
                                @empty
                                    <option disabled>&nbsp;&nbsp;&nbsp;&nbsp;No sections available</option>
                                @endforelse
                                </optgroup>
                                @endforeach
                            </select>
                            @error('Sub_section_ID')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4 mt-2 mb-3">
                        <div class="col-md-6">
                            <label for="Manufactured_Date" class="form-label">Manufactured Date <span
                                    class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control form-control-lg @error('Manufactured_Date') is-invalid @enderror"
                                id="Manufactured_Date" name="Manufactured_Date" value="{{ old('Manufactured_Date') }}">
                            @error('Manufactured_Date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="Use_By_Date" class="form-label">Use By Date <span
                                    class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control form-control-lg @error('Use_By_Date') is-invalid @enderror"
                                id="Use_By_Date" name="Use_By_Date" value="{{ old('Use_By_Date') }}">
                            @error('Use_By_Date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </section>

                <hr class="my-5 border-primary border-3 opacity-75">

                {{-- Form Actions --}}
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-1"></i> Save Product
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">
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
            // Generate barcode functionality
            $('#generateBarcode').on('click', function() {
                // Add a simple loading indicator or disable button
                const $button = $(this);
                const originalHtml = $button.html();
                $button.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...'
                    ).prop('disabled', true);

                $.ajax({
                    url: '{{ route('products.generate-barcode') }}',
                    type: 'GET',
                    success: function(response) {
                        $('#barcode').val(response.barcode);
                    },
                    error: function(xhr, status, error) {
                        console.error("Barcode generation failed: ", error);
                        // Optionally show an error message to the user
                        // e.g., using a non-alert message box or a temporary text beside the input
                    },
                    complete: function() {
                        // Restore button state
                        $button.html(originalHtml).prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
