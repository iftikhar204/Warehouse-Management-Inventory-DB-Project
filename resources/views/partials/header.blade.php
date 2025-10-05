<header class="navbar navbar-expand-md navbar-dark bg-dark sticky-top shadow-sm px-3 py-2">
    <!-- Branding -->
    <a class="navbar-brand d-flex align-items-center me-auto me-md-3" href="{{ route('dashboard') }}">
        <i class="bi bi-box-seam me-2 fs-4"></i>
        <span class="fs-5 fw-semibold">WMS</span>
    </a>

    <!-- Sidebar toggle for mobile -->
    <button class="navbar-toggler d-md-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Right side (Auth menu or login) -->
    <div class="navbar-nav ms-auto align-items-center">
        @auth
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2 fs-5"></i>
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i>Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        @else
            <a class="nav-link text-white d-flex align-items-center px-3" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right me-2 fs-5"></i>Login
            </a>
        @endauth
    </div>
</header>
