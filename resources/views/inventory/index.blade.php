@extends('layouts.app')

@section('title', 'Inventory Management')

@section('styles')
    <style>
        .inventory-header {
            top: 0;
            z-index: 1040;
            background-color: #ffffff;
            padding: 1rem 0;
            margin-bottom: 1.5rem;
        }

        .dropdown-menu {
            z-index: 1100;
        }

        .card-hover {
            transition: all 0.3s ease-in-out;
            border: 1px solid #e9ecef;
            border-radius: 1rem;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.07);
        }

        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .badge-custom {
            font-size: 0.75rem;
            padding: 0.4em 0.6em;
            border-radius: 0.4rem;
        }

        .progress {
            height: 15px;
            border-radius: 0.3rem;
        }
        .progress-bar {
    font-size: 0.75rem;
    font-weight: 500;
    color: #fff;
    text-align: center;
}

    </style>
@endsection

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="inventory-header d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h4 text-primary m-0 d-flex align-items-center">
                <i class="bi bi-box-seam me-2"></i> Inventory Overview
            </h2>

            <div class="d-flex gap-2">
                <!-- Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item" href="{{ request()->url() }}">All Warehouses</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @foreach ($warehouses as $warehouse)
                            <li>
                                <a class="dropdown-item {{ request('warehouse') == $warehouse->warehouse_ID ? 'active fw-bold' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['warehouse' => $warehouse->warehouse_ID]) }}">
                                    {{ $warehouse->Name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Movement and Adjust Buttons -->
                <a href="{{ route('inventory.movements') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left-right me-1"></i> Movements
                </a>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adjustInventoryModal">
                    <i class="bi bi-plus-slash-minus me-1"></i> Adjust
                </button>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Inventory Cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
            @forelse ($inventory as $item)
                @php
                    $product = $item->product;
                    $minStock = $product->min_stock_level ?? 0;
                    $maxStock = $product->max_stock_level ?? 100;
                    $stockPercentage = $maxStock > 0 ? min(100, round(($item->Quantity / $maxStock) * 100)) : 0;
                    $isLow = $item->Quantity <= $minStock;
                    $isExpired = $item->Use_By_Date && \Carbon\Carbon::parse($item->Use_By_Date)->isPast();
                    $isExpiring =
                        $item->Use_By_Date &&
                        \Carbon\Carbon::parse($item->Use_By_Date)->diffInDays(now(), false) >= -30;
                    $warehouse = $item->subsection?->warehouseSections?->first()?->warehouses?->first()?->Name ?? 'N/A';
                    $section = $item->subsection?->warehouseSections?->first()?->section_name ?? 'N/A';
                    $subsection = $item->subsection?->subsection_name ?? 'N/A';
                @endphp

                <div class="col fade-in-up">
                    <div class="card card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 text-truncate">
                                    <a href="{{ route('products.edit', $product->Product_ID) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $product->Product_Name }}
                                    </a>
                                </h5>
                                @if ($isLow)
                                    <span class="badge bg-warning text-dark badge-custom" title="Low Stock">Low</span>
                                @endif
                            </div>

                            <p class="text-muted small mb-2">
                                <i class="bi bi-upc-scan me-1"></i>{{ $product->barcode }}
                            </p>

                            <div class="mb-2">
                                <i class="bi bi-tags text-muted me-1"></i>
                                @forelse($product->types as $type)
                                    <span class="badge bg-light text-dark">{{ $type->Type_Name }}</span>
                                @empty
                                    <span class="text-muted">No Type</span>
                                @endforelse
                            </div>

                            <p class="mb-1"><i class="bi bi-building me-1 text-muted"></i><strong>Warehouse:</strong>
                                {{ $warehouse }}</p>
                            <p class="mb-1"><i class="bi bi-geo me-1 text-muted"></i><strong>Location:</strong>
                                {{ $section }} > {{ $subsection }}</p>
                            <p class="mb-1"><i class="bi bi-boxes me-1 text-muted"></i><strong>Quantity:</strong>
                                {{ $item->Quantity }}</p>
                            <p class="mb-1"><i class="bi bi-hash me-1 text-muted"></i><strong>Batch:</strong>
                                {{ $item->batch_number ?? 'N/A' }}</p>

                            <p class="mb-2"><i class="bi bi-calendar-check me-1 text-muted"></i><strong>Expiry:</strong>
                                @if ($item->Use_By_Date)
                                    {{ \Carbon\Carbon::parse($item->Use_By_Date)->format('Y-m-d') }}
                                    @if ($isExpired)
                                        <span class="badge bg-danger ms-2">Expired</span>
                                    @elseif($isExpiring)
                                        <span class="badge bg-warning text-dark ms-2">Expiring Soon</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </p>

                            <div class="progress mt-2" title="{{ $item->Quantity }} / {{ $maxStock }}">
                                <div class="progress-bar
                                    {{ $stockPercentage < 30 ? 'bg-danger' : ($stockPercentage < 70 ? 'bg-warning' : 'bg-success') }}"
                                    style="width: {{ $stockPercentage }}%;" role="progressbar"
                                    aria-valuenow="{{ $stockPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $stockPercentage }}%
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center shadow-sm">
                        <i class="bi bi-info-circle me-1"></i> No inventory data found.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @include('inventory.partials.adjust-modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el);
            });
        });
    </script>
@endsection
