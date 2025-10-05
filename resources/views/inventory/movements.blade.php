@extends('layouts.app')

@section('title', 'Stock Movements')

@section('content')
    <div class="container-fluid px-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h1 class="h3 fw-bold text-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i> Stock Movements
            </h1>
            <a href="{{ route('inventory.index') }}" class="btn btn-outline-primary btn-sm shadow-sm">
                <i class="bi bi-box-arrow-left me-1"></i> Back to Inventory
            </a>
        </div>

        <!-- Table Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-semibold">
                <i class="bi bi-shuffle me-2 text-secondary"></i> Recent Stock Movements
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle" id="movementsTable">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Location</th>
                                <th>Change</th>
                                <th>Old Qty</th>
                                <th>New Qty</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Notes</th>
                                <th>Date</th>
                                <th>By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movements as $movement)
                                <tr>
                                    <td>{{ $movement->movement_ID }}</td>
                                    <td>{{ $movement->stock->product->Product_Name ?? 'N/A' }}</td>
                                    <td>
                                        {{ $movement->stock?->subsection?->warehouseSections?->first()?->section_name ?? 'N/A' }}
                                        -
                                        {{ $movement->stock?->subsection?->subsection_name ?? 'N/A' }}
                                        ({{ $movement->stock?->subsection?->warehouseSections?->first()?->warehouses?->first()?->Name ?? 'N/A' }})
                                    </td>
                                    <td class="text-center fw-semibold">
                                        {{ $movement->change_quantity }}
                                    </td>
                                    <td>{{ $movement->old_quantity }}</td>
                                    <td>{{ $movement->new_quantity }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $movement->movement_type === 'in' ? 'success' : 'danger' }}">
                                            {{ ucfirst($movement->movement_type) }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst($movement->reason) }}</td>
                                    <td>{{ $movement->notes ?? 'N/A' }}</td>
                                    <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $movement->employee->F_Name ?? 'N/A' }} {{ $movement->employee->L_Name ?? '' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted">No stock movements found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            const rowCount = $('#movementsTable tbody tr').length;
            const noDataRow = $('#movementsTable tbody tr td[colspan]').length > 0;

            if (rowCount > 0 && !noDataRow) {
                $('#movementsTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    order: [
                        [0, 'desc']
                    ],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        paginate: {
                            previous: "Previous",
                            next: "Next"
                        },
                        emptyTable: "No data available"
                    }
                });
            }
        });
    </script>
@endsection
