@extends('layouts.app')

@section('title', 'Tambah Berita Baru')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-plus-circle me-2"></i>Tambah Berita Baru</h2>
                <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <!-- Card -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data" id="newsForm">
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

                                <!-- Konten Dinamis -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        Konten Berita <span class="text-danger">*</span>
                                    </label>
                                    @include('berita._contents-form')
                                    @error('contents')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
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
                                            <select name="category_id" id="category_id" class="form-select">
                                                <option value="">Pilih Kategori</option>
                                                @foreach($categories as $parent)
                                                <optgroup label="{{ $parent->name }}">
                                                    @foreach($parent->children as $child)
                                                    <option value="{{ $child->id }}"
                                                        {{ old('category_id') == $child->id ? 'selected' : '' }}>
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
                                                class="form-control @error('author') is-invalid @enderror"
                                                value="{{ old('author', auth()->user()->name ?? '') }}"
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

                                            <div id="imagePreview" class="mt-2 text-center" style="display: none;">
                                                <img id="previewImg" class="img-thumbnail mb-2" style="max-height: 150px;">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                                    <i class="bi bi-x-circle me-1"></i>Hapus Gambar
                                                </button>
                                            </div>
                                            <small class="text-muted d-block mt-1">Format: JPEG, PNG, JPG, GIF, WebP. Maksimal 2MB</small>
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
                                                placeholder="Masukkan deskripsi singkat berita (maksimal 500 karakter)...">{{ old('keterangan') }}</textarea>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <small class="text-muted">Deskripsi singkat yang akan muncul di halaman daftar berita.</small>
                                                <span id="keteranganCounter" class="badge bg-secondary">0/500</span>
                                            </div>
                                            @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const wrapper = document.getElementById("contents-wrapper");
        const form = document.getElementById("newsForm");

        let index = wrapper ? wrapper.querySelectorAll(".content-item").length : 0;
        let activeEditor = null;
        let savedRange = null;

        /* =========================
           ACTIVE EDITOR & SELECTION
        ========================= */
        document.addEventListener("focusin", function(e) {
            if (e.target.classList.contains("editor")) {
                activeEditor = e.target;
            }
        });

        document.addEventListener("mouseup", saveSelection);
        document.addEventListener("keyup", saveSelection);

        function saveSelection() {
            if (!activeEditor) return;
            const sel = window.getSelection();
            if (sel.rangeCount > 0) {
                savedRange = sel.getRangeAt(0);
            }
        }

        function restoreSelection() {
            if (!savedRange) return;
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(savedRange);
        }

        /* =========================
           ADD BUTTON LISTENERS
        ========================= */
        document.getElementById("addText")?.addEventListener("click", () => addContent("text"));
        document.getElementById("addImage")?.addEventListener("click", () => addContent("image"));
        document.getElementById("addVideo")?.addEventListener("click", () => addContent("video"));
        document.getElementById("addRelated")?.addEventListener("click", () => addContent("related"));

        /* =========================
           ADD CONTENT BLOCK FUNCTION
        ========================= */
        function addContent(type) {
            let html = "";

            /* TEXT BLOCK */
            if (type === "text") {
                html = `
                <div class="content-item border rounded p-3 mb-3 bg-light">
                    <input type="hidden" name="contents[${index}][type]" value="text">
                    <input type="hidden" name="contents[${index}][position]" class="position-input">

                    <div class="editor-container border mb-2">
                        <div class="toolbar bg-light p-2 border-bottom d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-command="bold"><b>B</b></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-command="italic"><i>I</i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-command="underline"><u>U</u></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-command="insertOrderedList">1.</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-command="insertUnorderedList">•</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary add-link">🔗</button>
                        </div>
                        <div class="editor article-content p-2" contenteditable="true" style="min-height:150px;outline:none;"></div>
                    </div>

                    <input type="hidden" name="contents[${index}][text]" class="editor-input">

                    <button type="button" class="btn btn-sm btn-outline-danger remove-content">Hapus</button>
                </div>`;
            }

            /* IMAGE BLOCK */
            if (type === "image") {
                html = `
                <div class="content-item border rounded p-3 mb-3 bg-light">
                    <input type="hidden" name="contents[${index}][type]" value="image">
                    <input type="hidden" name="contents[${index}][position]" class="position-input">

                    <input type="file" name="contents[${index}][image]" class="form-control mb-2 image-input" accept="image/*">
                    <img class="img-fluid rounded mb-2 d-none preview-img" style="max-height:200px;">

                    <input type="text" name="contents[${index}][caption]" class="form-control mb-2" placeholder="Keterangan gambar">

                    <button type="button" class="btn btn-sm btn-outline-danger remove-content">Hapus</button>
                </div>`;
            }

            /* VIDEO BLOCK */
            if (type === "video") {
                html = `
                <div class="content-item border rounded p-3 mb-3 bg-light">
                    <input type="hidden" name="contents[${index}][type]" value="video">
                    <input type="hidden" name="contents[${index}][position]" class="position-input">

                    <h6 class="fw-bold mb-3">🎥 Video Block</h6>

                    <!-- Upload Video -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Video</label>
                        <input type="file" name="contents[${index}][video]" class="form-control mb-2 video-input" accept="video/mp4,video/webm,video/ogg,video/quicktime">
                        <small class="text-muted d-block">Format: MP4, MOV, WEBM, OGG</small>
                    </div>

                    <!-- Preview -->
                    <div class="text-center mb-3">
                        <video class="video-preview rounded border d-none w-100" controls style="background:#000; margin: 0 auto; display: block; height: 350px; object-fit: cover;"></video>
                    </div>

                    <div class="text-center my-3 text-muted"><strong>- ATAU -</strong></div>

                    <!-- Youtube -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Youtube URL</label>
                        <input type="url" name="contents[${index}][youtube_url]" class="form-control" placeholder="https://youtube.com/watch?v=xxxxx">
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Pengaturan Tampilan Video</h6>

                    <div class="row g-3">
                        <!-- Rasio -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rasio Video</label>
                            <select name="contents[${index}][video_orientation]" class="form-select video-orientation">
                                <option value="landscape" selected>Landscape (16:9)</option>
                                <option value="portrait">Portrait (9:16)</option>
                            </select>
                        </div>

                        <!-- Posisi -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Posisi Alignment</label>
                            <select name="contents[${index}][video_align]" class="form-select video-align">
                                <option value="left">Kiri</option>
                                <option value="center" selected>Tengah</option>
                                <option value="right">Kanan</option>
                            </select>
                        </div>

                        <!-- Lebar Slider -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Lebar Video: <span class="video-width-label">100%</span>
                            </label>
                            <input type="range" min="30" max="100" value="100"
                                name="contents[${index}][video_width]"
                                class="form-range video-width-slider">
                        </div>

                        <!-- Tinggi Slider (DITAMBAHKAN) -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Tinggi Video: <span class="video-height-label">350px</span>
                            </label>
                            <input type="range" min="150" max="800" step="10" value="350"
                                name="contents[${index}][video_height]"
                                class="form-range video-height-slider">
                        </div>

                        <!-- Radius Slider -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Sudut Video (Radius): <span class="video-radius-label">12px</span>
                            </label>
                            <input type="range" min="0" max="40" value="12"
                                name="contents[${index}][video_radius]"
                                class="form-range video-radius-slider">
                        </div>

                        <!-- Caption -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Caption Video</label>
                            <input type="text" name="contents[${index}][caption]" class="form-control" placeholder="Caption Video">
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-outline-danger remove-content">
                            <i class="bi bi-trash me-1"></i>Hapus Block Video
                        </button>
                    </div>
                </div>`;
            }

            /* RELATED BLOCK */
            if (type === "related") {
                html = `
                <div class="content-item border rounded p-3 mb-3 bg-light">
                    <input type="hidden" name="contents[${index}][type]" value="related">
                    <input type="hidden" name="contents[${index}][position]" class="position-input">

                    <input type="text" name="contents[${index}][related_title]" class="form-control mb-2" placeholder="Judul Berita">
                    <input type="text" name="contents[${index}][related_url]" class="form-control mb-2" placeholder="Link atau URL">

                    <button type="button" class="btn btn-sm btn-outline-danger remove-content">Hapus</button>
                </div>`;
            }

            wrapper.insertAdjacentHTML("beforeend", html);
            index++;
            updatePositions();
        }

        /* =========================
           VIDEO PREVIEW & INPUT EVENT
        ========================= */
        document.addEventListener("change", function(e) {
            if (!e.target.classList.contains("video-input")) return;
            const file = e.target.files[0];
            if (!file) return;

            const item = e.target.closest(".content-item");
            const preview = item ? item.querySelector(".video-preview") : null;

            if (preview) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove("d-none");

                preview.onloadedmetadata = function() {
                    const ratio = this.videoWidth / this.videoHeight;
                    if (ratio < 1) {
                        preview.style.maxWidth = "380px";
                    } else {
                        preview.style.maxWidth = "100%";
                    }
                };
            }
        });

        // Event Slider Listener (Lebar, Tinggi, dan Radius)
        document.addEventListener("input", function(e) {
            const slider = e.target;

            if (
                slider.classList.contains("video-width-slider") ||
                slider.classList.contains("video-height-slider") ||
                slider.classList.contains("video-radius-slider")
            ) {
                const item = slider.closest(".content-item");
                if (!item) return;

                // Mengambil semua tag video (baik video tersimpan maupun preview upload)
                const videos = item.querySelectorAll("video");

                // Update Slider Lebar
                if (slider.classList.contains("video-width-slider")) {
                    const val = slider.value;
                    const label = item.querySelector(".video-width-label") || item.querySelector(".video-width-text");
                    if (label) label.textContent = val + "%";
                    videos.forEach(v => v.style.width = val + "%");
                }

                // Update Slider Tinggi
                if (slider.classList.contains("video-height-slider")) {
                    const val = slider.value;
                    const label = item.querySelector(".video-height-label") || item.querySelector(".video-height-text");
                    if (label) label.textContent = val + "px";
                    videos.forEach(v => v.style.height = val + "px");
                }

                // Update Slider Radius
                if (slider.classList.contains("video-radius-slider")) {
                    const val = slider.value;
                    const label = item.querySelector(".video-radius-label") || item.querySelector(".radius-text");
                    if (label) label.textContent = val + "px";
                    videos.forEach(v => v.style.borderRadius = val + "px");
                }

                if (typeof updateSliderProgress === "function") {
                    updateSliderProgress(slider);
                }
            }
        });

        // Event Change Listener (Alignment/Posisi)
        document.addEventListener("change", function(e) {
            if (!e.target.classList.contains("video-align")) return;

            const item = e.target.closest(".content-item");
            if (!item) return;

            const preview = item.querySelector(".video-preview");
            if (!preview) return;

            switch (e.target.value) {
                case "left":
                    preview.style.display = "block";
                    preview.style.margin = "0 auto 0 0";
                    break;
                case "center":
                    preview.style.display = "block";
                    preview.style.margin = "0 auto";
                    break;
                case "right":
                    preview.style.display = "block";
                    preview.style.margin = "0 0 0 auto";
                    break;
            }
        });

        function updateSliderProgress(slider) {
            const min = parseFloat(slider.min) || 0;
            const max = parseFloat(slider.max) || 100;
            const val = parseFloat(slider.value) || 0;
            const percent = ((val - min) / (max - min)) * 100;

            slider.style.setProperty('--progress', percent + '%');
            slider.style.background = `linear-gradient(to right, #0d6efd 0%, #0d6efd ${percent}%, #e9ecef ${percent}%, #e9ecef 100%)`;
        }

        /* =========================
           TOOLBAR & REMOVE HANDLERS
        ========================= */
        document.addEventListener("click", function(e) {
            const button = e.target.closest("button");
            if (!button) return;

            if (button.dataset.command) {
                e.preventDefault();
                if (!activeEditor) return;
                activeEditor.focus();
                restoreSelection();
                document.execCommand(button.dataset.command, false, null);
                saveSelection();
            }

            if (button.classList.contains("add-link")) {
                e.preventDefault();
                if (!activeEditor) return;
                const url = prompt("Masukkan URL");
                if (url) {
                    activeEditor.focus();
                    restoreSelection();
                    document.execCommand("createLink", false, url);
                }
            }

            if (button.classList.contains("remove-content")) {
                button.closest(".content-item").remove();
                updatePositions();
            }
        });

        /* =========================
           IMAGE PREVIEW
        ========================= */
        document.addEventListener("change", function(e) {
            if (e.target.classList.contains("image-input")) {
                const file = e.target.files[0];
                const preview = e.target.closest(".content-item").querySelector(".preview-img");
                if (file && preview) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove("d-none");
                }
            }
        });

        /* =========================
           SAVE EDITOR CONTENT BEFORE SUBMIT
        ========================= */
        form?.addEventListener("submit", function() {
            document.querySelectorAll(".editor").forEach(function(editor) {
                const hidden = editor.closest(".content-item").querySelector(".editor-input");
                if (hidden) {
                    hidden.value = editor.innerHTML;
                }
            });
        });

        /* =========================
           SORTABLE
        ========================= */
        if (typeof Sortable !== "undefined" && wrapper) {
            new Sortable(wrapper, {
                animation: 200,
                ghostClass: "sortable-ghost",
                onEnd: function() {
                    updatePositions();
                }
            });
        }

        function updatePositions() {
            const items = wrapper.querySelectorAll(".content-item");
            items.forEach((item, i) => {
                const pos = item.querySelector(".position-input");
                if (pos) pos.value = i + 1;
            });
        }

        /* =========================
           PUBLISH DATE SECTION TOGGLE
        ========================= */
        const statusSelect = document.getElementById("status");
        const publishSection = document.getElementById("publishDateSection");
        const publishInput = document.getElementById("publish_at");

        function togglePublishDate() {
            if (!statusSelect) return;
            if (statusSelect.value === "published") {
                publishSection.style.display = "block";
                if (!publishInput.value) {
                    const now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                    publishInput.value = now.toISOString().slice(0, 16);
                }
            } else {
                publishSection.style.display = "none";
                publishInput.value = "";
            }
        }

        statusSelect?.addEventListener("change", togglePublishDate);
        togglePublishDate();
    });
</script>
@endpush