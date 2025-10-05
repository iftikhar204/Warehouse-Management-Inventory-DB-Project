  <div class="modal fade" id="adjustInventoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('inventory.adjust') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Adjust Inventory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="adjustProduct" class="form-label">Product</label>
                            <select class="form-select" id="adjustProduct" name="product_id" required>
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->Product_ID }}" data-barcode="{{ $product->barcode }}">
                                        {{ $product->Product_Name }} ({{ $product->barcode }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="adjustLocation" class="form-label">Location</label>
                            <select class="form-select" id="adjustLocation" name="subsection_id" required>
                                <option value="">Select Location</option>
                                {{-- Loop through allWarehouses to build optgroups for Warehouse > Section > Subsection --}}
                                @foreach ($allWarehouses as $warehouse)
                                    <optgroup label="{{ $warehouse->Name }}">
                                        @forelse ($warehouse->sections as $section)
                                    <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;{{ $section->section_name }}">
                                        @forelse ($section->subsections as $subsection)
                                            <option value="{{ $subsection->Sub_Section_ID }}">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subsection->subsection_name }}
                                            </option>
                                        @empty
                                            <option disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No subsections
                                            </option>
                                        @endforelse
                                    </optgroup>
                                @empty
                                    <option disabled>&nbsp;&nbsp;&nbsp;&nbsp;No sections</option>
                                @endforelse
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="adjustType" class="form-label">Adjustment Type</label>
                            <select class="form-select" id="adjustType" name="adjustment_type" required>
                                <option value="add">Add Stock</option>
                                <option value="remove">Remove Stock</option>
                                <option value="set">Set Exact Quantity</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="adjustQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="adjustQuantity" name="quantity" min="1"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="adjustReason" class="form-label">Reason</label>
                            <select class="form-select" id="adjustReason" name="reason" required>
                                <option value="received">Received Goods</option>
                                <option value="shipped">Shipped Goods</option>
                                <option value="damaged">Damaged Goods</option>
                                <option value="lost">Lost Goods</option>
                                <option value="count">Stock Count Adjustment</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="adjustNotes" class="form-label">Notes</label>
                            <textarea class="form-control" id="adjustNotes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Adjustment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
