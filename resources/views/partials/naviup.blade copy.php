<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-dark shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- KIRI -->
        <div id="live-date" class="text-white d-flex align-items-center gap-3">
            <i class="fas fa-clock"></i>
            <span id="current-date">Minggu, 11 Jan 2026</span>

            <!-- Waktu hanya tampil di tablet & desktop -->
            <span id="current-time" class="d-none d-md-inline">
                07:00:43
            </span>
        </div>
        <!-- KANAN -->
        <div class="d-flex align-items-center gap-3 ms-auto medsos">
            <button id="searchToggle" class="text-white btn btn-sm btn-outline-light px-4 me-3">
                Cari Berita...
                <i class="fas fa-search ms-2"></i>
            </button>

            <a href="https://www.instagram.com/federasinote" class="text-white fs-5" target="_blank">
                <i class="fab fa-instagram"></i>
            </a>

            <a href="https://www.facebook.com/share/1CEaRjcEoP/" class="text-white fs-5" target="_blank">
                <i class="fab fa-facebook"></i>
            </a>

            <a href="https://www.tiktok.com/@federasinote?_r=1&_t=ZS-93dQmFVRXYS"
                class="text-white fs-5"
                target="_blank">
                <i class="fab fa-tiktok"></i>
            </a>

            <a href="https://youtube.com/@federasinote?si=RNapnqbZDx0V4342"
                class="text-white fs-5"
                target="_blank">
                <i class="fab fa-youtube"></i>
            </a>
            <a href="{{ route('login') }}" class="text-white fs-5 ms-md-3">
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