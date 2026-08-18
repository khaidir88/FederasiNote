@php
$contents = isset($news) ? $news->contents->sortBy('position')->values() : collect();
@endphp

<style>
    .block-editor {
        background: #f8f9fa;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
        transition: all 0.2s ease;
    }

    .block-editor:hover {
        border-color: #4e73df;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .drag-handle {
        cursor: grab;
        font-weight: bold;
        color: #858796;
        user-select: none;
    }

    .editor {
        min-height: 120px;
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 6px;
        outline: none;
    }

    .editor:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>

<div id="contents-wrapper" class="sortable">
    @foreach($contents as $i => $content)
    <div class="content-item block-editor" data-type="{{ $content->type }}">
        <div class="block-header d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <span class="drag-handle fs-5">⋮⋮</span>
                <span class="badge bg-primary text-uppercase block-type">
                    {{ $content->type }}
                </span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-content" data-id="{{ $content->id ?? '' }}">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </div>

        <input type="hidden" name="contents[{{ $i }}][id]" value="{{ $content->id }}">
        <input type="hidden" name="contents[{{ $i }}][type]" value="{{ $content->type }}">
        <input type="hidden" name="contents[{{ $i }}][position]" class="position-input" value="{{ $i+1 }}">

        {{-- ================= TEXT ================= --}}
        @if($content->type === 'text')
        <div class="editor-toolbar mb-2 btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary btn-cmd" data-command="bold"><b>B</b></button>
            <button type="button" class="btn btn-outline-secondary btn-cmd" data-command="italic"><i>I</i></button>
            <button type="button" class="btn btn-outline-secondary btn-cmd" data-command="insertOrderedList">1.</button>
            <button type="button" class="btn btn-outline-secondary btn-cmd" data-command="insertUnorderedList">•</button>
            <button type="button" class="btn btn-outline-secondary add-link">🔗 Link</button>
        </div>

        {{-- PERBAIKAN: Mendukung $content->text maupun $content->content --}}
        <div class="editor article-content p-2" contenteditable="true">{!! $content->text ?? $content->content !!}</div>
        <input type="hidden" name="contents[{{ $i }}][text]" class="editor-input" value="{{ $content->text ?? $content->content }}">
        @endif

        {{-- ================= IMAGE ================= --}}
        @if($content->type === 'image')
        @if($content->image_path)
        <div class="mb-2">
            <img src="{{ asset('images/articles/'.$content->image_path) }}" class="img-fluid rounded mb-2 style-preview" style="max-height: 200px;">
        </div>
        @endif

        <div class="mb-2">
            <label class="form-label fw-semibold">Pilih Gambar</label>
            <input type="file" name="contents[{{ $i }}][image]" class="form-control image-input" accept="image/*">
        </div>

        <div>
            <label class="form-label fw-semibold">Caption Gambar</label>
            <input type="text" name="contents[{{ $i }}][caption]" value="{{ $content->caption }}" class="form-control" placeholder="Keterangan gambar">
        </div>
        @endif

        {{-- ================= VIDEO ================= --}}
        @if($content->type === 'video')
        @if($content->video_path)
        <div class="mb-3">
            <video class="w-100 rounded" controls style="max-height: 300px; max-width: {{ $content->video_width ?? 100 }}%; border-radius: {{ $content->video_radius ?? 12 }}px;">
                <source src="{{ asset('videos/articles/'.$content->video_path) }}" type="video/mp4">
            </video>
        </div>
        @endif

        <div class="mb-3">
            <label class="form-label fw-bold">Upload Video Baru</label>
            <input type="file" name="contents[{{ $i }}][video]" class="form-control video-input" accept="video/mp4,video/webm,video/quicktime">
            <small class="text-muted">Format: MP4, MOV, WEBM</small>
        </div>

        <video class="video-preview rounded border w-100 d-none mb-3" controls style="max-height: 300px;"></video>
        <hr>

        <h6 class="fw-bold mb-3">Pengaturan Tampilan Video</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Rasio Video</label>
                <select class="form-select video-orientation" name="contents[{{ $i }}][video_orientation]">
                    <option value="landscape" {{ ($content->video_orientation ?? 'landscape') === 'landscape' ? 'selected' : '' }}>Landscape (16:9)</option>
                    <option value="portrait" {{ ($content->video_orientation ?? '') === 'portrait' ? 'selected' : '' }}>Portrait (9:16)</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Lebar Video: <span class="video-width-text">{{ $content->video_width ?? 100 }}%</span>
                </label>
                <input type="range" min="30" max="100" value="{{ $content->video_width ?? 100 }}" name="contents[{{ $i }}][video_width]" class="form-range video-width-slider">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Tinggi Video: <span class="video-height-text">{{ $content->video_height ?? 800 }}px</span>
                </label>
                <input type="range" min="150" max="800" value="{{ $content->video_height ?? 800 }}" name="contents[{{ $i }}][video_height]" class="form-range video-height-slider">
            </div>

            <div class="col-md-4">
                <label class="form-label">Posisi Alignment</label>
                <select name="contents[{{ $i }}][video_align]" class="form-select video-align">
                    <option value="left" {{ ($content->video_align ?? '') === 'left' ? 'selected' : '' }}>Kiri</option>
                    <option value="center" {{ ($content->video_align ?? 'center') === 'center' ? 'selected' : '' }}>Tengah</option>
                    <option value="right" {{ ($content->video_align ?? '') === 'right' ? 'selected' : '' }}>Kanan</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Sudut Video (Border Radius: <span class="radius-text">{{ $content->video_radius ?? 12 }}</span>px)</label>
                <input type="range" min="0" max="40" value="{{ $content->video_radius ?? 12 }}" class="form-range video-radius-slider">
                <input type="hidden" name="contents[{{ $i }}][video_radius]" value="{{ $content->video_radius ?? 12 }}" class="video-radius-input">
            </div>

            <div class="col-md-6">
                <label class="form-label">Youtube URL</label>
                <input type="url" name="contents[{{ $i }}][youtube_url]" value="{{ $content->youtube_url ?? '' }}" class="form-control" placeholder="https://youtube.com/watch?v=xxxxx">
            </div>

            <div class="col-12">
                <label class="form-label">Caption Video</label>
                <input type="text" name="contents[{{ $i }}][caption]" value="{{ $content->caption }}" class="form-control" placeholder="Caption Video">
            </div>
        </div>
        @endif

        {{-- ================= RELATED ================= --}}
        @if($content->type === 'related')
        <div class="border-start border-3 border-primary ps-3">
            <label class="fw-bold text-primary mb-2">Baca juga:</label>
            <input type="text" name="contents[{{ $i }}][related_title]" value="{{ $content->related_title }}" class="form-control mb-2" placeholder="Judul berita">
            <input type="text" name="contents[{{ $i }}][related_url]" value="{{ $content->related_url }}" class="form-control" placeholder="URL berita">
        </div>
        @endif
    </div>
    @endforeach
