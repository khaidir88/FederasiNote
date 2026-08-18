@extends('layouts.app')

@section('title', 'Edit Berita')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-pencil-square me-2"></i>Edit Berita</h2>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                </a>
            </div>

            <!-- Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Form Edit -->
                    <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" id="newsForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-8">
                                <!-- Judul -->
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-semibold">
                                        Judul articles <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        id="title"
                                        name="title"
                                        value="{{ old('title', $article->title) }}"
                                        class="form-control @error('title') is-invalid @enderror"
                                        required
                                        maxlength="255"
                                        placeholder="Masukkan judul articles">
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Keterangan -->
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
                                        placeholder="Masukkan deskripsi singkat articles (maksimal 500 karakter)...">{{ old('keterangan', $article->keterangan) }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted">Deskripsi singkat yang akan muncul di halaman daftar articles.</small>
                                        <span id="keteranganCounter" class="badge bg-secondary">{{ strlen(old('keterangan', $article->keterangan)) }}/500</span>
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
                                        placeholder="Tulis konten lengkap articles di sini...">{{ old('content', $article->content) }}</textarea>
                                    @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Meta Description -->
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description (Opsional)</label>
                                    <textarea id="meta_description"
                                        name="meta_description"
                                        rows="3"
                                        maxlength="160"
                                        class="form-control @error('meta_description') is-invalid @enderror"
                                        placeholder="Deskripsi untuk SEO...">{{ old('meta_description', $article->meta_description) }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted">Maksimal 160 karakter untuk SEO.</small>
                                        <span id="metaCounter" class="badge bg-secondary">{{ strlen(old('meta_description', $article->meta_description)) }}/160</span>
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
                                        Pengaturan articles
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
                                                    {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
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
                                                value="{{ old('author', $article->author) }}"
                                                class="form-control @error('author') is-invalid @enderror"
                                                maxlength="100"
                                                placeholder="Nama penulis">
                                            @error('author')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Gambar Utama -->
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Gambar Utama</label>

                                            <!-- Preview Gambar Saat Ini -->
                                            @if($article->image)
                                            <div class="mb-3 text-center" id="currentImageSection">
                                                <img src="{{ Storage::exists('public/' . $article->image) ? asset('storage/' . $article->image) : asset('storage/news/' . $article->image) }}"
                                                    alt="Gambar articles"
                                                    class="img-thumbnail mb-2"
                                                    style="max-height: 150px;">

                                                <!-- Checkbox Hapus Gambar -->
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                        id="remove_image"
                                                        name="remove_image"
                                                        value="1"
                                                        class="form-check-input"
                                                        {{ old('remove_image') ? 'checked' : '' }}>
                                                    <label for="remove_image" class="form-check-label text-danger">
                                                        Hapus gambar saat ini
                                                    </label>
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Upload Gambar Baru -->
                                            <div id="imageUploadSection" class="{{ $article->image ? 'mt-3' : '' }}">
                                                <input type="file"
                                                    id="image"
                                                    name="image"
                                                    accept="image/*"
                                                    class="form-control @error('image') is-invalid @enderror"
                                                    onchange="previewNewImage(this)">
                                                @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

                                                <!-- Preview Gambar Baru -->
                                                <div id="newImagePreview" class="mt-2 text-center" style="display: none;">
                                                    <img id="previewImage"
                                                        class="img-thumbnail mb-2"
                                                        style="max-height: 150px;">

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="removeNewImage()">
                                                        <i class="bi bi-x-circle me-1"></i>Hapus Gambar Baru
                                                    </button>
                                                </div>

                                                <small class="text-muted d-block mt-1">Format: JPEG, PNG, JPG, GIF, WebP. Maksimal 2MB</small>
                                            </div>
                                        </div>

                                        <!-- Link YouTube -->
                                        <div class="mb-3">
                                            <label for="video_url" class="form-label">Link YouTube (Opsional)</label>
                                            <input type="url"
                                                id="video_url"
                                                name="video_url"
                                                placeholder="https://www.youtube.com/watch?v=xxxxxx"
                                                value="{{ old('video_url', $article->video_url) }}"
                                                class="form-control @error('video_url') is-invalid @enderror">
                                            @error('video_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select id="status"
                                                name="status"
                                                class="form-select @error('status') is-invalid @enderror"
                                                required
                                                onchange="togglePublishDate()">
                                                <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="archived" {{ old('status', $article->status) == 'archived' ? 'selected' : '' }}>Arsip</option>
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tanggal Publikasi -->
                                        <div class="mb-3" id="publishDateSection" style="{{ old('status', $article->status) == 'published' ? '' : 'display: none;' }}">
                                            <label for="publish_at" class="form-label">Tanggal Publikasi</label>
                                            <input type="datetime-local"
                                                id="publish_at"
                                                name="publish_at"
                                                value="{{ old('publish_at', $article->publish_at ? $article->publish_at->format('Y-m-d\TH:i') : '') }}"
                                                class="form-control @error('publish_at') is-invalid @enderror">
                                            @error('publish_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Kosongkan untuk publish sekarang</small>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="set_current_time">
                                                <label class="form-check-label" for="set_current_time">
                                                    Atur ke waktu sekarang
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Tags -->
                                        <div class="mb-3">
                                            <label for="tags" class="form-label">Tags (Opsional)</label>
                                            <input type="text"
                                                id="tags"
                                                name="tags"
                                                value="{{ old('tags', $article->tags_string) }}"
                                                class="form-control @error('tags') is-invalid @enderror"
                                                placeholder="articles, update, informasi"
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
                                    <button type="submit" class="btn btn-primary" onclick="return beforeSubmit()">
                                        <i class="bi bi-check-circle me-1"></i>Update articles
                                    </button>
                                    <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-info" target="_blank">
                                        <i class="bi bi-eye me-1"></i>Preview
                                    </a>
                                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Batal
                                    </a>
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
    // Inisialisasi CKEditor
    document.addEventListener('DOMContentLoaded', function() {
        // CKEditor untuk konten
        if (document.getElementById('content')) {
            CKEDITOR.replace('content', {
                toolbar: [{
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat']
                    },
                    {
                        name: 'paragraph',
                        items: ['NumberedList', 'BulletedList', '-', 'Blockquote']
                    },
                    {
                        name: 'links',
                        items: ['Link', 'Unlink']
                    },
                    {
                        name: 'insert',
                        items: ['Image', 'Table']
                    },
                    {
                        name: 'tools',
                        items: ['Maximize']
                    },
                    {
                        name: 'document',
                        items: ['Source']
                    }
                ],
                height: 300
            });
        }

        // Counter untuk keterangan
        const keteranganTextarea = document.getElementById('keterangan');
        const keteranganCounter = document.getElementById('keteranganCounter');

        function updateKeteranganCounter() {
            if (keteranganTextarea && keteranganCounter) {
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
        }

        // Counter untuk meta description
        const metaTextarea = document.getElementById('meta_description');
        const metaCounter = document.getElementById('metaCounter');

        function updateMetaCounter() {
            if (metaTextarea && metaCounter) {
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
        }

        // Toggle publish date berdasarkan status
        function togglePublishDate() {
            const statusSelect = document.getElementById('status');
            const publishDateSection = document.getElementById('publishDateSection');

            if (statusSelect && publishDateSection) {
                if (statusSelect.value === 'published') {
                    publishDateSection.style.display = 'block';
                } else {
                    publishDateSection.style.display = 'none';
                    // Clear date jika status bukan published
                    const publishAtInput = document.getElementById('publish_at');
                    if (publishAtInput) {
                        publishAtInput.value = '';
                    }
                }
            }
        }

        // Set current time checkbox
        const setCurrentTimeCheckbox = document.getElementById('set_current_time');
        const publishAtInput = document.getElementById('publish_at');

        if (setCurrentTimeCheckbox && publishAtInput) {
            setCurrentTimeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    const now = new Date();
                    const localDateTime = new Date(now.getTime() - (now.getTimezoneOffset() * 60000))
                        .toISOString()
                        .slice(0, 16);
                    publishAtInput.value = localDateTime;
                }
            });
        }

        // Initialize counters
        if (keteranganTextarea && keteranganCounter) {
            updateKeteranganCounter();
            keteranganTextarea.addEventListener('input', updateKeteranganCounter);
        }

        if (metaTextarea && metaCounter) {
            updateMetaCounter();
            metaTextarea.addEventListener('input', updateMetaCounter);
        }

        // Initialize publish date section
        togglePublishDate();
    });

    // Fungsi untuk preview gambar baru
    function previewNewImage(input) {
        const preview = document.getElementById('previewImage');
        const previewDiv = document.getElementById('newImagePreview');
        const currentImageSection = document.getElementById('currentImageSection');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewDiv.style.display = 'block';

                // Sembunyikan section gambar saat ini jika ada
                if (currentImageSection) {
                    currentImageSection.style.display = 'none';
                    const removeImageCheckbox = document.getElementById('remove_image');
                    if (removeImageCheckbox) {
                        removeImageCheckbox.checked = true;
                    }
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeNewImage() {
        const input = document.getElementById('image');
        const previewDiv = document.getElementById('newImagePreview');
        const currentImageSection = document.getElementById('currentImageSection');

        input.value = '';
        previewDiv.style.display = 'none';

        // Tampilkan kembali section gambar saat ini jika ada
        if (currentImageSection) {
            currentImageSection.style.display = 'block';
            const removeImageCheckbox = document.getElementById('remove_image');
            if (removeImageCheckbox) {
                removeImageCheckbox.checked = false;
            }
        }
    }

    // Fungsi untuk handle form submission
    function beforeSubmit() {
        // Pastikan CKEditor data disimpan ke textarea
        if (CKEDITOR.instances.content) {
            CKEDITOR.instances.content.updateElement();
        }

        // Validasi
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

    // Fungsi untuk toggle publish date (untuk onchange di select)
    window.togglePublishDate = function() {
        const statusSelect = document.getElementById('status');
        const publishDateSection = document.getElementById('publishDateSection');

        if (statusSelect.value === 'published') {
            publishDateSection.style.display = 'block';
        } else {
            publishDateSection.style.display = 'none';
        }
    }
</script>
@endpush