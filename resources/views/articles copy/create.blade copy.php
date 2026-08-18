@extends('layouts.app')

@section('title', 'Tambah Artikel Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Tambah Artikel Baru</h2>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Artikel
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Judul Utama -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}"
                                        placeholder="Masukkan judul artikel yang menarik..." required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Judul akan digunakan untuk generate URL artikel</small>
                                </div>


                                <!-- Judul Kecil (Subjudul) -->
                                <div class="mb-3">
                                    <label for="subtitle" class="form-label">Judul Kecil (Subjudul)</label>
                                    <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                                        id="subtitle" name="subtitle" value="{{ old('subtitle') }}"
                                        placeholder="Masukkan judul kecil/subjudul (opsional)">
                                    @error('subtitle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Konten -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                        id="content" name="content" rows="15"
                                        placeholder="Tulis konten artikel Anda di sini..." required>{{ old('content') }}</textarea>
                                    @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Meta Description -->
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                        id="meta_description" name="meta_description" rows="3"
                                        maxlength="160" placeholder="Deskripsi singkat untuk SEO...">{{ old('meta_description') }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted">Maksimal 160 karakter. Penting untuk SEO</small>
                                        <small class="char-counter text-muted">0/160 karakter</small>
                                    </div>
                                    @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Sidebar Settings -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-gear me-1"></i>Pengaturan Artikel
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Kategori -->
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                            <select class="form-select @error('category_id') is-invalid @enderror"
                                                id="category_id" name="category_id" required>
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

                                        <!-- Author -->
                                        <div class="mb-3">
                                            <label for="author" class="form-label">Penulis <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('author') is-invalid @enderror"
                                                id="author" name="author" value="{{ old('author', auth()->user()->name) }}"
                                                placeholder="Nama penulis..." required>
                                            @error('author')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Gambar Utama -->
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Gambar Utama <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                                id="image" name="image" accept="image/*"
                                                onchange="previewImage(this)" required>
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                            <!-- Image Preview -->
                                            <div class="mt-2" id="imagePreview" style="display: none;">
                                                <img id="preview" class="img-thumbnail" style="max-height: 200px;">
                                                <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                                    onclick="removeImage()">
                                                    <i class="bi bi-x-circle"></i> Hapus Gambar
                                                </button>
                                            </div>

                                            <!-- Image Caption -->
                                            <div class="mt-3">
                                                <label for="image_caption" class="form-label small">
                                                    Keterangan Gambar (Caption)
                                                </label>
                                                <input type="text"
                                                    name="image_caption"
                                                    id="image_caption"
                                                    class="form-control form-control-sm @error('image_caption') is-invalid @enderror"
                                                    value="{{ old('image_caption') }}"
                                                    placeholder="Contoh: Sumber: Getty Images, Foto: John Doe">
                                                @error('image_caption')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                                <small class="text-muted">
                                                    Biarkan kosong untuk menggunakan: <em>Sumber: Dok. Pribadi</em>
                                                </small>
                                            </div>

                                            <small class="text-muted d-block mt-2">Format: JPEG, PNG, JPG, GIF, WebP. Maksimal 2MB</small>
                                        </div>

                                        <!-- Status Publikasi -->
                                        <div class="mb-3">
                                            <label for="published_at" class="form-label">Jadwal Publikasi</label>
                                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                                                id="published_at" name="published_at"
                                                value="{{ old('published_at') }}">
                                            @error('published_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Kosongkan untuk publish sekarang</small>
                                        </div>

                                        <!-- Tags -->
                                        <div class="mb-3">
                                            <label for="tags" class="form-label">Tags</label>
                                            <input type="text" class="form-control @error('tags') is-invalid @enderror"
                                                id="tags" name="tags" value="{{ old('tags') }}"
                                                placeholder="teknologi, programming, web...">
                                            @error('tags')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Pisahkan dengan koma</small>
                                        </div>

                                        <!-- Link Youtube -->
                                        <div class="mb-3">
                                            <label for="video_url" class="form-label">Link YouTube (opsional)</label>
                                            <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror"
                                                placeholder="https://www.youtube.com/watch?v=xxxxxx"
                                                value="{{ old('video_url') }}">
                                            @error('video_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Masukkan link video YouTube jika ada.</small>
                                        </div>

                                        <!-- Status Indicator -->
                                        <div class="alert alert-info">
                                            <small>
                                                <i class="bi bi-info-circle"></i>
                                                <strong>Status:</strong>
                                                <span id="statusIndicator">Akan dipublikasikan sekarang</span>
                                            </small>
                                        </div>

                                        <!-- Status Select -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" class="form-select @error('status') is-invalid @enderror" id="status">
                                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Pilih status artikel</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bi bi-plus-circle me-1"></i>Tambah Artikel
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                                        <i class="bi bi-file-earmark me-1"></i>Simpan Draft
                                    </button>
                                    <a href="{{ route('articles.index') }}" class="btn btn-outline-danger">
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

<!-- Include CKEditor -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<script>
    // CKEditor Configuration
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
        height: 400,
        filebrowserUploadUrl: "{{ route('upload.image') }}",
        filebrowserUploadMethod: 'form'
    });

    // Image Preview Function
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            // Validasi ukuran file (maksimal 2MB)
            if (input.files[0].size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                input.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!validTypes.includes(input.files[0].type)) {
                alert('Format file tidak didukung. Gunakan JPEG, PNG, JPG, GIF, atau WebP.');
                input.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Remove Image Function
    function removeImage() {
        const input = document.getElementById('image');
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');

        input.value = '';
        preview.src = '';
        imagePreview.style.display = 'none';
    }

    // Save as Draft Function
    function saveDraft() {
        document.getElementById('status').value = 'draft';
        document.getElementById('articleForm').submit();
    }

    // Update Status Indicator
    document.getElementById('published_at').addEventListener('change', function() {
        const statusIndicator = document.getElementById('statusIndicator');
        if (this.value) {
            const date = new Date(this.value);
            statusIndicator.innerHTML = `Akan dipublikasikan pada: ${date.toLocaleString('id-ID')}`;
        } else {
            statusIndicator.innerHTML = 'Akan dipublikasikan sekarang';
        }
    });

    // Update status indicator berdasarkan pilihan status
    document.getElementById('status').addEventListener('change', function() {
        const statusIndicator = document.getElementById('statusIndicator');
        if (this.value === 'draft') {
            statusIndicator.innerHTML = 'Artikel akan disimpan sebagai draft';
        } else {
            const publishedAt = document.getElementById('published_at').value;
            if (publishedAt) {
                const date = new Date(publishedAt);
                statusIndicator.innerHTML = `Akan dipublikasikan pada: ${date.toLocaleString('id-ID')}`;
            } else {
                statusIndicator.innerHTML = 'Akan dipublikasikan sekarang';
            }
        }
    });

    // Form Validation
    // PERBAIKAN: Hapus validasi untuk subtitle
    document.getElementById('articleForm').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const content = CKEDITOR.instances.content.getData().trim();
        const category = document.getElementById('category_id').value;
        const author = document.getElementById('author').value.trim();
        const info = document.getElementById('info').value.trim();
        const image = document.getElementById('image').value;
        const status = document.getElementById('status').value;

        // Validasi konten (hapus tag HTML untuk cek konten kosong)
        const contentText = content.replace(/<[^>]*>/g, '').trim();

        let errors = [];

        // HANYA validasi yang benar-benar required
        if (!title) errors.push('Judul artikel wajib diisi');
        if (!contentText) errors.push('Konten artikel wajib diisi');
        if (!category) errors.push('Kategori wajib dipilih');
        if (!author) errors.push('Penulis wajib diisi');
        if (!info) errors.push('Info penulis wajib diisi');

        // Jika status published, cek gambar
        if (status === 'published' && !image) {
            errors.push('Gambar utama wajib diisi untuk artikel yang dipublikasikan');
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert('Harap lengkapi data berikut:\n\n' + errors.join('\n'));
            return false;
        }

        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menyimpan...';
        submitBtn.disabled = true;

        return true;
    });

    // Character Counter for Meta Description
    const metaDesc = document.getElementById('meta_description');
    if (metaDesc) {
        // Initialize counter on page load
        updateCharCounter();

        metaDesc.addEventListener('input', updateCharCounter);

        function updateCharCounter() {
            const charCount = metaDesc.value.length;
            const counter = document.querySelector('.char-counter');

            if (counter) {
                counter.textContent = `${charCount}/160 karakter`;

                if (charCount > 160) {
                    counter.classList.remove('text-muted');
                    counter.classList.add('text-danger');
                    // Potong teks jika melebihi 160 karakter
                    metaDesc.value = metaDesc.value.substring(0, 160);
                } else {
                    counter.classList.remove('text-danger');
                    counter.classList.add('text-muted');
                }
            }
        }
    }

    // Set default published_at untuk status published
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const publishedAtInput = document.getElementById('published_at');

        if (statusSelect.value === 'published' && !publishedAtInput.value) {
            // Set waktu sekarang + 1 menit
            const now = new Date();
            now.setMinutes(now.getMinutes() + 1);
            publishedAtInput.value = now.toISOString().slice(0, 16);
        }
    });
</script>

<style>
    .char-counter {
        font-size: 0.875em;
    }

    .ck-editor__editable {
        min-height: 400px;
    }

    #imagePreview {
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 10px;
        background-color: #f8f9fa;
        margin-bottom: 15px;
    }

    /* Style untuk input caption */
    #image_caption {
        font-size: 0.875rem;
    }

    .img-thumbnail {
        border: 2px dashed #dee2e6;
        padding: 5px;
        max-width: 100%;
        height: auto;
    }

    .form-label.small {
        font-size: 0.875rem;
        font-weight: 500;
    }
</style>
@endsection