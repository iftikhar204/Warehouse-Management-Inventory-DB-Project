@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 text-primary"><i class="bi bi-box-fill me-2"></i> Product Management</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-1"></i> Add Product
    </a>
</div>

<div class="card fade-in">
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            @foreach ($products as $product)
                @php
                    $typeName = optional($product->types->first())->Type_Name;
                    $typeIcon = match ($typeName) {
                        'Electronics' => 'bi-laptop',
                        'Furniture' => 'bi-chair',
                        'Clothing' => 'bi-shirt',
                        'Food' => 'bi-basket',
                        'Books' => 'bi-book',
                        default => 'bi-box',
                    };
                @endphp
                <div class="col-md-4 mb-4 fade-in">
                    <div class="card shadow-sm h-100">
                        <div class="bg-light d-flex justify-content-center align-items-center" style="height: 200px;">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->Product_Name }}"
                                    class="img-fluid" style="max-height: 180px;"
                                    onerror="this.outerHTML = `<i class='bi {{ $typeIcon }} text-primary display-1'></i>`;">
                            @else
                                <i class="bi {{ $typeIcon }} text-primary display-1"></i>
                            @endif
                        </div>
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">{{ $product->Product_Name }}</h5>
                                <p class="text-muted mb-2">Barcode: <span class="fw-semibold">{{ $product->barcode }}</span></p>
                                <p class="mb-2">Stock: <strong>{{ $product->stocks->sum('Quantity') ?? 0 }}</strong></p>
                                <div class="mb-2">
                                    @forelse ($product->types as $type)
                                        <span class="badge bg-info text-dark">{{ $type->Type_Name }}</span>
                                    @empty
                                        <span class="text-muted">No Type</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="ms-3 d-flex flex-column align-items-end">
                                <button class="btn btn-sm btn-outline-info mb-1" data-bs-toggle="modal"
                                    data-bs-target="#viewProductModal{{ $product->Product_ID }}" title="View">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                <a href="{{ route('products.edit', $product->Product_ID) }}"
                                    class="btn btn-sm btn-outline-primary mb-1" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-product"
                                    data-id="{{ $product->Product_ID }}" title="Delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- View Modal -->
                    <div class="modal fade" id="viewProductModal{{ $product->Product_ID }}" tabindex="-1"
                        aria-labelledby="viewProductModalLabel{{ $product->Product_ID }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-eye-fill me-2"></i> Product Details
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-4 py-3">
                                    <div class="text-center mb-4">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->Product_Name }}" class="img-fluid rounded shadow"
                                                style="max-height: 250px;"
                                                onerror="this.outerHTML = `<i class='bi {{ $typeIcon }} text-primary display-1'></i>`;">
                                        @else
                                            <i class="bi {{ $typeIcon }} text-primary display-1"></i>
                                        @endif
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <p><strong>Product Name:</strong> {{ $product->Product_Name }}</p>
                                            <p><strong>Barcode:</strong> {{ $product->barcode ?? 'N/A' }}</p>
                                            <p><strong>Product Date:</strong> {{ $product->Product_Date ?? 'N/A' }}</p>
                                            <p><strong>Weight:</strong> {{ $product->weight ?? 'N/A' }} kg</p>
                                            <p><strong>Dimensions:</strong> {{ $product->dimensions ?? 'N/A' }}</p>
                                            <p><strong>Description:</strong> {{ $product->description ?? 'N/A' }}</p>
                                            <p><strong>Status:</strong> {{ $product->status ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Price:</strong>
                                                {{ $product->price ? 'PKR ' . number_format($product->price, 2) : 'N/A' }}
                                            </p>
                                            <p><strong>Types:</strong>
                                                @forelse ($product->types as $type)
                                                    <span class="badge bg-info text-dark">{{ $type->Type_Name }}</span>
                                                @empty
                                                    <span class="text-muted">No type assigned</span>
                                                @endforelse
                                            </p>
                                            @php $stock = $product->stocks->first(); @endphp
                                            @if ($stock)
                                                <p><strong>Quantity:</strong> {{ $stock->Quantity }}</p>
                                                <p><strong>Batch Number:</strong> {{ $stock->batch_number ?? 'N/A' }}</p>
                                                <p><strong>Manufactured Date:</strong> {{ $stock->Manufactured_Date ?? 'N/A' }}</p>
                                                <p><strong>Use By Date:</strong> {{ $stock->Use_By_Date ?? 'N/A' }}</p>
                                            @else
                                                <p class="text-muted"><em>No stock information available.</em></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($products->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-box display-3 mb-3"></i>
                <p class="fs-5">No products found.</p>
            </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon-fill me-2"></i> Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product?
            </div>
            <div class="modal-footer">
                <form id="deleteProductForm" method="POST">
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.delete-product').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const form = document.getElementById('deleteProductForm');
                form.setAttribute('action', `/products/${productId}`);
                const modal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
                modal.show();
            });
        });
    });
</script>
@endsection

@section('styles')
<style>
    .fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .btn-outline-primary:hover,
    .btn-outline-danger:hover,
    .btn-outline-info:hover {
        transform: scale(1.05);
        transition: 0.2s ease-in-out;
    }

    .modal-content {
        animation: fadeIn 0.4s ease-in-out;
        border-radius: 0.6rem;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 0.4rem;
        padding: 0.4em 0.6em;
    }

    .img-fluid {
        transition: transform 0.3s ease-in-out;
    }

    .img-fluid:hover {
        transform: scale(1.03);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
    }
</style>
@endsection
