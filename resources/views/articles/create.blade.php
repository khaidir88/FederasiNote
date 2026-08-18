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
                                        <!-- Keterangan (Deskripsi Singkat) -->
                                        <div class="mb-3">
                                            <label for="keterangan" class="form-label fw-semibold">
                                                Keterangan / Deskripsi Singkat <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="keterangan"
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
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary" onclick="return submitForm()">
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
<!-- Gunakan CDN yang lebih update -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    // Inisialisasi CKEditor dengan cara yang lebih aman
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi CKEditor untuk konten
        CKEDITOR.replace('content', {
            toolbar: [{
                    name: 'document',
                    items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']
                },
                {
                    name: 'clipboard',
                    items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                },
                {
                    name: 'editing',
                    items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']
                },
                {
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat']
                },
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']
                },
                {
                    name: 'links',
                    items: ['Link', 'Unlink', 'Anchor']
                },
                {
                    name: 'insert',
                    items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
                },
                '/',
                {
                    name: 'styles',
                    items: ['Styles', 'Format', 'Font', 'FontSize']
                },
                {
                    name: 'colors',
                    items: ['TextColor', 'BGColor']
                },
                {
                    name: 'tools',
                    items: ['Maximize', 'ShowBlocks']
                }
            ],
            height: 300,
            filebrowserUploadUrl: "{{ route('upload.image') }}",
            filebrowserUploadMethod: 'form'
        });

        // Counter untuk keterangan
        const keteranganTextarea = document.getElementById('keterangan');
        const keteranganCounter = document.getElementById('keteranganCounter');

        function updateKeteranganCounter() {
            const length = keteranganTextarea.value.length;
            keteranganCounter.textContent = `${length}/500`;
            if (length > 500) {
                keteranganCounter.className = 'badge bg-danger';
            } else if (length > 450) {
                keteranganCounter.className = 'badge bg-warning';
            } else {
                keteranganCounter.className = 'badge bg-secondary';
            }
        }

        // Counter untuk meta description
        const metaTextarea = document.getElementById('meta_description');
        const metaCounter = document.getElementById('metaCounter');
        const statusSelect = document.getElementById('status');
        const publishDateSection = document.getElementById('publishDateSection');

        function updateMetaCounter() {
            const length = metaTextarea.value.length;
            metaCounter.textContent = `${length}/160`;
            if (length > 160) {
                metaCounter.className = 'badge bg-danger';
            } else if (length > 140) {
                metaCounter.className = 'badge bg-warning';
            } else {
                metaCounter.className = 'badge bg-secondary';
            }
        }

        function togglePublishDate() {
            if (statusSelect.value === 'published') {
                publishDateSection.style.display = 'block';
            } else {
                publishDateSection.style.display = 'none';
                // Clear the date when status is not published
                document.getElementById('publish_at').value = '';
            }
        }

        if (keteranganTextarea && keteranganCounter) {
            updateKeteranganCounter();
            keteranganTextarea.addEventListener('input', updateKeteranganCounter);
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

    // Fungsi untuk preview gambar
    function previewImage(input) {
        const preview = document.getElementById('previewImg');
        const previewDiv = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
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

    // Fungsi untuk handle form submission
    function submitForm() {
        // Pastikan CKEditor data disimpan ke textarea
        if (CKEDITOR.instances.content) {
            CKEDITOR.instances.content.updateElement();
        }

        // Validasi minimal
        const title = document.getElementById('title').value.trim();
        const keterangan = document.getElementById('keterangan').value.trim();
        const content = document.getElementById('content').value.trim();

        if (!title) {
            alert('Judul berita harus diisi!');
            document.getElementById('title').focus();
            return false;
        }

        if (!keterangan) {
            alert('Keterangan/deskripsi singkat harus diisi!');
            document.getElementById('keterangan').focus();
            return false;
        }

        if (!content) {
            alert('Konten berita harus diisi!');
            document.getElementById('content').focus();
            return false;
        }

        return true;
    }
</script>
@endpush