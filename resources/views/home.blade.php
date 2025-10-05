@extends('layouts.app')

@section('title', 'WMS Dashboard')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    .card-hover-effect {
        transition: all 0.3s ease-in-out;
    }

    .card-hover-effect:hover {
        transform: translateY(-6px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
    }

    .icon-bounce:hover {
        animation: bounce 0.6s;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s ease-out forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up:nth-child(1) { animation-delay: 0.1s; }
    .fade-in-up:nth-child(2) { animation-delay: 0.2s; }
    .fade-in-up:nth-child(3) { animation-delay: 0.3s; }
    .fade-in-up:nth-child(4) { animation-delay: 0.4s; }

    .quick-link-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .quick-link-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.75rem 1.25rem rgba(0, 0, 0, 0.1);
    }

    .welcome-gradient {
        background: linear-gradient(to right, #2563eb, #4f46e5);
    }

    .icon-circle {
        width: 3.25rem;
        height: 3.25rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .text-indigo-500 { color: #6366f1; }
    .text-green-500 { color: #22c55e; }
    .text-yellow-500 { color: #eab308; }
    .text-red-500 { color: #ef4444; }
</style>
@endsection

@section('content')
<div class="container py-5">
    {{-- Welcome Section --}}
    <div class="welcome-gradient text-white p-5 rounded-4 shadow-lg mb-5 fade-in-up">
        <h1 class="display-5 fw-bold d-flex align-items-center">
            <i class="bi bi-speedometer2 me-3 fs-1 icon-bounce"></i>
            Welcome to Our WMS Dashboard
        </h1>
        <p class="lead">Gain real-time insights and efficiently manage your warehouse operations.</p>
        <a href="{{ route('warehouses.create') }}" class="btn btn-light btn-lg rounded-pill shadow-sm mt-3 animate__animated animate__pulse">
            <i class="bi bi-plus-circle-fill me-2"></i> Add New Warehouse
        </a>
    </div>

    {{-- Flash Messages --}}
    @foreach (['success' => 'check-circle-fill', 'error' => 'exclamation-triangle-fill'] as $type => $icon)
        @if (session($type))
        <div class="alert alert-{{ $type == 'success' ? 'success' : 'danger' }} alert-dismissible fade show fade-in-up" role="alert">
            <i class="bi bi-{{ $icon }} me-2"></i> {{ session($type) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    @endforeach

    {{-- KPIs --}}
    <h2 class="h4 fw-bold mb-4 fade-in-up"><i class="bi bi-bar-chart-line-fill me-2"></i>At a Glance</h2>
    <div class="row g-4 mb-5">
        @php
            $cards = [
                ['icon' => 'box-seam', 'color' => 'indigo', 'label' => 'Total Warehouses', 'value' => $totalWarehouses ?? 0, 'desc' => 'Active facilities'],
                ['icon' => 'columns-gap', 'color' => 'green', 'label' => 'Available Capacity', 'value' => number_format($availableCapacity ?? 0) . ' sq ft', 'desc' => 'Ready for inventory'],
                ['icon' => 'truck', 'color' => 'yellow', 'label' => 'Pending Shipments', 'value' => $pendingOrders ?? 0, 'desc' => 'Awaiting dispatch'],
                ['icon' => 'exclamation-triangle', 'color' => 'red', 'label' => 'Low Stock Items', 'value' => $lowStockItems ?? 0, 'desc' => 'Needs replenishment']
            ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-12 col-md-6 col-lg-3 fade-in-up">
            <div class="card shadow-sm card-hover-effect rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="icon-circle bg-{{ $card['color'] }}-100">
                            <i class="bi bi-{{ $card['icon'] }} text-{{ $card['color'] }}-500 fs-4 icon-bounce"></i>
                        </div>
                        <span class="text-muted">{{ $card['label'] }}</span>
                    </div>
                    <h3 class="fw-bold">{{ $card['value'] }}</h3>
                    <p class="text-muted small">{{ $card['desc'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Quick Links --}}
    <h2 class="h4 fw-bold mb-4 fade-in-up"><i class="bi bi-grid-fill me-2"></i>Quick Actions</h2>
    <div class="row g-4">
        @php
            $actions = [
                ['title' => 'Manage Warehouses', 'icon' => 'building', 'route' => route('warehouses.index'), 'desc' => 'Add, edit, or view all your warehouse facilities.'],
                ['title' => 'Manage Inventory', 'icon' => 'boxes', 'route' => route('inventory.index'), 'desc' => 'Track stock levels, product locations, and movements.'],
                ['title' => 'Handle Shipments', 'icon' => 'truck', 'route' => route('orders.index'), 'desc' => 'Create, track, and manage incoming and outgoing shipments.'],
                ['title' => 'User Management', 'icon' => 'people-fill', 'route' => route('profile.edit'), 'desc' => 'Manage user roles, permissions, and access.'],
                ['title' => 'Settings', 'icon' => 'gear-fill', 'route' => route('settings.index'), 'desc' => 'Configure system preferences and integrations.'],
            ];
        @endphp

        @foreach ($actions as $action)
        <div class="col-sm-6 col-lg-4 col-xl-3 fade-in-up quick-link-card">
            <a href="{{ $action['route'] }}" class="card h-100 shadow-sm card-hover-effect text-decoration-none text-dark">
                <div class="card-body text-center p-4">
                    <i class="bi bi-{{ $action['icon'] }} fs-1 mb-3 text-primary icon-bounce"></i>
                    <h5 class="fw-semibold">{{ $action['title'] }}</h5>
                    <p class="text-muted small">{{ $action['desc'] }}</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
