@extends('layouts.app')

@section('title', 'Edit Artikel')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-pencil-square me-2"></i>Edit Artikel</h2>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <!-- Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-8">
                                <!-- Judul -->
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-semibold">
                                        Judul Artikel <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        id="title"
                                        name="title"
                                        value="{{ old('title', $article->title) }}"
                                        class="form-control @error('title') is-invalid @enderror"
                                        required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Konten -->
                                <div class="mb-3">
                                    <label for="content" class="form-label fw-semibold">
                                        Konten <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="content"
                                        name="content"
                                        rows="15"
                                        class="form-control @error('content') is-invalid @enderror"
                                        required>{{ old('content', $article->content) }}</textarea>
                                    @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Meta Description -->
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label fw-semibold">Meta Description</label>
                                    <textarea id="meta_description"
                                        name="meta_description"
                                        rows="3"
                                        maxlength="160"
                                        class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $article->meta_description) }}</textarea>
                                    <small class="text-muted">Maksimal 160 karakter untuk SEO.</small>
                                    @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light fw-semibold">
                                        Pengaturan Artikel
                                    </div>
                                    <div class="card-body">
                                        <!-- Kategori -->
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                            <select id="category_id"
                                                name="category_id"
                                                class="form-select @error('category_id') is-invalid @enderror"
                                                required>
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
                                            <label for="author" class="form-label">Penulis <span class="text-danger">*</span></label>
                                            <input type="text"
                                                id="author"
                                                name="author"
                                                value="{{ old('author', $article->author) }}"
                                                class="form-control @error('author') is-invalid @enderror"
                                                required>
                                            @error('author')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Gambar -->
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Gambar Utama</label>

                                            <!-- Preview Gambar Saat Ini -->
                                            @if($article->image)
                                            <div class="mb-3 text-center">
                                                <img src="{{ asset('images/articles/' . $article->image) }}"
                                                    alt="Gambar Artikel"
                                                    class="img-thumbnail mb-2"
                                                    style="max-height: 150px;">

                                                <!-- Image Caption Saat Ini -->
                                                <div class="mb-2">
                                                    <label for="image_caption" class="form-label small">Keterangan Gambar (Caption)</label>
                                                    <input type="text"
                                                        id="image_caption"
                                                        name="image_caption"
                                                        value="{{ old('image_caption', $article->image_caption) }}"
                                                        class="form-control form-control-sm"
                                                        placeholder="Contoh: Sumber: Dok. Pribadi, atau credit fotografer...">

                                                </div>

                                                <!-- Checkbox Hapus Gambar -->
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                        id="remove_image"
                                                        name="remove_image"
                                                        value="1"
                                                        class="form-check-input"
                                                        onchange="toggleImageUpload(this)">
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

                                                <!-- Preview Gambar Baru -->
                                                <div id="newImagePreview" class="mt-2 text-center" style="display: none;">
                                                    <img id="previewImage"
                                                        class="img-thumbnail mb-2"
                                                        style="max-height: 150px;">

                                                    <!-- Image Caption untuk Gambar Baru -->
                                                    <div class="mb-2">
                                                        <label for="new_image_caption" class="form-label small">Keterangan Gambar Baru</label>
                                                        <input type="text"
                                                            id="new_image_caption"
                                                            name="image_caption"
                                                            value="{{ old('image_caption', $article->image_caption) }}"
                                                            class="form-control form-control-sm"
                                                            placeholder="Contoh: Sumber: Dok. Pribadi, atau credit fotografer...">
                                                        <small class="text-muted">Opsional: Berikan credit/sumber foto</small>
                                                    </div>

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="removeNewImage()">
                                                        <i class="bi bi-x-circle me-1"></i>Hapus Gambar Baru
                                                    </button>
                                                </div>
                                            </div>

                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Format: JPEG, PNG, JPG, GIF, WebP. Maksimal 2MB</small>
                                        </div>

                                        <!-- Link YouTube -->
                                        <div class="mb-3">
                                            <label for="video_url" class="form-label">Link YouTube (Opsional)</label>
                                            <input
                                                type="url"
                                                id="video_url"
                                                name="video_url"
                                                placeholder="https://www.youtube.com/watch?v=xxxxxx"
                                                value="{{ old('video_url', $article->video_url ?? '') }}"
                                                class="form-control @error('video_url') is-invalid @enderror">
                                            <small class="text-muted">Masukkan link video YouTube jika ada.</small>
                                            @error('video_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        <!-- Tanggal Publikasi -->
                                        <div class="mb-3">
                                            <label for="published_at" class="form-label">Tanggal Publikasi</label>
                                            <input type="datetime-local"
                                                id="published_at"
                                                name="published_at"
                                                value="{{ old('published_at', optional($article->published_at)->format('Y-m-d\TH:i')) }}"
                                                class="form-control @error('published_at') is-invalid @enderror">
                                            <small class="text-muted">Kosongkan untuk menyimpan sebagai draft.</small>
                                            @error('published_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tags -->
                                        <div class="mb-3">
                                            <label for="tags" class="form-label">Tags</label>
                                            <input type="text"
                                                id="tags"
                                                name="tags"
                                                value="{{ old('tags', $article->tags ? implode(', ', json_decode($article->tags, true)) : '') }}"
                                                class="form-control @error('tags') is-invalid @enderror">
                                            <small class="text-muted">Pisahkan dengan koma, contoh: teknologi, web, berita</small>
                                            @error('tags')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>Update Artikel
                                    </button>
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
    CKEDITOR.replace('content', {
        toolbar: [{
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat']
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
        height: 400
    });
</script>
@endpush