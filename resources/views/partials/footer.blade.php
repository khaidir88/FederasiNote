<!-- FOOTER -->
<footer class="footer pt-5 pb-4">
    <div class="container">
        <div class="row">

            <!-- About -->
            <div class="col-md-4 mb-4">
                <h4 class="fw-bold text-white">FEDERASI NOTE</h4>
                <p class="mb-0">Information All About The World</p>
            </div>

            <!-- Contact -->
            <div class="col-md-4 mb-4">
                <h4 class="fw-bold text-white">Contact</h4>
                <ul class="list-unstyled">
                    <li class="d-flex align-items-start mb-2">
                        <i class="fas fa-square me-2 text-white"></i>
                        <span>Contact Support</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="fas fa-square me-2 text-white"></i>
                        <span>Customer Service</span>
                    </li>
                </ul>
            </div>

            <!-- Social -->
            <div class="col-md-4 mb-4">
                <h4 class="fw-bold text-white">Social Media</h4>
                <a href="https://www.instagram.com/federasinote" class="fa-stack fs-4" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-instagram"></i>
                </a>

                <a href="https://www.facebook.com/share/1CEaRjcEoP/"
                    class="fa-stack fs-4"
                    target="_blank"
                    rel="noopener noreferrer">
                    <i class="fab fa-facebook"></i>
                </a>

                <a href="https://www.tiktok.com/@federasinote?_r=1&_t=ZS-93dQmFVRXYS"
                    class="fa-stack fs-4"
                    target="_blank"
                    rel="noopener noreferrer">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="https://youtube.com/@federasinote?si=RNapnqbZDx0V4342"
                    class="fa-stack fs-4"
                    target="_blank"
                    rel="noopener noreferrer">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>

        </div>
    </div>
</footer>
<!-- END FOOTER -->

<!-- COPYRIGHT -->
<div class="bg-dark text-center text-white py-2">
    <small>
        Copyright © 2025
        <strong>FEDERASI NOTE</strong> — All rights reserved
    </small>
</div>

<script>
    document.getElementById('liveSearch').addEventListener('keyup', function() {
        let query = this.value;
        let resultBox = document.getElementById('searchResults');

        if (query.length < 2) {
            resultBox.innerHTML = '';
            resultBox.style.display = 'none';
            return;
        }

        fetch(`/live-search?q=${query}`)
            .then(response => response.json())
            .then(data => {
                resultBox.innerHTML = '';
                resultBox.style.display = 'block';

                if (data.length === 0) {
                    resultBox.innerHTML = `<li class="list-group-item text-muted">Tidak ditemukan</li>`;
                    return;
                }

                data.forEach(item => {
                    resultBox.innerHTML += `
                    <li class="list-group-item">
                        <a href="/news/${item.slug}" class="text-decoration-none d-block">
                            <strong>${item.title}</strong><br>
                            <small class="text-muted">${item.category.name}</small>
                        </a>
                    </li>
                `;
                });
            });
    });
</script>

<script>
    const toggle = document.getElementById('searchToggle');
    const overlay = document.getElementById('searchOverlay');
    const input = document.getElementById('liveSearch');
    const results = document.getElementById('searchResults');

    toggle.addEventListener('click', () => {
        overlay.style.display = 'flex';
        input.focus();
    });

    // klik background → close
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.style.display = 'none';
            input.value = '';
            results.innerHTML = '';
        }
    });

    // live search
    input.addEventListener('keyup', function() {
        let q = this.value;

        if (q.length < 2) {
            results.innerHTML = '';
            return;
        }

        fetch(`/live-search?q=${q}`)
            .then(res => res.json())
            .then(data => {
                results.innerHTML = '';

                if (data.length === 0) {
                    results.innerHTML = `<li class="list-group-item text-muted">Tidak ditemukan</li>`;
                    return;
                }

                data.forEach(item => {
                    results.innerHTML += `
                    <li class="list-group-item">
                        <a href="/news/${item.slug}" class="text-decoration-none">
                            <strong>${item.title}</strong><br>
                            <small class="text-muted">${item.category.name}</small>
                        </a>
                    </li>
                `;
                });
            });
    });
</script>