</div>

{{-- BUTTON ADD BLOCK --}}
<div class="d-flex gap-2 mt-3 mb-5">
    <button type="button" class="btn btn-outline-primary" id="addText"><i class="bi bi-file-text"></i> + Text</button>
    <button type="button" class="btn btn-outline-success" id="addImage"><i class="bi bi-image"></i> + Gambar</button>
    <button type="button" class="btn btn-outline-warning" id="addVideo"><i class="bi bi-camera-video"></i> + Video</button>
    <button type="button" class="btn btn-outline-info" id="addRelated"><i class="bi bi-link"></i> + Baca Juga</button>
</div>

{{-- TEMPLATES DINAMIS UNTUK JAVASCRIPT --}}
<template id="template-text">
    <div class="content-item block-editor" data-type="text">
        <div class="block-header d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <span class="drag-handle fs-5">⋮⋮</span>
                <span class="badge bg-primary text-uppercase block-type">TEXT</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-content"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <input type="hidden" name="contents[__INDEX__][id]" value="">
        <input type="hidden" name="contents[__INDEX__][type]" value="text">
        <input type="hidden" name="contents[__INDEX__][position]" class="position-input" value="__INDEX__">

        <div class="editor-toolbar mb-2 btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary btn-cmd" data-command="bold"><b>B</b></button>
            <button type="button" class="btn btn-outline-secondary btn-cmd" data-command="italic"><i>I</i></button>
            <button type="button" class="btn btn-outline-secondary btn-cmd" data-command="insertOrderedList">1.</button>
            <button type="button" class="btn btn-outline-secondary btn-cmd" data-command="insertUnorderedList">•</button>
            <button type="button" class="btn btn-outline-secondary add-link">🔗 Link</button>
        </div>
        <div class="editor article-content p-2" contenteditable="true"></div>
        <input type="hidden" name="contents[__INDEX__][text]" class="editor-input">
    </div>
</template>

