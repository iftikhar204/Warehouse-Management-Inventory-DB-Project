@extends('layouts.app')

@section('title', 'Warehouse Overview')

@section('content')
    <div class="dashboard">
        <main class="dashboard-content p-4">
            <div id="warehousePage" class="page-content active">
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
                    <h2>Warehouse Overview</h2>
                    <select id="warehouseSelect" class="form-control w-auto">
                        <option value="1">Warehouse #1 - Harrison, NJ</option>
                        <option value="2">Warehouse #2 - Jersey City, NJ</option>
                        <option value="3">Warehouse #3 - Newark, NJ</option>
                    </select>
                </div>

                <div class="warehouse-stats row">
                    <div class="col-md-3">
                        <div class="stat-card p-3 bg-light border rounded">
                            <h5>Total Capacity</h5>
                            <p>10,000 units</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 bg-light border rounded">
                            <h5>Used Capacity</h5>
                            <p>6,750 units (67.5%)</p>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 67.5%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 bg-light border rounded">
                            <h5>Sections</h5>
                            <p>8</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 bg-light border rounded">
                            <h5>Manager</h5>
                            <p>Saravana Ramasamy</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="warehouse-sections">
                            <h4>Sections</h4>
                            <div class="section-tree bg-light p-3 border rounded">
                                <ul>
                                    <li><span>A1 - Electronics</span>
                                        <ul>
                                            <li>A1-1 - Computers</li>
                                            <li>A1-2 - Audio Equipment</li>
                                        </ul>
                                    </li>
                                    <li><span>A2 - Home Furniture</span>
                                        <ul>
                                            <li>A2-1 - Living Room</li>
                                            <li>A2-2 - Bedroom</li>
                                        </ul>
                                    </li>
                                    <li><span>B1 - Groceries</span>
                                        <ul>
                                            <li>B1-1 - Frozen</li>
                                            <li>B1-2 - Dairy</li>
                                            <li>B1-3 - Fresh Produce</li>
                                        </ul>
                                    </li>
                                    <li><span>C1 - Clothing</span>
                                        <ul>
                                            <li>C1-1 - Men's</li>
                                            <li>C1-2 - Women's</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="capacity-visualization">
                            <h4>Warehouse Capacity</h4>
                            <div class="row">
                                <div class="col-md-8">
                                    <canvas id="capacityChart"></canvas>
                                </div>
                                <div class="col-md-4">
                                    <div class="capacity-legend">
                                        <div class="legend-item">
                                            <span class="legend-color electronics"></span>
                                            <span>Electronics (35%)</span>
                                        </div>
                                        <div class="legend-item">
                                            <span class="legend-color furniture"></span>
                                            <span>Furniture (25%)</span>
                                        </div>
                                        <div class="legend-item">
                                            <span class="legend-color groceries"></span>
                                            <span>Groceries (20%)</span>
                                        </div>
                                        <div class="legend-item">
                                            <span class="legend-color clothing"></span>
                                            <span>Clothing (15%)</span>
                                        </div>
                                        <div class="legend-item">
                                            <span class="legend-color other"></span>
                                            <span>Other (5%)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-details mt-4" id="sectionDetails">
                        <div class="stat-card">
                            <h4>A1 - Electronics Section</h4>
                            <p>Capacity: 2,000 units (1,350 used - 67.5%)</p>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 67.5%"></div>
                            </div>

                            <h5 class="mt-3">Products in this section:</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>LED Step Lights</td>
                                        <td>5</td>
                                    </tr>
                                    <tr>
                                        <td>Wireless Charger</td>
                                        <td>120</td>
                                    </tr>
                                    <tr>
                                        <td>Bluetooth Speaker</td>
                                        <td>85</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@section('styles')
    <style>
        .legend-color {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            vertical-align: middle;
            border-radius: 3px;
        }

        .electronics {
            background-color: #4e73df;
        }

        .furniture {
            background-color: #1cc88a;
        }

        .groceries {
            background-color: #36b9cc;
        }

        .clothing {
            background-color: #f6c23e;
        }

        .other {
            background-color: #e74a3b;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('capacityChart').getContext('2d');
        const capacityChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Electronics', 'Furniture', 'Groceries', 'Clothing', 'Other'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
