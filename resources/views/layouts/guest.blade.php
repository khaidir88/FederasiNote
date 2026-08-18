<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Federasi Note</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('public/../images/logo.png') }}">

    <!-- Google Fonts -->

    <!-- <link href="https://fonts.googleapis.com/css?family=Raleway:400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('public/../css/news.css') }}">

    @stack('styles')
</head>

<body>
    <!-- ✅ Top Navbar -->
    @include('partials.naviup')

    <!-- ✅ Navbar utama -->
    <div class="mt-0 pt-1">
        @include('partials.navbar')

    </div>

    <!-- ✅ Konten utama -->
    <main class="container my-4">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- ✅ Tombol scroll ke atas -->
    <button onclick="scrollToTop()" id="btnTop" title="Kembali ke atas">
        <i class="bi bi-arrow-up-short"></i>
    </button>
    <div class="mt-0 pt-1">
        @include('partials.footer')

    </div>
    <!-- ✅ JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            const searchResults = document.getElementById('searchResults');

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length < 2) {
                    searchResults.innerHTML = '';
                    return;
                }

                fetch(`{{ route('search') }}?q=${query}`)
                    .then(res => res.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        if (data.length) {
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.classList.add('list-group-item', 'list-group-item-action');
                                li.innerHTML = `<a href="${item.url}" class="text-decoration-none">
                                            <strong>[${item.type}]</strong> ${item.title}
                                        </a>`;
                                searchResults.appendChild(li);
                            });
                        } else {
                            searchResults.innerHTML = '<li class="list-group-item">Tidak ada hasil</li>';
                        }
                    });
            });

            // Tutup hasil search saat klik di luar
            document.addEventListener('click', function(e) {
                if (!searchResults.contains(e.target) && e.target !== searchInput) {
                    searchResults.innerHTML = '';
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('navbarNav');
            const toggler = document.querySelector('.navbar-toggler');
            const btnTop = document.getElementById('btnTop');
            const nav = document.querySelector('.navbar-custom');
            const modeToggle = document.getElementById('modeToggle');

            // ========================
            // Mode Light/Dark Toggle
            // ========================
            if (modeToggle && nav) {
                modeToggle.addEventListener('click', function() {
                    nav.classList.toggle('light-mode');

                    if (nav.classList.contains('light-mode')) {
                        modeToggle.textContent = 'Dark';
                        modeToggle.classList.remove('btn-outline-light');
                        modeToggle.classList.add('btn-outline-dark');
                    } else {
                        modeToggle.textContent = 'Light';
                        modeToggle.classList.remove('btn-outline-dark');
                        modeToggle.classList.add('btn-outline-light');
                    }
                });
            }

            // ========================
            // Tutup menu saat klik di luar navbar (mobile)
            // ========================
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 992 && navbar && navbar.classList.contains('show')) {
                    if (!navbar.contains(e.target) && !toggler.contains(e.target)) {
                        const bsCollapse = new bootstrap.Collapse(navbar, {
                            toggle: false
                        });
                        bsCollapse.hide();
                    }
                }
            });

            // ========================
            // Tutup menu setelah klik nav-link (mobile)
            // // ========================
            // document.querySelectorAll('.nav-link').forEach(link => {
            //     link.addEventListener('click', function() {
            //         if (window.innerWidth < 992 && navbar && navbar.classList.contains('show')) {
            //             const bsCollapse = new bootstrap.Collapse(navbar, {
            //                 toggle: false
            //             });
            //             bsCollapse.hide();
            //         }
            //     });
            // });

            // ========================
            // Scroll events
            // ========================
            window.addEventListener('scroll', function() {
                const scrollY = window.scrollY || document.documentElement.scrollTop;

                // Navbar scroll effect
                if (nav) {
                    nav.classList.toggle('navbar-scrolled', scrollY > 60);
                }

                // Tombol scroll ke atas
                if (btnTop) {
                    btnTop.style.display = scrollY > 200 ? 'block' : 'none';
                }
            });
        });

        // ========================
        // Fungsi tombol scroll ke atas
        // ========================
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>


    <script>
        function updateDateTime() {
            const now = new Date();

            // Format hari dalam Bahasa Indonesia
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayName = days[now.getDay()];

            // Format bulan dalam Bahasa Indonesia
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const monthName = months[now.getMonth()];

            // Format tanggal
            const date = now.getDate();
            const year = now.getFullYear();

            // Format waktu
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');

            // Update elemen HTML
            document.getElementById('current-date').textContent = `${dayName}, ${date} ${monthName} ${year}`;
            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
        }

        // Update setiap detik
        setInterval(updateDateTime, 1000);

        // Jalankan sekali saat halaman dimuat
        updateDateTime();
    </script>

    @stack('scripts')
</body>

</html>