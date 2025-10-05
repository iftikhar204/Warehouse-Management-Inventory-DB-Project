{{-- resources/views/products/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 text-primary"><i class="bi bi-pencil-fill me-2"></i> Edit Product</h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('products.update', $product->Product_ID) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h5 class="mb-3 text-info"><i class="bi bi-box me-2"></i> Product Details</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="Product_Name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('Product_Name') is-invalid @enderror"
                            id="Product_Name" name="Product_Name" value="{{ old('Product_Name', $product->Product_Name) }}"
                            required>
                        @error('Product_Name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="barcode" class="form-label">Barcode <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode"
                                name="barcode" value="{{ old('barcode', $product->barcode) }}" required>
                            <button class="btn btn-outline-secondary" type="button"
                                id="generateBarcodeBtn">Generate</button>
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="Type_Name" class="form-label">Product Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('Type_Name') is-invalid @enderror" id="Type_Name" name="Type_Name"
                            required>
                            <option value="">Select a Type</option>
                            @foreach ($productTypes as $type)
                                <option value="{{ $type->Type_Name }}"
                                    {{ old('Type_Name', optional($product->types->first())->Type_Name) == $type->Type_Name ? 'selected' : '' }}>
                                    {{ $type->Type_Name }}
                                </option>
                            @endforeach
                        </select>
                        @error('Type_Name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="Product_Date" class="form-label">Product Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('Product_Date') is-invalid @enderror"
                            id="Product_Date" name="Product_Date" value="{{ old('Product_Date', $product->Product_Date) }}"
                            required>
                        @error('Product_Date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="weight" class="form-label">Weight (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror"
                            id="weight" name="weight" value="{{ old('weight', $product->weight) }}" required>
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="dimensions" class="form-label">Dimensions <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('dimensions') is-invalid @enderror" id="dimensions"
                            name="dimensions" value="{{ old('dimensions', $product->dimensions) }}"
                            placeholder="e.g., 10x5x2 cm" required>
                        @error('dimensions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                            <option value="out_of_stock"
                                {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock
                            </option>
                            <option value="discontinued"
                                {{ old('status', $product->status) == 'discontinued' ? 'selected' : '' }}>Discontinued
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                            id="price" name="price" value="{{ old('price', $product->price) }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="image" class="form-label">Product Image</label>
                        <input class="form-control @error('image') is-invalid @enderror" type="file" id="image"
                            name="image" onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($product->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Current Product Image"
                                    class="img-thumbnail" style="max-width: 150px;" id="image-preview">
                            </div>
                        @else
                            <div class="mt-2">
                                <img src="" alt="Image Preview" class="img-thumbnail d-none"
                                    style="max-width: 150px;" id="image-preview">
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3 text-info"><i class="bi bi-boxes me-2"></i> Stock Details (Multiple Locations)</h5>
                <div id="stock-records-container">
                    @forelse($product->stocks as $index => $stock)
                        @include('products._stock_form_partial', [
                            'stock' => $stock,
                            'index' => $index,
                            'allWarehouses' => $allWarehouses,
                        ])
                    @empty
                        {{-- If no stocks, add one empty stock form --}}
                        @include('products._stock_form_partial', [
                            'stock' => null,
                            'index' => 0,
                            'allWarehouses' => $allWarehouses,
                        ])
                    @endforelse
                </div>
                <button type="button" class="btn btn-success mt-3" id="add-stock-record">
                    <i class="bi bi-plus-circle me-1"></i> Add Another Stock Location
                </button>


                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-save me-2"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Image Preview Script
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('image-preview');
                output.src = reader.result;
                output.classList.remove('d-none');
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Barcode generation
            document.getElementById('generateBarcodeBtn').addEventListener('click', function() {
                fetch('{{ route('products.generateBarcode') }}')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('barcode').value = data.barcode;
                    })
                    .catch(error => console.error('Error generating barcode:', error));
            });

            // Dynamic Stock Fields
            // Use the current number of existing stock records as the starting index,
            // or 0 if there are none. This ensures correct indexing when adding new fields.
            const stockContainer = document.getElementById('stock-records-container');
            const addStockButton = document.getElementById('add-stock-record');

            addStockButton.addEventListener('click', function() {
                let newStockIndex = stockContainer.children.length; // Get the current number of stock forms

                const template = `
                    <div class="card card-body bg-light mb-3 stock-record" data-index="${newStockIndex}">
                        <div class="d-flex justify-content-end mb-2">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-stock-record">
                                <i class="bi bi-x-circle"></i> Remove
                            </button>
                        </div>
                        <input type="hidden" name="stocks[${newStockIndex}][Stock_ID]" value="">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="quantity_${newStockIndex}" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantity_${newStockIndex}" name="stocks[${newStockIndex}][Quantity]" value="" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label for="batch_number_${newStockIndex}" class="form-label">Batch Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="batch_number_${newStockIndex}" name="stocks[${newStockIndex}][batch_number]" value="" required>
                            </div>
                            <div class="col-md-4">
                                <label for="subsection_${newStockIndex}" class="form-label">Storage Location <span class="text-danger">*</span></label>
                                <select class="form-select subsection-select" id="subsection_${newStockIndex}" name="stocks[${newStockIndex}][Sub_section_ID]" required>
                                    <option value="">Select Subsection</option>
                                    @foreach ($allWarehouses as $warehouse)
                                        <optgroup label="{{ $warehouse->Name }} ({{ $warehouse->Location }})">
                                            @foreach ($warehouse->sections as $section)
                                                <optgroup label="&nbsp;&nbsp;Section: {{ $section->section_name }}">
                                                    @foreach ($section->subsections as $subsection)
                                                        <option value="{{ $subsection->Sub_Section_ID }}">
                                                            &nbsp;&nbsp;&nbsp;&nbsp;{{ $subsection->subsection_name }} (Cap: {{ $subsection->Sub_Capacity }} sq ft)
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="manufactured_date_${newStockIndex}" class="form-label">Manufactured Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="manufactured_date_${newStockIndex}" name="stocks[${newStockIndex}][Manufactured_Date]" value="" required>
                            </div>
                            <div class="col-md-6">
                                <label for="use_by_date_${newStockIndex}" class="form-label">Use By Date</label>
                                <input type="date" class="form-control" id="use_by_date_${newStockIndex}" name="stocks[${newStockIndex}][Use_By_Date]" value="">
                            </div>
                        </div>
                    </div>
                `;
                stockContainer.insertAdjacentHTML('beforeend', template);
                addRemoveEventListeners();
            });

            function addRemoveEventListeners() {
                document.querySelectorAll('.remove-stock-record').forEach(button => {
                    // Prevent adding multiple listeners to the same button
                    button.removeEventListener('click', handleRemoveStock);
                    button.addEventListener('click', handleRemoveStock);
                });
            }

            function handleRemoveStock(event) {
                if (confirm('Are you sure you want to remove this stock record?')) {
                    event.target.closest('.stock-record').remove();
                }
            }

            // Initial attachment of event listeners for existing stock records
            addRemoveEventListeners();
        });
    </script>
@endsection
