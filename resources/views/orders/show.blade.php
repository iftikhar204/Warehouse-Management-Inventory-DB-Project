@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="fade-in container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h1 class="h3 text-primary fw-bold text-shadow-sm">
            <i class="bi bi-file-earmark-text me-2"></i> Order <span class="text-secondary">#ORD-{{ str_pad($order->Order_ID, 5, '0', STR_PAD_LEFT) }}</span>
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary shadow-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>

            @if($order->Status == 'pending')
                <form action="{{ route('orders.process', $order->Order_ID) }}" method="POST">@csrf
                    <button class="btn btn-success shadow-sm"><i class="bi bi-gear me-1"></i> Process</button>
                </form>
            @elseif($order->Status == 'processing')
                <form action="{{ route('orders.ship', $order->Order_ID) }}" method="POST">@csrf
                    <button class="btn btn-info shadow-sm"><i class="bi bi-truck me-1"></i> Ship</button>
                </form>
            @elseif($order->Status == 'shipped')
                <form action="{{ route('orders.deliver', $order->Order_ID) }}" method="POST">@csrf
                    <button class="btn btn-success shadow-sm"><i class="bi bi-check-circle me-1"></i> Deliver</button>
                </form>
            @endif

            @if($order->Status == 'pending')
                <form action="{{ route('orders.cancel', $order->Order_ID) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">@csrf
                    <button class="btn btn-danger shadow-sm"><i class="bi bi-x-circle me-1"></i> Cancel</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Order Information --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 fade-in">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> Order Info</h5>
                </div>
                <div class="card-body">
                    <p><strong>Date:</strong> {{ $order->created_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge {{ match($order->Status) {
                            'pending' => 'bg-secondary',
                            'processing' => 'bg-warning text-dark',
                            'shipped' => 'bg-info text-dark',
                            'delivered' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            default => 'bg-light'
                        } }}">
                            <i class="bi me-1 {{ match($order->Status) {
                                'pending' => 'bi-clock',
                                'processing' => 'bi-hourglass',
                                'shipped' => 'bi-truck',
                                'delivered' => 'bi-check-circle',
                                'cancelled' => 'bi-x-circle',
                                default => 'bi-circle'
                            } }}"></i>
                            {{ ucfirst($order->Status) }}
                        </span>
                    </p>
                    <p><strong>Priority:</strong> <span class="badge bg-{{ $order->priority === 'urgent' ? 'danger' : ($order->priority === 'high' ? 'warning text-dark' : ($order->priority === 'medium' ? 'primary' : 'secondary')) }}">{{ ucfirst($order->priority) }}</span></p>
                    <p><strong>Shipping Date:</strong> {{ $order->Shipping_Date?->format('M d, Y') ?? 'N/A' }}</p>
                    <p><strong>Notes:</strong> {{ $order->notes ?? 'None' }}</p>
                </div>
            </div>
        </div>

        {{-- Supplier/Distributor --}}
        <div class="col-md-6 fade-in">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i> Party Information</h5>
                </div>
                <div class="card-body">
                    @if($order->supplier)
                        <p><strong>Supplier:</strong> {{ $order->supplier->Supplier_Name }}</p>
                        <p><strong>Reliability:</strong> {{ $order->supplier->reliability_rating }}/5</p>
                        <p><strong>Lead Time:</strong> {{ $order->supplier->lead_time_days }} days</p>
                    @elseif($order->distributor)
                        <p><strong>Distributor:</strong> {{ $order->distributor->Distributor_Name }}</p>
                        <p><strong>Location:</strong> {{ $order->distributor->Distributing_Location }}</p>
                        <p><strong>Account Manager:</strong> {{ $order->distributor->account_manager }}</p>
                    @endif
                    <p class="mt-3"><strong>Shipping Address:</strong><br>
                        {{ $order->shippingAddress?->street_address }}<br>
                        {{ $order->shippingAddress?->city }}, {{ $order->shippingAddress?->state_province }}<br>
                        {{ $order->shippingAddress?->postal_code }}, {{ $order->shippingAddress?->country }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="card mb-4 shadow-sm fade-in border-0">
        <div class="card-header bg-info text-white d-flex justify-content-between">
            <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i> Items</h5>
            <span class="badge bg-light text-dark">{{ $items->count() }} items</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $item->product->Product_Name }}</strong><br>
                                <small class="text-muted">SKU: {{ $item->product->barcode }}</small>
                            </td>
                            <td class="text-center">{{ $item->quantity_ordered ?? $item->quantity_shipped }}</td>
                            <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-end fw-bold">${{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">No items found.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-semibold">Subtotal:</td>
                            <td class="text-end">${{ number_format($order->total_amount ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Shipping:</td>
                            <td class="text-end">${{ number_format($order->shipping_cost ?? 0, 2) }}</td>
                        </tr>
                        <tr class="table-primary fw-bold">
                            <td colspan="3" class="text-end">Grand Total:</td>
                            <td class="text-end">${{ number_format(($order->total_amount ?? 0) + ($order->shipping_cost ?? 0), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Timeline Tracking --}}
    <div class="card shadow-sm fade-in border-0">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> Tracking</h5>
        </div>
        <div class="card-body">
            @forelse($order->tracking->sortBy('Timestamp') as $track)
            <div class="timeline-item mb-4 fade-in">
                <div class="border-start ps-3">
                    <h6 class="mb-1">{{ ucfirst(str_replace('_', ' ', $track->status_change)) }}</h6>
                    <small class="text-muted">{{ $track->Timestamp?->format('M d, Y H:i') ?? 'N/A' }}</small>
                    <p class="mb-1">{{ $track->Tracking_Comments ?? 'No comments.' }}</p>
                    @if($track->employee)
                        <small class="text-primary">Handled by: {{ $track->employee->F_Name }} {{ $track->employee->L_Name }}</small>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-muted text-center">No tracking available.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .fade-in {
        animation: fadeInUp 0.5s ease-in-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .text-shadow-sm {
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    .timeline-item {
        position: relative;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -10px;
        top: 5px;
        width: 8px;
        height: 8px;
        background: #0d6efd;
        border-radius: 50%;
    }
</style>
@endsection
