@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center border-bottom mb-4">
            <div>
                <h1 class="display-6 fw-semibold d-flex align-items-center">
                    <i class="bi bi-speedometer2 me-2 text-primary"></i>Warehouse Management Dashboard
                </h1>
                <p class="text-muted">Overview of warehouse operations and activities</p>
            </div>
            <div class="btn-toolbar">
                <div class="btn-group me-2">
                    <a href="https://wa.me/?text={{ urlencode(route('dashboard')) }}" target="_blank"
                        class="btn btn-outline-success btn-sm">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('dashboard')) }}"
                        target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>

                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('dashboard')) }}&text={{ urlencode('Check out this dashboard!') }}"
                        target="_blank" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-twitter-x"></i> Twitter
                    </a>
                </div>


                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-calendar-event"></i>
                        Filter: {{ ucwords(str_replace('_', ' ', $period)) }}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach (['today', 'this_week', 'this_month', 'this_year', 'all_time'] as $opt)
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard', ['period' => $opt]) }}">
                                    {{ ucwords(str_replace('_', ' ', $opt)) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
        {{-- Overview Cards --}}
        <div class="row g-4 mb-4">
            @php
                $cards = [
                    [
                        'title' => 'Total Warehouses',
                        'value' => $totalWarehouses,
                        'icon' => 'bi-building',
                        'class' => 'primary',
                    ],
                    [
                        'title' => 'Active Products',
                        'value' => $activeProducts,
                        'icon' => 'bi-box-seam',
                        'class' => 'success',
                    ],
                    ['title' => 'Pending Orders', 'value' => $pendingOrders, 'icon' => 'bi-cart', 'class' => 'warning'],
                    [
                        'title' => 'Low Stock Items',
                        'value' => $lowStockItems,
                        'icon' => 'bi-exclamation-triangle',
                        'class' => 'danger',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-md-6 col-xl-3">
                    <div class="card text-white bg-{{ $card['class'] }} shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 text-uppercase small">{{ $card['title'] }}</h6>
                                <h3 class="mb-0">{{ $card['value'] }}</h3>
                            </div>
                            <i class="bi {{ $card['icon'] }} fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Inventory Alerts --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-bell text-warning me-2"></i> Inventory Alerts</h5>
            </div>
            <div class="card-body">
                @if ($inventoryAlerts->isEmpty())
                    <p class="text-muted">No inventory alerts at the moment.</p>
                @else
                    <div class="list-group">
                        @foreach ($inventoryAlerts as $product)
                            @php
                                $firstStock = $product->stocks->first();
                                $locationString = 'Unknown Location';

                                if ($firstStock && $firstStock->subsection) {
                                    $sub = $firstStock->subsection;
                                    $sec = $sub->warehouseSections->first();
                                    $wh = $sec?->warehouses?->first();

                                    // Assign variables *outside* the interpolated string
                                    $whName = isset($wh->Name) ? $wh->Name : 'N/A';
                                    $secName = isset($sec->section_name) ? $sec->section_name : 'N/A';
                                    $subName = isset($sub->subsection_name) ? $sub->subsection_name : 'N/A';

                                    // Now safely interpolate
                                    $locationString = trim("{$whName} - {$secName} - {$subName}");
                                }
                            @endphp

                            <a href="{{ route('products.edit', $product->Product_ID) }}"
                                class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">{{ $product->Product_Name }}</h6>
                                    <span class="badge bg-danger">{{ $product->stock_quantity }} in stock</span>
                                </div>
                                <small class="text-muted">{{ $locationString }}</small>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-clock-history text-info me-2"></i> Recent Orders</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted">
                            <th>Order #</th>
                            <th>Distributor / Supplier</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td>{{ $order->Order_ID }}</td>
                                <td>{{ $order->related_party_name }}</td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'pending' => 'secondary',
                                            'processing' => 'warning',
                                            'shipped' => 'info',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusMap[$order->Status] ?? 'dark' }}">
                                        {{ ucfirst($order->Status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at ? $order->created_at->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('orders.show', $order->Order_ID) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No recent orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
