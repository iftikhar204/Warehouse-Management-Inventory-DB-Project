@extends('layouts.app')

@section('title', 'Warehouse Management')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .fade-in-up {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-stagger:nth-child(1) { animation-delay: 0.1s; }
    .fade-stagger:nth-child(2) { animation-delay: 0.2s; }
    .fade-stagger:nth-child(3) { animation-delay: 0.3s; }

    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
        transition: all 0.2s ease;
    }

    .btn-outline-info:hover,
    .btn-outline-danger:hover,
    .btn-outline-primary:hover {
        transform: scale(1.05);
        transition: 0.2s;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom animate__animated animate__fadeInDown">
    <h1 class="h2 text-primary"><i class="bi bi-box-seam me-2"></i> Warehouse Management</h1>
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-1"></i> Add Warehouse
    </a>
</div>

<div class="card shadow-sm fade-in-up">
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInUp" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInUp" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle text-center" id="warehouseTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Sections</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($warehouses as $warehouse)
                    @php
                        $status = $warehouse->operational_status;
                        $badgeClass = match($status) {
                            'active' => 'bg-success',
                            'maintenance' => 'bg-warning text-dark',
                            'inactive' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <tr class="fade-in-up fade-stagger">
                        <td>{{ $warehouse->warehouse_ID }}</td>
                        <td>{{ $warehouse->Name }}</td>
                        <td>{{ $warehouse->address->city ?? 'N/A' }}, {{ $warehouse->address->country ?? 'N/A' }}</td>
                        <td>{{ number_format($warehouse->Max_Capacity) }} sq ft</td>
                        <td><span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span></td>
                        <td>{{ $warehouse->sections->count() }}</td>
                        <td>
                            <a href="{{ route('warehouses.overview', $warehouse->warehouse_ID) }}" class="btn btn-sm btn-outline-info" title="View Overview">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="{{ route('warehouses.edit', $warehouse->warehouse_ID) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger delete-warehouse" data-id="{{ $warehouse->warehouse_ID }}" title="Delete">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteWarehouseModal" tabindex="-1" aria-labelledby="deleteWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon-fill me-2"></i> Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this warehouse?
            </div>
            <div class="modal-footer">
                <form id="deleteWarehouseForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#warehouseTable').DataTable();

        $(document).on('click', '.delete-warehouse', function () {
            const id = $(this).data('id');
            $('#deleteWarehouseForm').attr('action', `/warehouses/${id}`);
            $('#deleteWarehouseModal').modal('show');
        });
    });
</script>
@endsection
