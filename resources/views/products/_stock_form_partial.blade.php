{{-- resources/views/products/_stock_form_partial.blade.php --}}

<div class="card card-body bg-light mb-3 stock-record" data-index="{{ $index }}">
    <div class="d-flex justify-content-end mb-2">
        @if ($stock)
            <button type="button" class="btn btn-sm btn-outline-danger remove-stock-record">
                <i class="bi bi-x-circle"></i> Remove This Stock
            </button>
        @endif
    </div>
    <input type="hidden" name="stocks[{{ $index }}][Stock_ID]" value="{{ $stock->Stock_ID ?? '' }}">
    <div class="row g-3">
        <div class="col-md-4">
            <label for="quantity_{{ $index }}" class="form-label">Quantity <span
                    class="text-danger">*</span></label>
            <input type="number" class="form-control @error('stocks.' . $index . '.Quantity') is-invalid @enderror"
                id="quantity_{{ $index }}" name="stocks[{{ $index }}][Quantity]"
                value="{{ old('stocks.' . $index . '.Quantity', $stock->Quantity ?? '') }}" required min="0">
            @error('stocks.' . $index . '.Quantity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="batch_number_{{ $index }}" class="form-label">Batch Number <span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control @error('stocks.' . $index . '.batch_number') is-invalid @enderror"
                id="batch_number_{{ $index }}" name="stocks[{{ $index }}][batch_number]"
                value="{{ old('stocks.' . $index . '.batch_number', $stock->batch_number ?? '') }}" required>
            @error('stocks.' . $index . '.batch_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="subsection_{{ $index }}" class="form-label">Storage Location <span
                    class="text-danger">*</span></label>
            <select class="form-select @error('stocks.' . $index . '.Sub_section_ID') is-invalid @enderror"
                id="subsection_{{ $index }}" name="stocks[{{ $index }}][Sub_section_ID]" required>
                <option value="">Select Subsection</option>
                @foreach ($allWarehouses as $warehouse)
                    <optgroup label="{{ $warehouse->Name }} ({{ $warehouse->Location }})">
                        @foreach ($warehouse->sections as $section)
                            {{-- THIS IS THE LINE TO CHECK: Ensure you're accessing $section->Section_Name --}}
                    <optgroup label="&nbsp;&nbsp;Section: {{ $section->section_name }}">
                        @foreach ($section->subsections as $subsection)
                            <option value="{{ $subsection->Sub_Section_ID }}"
                                {{ old('stocks.' . $index . '.Sub_section_ID', $stock->Sub_Section_ID ?? '') == $subsection->Sub_Section_ID ? 'selected' : '' }}>
                                &nbsp;&nbsp;&nbsp;&nbsp;{{ $subsection->subsection_name }} (Cap:
                                {{ $subsection->Sub_Capacity }} sq ft) </option>
                        @endforeach
                    </optgroup>
                @endforeach
                </optgroup>
                @endforeach
            </select>
            @error('stocks.' . $index . '.Sub_section_ID')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="manufactured_date_{{ $index }}" class="form-label">Manufactured Date <span
                    class="text-danger">*</span></label>
            <input type="date"
                class="form-control @error('stocks.' . $index . '.Manufactured_Date') is-invalid @enderror"
                id="manufactured_date_{{ $index }}" name="stocks[{{ $index }}][Manufactured_Date]"
                value="{{ old('stocks.' . $index . '.Manufactured_Date', $stock->Manufactured_Date ?? '') }}" required>
            @error('stocks.' . $index . '.Manufactured_Date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="use_by_date_{{ $index }}" class="form-label">Use By Date</label>
            <input type="date" class="form-control @error('stocks.' . $index . '.Use_By_Date') is-invalid @enderror"
                id="use_by_date_{{ $index }}" name="stocks[{{ $index }}][Use_By_Date]"
                value="{{ old('stocks.' . $index . '.Use_By_Date', $stock->Use_By_Date ?? '') }}">
            @error('stocks.' . $index . '.Use_By_Date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
