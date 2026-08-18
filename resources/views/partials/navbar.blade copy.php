<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container-fluid py-1">

        <!-- LOGO -->
        <a class="navbar-brand me-5 ms-2" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Portal Berita Logo" style="height:60px;">
        </a>

        <!-- TOGGLER -->
        <button class="navbar-toggler collapsed" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="toggler-icon top-bar"></span>
            <span class="toggler-icon middle-bar"></span>
            <span class="toggler-icon bottom-bar"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav flex-grow-1 justify-content-center gap-1 me-2"
                data-bs-auto-close="outside">
                <li class="nav-item">
                    <a class="nav-link text-gold {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Trending
                    </a>
                </li>
                @php
                $segment1 = Request::segment(2); // parent slug
                $segment2 = Request::segment(3); // child slug
                @endphp

                @foreach($navCategories->where('is_active', 1) as $parent)
                <li class="nav-item dropdown">

                    <a class="nav-link text-gold dropdown-toggle
        {{ $segment1 == $parent->slug ? 'active' : '' }}"
                        href="{{ route('category.show', $parent->slug) }}"
                        id="cat{{ $parent->id }}"
                        role="button"
                        data-bs-toggle="dropdown">

                        <i class="nav-icon"></i> {{ $parent->name }}
                    </a>

                    {{-- Dropdown --}}
                    @if($parent->children->where('is_active', 1)->count())
                    <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="cat{{ $parent->id }}">
                        @foreach($parent->children->where('is_active', 1) as $child)
                        <li>
                            <a class="dropdown-item text-gold 
                {{ $segment2 == $child->slug ? 'active' : '' }}"
                                href="{{ route('category.show', [$parent->slug, $child->slug]) }}">
                                <i class="nav-icon"></i> {{ $child->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                </li>
                @endforeach


                <li class="nav-item dropdown">
                    <a class="nav-link text-gold dropdown-toggle {{ request()->routeIs('login') || request()->routeIs('register') ? 'active' : '' }}"
                        href="#" id="agenDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="nav-icon"></i>Agenda Pemerintahan
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="agenDropdown">
                        <li><a class="dropdown-item text-gold" href="{{ route('kementerian.kota') }}"><i class="nav-icon"></i> Dinas Kota</a></li>
                        <li><a class="dropdown-item text-gold" href="{{ route('kementerian.provinsi') }}"><i class="nav-icon"></i>Dinas Provinsi</a></li>
                    </ul>
                </li>


                {{-- AUTH MENU --}}
                <!-- End Dropdown Menu -->
                @guest
                <li class="nav-item dropdown">
                    <a class="nav-link text-gold {{ request()->routeIs('login') || request()->routeIs('register') ? 'active' : '' }}"
                        href="#" id="authDropdown" role="button">
                        <i class="bi bi-person-circle nav-icon"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="authDropdown">
                        <li>
                            <a class="dropdown-item text-gold" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right nav-icon"></i>Login
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-gold" href="{{ route('register') }}">
                                <i class="bi bi-person-plus nav-icon"></i>Register
                            </a>
                        </li>
                    </ul>
                </li>

                @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="userDropdown"> {{-- ✅ kiri --}}
                        <li>
                            <a class="dropdown-item text-gold" href="{{ route('profile.edit') }}">
                                <i class="bi bi-gear nav-icon"></i> Profil
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-gold" type="submit">
                                    <i class="bi bi-box-arrow-right nav-icon"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

                @endguest


            </ul>
        </div>
    </div>
</nav>