<template id="template-image">
    <div class="content-item block-editor" data-type="image">
        <div class="block-header d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <span class="drag-handle fs-5">⋮⋮</span>
                <span class="badge bg-primary text-uppercase block-type">IMAGE</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-content"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <input type="hidden" name="contents[__INDEX__][id]" value="">
        <input type="hidden" name="contents[__INDEX__][type]" value="image">
        <input type="hidden" name="contents[__INDEX__][position]" class="position-input" value="__INDEX__">

        <div class="mb-2">
            <label class="form-label fw-semibold">Pilih Gambar</label>
            <input type="file" name="contents[__INDEX__][image]" class="form-control image-input" accept="image/*">
        </div>
        <div>
            <label class="form-label fw-semibold">Caption Gambar</label>
            <input type="text" name="contents[__INDEX__][caption]" class="form-control" placeholder="Keterangan gambar">
        </div>
    </div>
</template>

<template id="template-video">
    <div class="content-item block-editor" data-type="video">
        <div class="block-header d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <span class="drag-handle fs-5">⋮⋮</span>
                <span class="badge bg-primary text-uppercase block-type">VIDEO</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-content"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <input type="hidden" name="contents[__INDEX__][id]" value="">
        <input type="hidden" name="contents[__INDEX__][type]" value="video">
        <input type="hidden" name="contents[__INDEX__][position]" class="position-input" value="__INDEX__">

        <div class="mb-3">
            <label class="form-label fw-bold">Upload Video Baru</label>
            <input type="file" name="contents[__INDEX__][video]" class="form-control video-input" accept="video/mp4,video/webm,video/quicktime">
            <small class="text-muted">Format: MP4, MOV, WEBM</small>
        </div>
        <video class="video-preview rounded border w-100 d-none mb-3" controls style="max-height: 300px;"></video>
        <hr>
        <h6 class="fw-bold mb-3">Pengaturan Tampilan Video</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Rasio Video</label>
                <select class="form-select video-orientation" name="contents[__INDEX__][video_orientation]">
                    <option value="landscape" selected>Landscape (16:9)</option>
                    <option value="portrait">Portrait (9:16)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Lebar Video: <span class="video-width-text">100%</span></label>
                <input type="range" min="30" max="100" value="100" name="contents[__INDEX__][video_width]" class="form-range video-width-slider">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tinggi Video: <span class="video-height-text">350px</span></label>
                <input type="range" min="150" max="800" value="350" name="contents[__INDEX__][video_height]" class="form-range video-height-slider">
            </div>
            <div class="col-md-4">
                <label class="form-label">Posisi Alignment</label>
                <select name="contents[__INDEX__][video_align]" class="form-select video-align">
                    <option value="left">Kiri</option>
                    <option value="center" selected>Tengah</option>
                    <option value="right">Kanan</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Sudut Video (Border Radius: <span class="radius-text">12</span>px)</label>
                <input type="range" min="0" max="40" value="12" class="form-range video-radius-slider">
                <input type="hidden" name="contents[__INDEX__][video_radius]" value="12" class="video-radius-input">
            </div>
            <div class="col-md-6">
                <label class="form-label">Youtube URL</label>
                <input type="url" name="contents[__INDEX__][youtube_url]" class="form-control" placeholder="https://youtube.com/watch?v=xxxxx">
            </div>
            <div class="col-12">
                <label class="form-label">Caption Video</label>
                <input type="text" name="contents[__INDEX__][caption]" class="form-control" placeholder="Caption Video">
            </div>
        </div>
    </div>
</template>

<template id="template-related">
    <div class="content-item block-editor" data-type="related">
        <div class="block-header d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <span class="drag-handle fs-5">⋮⋮</span>
                <span class="badge bg-primary text-uppercase block-type">BACA JUGA</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-content"><i class="bi bi-trash"></i> Hapus</button>
        </div>
        <input type="hidden" name="contents[__INDEX__][id]" value="">
        <input type="hidden" name="contents[__INDEX__][type]" value="related">
        <input type="hidden" name="contents[__INDEX__][position]" class="position-input" value="__INDEX__">

        <div class="border-start border-3 border-primary ps-3">
            <label class="fw-bold text-primary mb-2">Baca juga:</label>
            <input type="text" name="contents[__INDEX__][related_title]" class="form-control mb-2" placeholder="Judul berita">
            <input type="text" name="contents[__INDEX__][related_url]" class="form-control" placeholder="URL berita">
        </div>
    </div>
</template>

