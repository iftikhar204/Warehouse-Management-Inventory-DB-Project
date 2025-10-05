<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom shadow-sm fixed-top">
    <div class="container-fluid px-4">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center text-warning fw-bold" href="#">
            <i class=" text-primary bi bi-box-seam me-2 fs-4"></i>
            <span class="fs-5">WMS</span>
        </a>

        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapsible content -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">

            <!-- Left menu -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @php
                    $navItems = [
                        ['route' => 'home', 'label' => 'Dashboard', 'icon' => 'speedometer2'],
                        ['route' => 'warehouses.index', 'label' => 'Warehouses', 'icon' => 'building'],
                        ['route' => 'products.index', 'label' => 'Products', 'icon' => 'boxes'],
                        ['route' => 'inventory.index', 'label' => 'Inventory', 'icon' => 'clipboard-data'],
                        ['route' => 'orders.index', 'label' => 'Orders', 'icon' => 'cart-check'],
                        ['route' => 'employees.index', 'label' => 'Employees', 'icon' => 'people'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    <li class="nav-item me-2">
                        <a href="{{ route($item['route']) }}"
                           class="nav-link px-3 d-flex align-items-center {{ request()->routeIs($item['route']) ? 'active text-warning fw-semibold' : 'text-light' }}">
                            <i class=" text-primary bi bi-{{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Right auth area -->
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center text-light px-3"
                           href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class=" text-primary bi bi-person-circle me-2"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                                    <i class=" text-warning bi bi-person me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center">
                                        <i class="text-warning bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 d-flex align-items-center" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 d-flex align-items-center" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-2"></i> Register
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
