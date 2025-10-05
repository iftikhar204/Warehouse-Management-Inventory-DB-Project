<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" id="sidebarMenu">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('warehouse*') ? 'active' : '' }}" href="{{ route('warehouse.index') }}">
                    <i class="bi bi-building me-2"></i>
                    Warehouses
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="bi bi-box-seam me-2"></i>
                    Products
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('inventory*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                    <i class="bi bi-clipboard-data me-2"></i>
                    Inventory
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('orders*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                    <i class="bi bi-truck me-2"></i>
                    Orders
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('employees*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <i class="bi bi-people me-2"></i>
                    Employees
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Administration</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-gear me-2"></i>
                    Settings
                </a>
            </li>
        </ul>
    </div>
</div>
