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
        ? asset('public/images/articles/' . $news->image)
        : asset('storage/news/' . $news->image)
}}"
                                                    alt="{{ $news->title }}"
                                                    class="img-fluid rounded"
                                                    alt="Gambar Berita"
                                                    class="img-thumbnail mb-2"
                                                    style="max-height: 150px;">

                                                <!-- Checkbox Hapus Gambar -->

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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<!-- Gunakan CDN yang lebih update -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const wrapper = document.getElementById('contents-wrapper');
        const form = document.getElementById('newsForm');
        let index = wrapper.querySelectorAll('.content-item').length;
        let activeEditor = null;

        // ==============================
        // SET ACTIVE EDITOR
        // ==============================
        document.addEventListener('focusin', function(e) {
            if (e.target.classList.contains('editor')) {
                activeEditor = e.target;
            }
        });

        // ==============================
        // ADD BUTTON LISTENER
        // ==============================
        document.getElementById('addText').addEventListener('click', () => addContent('text'));
        document.getElementById('addImage').addEventListener('click', () => addContent('image'));
        document.getElementById('addVideo').addEventListener('click', () => addContent('video'));
        document.getElementById('addRelated').addEventListener('click', () => addContent('related'));

        // ==============================
        // ADD CONTENT
        // ==============================
        function addContent(type) {

            let html = '';

            // ===== TEXT =====
            if (type === 'text') {
                html = `
            <div class="content-item border rounded p-3 mb-3 bg-light">
                <input type="hidden" name="contents[${index}][type]" value="text">

                <div class="editor-container border mb-2">
                    <div class="toolbar bg-light p-2 border-bottom d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-command="bold"><b>B</b></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-command="italic"><i>I</i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-command="insertOrderedList">1.</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-command="insertUnorderedList">•</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary add-link">🔗</button>
                        
                    </div>

                    <div class="editor article-content p-2"
                        contenteditable="true"
                        style="min-height:150px; outline:none;"></div>
                </div>

                <input type="hidden" name="contents[${index}][text]" class="editor-input">

                <button type="button" class="btn btn-sm btn-outline-danger remove-content">
                    Hapus
                </button>
            </div>`;
            }

            // ===== IMAGE =====
            if (type === 'image') {
                html = `
            <div class="content-item border rounded p-3 mb-3 bg-light">
                <input type="hidden" name="contents[${index}][type]" value="image">

                <input type="file"
                    name="contents[${index}][image]"
                    class="form-control mb-2 image-input"
                    accept="image/*">

                <img class="img-fluid rounded mb-2 d-none preview-img" style="max-height:200px;">

                <input type="text"
                    name="contents[${index}][caption]"
                    class="form-control mb-2"
                    placeholder="Keterangan gambar">

                <button type="button" class="btn btn-sm btn-outline-danger remove-content">
                    Hapus
                </button>
            </div>`;
            }

            // ===== VIDEO =====
            if (type === 'video') {
                html = `
            <div class="content-item border rounded p-3 mb-3 bg-light">
                <input type="hidden" name="contents[${index}][type]" value="video">

                <input type="file"
                    name="contents[${index}][video]"
                    class="form-control mb-2"
                    accept="video/mp4,video/webm">

                <div class="text-center my-2">— atau —</div>

                <input type="url"
                    name="contents[${index}][youtube_url]"
                    class="form-control mb-2"
                    placeholder="https://youtube.com/watch?v=...">

                <input type="text"
                    name="contents[${index}][caption]"
                    class="form-control mb-2"
                    placeholder="Caption video">

                <button type="button" class="btn btn-sm btn-outline-danger remove-content">
                    Hapus
                </button>
            </div>`;
            }

            // ===== BACA JUGA =====
            if (type === 'related') {
                html = `
            <div class="content-item border rounded p-3 mb-3 bg-light">
                <input type="hidden" name="contents[${index}][type]" value="related">

                <input type="text"
                    name="contents[${index}][related_title]"
                    class="form-control mb-2"
                    placeholder="Judul Berita">

                    
                <input type="text"
                    name="contents[${index}][related_url]"
                    class="form-control mb-2"
                    placeholder="Link atau Url">

                <button type="button" class="btn btn-sm btn-outline-danger remove-content">
                    Hapus
                </button>
            </div>`;
            }

            wrapper.insertAdjacentHTML('beforeend', html);
            index++;
        }

        // ==============================
        // TOOLBAR + REMOVE (SINGLE EVENT)
        // ==============================
        document.addEventListener('click', function(e) {

            // REMOVE CONTENT
            if (e.target.closest('.remove-content')) {
                e.target.closest('.content-item').remove();
            }

            // FORMAT BUTTON
            if (e.target.dataset.command) {
                e.preventDefault();
                if (!activeEditor) return;

                activeEditor.focus();
                document.execCommand(e.target.dataset.command, false, null);
            }

            // LINK
            if (e.target.classList.contains('add-link')) {
                e.preventDefault();
                if (!activeEditor) return;

                const url = prompt("Masukkan URL:");
                if (url) {
                    activeEditor.focus();
                    document.execCommand("createLink", false, url);
                }
            }

            // IMAGE URL
            if (e.target.classList.contains('add-image')) {
                e.preventDefault();
                if (!activeEditor) return;

                const url = prompt("Masukkan URL gambar:");
                if (url) {
                    activeEditor.focus();
                    document.execCommand("insertImage", false, url);
                }
            }

            // TABLE
            if (e.target.classList.contains('add-table')) {
                e.preventDefault();
                if (!activeEditor) return;

                const rows = prompt("Jumlah baris:", 2);
                const cols = prompt("Jumlah kolom:", 2);

                if (rows && cols) {
                    let table = "<table border='1' style='border-collapse:collapse;width:100%;'>";
                    for (let i = 0; i < rows; i++) {
                        table += "<tr>";
                        for (let j = 0; j < cols; j++) {
                            table += "<td>&nbsp;</td>";
                        }
                        table += "</tr>";
                    }
                    table += "</table><br>";

                    activeEditor.focus();
                    document.execCommand("insertHTML", false, table);
                }
            }
        });

        // ==============================
        // IMAGE PREVIEW
        // ==============================
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('image-input')) {
                const file = e.target.files[0];
                const preview = e.target.closest('.content-item').querySelector('.preview-img');

                if (file) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('d-none');
                }
            }
        });

        // ==============================
        // SUBMIT FORM
        // ==============================
        form.addEventListener('submit', function() {
            document.querySelectorAll('.editor').forEach(function(editor) {
                const hiddenInput = editor.closest('.content-item')
                    .querySelector('.editor-input');

                hiddenInput.value = editor.innerHTML;
            });
        });

    });
