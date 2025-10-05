@extends('layouts.app')

@section('title', 'Create New Order')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 text-primary"><i class="bi bi-cart-plus me-2"></i> Create New Order</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                {{-- Order Details Section --}}
                <h5 class="mb-3 text-info"><i class="bi bi-info-circle me-2"></i> Order Details</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="order_type" class="form-label">Order Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('order_type') is-invalid @enderror" id="order_type" name="order_type" required>
                            <option value="">Select Order Type</option>
                            <option value="incoming" {{ old('order_type') == 'incoming' ? 'selected' : '' }}>Incoming (From Supplier)</option>
                            <option value="outgoing" {{ old('order_type') == 'outgoing' ? 'selected' : '' }}>Outgoing (To Distributor)</option>
                        </select>
                        @error('order_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Supplier Information Section (Dynamic) --}}
                <div id="supplierSection" class="mb-4" style="display: {{ old('order_type') == 'incoming' ? 'block' : 'none' }};">
                    <h5 class="mt-4 mb-3 text-info"><i class="bi bi-person-badge me-2"></i> Supplier Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="Supplier_ID" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('Supplier_ID') is-invalid @enderror" id="Supplier_ID" name="Supplier_ID">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->Supplier_ID }}" {{ old('Supplier_ID') == $supplier->Supplier_ID ? 'selected' : '' }}>{{ $supplier->Supplier_Name }}</option>
                                @endforeach
                            </select>
                            @error('Supplier_ID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="expected_delivery" class="form-label">Expected Delivery Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('expected_delivery') is-invalid @enderror" id="expected_delivery" name="expected_delivery" value="{{ old('expected_delivery') }}">
                            @error('expected_delivery')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Distributor Information Section (Dynamic) --}}
                <div id="distributorSection" class="mb-4" style="display: {{ old('order_type') == 'outgoing' ? 'block' : 'none' }};">
                    <h5 class="mt-4 mb-3 text-info"><i class="bi bi-truck me-2"></i> Distributor Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="Distributor_ID" class="form-label">Distributor <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('Distributor_ID') is-invalid @enderror" id="Distributor_ID" name="Distributor_ID">
                                <option value="">Select Distributor</option>
                                @foreach ($distributors as $distributor)
                                    <option value="{{ $distributor->Distributor_ID }}" {{ old('Distributor_ID') == $distributor->Distributor_ID ? 'selected' : '' }}>{{ $distributor->Distributor_Name }}</option>
                                @endforeach
                            </select>
                            @error('Distributor_ID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="Shipping_Date" class="form-label">Shipping Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('Shipping_Date') is-invalid @enderror" id="Shipping_Date" name="Shipping_Date" value="{{ old('Shipping_Date') }}">
                            @error('Shipping_Date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Shipping Address Section --}}
                <h5 class="mt-4 mb-3 text-info"><i class="bi bi-geo-alt me-2"></i> Shipping Address</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="street_address" class="form-label">Street Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('street_address') is-invalid @enderror" id="street_address" name="street_address" value="{{ old('street_address') }}" required>
                        @error('street_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="state_province" class="form-label">State/Province</label>
                        <input type="text" class="form-control @error('state_province') is-invalid @enderror" id="state_province" name="state_province" value="{{ old('state_province') }}">
                        @error('state_province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}" required>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Order Items Section --}}
                <h5 class="mt-4 mb-3 text-info"><i class="bi bi-box-seam me-2"></i> Order Items</h5>

                <div id="orderItems">
                    <div class="row g-3 mb-3 item-row border rounded p-3 bg-light"> {{-- Added border, padding, and background --}}
                        <div class="col-md-5">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <select class="form-select product-select @error('items.0.Product_ID') is-invalid @enderror" name="items[0][Product_ID]" required>
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->Product_ID }}"
                                        data-stock="{{ optional($product->stocks)->sum('Quantity') ?? 0 }}"
                                        data-price="{{ $product->price ?? '' }}" {{-- Assuming product has a price --}}
                                        {{ old('items.0.Product_ID') == $product->Product_ID ? 'selected' : '' }}>
                                        {{ $product->Product_Name }} ({{ $product->barcode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('items.0.Product_ID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted product-stock-info">Current Stock: N/A</small>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control quantity @error('items.0.quantity') is-invalid @enderror" name="items[0][quantity]" min="1" value="{{ old('items.0.quantity', 1) }}" required>
                            @error('items.0.quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="text-danger stock-warning" style="display: none;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Insufficient stock!</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control price @error('items.0.unit_price') is-invalid @enderror" name="items[0][unit_price]" value="{{ old('items.0.unit_price') }}" required>
                            </div>
                            @error('items.0.unit_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 d-flex align-items-end justify-content-end">
                            <button type="button" class="btn btn-outline-danger remove-item" title="Remove Item">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" id="addItem" class="btn btn-sm btn-outline-success mt-3 mb-4 shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add Another Item
                </button>

                <hr class="my-4">

                <div class="mb-3">
                    <label for="notes" class="form-label">Order Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg shadow">
                        <i class="bi bi-check-circle me-2"></i> Create Order
                    </button>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-lg shadow">
                        <i class="bi bi-x-circle me-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Order Summary Card --}}
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-calculator me-2"></i> Order Summary</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="orderSummary">
                        {{-- Summary items will be appended here by JavaScript --}}
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="3">Subtotal</th>
                            <th id="subtotal">$0.00</th>
                        </tr>
                        <tr>
                            <th colspan="3">Shipping</th>
                            <th id="shipping">$0.00</th>
                        </tr>
                        <tr class="table-primary fs-5">
                            <th colspan="3">Grand Total</th>
                            <th id="total">$0.00</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 on existing elements
            $('.select2').select2({
                theme: "bootstrap-5", // Use Bootstrap 5 theme if you included it
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder') || 'Select an option',
                allowClear: true, // Allows clearing the selection
            });

            // Function to initialize Select2 for new elements
            function initializeSelect2ForNewElements(element) {
                element.select2({
                    theme: "bootstrap-5",
                    width: '100%', // New elements usually benefit from full width
                    placeholder: $(element).data('placeholder') || 'Select an option',
                    allowClear: true,
                });
            }

            // Function to update the required attributes based on order type
            function updateRequiredFields() {
                const type = $('#order_type').val();

                $('#Supplier_ID').prop('required', type === 'incoming');
                $('#expected_delivery').prop('required', type === 'incoming');
                $('#Distributor_ID').prop('required', type === 'outgoing');
                $('#Shipping_Date').prop('required', type === 'outgoing');

                // Toggle visibility with fade effect
                if (type === 'incoming') {
                    $('#supplierSection').fadeIn();
                    $('#distributorSection').fadeOut();
                } else if (type === 'outgoing') {
                    $('#distributorSection').fadeIn();
                    $('#supplierSection').fadeOut();
                } else {
                    $('#supplierSection').fadeOut();
                    $('#distributorSection').fadeOut();
                }
            }

            // Initial call on page load
            updateRequiredFields();

            $('#order_type').change(updateRequiredFields);

            let itemCount = $('.item-row').length; // Initialize itemCount based on existing rows

            $('#addItem').click(function() {
                const productsOptions = $('#orderItems .product-select:first').html(); // Get options from the first select
                const newRow = `
                <div class="row g-3 mb-3 item-row border rounded p-3 bg-light">
                    <div class="col-md-5">
                        <label class="form-label">Product <span class="text-danger">*</span></label>
                        <select class="form-select product-select" name="items[${itemCount}][Product_ID]" required>
                            ${productsOptions}
                        </select>
                        <small class="text-muted product-stock-info">Current Stock: N/A</small>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control quantity" name="items[${itemCount}][quantity]" min="1" value="1" required>
                        <div class="text-danger stock-warning" style="display: none;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Insufficient stock!</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control price" name="items[${itemCount}][unit_price]" required>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end justify-content-end">
                        <button type="button" class="btn btn-outline-danger remove-item" title="Remove Item">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                </div>`;
                $('#orderItems').append(newRow);

                // Initialize Select2 for the newly added product select
                initializeSelect2ForNewElements($(`#orderItems .item-row:last .product-select`));

                itemCount++;
                updateOrderSummary(); // Recalculate summary after adding a new row
            });

            // Handle removing an item
            $(document).on('click', '.remove-item', function() {
                if (confirm('Are you sure you want to remove this item?')) {
                    $(this).closest('.item-row').remove();
                    // Re-index names to prevent gaps in array indexes
                    $('#orderItems .item-row').each(function(index) {
                        $(this).find('.product-select').attr('name', `items[${index}][Product_ID]`);
                        $(this).find('.quantity').attr('name', `items[${index}][quantity]`);
                        $(this).find('.price').attr('name', `items[${index}][unit_price]`);
                    });
                    itemCount = $('.item-row').length; // Update itemCount after re-indexing
                    updateOrderSummary();
                    checkStockAvailability(); // Re-check stock after removal
                }
            });

            // Event listener for product selection, quantity, and price changes
            $(document).on('keyup change', '.product-select, .quantity, .price', function() {
                updateOrderSummary();
                checkStockAvailability();
            });

            // Update product details (stock info and price) when a product is selected
            $(document).on('change', '.product-select', function() {
                const selectedOption = $(this).find('option:selected');
                const availableStock = parseFloat(selectedOption.data('stock')) || 0;
                const unitPrice = parseFloat(selectedOption.data('price')) || 0; // Get price from data attribute

                const itemRow = $(this).closest('.item-row');
                itemRow.find('.product-stock-info').text(`Current Stock: ${availableStock}`);
                itemRow.find('.price').val(unitPrice.toFixed(2)); // Autofill price

                updateOrderSummary();
                checkStockAvailability(); // Check stock when product changes
            });


            // Function to check stock availability for outgoing orders
            function checkStockAvailability() {
                const orderType = $('#order_type').val();
                let canSubmit = true; // Flag to control form submission

                $('.item-row').each(function() {
                    const selectedProductOption = $(this).find('.product-select option:selected');
                    const availableStock = parseFloat(selectedProductOption.data('stock')) || 0;
                    const requestedQuantity = parseFloat($(this).find('.quantity').val()) || 0;
                    const stockWarning = $(this).find('.stock-warning');
                    const quantityInput = $(this).find('.quantity');

                    if (orderType === 'outgoing') {
                        if (requestedQuantity > availableStock) {
                            stockWarning.fadeIn(); // Show warning with fade effect
                            quantityInput.addClass('is-invalid');
                            canSubmit = false;
                        } else {
                            stockWarning.fadeOut(); // Hide warning with fade effect
                            quantityInput.removeClass('is-invalid');
                        }
                    } else {
                        // If not outgoing, hide any warnings and remove invalid state
                        stockWarning.hide();
                        quantityInput.removeClass('is-invalid');
                    }
                });

                // Control form submission based on stock availability
                $('button[type="submit"]').prop('disabled', !canSubmit && orderType === 'outgoing');
            }


            // Function to update the order summary table
            function updateOrderSummary() {
                let subtotal = 0;
                $('#orderSummary').empty();

                $('.item-row').each(function() {
                    const productName = $(this).find('.product-select option:selected').text().split(' (')[0];
                    const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    const price = parseFloat($(this).find('.price').val()) || 0;
                    const total = quantity * price;
                    subtotal += total;

                    if (productName && quantity > 0 && price >= 0) { // Only add valid items to summary
                        $('#orderSummary').append(`
                            <tr>
                                <td>${productName}</td>
                                <td>${quantity}</td>
                                <td>$${price.toFixed(2)}</td>
                                <td>$${total.toFixed(2)}</td>
                            </tr>
                        `);
                    }
                });

                $('#subtotal').text('$' + subtotal.toFixed(2));
                $('#shipping').text('$0.00'); // Keep shipping fixed for now, adjust as needed
                $('#total').text('$' + subtotal.toFixed(2));
            }

            // Initial trigger for all relevant functions
            updateOrderSummary();
            checkStockAvailability();
            // Trigger product select change for existing items to show initial stock and price
            $('.product-select').trigger('change');
        });
    </script>
@endsection
