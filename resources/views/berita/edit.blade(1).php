@extends('layouts.app')

@section('title', 'Edit Berita')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-pencil-square me-2"></i>Edit Berita</h2>
                <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                </a>
            </div>

            <!-- Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Form Edit -->
                    <form action="{{ route('berita.update', $news->id) }}" method="POST" enctype="multipart/form-data" id="newsForm">
                        @csrf
                        @method('PUT')

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
                                        value="{{ old('title', $news->title) }}"
                                        class="form-control @error('title') is-invalid @enderror"
                                        required
                                        maxlength="255"
                                        placeholder="Masukkan judul berita">
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Konten -->
                                @include('berita._contents-edit')

                                <!-- Meta Description -->
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description (Opsional)</label>
                                    <textarea id="meta_description"
                                        name="meta_description"
                                        rows="3"
                                        maxlength="160"
                                        class="form-control @error('meta_description') is-invalid @enderror"
                                        placeholder="Deskripsi untuk SEO...">{{ old('meta_description', $news->meta_description) }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted">Maksimal 160 karakter untuk SEO.</small>
                                        <span id="metaCounter" class="badge bg-secondary">{{ strlen(old('meta_description', $news->meta_description)) }}/160</span>
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
                                            <select name="category_id" id="category_id" class="form-select">
                                                <option value="">Pilih Kategori</option>

                                                @foreach($categories as $parent)
                                                <optgroup label="{{ $parent->name }}">
                                                    @foreach($parent->children as $child)
                                                    <option value="{{ $child->id }}"
                                                        {{ old('category_id', $news->category_id) == $child->id ? 'selected' : '' }}>
                                                        {{ $child->name }}
                                                    </option>
                                                    @endforeach
                                                </optgroup>
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
                                                value="{{ old('author', $news->author) }}"
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
                                            @if($news->image)
                                            <div class="mb-3 text-center" id="currentImageSection">
                                                <img src="{{ 
    file_exists(public_path('images/articles/' . $news->image))
        ? asset('images/articles/' . $news->image)
        : asset('storage/news/' . $news->image)
}}"
                                                    alt="{{ $news->title }}"
                                                    class="img-fluid rounded"
                                                    alt="Gambar Berita"
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
                                            <div id="imageUploadSection" class="{{ $news->image ? 'mt-3' : '' }}">
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
                                                placeholder="Masukkan deskripsi singkat berita (maksimal 500 karakter)...">{{ old('keterangan', $news->keterangan) }}</textarea>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <small class="text-muted">Deskripsi singkat yang akan muncul di halaman daftar berita.</small>
                                                <span id="keteranganCounter" class="badge bg-secondary">{{ strlen(old('keterangan', $news->keterangan)) }}/500</span>
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
                                                value="{{ old('video_url', $news->video_url) }}"
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
                                                <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="archived" {{ old('status', $news->status) == 'archived' ? 'selected' : '' }}>Arsip</option>
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tanggal Publikasi -->
                                        <div class="mb-3" id="publishDateSection" style="{{ old('status', $news->status) == 'published' ? '' : 'display: none;' }}">
                                            <label for="publish_at" class="form-label">Tanggal Publikasi</label>
                                            <input type="datetime-local"
                                                id="publish_at"
                                                name="publish_at"
                                                value="{{ old('publish_at', $news->publish_at ? $news->publish_at->format('Y-m-d\TH:i') : '') }}"
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
                                                value="{{ old('tags', $news->tags ? implode(', ', $news->tags) : '') }}"
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
                                <input type="hidden" name="deleted_contents" id="deleted_contents">

                                <!-- Tombol Aksi -->
                                <div class="d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary" onclick="return beforeSubmit()">
                                        <i class="bi bi-check-circle me-1"></i>Update Berita
                                    </button>
                                    <a href="{{ route('berita.show', $news->slug) }}" class="btn btn-outline-info" target="_blank">
                                        <i class="bi bi-eye me-1"></i>Preview
                                    </a>
                                    <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary">
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
<!-- Gunakan CDN yang lebih update -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        let index = Date.now(); // ✅ index unik, tidak tabrakan walau hapus-tambah

        function initCKEditor(textarea) {
            if (!textarea.id) {
                textarea.id = 'editor_' + Math.random().toString(36).substr(2, 9);
            }
            if (!CKEDITOR.instances[textarea.id]) {
                CKEDITOR.replace(textarea.id, {
                    height: 200
                });
            }
        }

        document.querySelectorAll('.ckeditor').forEach(initCKEditor);

        document.getElementById('addText').addEventListener('click', () => addContent('text'));
        document.getElementById('addImage').addEventListener('click', () => addContent('image'));
        document.getElementById('addVideo').addEventListener('click', () => addContent('video'));

        function addContent(type) {
            const wrapper = document.getElementById('contents-wrapper');
            let html = '';

            const currentIndex = index++;

            if (type === 'text') {
                html = `
            <div class="content-item border rounded p-3 mb-3 bg-light">
                <input type="hidden" name="contents[${currentIndex}][type]" value="text">
                <textarea name="contents[${currentIndex}][text]" class="form-control ckeditor mb-2" rows="4"></textarea>
                <button type="button" class="btn btn-sm btn-outline-danger remove-content">Hapus</button>
            </div>`;
            }

            if (type === 'image') {
                html = `
            <div class="content-item border rounded p-3 mb-3 bg-light">
                <input type="hidden" name="contents[${currentIndex}][type]" value="image">
                <input type="file" name="contents[${currentIndex}][image]" class="form-control mb-2">
                <input type="text" name="contents[${currentIndex}][caption]" class="form-control mb-2" placeholder="Caption">
                <button type="button" class="btn btn-sm btn-outline-danger remove-content">Hapus</button>
            </div>`;
            }

            if (type === 'video') {
                html = `
            <div class="content-item border rounded p-3 mb-3 bg-light">
                <input type="hidden" name="contents[${currentIndex}][type]" value="video">

                <label class="form-label">Upload Video (opsional)</label>
                <input type="file" name="contents[${currentIndex}][video]" class="form-control mb-2" accept="video/mp4,video/webm">

                <div class="text-center fw-bold mb-2">— ATAU —</div>

                <label class="form-label">YouTube URL</label>
                <input type="url" name="contents[${currentIndex}][youtube_url]" class="form-control mb-2" placeholder="https://youtube.com/watch?v=...">

                <input type="text" name="contents[${currentIndex}][caption]" class="form-control mb-2" placeholder="Caption">

                <button type="button" class="btn btn-sm btn-outline-danger remove-content">Hapus</button>
            </div>`;
            }

            wrapper.insertAdjacentHTML('beforeend', html);

            // init CKEditor hanya untuk textarea baru
            wrapper.querySelectorAll('.ckeditor').forEach(initCKEditor);
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-content')) {
                e.target.closest('.content-item').remove();
            }
        });

    });
</script>

<script>
    document.getElementById('newsForm').addEventListener('submit', function() {
        for (let instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement(); // ✅ paksa sync ke textarea
        }
    });
</script>
@endpush