</script>
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const img = document.getElementById('previewImg');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validasi type
            if (!file.type.startsWith('image/')) {
                alert("File harus berupa gambar");
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        const input = document.getElementById('image');
        const preview = document.getElementById('imagePreview');
        const img = document.getElementById('previewImg');

        input.value = '';
        img.src = '';
        preview.style.display = 'none';
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const wrapper = document.getElementById('contents-wrapper');

        new Sortable(wrapper, {
            animation: 200,
            ghostClass: 'sortable-ghost',

            onEnd: function() {
                updatePositions();
            }
        });

        function updatePositions() {

            document.querySelectorAll('#contents-wrapper .content-item')
                .forEach((item, index) => {

                    // update hidden position
                    let positionInput = item.querySelector('.position-input');
                    if (positionInput) {
                        positionInput.value = index + 1;
                    }

                    // update name attribute
                    item.querySelectorAll('input, textarea, select').forEach(el => {

                        if (el.name) {
                            el.name = el.name.replace(/contents\[\d+\]/, `contents[${index}]`);
                        }

                    });

                });

        }

    });
</script>
<script>
    function previewNewImage(input) {

        const previewContainer = document.getElementById('newImagePreview');
        const previewImage = document.getElementById('previewImage');

        if (input.files && input.files[0]) {

            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeNewImage() {

        const input = document.getElementById('image');
        const previewContainer = document.getElementById('newImagePreview');
        const previewImage = document.getElementById('previewImage');

        input.value = '';
        previewImage.src = '';
        previewContainer.style.display = 'none';
    }
</script>
@endpush