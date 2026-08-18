<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-dark shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">


        <!-- KIRI -->
        <div id="live-date" class="text-white d-flex align-items-center gap-3">
            <i class="fas fa-clock"></i>
            <span id="current-date">Minggu, 11 Jan 2026</span>
            <span id="current-time">07:00:43</span>
        </div>

        <!-- TENGAH (SEARCH) -->
        <div class="nav-search-center d-none d-lg-block">
            <button id="searchToggle" class="btn btn-sm btn-outline-light px-4">
                Cari Berita...
                <i class="fas fa-search ms-2"></i>
            </button>
        </div>

        <!-- KANAN -->
        <div class="d-flex align-items-center gap-3 ms-auto">
            <a href="#" class="text-white fs-5"><i class="fab fa-whatsapp"></i></a>
            <a href="#" class="text-white fs-5"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-white fs-5"><i class="fab fa-tiktok"></i></a>
            <a href="{{ route('login') }}" class="text-white fs-5 ms-3">
                <i class="bi bi-person-circle"></i>
            </a>
        </div>

    </div>
</nav>


<!-- 🔽 Overlay Search (HARUS DI LUAR NAVBAR) -->
<div id="searchOverlay" class="search-overlay">
    <div class="search-box">
        <input
            id="liveSearch"
            type="search"
            class="form-control form-control-lg"
            placeholder="Cari berita..."
            autocomplete="off">

        <ul id="searchResults" class="list-group mt-3"></ul>
    </div>
</div>