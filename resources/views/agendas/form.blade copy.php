<div class="row g-3">
    {{-- PILIH DINAS DENGAN LIVE SEARCH --}}
    <div class="col-md-12">
        <label class="form-label fw-semibold">Dinas</label>
        <div class="custom-select-wrapper position-relative">
            <input type="text" id="searchDinas" class="form-control" placeholder="🔍 Cari dinas...">
            <ul id="dinasDropdown" class="list-group position-absolute w-100 shadow-sm"
                style="display:none; max-height:200px; overflow-y:auto; z-index:1055;">
                @foreach($dinasList as $dinas)
                <li class="list-group-item list-group-item-action"
                    data-id="{{ $dinas->id }}"
                    data-kategori="{{ $dinas->kategori ?? ($dinas->kota ?? '-') }}">
                    {{ $dinas->nama }}
                </li>
                @endforeach
            </ul>
            <input type="hidden" name="dinas_id" id="selectedDinasId">
        </div>
    </div>

    {{-- KATEGORI / KOTA / PROVINSI --}}
    <div class="col-md-12">
        <label class="form-label fw-semibold">Kategori / Kota / Provinsi</label>
        <input type="text" id="kategoriDinas" class="form-control bg-light" readonly placeholder="Kategori otomatis...">
    </div>

    {{-- JUDUL AGENDA --}}
    <div class="col-md-12">
        <label class="form-label fw-semibold">Judul Agenda</label>
        <input type="text" name="judul" class="form-control" placeholder="Masukkan judul agenda" required>
    </div>

    {{-- DESKRIPSI --}}
    <div class="col-md-12">
        <label class="form-label fw-semibold">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Tulis deskripsi singkat..."></textarea>
    </div>

    {{-- FOTO --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Foto</label>
        <input type="file" name="foto" class="form-control">
    </div>

    {{-- LINK --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Link</label>
        <input type="url" name="link" class="form-control" placeholder="https://contoh.com">
    </div>

    {{-- VIDEO --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Video</label>
        <input type="text" name="video" class="form-control" placeholder="URL video (jika ada)">
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchDinas = document.getElementById('searchDinas');
        const dinasDropdown = document.getElementById('dinasDropdown');
        const hiddenDinasId = document.getElementById('selectedDinasId');
        const kategoriDinas = document.getElementById('kategoriDinas');
        const items = dinasDropdown.querySelectorAll('li');

        // === Fungsi disable & enable field ===
        function disableDinasField() {
            searchDinas.setAttribute('readonly', true);
            searchDinas.classList.add('bg-light');
            dinasDropdown.style.display = 'none';
        }

        function enableDinasField() {
            searchDinas.removeAttribute('readonly');
            searchDinas.classList.remove('bg-light');
        }

        // === Modal events ===
        const editModal = document.getElementById('editAgendaModal');
        const createModal = document.getElementById('createAgendaModal');

        if (editModal) {
            editModal.addEventListener('show.bs.modal', () => {
                disableDinasField();
            });
        }

        if (createModal) {
            createModal.addEventListener('show.bs.modal', () => {
                enableDinasField();
                searchDinas.value = '';
                hiddenDinasId.value = '';
                kategoriDinas.value = '';
            });
        }

        // === Pencarian dinas ===
        searchDinas.addEventListener('focus', () => {
            if (!searchDinas.hasAttribute('readonly')) {
                dinasDropdown.style.display = 'block';
            }
        });

        searchDinas.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // === Pilih Dinas dan tampilkan kategori otomatis ===
        items.forEach(item => {
            item.addEventListener('click', () => {
                searchDinas.value = item.textContent;
                hiddenDinasId.value = item.dataset.id;
                kategoriDinas.value = item.dataset.kategori || '-';
                dinasDropdown.style.display = 'none';
            });
        });

        // Klik di luar menutup dropdown
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-select-wrapper')) {
                dinasDropdown.style.display = 'none';
            }
        });
    });
</script>
@endpush