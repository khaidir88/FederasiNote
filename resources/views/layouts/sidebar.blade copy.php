<!-- Toggle Button -->
<button class="btn btn-dark d-md-none position-fixed top-0 start-0 m-5" id="sidebarToggle">
    <i class="bi bi-list"></i>
</button>

<!-- Overlay untuk klik luar -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<!-- 🔹 Sidebar -->
<nav class="sidebar bg-dark" id="sidebar">
    <div class="d-flex justify-content-between align-items-center p-3">
        <h2 class="mb-0 text-white">
            <i class="bi bi-newspaper"></i> Federasi Note
        </h2>
        <!-- Tombol Close -->
        <button class="btn btn-sm btn-outline-light d-md-none" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="text-center mb-3">
        <small class="text-white">
            @if(auth()->check())
            @if(auth()->user()->hasRole('Admin'))
            Admin Dashboard
            @elseif(auth()->user()->hasRole('User'))
            User Dashboard
            @else
            Dashboard
            @endif
            @else
            Dashboard
            @endif
        </small>

    </div>

    <ul class="sidebar-nav list-unstyled px-2">
        <li class="sidebar-nav-item mt-5">
            <a href="{{ route('dashboard') }}"
                class="sidebar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        @can ('View Articles')
        <li class="sidebar-nav-item">
            <a href="{{ route('berita.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('berita.index') ? 'active' : '' }}">
                <i class="bi bi-file-text"></i> Berita
            </a>
        </li>
        @endcan

        @can ('View Agenda')
        <li class="sidebar-nav-item">
            <a href="{{ route('agendas.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('agendas.index') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Agenda
            </a>
        </li>
        @endcan
        @can ('View Dinas')
        <li class="sidebar-nav-item">
            <a href="{{ route('dinas.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('dinas.index') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Dinas
            </a>
        </li>
        @endcan
        @can ('View Category')
        <li class="sidebar-nav-item">
            <a href="{{ route('categories.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                <i class="bi bi-bookmarks"></i> Kategori
            </a>
        </li>
        @endcan
        @can('View User')
        <li class="sidebar-nav-item">
            <a href="{{ route('users.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Pengguna
            </a>
        </li>
        @endcan
        @can ('View Permission')
        <li class="sidebar-nav-item">
            <a href="{{ route('permissions.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i> Permissions
            </a>
        </li>
        @endcan
        @can ('View Role')
        <li class="sidebar-nav-item">
            <a href="{{ route('roles.index') }}"
                class="sidebar-nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Roles
            </a>
        </li>
        @endcan

        <li class="sidebar-nav-item-flex-end mt-5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-nav-link w-100 text-start border-0 bg-transparent">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </li>


    </ul>
</nav>