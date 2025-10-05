@extends('layouts.app')

@section('title', 'Order Management')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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
    .fade-stagger:nth-child(4) { animation-delay: 0.4s; }

    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
        transition: all 0.2s ease;
    }

    .btn-outline-info:hover,
    .btn-outline-danger:hover,
    .btn-outline-success:hover {
        transform: scale(1.05);
        transition: 0.2s;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom animate__animated animate__fadeInDown">
    <h1 class="h2 text-primary"><i class="bi bi-bag-check me-2"></i> Order Management</h1>
    <a href="{{ route('orders.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-1"></i> Create Order
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

        <!-- Filter Dropdown -->
        <div class="mb-3">
            <div class="btn-group">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-funnel"></i> Filter by Status
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => '']) }}">All</a></li>
                    <li><hr class="dropdown-divider"></li>
                    @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                        <li>
                            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => $status]) }}">
                                {{ ucfirst($status) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle text-center" id="ordersTable">
                <thead class="table-dark">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    @php
                        $incoming = $order->incomingOrderItems->sum('quantity_ordered');
                        $outgoing = $order->outgoingOrderItems->sum('quantity_shipped');
                        $itemsCount = $incoming + $outgoing;
                        $status = $order->Status;
                        $badgeClass = match($status) {
                            'pending' => 'bg-secondary',
                            'processing' => 'bg-warning text-dark',
                            'shipped' => 'bg-info text-dark',
                            'delivered' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            default => 'bg-light'
                        };
                        $priority = $order->priority;
                        $priorityClass = match($priority) {
                            'low' => 'bg-secondary',
                            'medium' => 'bg-primary',
                            'high' => 'bg-warning text-dark',
                            'urgent' => 'bg-danger',
                            default => 'bg-light'
                        };
                    @endphp
                    <tr class="fade-in-up fade-stagger">
                        <td>ORD-{{ str_pad($order->Order_ID, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $order->distributor->Distributor_Name ?? $order->supplier->Supplier_Name ?? 'N/A' }}</td>
                        <td>{{ $order->created_at?->format('Y-m-d') ?? 'N/A' }}</td>
                        <td>{{ $itemsCount }}</td>
                        <td>{{ $order->total_amount ?? 'N/A' }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span></td>
                        <td><span class="badge {{ $priorityClass }}">{{ ucfirst($priority) }}</span></td>
                        <td>
                            <a href="{{ route('orders.show', $order->Order_ID) }}" class="btn btn-sm btn-outline-info" title="View">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            @if ($order->Status === 'pending')
                                <form action="{{ route('orders.process', $order->Order_ID) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Process">
                                        <i class="bi bi-gear-fill"></i>
                                    </button>
                                </form>
                            @endif
                            @if (in_array($order->Status, ['pending', 'delivered', 'cancelled']))
                                <button class="btn btn-sm btn-outline-danger delete-order" data-id="{{ $order->Order_ID }}" data-order-number="ORD-{{ str_pad($order->Order_ID, 5, '0', STR_PAD_LEFT) }}" title="Delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon-fill me-2"></i> Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete order <strong id="orderToDeleteNumber"></strong>? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <form id="deleteOrderForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function () {
        $('#ordersTable').DataTable();

        $(document).on('click', '.delete-order', function () {
            const orderId = $(this).data('id');
            const orderNumber = $(this).data('order-number');
            $('#deleteOrderForm').attr('action', `/orders/${orderId}`);
            $('#orderToDeleteNumber').text(orderNumber);
            $('#deleteOrderModal').modal('show');
        });
    });
</script>
@endsection
