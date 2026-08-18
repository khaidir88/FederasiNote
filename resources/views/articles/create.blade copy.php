@extends('layouts.app')

@section('title', 'Tambah Berita Baru')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-plus-circle me-2"></i>Tambah Berita Baru</h2>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <!-- Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" id="newsForm">
                        @csrf

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-8">
                                <!-- Judul -->
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-semibold">
                                        Judul Berita <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        id="title"
                                        name="title"
                                        value="{{ old('title') }}"
                                        class="form-control @error('title') is-invalid @enderror"
                                        required
                                        maxlength="255"
                                        placeholder="Masukkan judul berita">
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Keterangan (Deskripsi Singkat) -->
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label fw-semibold">
                                        Keterangan / Deskripsi Singkat <span class="text-danger">*</span>
                                    </label>
                                    <textarea type="text" id="keterangan"
                                        name="keterangan"
                                        rows="4"
                                        class="form-control @error('keterangan') is-invalid @enderror"
                                        required
                                        maxlength="500"
                                        placeholder="Masukkan deskripsi singkat berita (maksimal 500 karakter)...">{{ old('keterangan') }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted">Deskripsi singkat yang akan muncul di halaman daftar articles.</small>
                                        <span id="keteranganCounter" class="badge bg-secondary">0/500</span>
                                    </div>
                                    @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Konten -->
                                <div class="mb-3">
                                    <label for="content" class="form-label fw-semibold">
                                        Konten Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="content"
                                        name="content"
                                        rows="15"
                                        class="form-control @error('content') is-invalid @enderror"
                                        required
                                        placeholder="Tulis konten lengkap berita di sini...">{{ old('content') }}</textarea>
                                    @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Meta Description -->
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea id="meta_description"
                                        name="meta_description"
                                        rows="3"
                                        maxlength="160"
                                        class="form-control @error('meta_description') is-invalid @enderror"
                                        placeholder="Deskripsi untuk SEO...">{{ old('meta_description') }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted">Maksimal 160 karakter untuk SEO.</small>
                                        <span id="metaCounter" class="badge bg-secondary">0/160</span>
                                    </div>
                                    @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light fw-semibold">
                                        Pengaturan Berita
                                    </div>
                                    <div class="card-body">
                                        <!-- Kategori -->
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Kategori</label>
                                            <select id="category_id"
                                                name="category_id"
                                                class="form-select @error('category_id') is-invalid @enderror">
                                                <option value="">Pilih Kategori</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Penulis -->
                                        <div class="mb-3">
                                            <label for="author" class="form-label">Penulis</label>
                                            <input type="text"
                                                id="author"
                                                name="author"
                                                value="{{ old('author', auth()->user()->name) }}"
                                                class="form-control @error('author') is-invalid @enderror"
                                                maxlength="100"
                                                placeholder="Nama penulis">
                                            @error('author')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Kosongkan untuk menggunakan nama Anda</small>
                                        </div>

                                        <!-- Gambar Utama -->
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Gambar Utama</label>
                                            <input type="file"
                                                id="image"
                                                name="image"
                                                accept="image/*"
                                                class="form-control @error('image') is-invalid @enderror"
                                                onchange="previewImage(this)">
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                            <!-- Preview Gambar -->
                                            <div id="imagePreview" class="mt-2 text-center" style="display: none;">
                                                <img id="previewImg"
                                                    class="img-thumbnail mb-2"
                                                    style="max-height: 150px;">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="removeImage()">
                                                    <i class="bi bi-x-circle me-1"></i>Hapus Gambar
                                                </button>
                                            </div>

                                            <small class="text-muted d-block mt-1">Format: JPEG, PNG, JPG, GIF, WebP. Maksimal 2MB</small>
                                        </div>

                                        <!-- Link YouTube -->
                                        <div class="mb-3">
                                            <label for="video_url" class="form-label">Link YouTube (Opsional)</label>
                                            <input type="url"
                                                id="video_url"
                                                name="video_url"
                                                placeholder="https://www.youtube.com/watch?v=xxxxxx"
                                                value="{{ old('video_url') }}"
                                                class="form-control @error('video_url') is-invalid @enderror">
                                            @error('video_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Masukkan link video YouTube jika ada.</small>
                                        </div>

                                        <!-- Status -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select id="status"
                                                name="status"
                                                class="form-select @error('status') is-invalid @enderror"
                                                required>
                                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Arsip</option>
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tanggal Publikasi -->
                                        <div class="mb-3" id="publishDateSection" style="{{ old('status') == 'published' ? '' : 'display: none;' }}">
                                            <label for="publish_at" class="form-label">Tanggal Publikasi</label>
                                            <input type="datetime-local"
                                                id="publish_at"
                                                name="publish_at"
                                                value="{{ old('publish_at') }}"
                                                class="form-control @error('publish_at') is-invalid @enderror">
                                            @error('publish_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Kosongkan untuk publish sekarang</small>
                                        </div>

                                        <!-- Tags -->
                                        <div class="mb-3">
                                            <label for="tags" class="form-label">Tags</label>
                                            <input type="text"
                                                id="tags"
                                                name="tags"
                                                value="{{ old('tags') }}"
                                                class="form-control @error('tags') is-invalid @enderror"
                                                placeholder="berita, update, informasi"
                                                maxlength="500">
                                            @error('tags')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Pisahkan dengan koma</small>
                                        </div>

                                        <!-- User ID (hidden) -->
                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                                        <!-- Slug (opsional, bisa dibuat otomatis di controller) -->
                                        <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i>Simpan Berita
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    // Variabel global untuk CKEditor
    let ckeditorInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== DEBUG: Form Loaded ===');

        // Debug khusus untuk field keterangan
        const keteranganField = document.getElementById('keterangan');
        console.log('Keterangan field found:', !!keteranganField);
        if (keteranganField) {
            console.log('Keterangan name:', keteranganField.name);
            console.log('Keterangan value:', keteranganField.value);
            console.log('Keterangan old value:', '{{ old("keterangan") }}');
        }

        try {
            // Inisialisasi CKEditor untuk konten
            ckeditorInstance = CKEDITOR.replace('content', {
                height: 300,
                filebrowserUploadUrl: "{{ route('upload.image') }}",
                filebrowserUploadMethod: 'form',
                removePlugins: 'elementspath',
                resize_enabled: false,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ['Link', 'Unlink'],
                    ['Image', 'Table', 'HorizontalRule'],
                    ['Source']
                ]
            });
        } catch (error) {
            console.error('CKEditor initialization error:', error);
        }

        // Counter untuk keterangan
        const keteranganTextarea = document.getElementById('keterangan');
        const keteranganCounter = document.getElementById('keteranganCounter');

        function updateKeteranganCounter() {
            if (keteranganTextarea && keteranganCounter) {
                const length = keteranganTextarea.value.length;
                keteranganCounter.textContent = `${length}/500`;
                keteranganCounter.className = length > 500 ? 'badge bg-danger' :
                    length > 450 ? 'badge bg-warning' : 'badge bg-secondary';

                // Debug: log perubahan
                if (length > 0) {
                    console.log('Keterangan updated:', keteranganTextarea.value.substring(0, 50) + '...');
                }
            }
        }

        // Counter untuk meta description
        const metaTextarea = document.getElementById('meta_description');
        const metaCounter = document.getElementById('metaCounter');

        function updateMetaCounter() {
            if (metaTextarea && metaCounter) {
                const length = metaTextarea.value.length;
                metaCounter.textContent = `${length}/160`;
                metaCounter.className = length > 160 ? 'badge bg-danger' :
                    length > 140 ? 'badge bg-warning' : 'badge bg-secondary';
            }
        }

        // Toggle tanggal publikasi
        const statusSelect = document.getElementById('status');
        const publishDateSection = document.getElementById('publishDateSection');

        function togglePublishDate() {
            if (statusSelect && publishDateSection) {
                if (statusSelect.value === 'published') {
                    publishDateSection.style.display = 'block';
                    if (!document.getElementById('publish_at').value) {
                        const now = new Date();
                        const timezoneOffset = now.getTimezoneOffset() * 60000;
                        const localDate = new Date(now.getTime() - timezoneOffset);
                        document.getElementById('publish_at').value = localDate.toISOString().slice(0, 16);
                    }
                } else {
                    publishDateSection.style.display = 'none';
                    document.getElementById('publish_at').value = '';
                }
            }
        }

        // Inisialisasi
        if (keteranganTextarea && keteranganCounter) {
            updateKeteranganCounter();
            keteranganTextarea.addEventListener('input', updateKeteranganCounter);

            // Debug setiap kali ada perubahan
            keteranganTextarea.addEventListener('input', function() {
                console.log('Keterangan input event:', this.value.substring(0, 30));
            });
        }

        if (metaTextarea && metaCounter) {
            updateMetaCounter();
            metaTextarea.addEventListener('input', updateMetaCounter);
        }

        if (statusSelect) {
            togglePublishDate();
            statusSelect.addEventListener('change', togglePublishDate);
        }
    });

    // **PERBAIKAN UTAMA: Event listener untuk form submission dengan debug khusus keterangan**
    document.getElementById('newsForm').addEventListener('submit', function(e) {
        console.log('=== DEBUG: Form Submission Started ===');

        // Debug khusus untuk keterangan sebelum validasi
        const keteranganField = document.getElementById('keterangan');
        const keteranganValue = keteranganField ? keteranganField.value.trim() : '';
        console.log('Keterangan before validation:', keteranganValue);
        console.log('Keterangan length:', keteranganValue.length);

        // Tampilkan loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menyimpan...';
        submitBtn.disabled = true;

        // Pastikan data CKEditor disimpan
        if (ckeditorInstance) {
            try {
                ckeditorInstance.updateElement();
            } catch (error) {
                console.error('Error updating CKEditor:', error);
            }
        }

        // Validasi basic - FOKUS PADA KETERANGAN
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();

        if (!title) {
            e.preventDefault();
            alert('Judul berita harus diisi!');
            document.getElementById('title').focus();
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return false;
        }

        // **VALIDASI KHUSUS KETERANGAN**
        if (!keteranganValue) {
            e.preventDefault();
            alert('Keterangan/deskripsi singkat harus diisi!');
            document.getElementById('keterangan').focus();
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return false;
        }

        if (keteranganValue.length > 500) {
            e.preventDefault();
            alert('Keterangan maksimal 500 karakter!');
            document.getElementById('keterangan').focus();
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return false;
        }

        if (!content || content === '<p>&nbsp;</p>') {
            e.preventDefault();
            alert('Konten berita harus diisi!');
            document.getElementById('content').focus();
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return false;
        }

        // **DEBUG LANJUTAN: Periksa semua data form**
        const formData = new FormData(this);
        console.log('=== FormData to be sent ===');

        // Cek khusus keterangan di FormData
        let keteranganFound = false;
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${typeof value === 'string' ? value.substring(0, 50) : value}`);
            if (key === 'keterangan') {
                keteranganFound = true;
                console.log('✅ KETERANGAN FOUND IN FORMDATA');
                console.log('Keterangan value in FormData:', value);
                console.log('Keterangan length in FormData:', value.length);
            }
        }

        if (!keteranganFound) {
            console.error('❌ KETERANGAN NOT FOUND IN FORMDATA!');
            // Coba tambahkan manual
            formData.append('keterangan', keteranganValue);
            console.log('Keterangan manually added to FormData');
        }

        console.log('=== DEBUG: Form validation passed ===');
        console.log('Form will submit...');

        // **OPSIONAL: Tambahkan timeout untuk melihat loading state**
        setTimeout(() => {
            console.log('Timeout finished, form submitting...');
        }, 100);

        // Kembalikan true untuk melanjutkan submit
        return true;
    });

    // Fungsi untuk preview gambar
    function previewImage(input) {
        const preview = document.getElementById('previewImg');
        const previewDiv = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const maxSize = 2 * 1024 * 1024;
            if (input.files[0].size > maxSize) {
                alert('Ukuran gambar maksimal 2MB!');
                input.value = '';
                return;
            }

            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!validTypes.includes(input.files[0].type)) {
                alert('Format gambar harus JPEG, PNG, JPG, GIF, atau WebP!');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewDiv.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        const input = document.getElementById('image');
        const previewDiv = document.getElementById('imagePreview');
        input.value = '';
        previewDiv.style.display = 'none';
    }

    // **FUNGSI ALTERNATIF: Validasi cepat dengan alert**
    function validateKeteranganQuick() {
        const keterangan = document.getElementById('keterangan').value.trim();
        alert('Keterangan value: "' + keterangan + '"\nLength: ' + keterangan.length);
        return false;
    }
</script>

<!-- DEBUG BUTTON - Tambahkan di luar form untuk testing -->
<div style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
    <button onclick="validateKeteranganQuick()"
        class="btn btn-warning btn-sm">
        <i class="bi bi-bug"></i> Debug Keterangan
    </button>
</div>
@endpush