{{-- JAVASCRIPT HANDLER --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('contents-wrapper');
        const mainForm = wrapper ? wrapper.closest('form') : null;

        // FUNGSI UTAMA: Sinkronisasi Teks dari .editor ke Hidden Input (.editor-input)
        function syncAllEditors() {
            if (!wrapper) return;
            const textBlocks = wrapper.querySelectorAll('.content-item');
            textBlocks.forEach(block => {
                const editor = block.querySelector('.editor');
                const hiddenInput = block.querySelector('.editor-input');
                if (editor && hiddenInput) {
                    hiddenInput.value = editor.innerHTML;
                }
            });
        }

        // Re-index urutan name array agar tidak ada index loncat saat dikirim ke Laravel
        function reindexBlocks() {
            const items = wrapper.querySelectorAll('.content-item');
            items.forEach((item, index) => {
                const pos = item.querySelector('.position-input');
                if (pos) pos.value = index + 1;

                item.querySelectorAll('[name^="contents["]').forEach(input => {
                    const name = input.getAttribute('name');
                    const newName = name.replace(/contents\[\d+\]/, `contents[${index}]`);
                    input.setAttribute('name', newName);
                });
            });
        }

        // Fungsi Tambah Blok Baru
        function addBlock(type) {
            const template = document.getElementById(`template-${type}`);
            if (!template) return;

            const count = wrapper.querySelectorAll('.content-item').length;
            const cloneHtml = template.innerHTML.replace(/__INDEX__/g, count);

            wrapper.insertAdjacentHTML('beforeend', cloneHtml);
            reindexBlocks();
        }

        // Event Listener Tombol Tambah
        document.getElementById('addText')?.addEventListener('click', () => addBlock('text'));
        document.getElementById('addImage')?.addEventListener('click', () => addBlock('image'));
        document.getElementById('addVideo')?.addEventListener('click', () => addBlock('video'));
        document.getElementById('addRelated')?.addEventListener('click', () => addBlock('related'));

        // Event Delegation: Click Handling (Hapus, Toolbar Cmd, Link)
        wrapper.addEventListener('click', function(e) {
            // Hapus Blok
            if (e.target.closest('.remove-content')) {
                const block = e.target.closest('.content-item');
                block.remove();
                reindexBlocks();
            }

            // Command Editor Text (Bold, Italic, List)
            if (e.target.closest('.btn-cmd')) {
                e.preventDefault();
                const btn = e.target.closest('.btn-cmd');
                const cmd = btn.dataset.command;

                // Pastikan editor yang bersangkutan mendapatkan fokus
                const block = btn.closest('.content-item');
                const editor = block ? block.querySelector('.editor') : null;
                if (editor) editor.focus();

                document.execCommand(cmd, false, null);
                syncAllEditors(); // Langsung sync setelah execCommand
            }

            // Link Button Editor Text
            if (e.target.closest('.add-link')) {
                e.preventDefault();
                const btn = e.target.closest('.add-link');
                const block = btn.closest('.content-item');
                const editor = block ? block.querySelector('.editor') : null;

                const url = prompt('Masukkan URL Link:');
                if (url) {
                    if (editor) editor.focus();
                    document.execCommand('createLink', false, url);
                    syncAllEditors(); // Langsung sync setelah tambah link
                }
            }
        });

        // Event Input: Sync Realtime saat Pengguna Mengetik
        wrapper.addEventListener('input', function(e) {
            if (e.target.classList.contains('editor')) {
                const block = e.target.closest('.content-item');
                const hiddenInput = block ? block.querySelector('.editor-input') : null;
                if (hiddenInput) {
                    hiddenInput.value = e.target.innerHTML;
                }
            }

            // Sync Slider Width Text
            if (e.target.classList.contains('video-width-slider')) {
                const textSpan = e.target.parentElement.querySelector('.video-width-text');
                if (textSpan) textSpan.innerText = e.target.value + '%';
            }

            // Sync Slider Height Text
            if (e.target.classList.contains('video-height-slider')) {
                const textSpan = e.target.parentElement.querySelector('.video-height-text');
                if (textSpan) textSpan.innerText = e.target.value + 'px';
            }

            // Sync Slider Radius Text & Input
            if (e.target.classList.contains('video-radius-slider')) {
                const parent = e.target.parentElement;
                const textSpan = parent.querySelector('.radius-text');
                const hiddenRadius = parent.querySelector('.video-radius-input');
                if (textSpan) textSpan.innerText = e.target.value;
                if (hiddenRadius) hiddenRadius.value = e.target.value;
            }
        });

        // Preview Local Video File Upload
        wrapper.addEventListener('change', function(e) {
            if (e.target.classList.contains('video-input')) {
                const file = e.target.files[0];
                const block = e.target.closest('.content-item');
                const preview = block.querySelector('.video-preview');

                if (file && preview) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('d-none');
                }
            }
        });

        // SINKRONISASI AKHIR SAAT FORM SUBMIT (Mencegah Teks Kosong Tersimpan)
        if (mainForm) {
            mainForm.addEventListener('submit', function() {
                syncAllEditors();
            });
        }
    });
</script>