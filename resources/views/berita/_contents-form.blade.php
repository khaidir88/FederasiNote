@php
$contents = isset($news) ? $news->contents->sortBy('position')->values() : collect();
@endphp

<div id="contents-wrapper" class="sortable">

    @foreach($contents as $i => $content)
    <div class="content-item block-editor " data-type="{{ $content->type }}">

        <div class="block-header d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center gap-2">

                <span class="drag-handle">⋮⋮</span>

                <span class="block-type">
                    {{ strtoupper($content->type) }}
                </span>

            </div>

            <button type="button"
                class="btn btn-sm btn-light text-danger remove-content"
                data-id="{{ $content->id }}">
                ✕
            </button>

        </div>

        <input type="hidden" name="contents[{{ $i }}][id]" value="{{ $content->id }}">
        <input type="hidden" name="contents[{{ $i }}][type]" value="{{ $content->type }}">
        <input type="hidden" name="contents[{{ $i }}][position]" class="position-input" value="{{ $i+1 }}">

        {{-- ================= TEXT ================= --}}
        @if($content->type === 'text')

        <div class="editor-toolbar">
            <button data-command="bold"><b>B</b></button>
            <button data-command="italic"><i>I</i></button>
            <button data-command="insertOrderedList">1.</button>
            <button data-command="insertUnorderedList">•</button>
            <button class="add-link">🔗</button>
        </div>

        <div class="editor article-content p-2"
            contenteditable="true">
            {!! $content->content !!}
        </div>

        <input type="hidden"
            name="contents[{{ $i }}][text]"
            class="editor-input"
            value="{{ $content->content }}">

        @endif

        {{-- ================= IMAGE ================= --}}
        @if($content->type === 'image')

        @if($content->image_path)
        <img src="{{ asset('public/images/articles/'.$content->image_path) }}"
            class="img-fluid rounded mb-2">
        @endif

        <input type="file"
            name="contents[{{ $i }}][image]"
            class="form-control mb-2">

        <input type="text"
            name="contents[{{ $i }}][caption]"
            value="{{ $content->caption }}"
            class="form-control"
            placeholder="Keterangan gambar">

        @endif

        {{-- ================= VIDEO ================= --}}
        @if($content->type === 'video')

        {{-- Video yang sudah tersimpan --}}
        @if($content->video_path)
        <video
            class="w-100 rounded mb-3"
            controls
            style="
            max-width: {{ $content->video_width ?? 100 }}%;
            border-radius: {{ $content->video_radius ?? 12 }}px;
            {{ ($content->video_orientation ?? 'landscape') == 'portrait'
                ? 'max-width:380px;aspect-ratio:9/16;display:block;margin:auto;object-fit:cover;'
                : 'aspect-ratio:16/9;object-fit:cover;' }}
        ">
            <source src="{{ asset('videos/articles/'.$content->video_path) }}" type="video/mp4">
        </video>
        @endif

        {{-- Upload Video --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Upload Video Baru</label>
            <input
                type="file"
                name="contents[{{ $i }}][video]"
                class="form-control video-input"
                accept="video/mp4,video/webm,video/quicktime">
            <small class="text-muted">Format : MP4, MOV, WEBM</small>
        </div>

        {{-- Preview Upload --}}
        <video class="video-preview rounded border w-100 d-none mb-3" controls></video>
        <hr>

        <h6 class="fw-bold mb-3">Pengaturan Tampilan Video</h6>

        <div class="row g-3">

            {{-- Orientasi / Rasio --}}
            <div class="col-md-4">
                <label class="form-label">Rasio Video</label>
                <select class="form-select video-orientation" name="contents[{{ $i }}][video_orientation]">
                    <option value="landscape" {{ ($content->video_orientation ?? 'landscape') === 'landscape' ? 'selected' : '' }}>
                        Landscape (16:9)
                    </option>
                    <option value="portrait" {{ ($content->video_orientation ?? '') === 'portrait' ? 'selected' : '' }}>
                        Portrait (9:16)
                    </option>
                </select>
            </div>

            {{-- Lebar Video --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Lebar Video: <span class="video-width-text">{{ $content->video_width ?? 100 }}%</span>
                </label>

                <input
                    type="range"
                    min="30"
                    max="100"
                    value="{{ $content->video_width ?? 100 }}"
                    name="contents[{{ $i }}][video_width]"
                    class="form-range video-width-slider"
                    oninput="this.parentElement.querySelector('.video-width-text').innerText = this.value + '%'">
            </div>
            <!-- Tinggi Video (px) -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Tinggi Video: <span class="video-height-label">{{ $content->video_height ?? 350 }}px</span>
                </label>
                <input
                    type="range"
                    min="150"
                    max="800"
                    step="10"
                    value="{{ $content->video_height ?? 350 }}"
                    name="contents[{{ $i }}][video_height]"
                    class="form-range video-height-slider">
            </div>

            {{-- Posisi / Align --}}
            <div class="col-md-4">
                <label class="form-label">Posisi Alignment</label>
                <select name="contents[{{ $i }}][video_align]" class="form-select video-align">
                    <option value="left" {{ ($content->video_align ?? '') === 'left' ? 'selected' : '' }}>Kiri</option>
                    <option value="center" {{ ($content->video_align ?? 'center') === 'center' ? 'selected' : '' }}>Tengah</option>
                    <option value="right" {{ ($content->video_align ?? '') === 'right' ? 'selected' : '' }}>Kanan</option>
                </select>
            </div>

            {{-- Border Radius (Sudut Video) --}}
            <div class="col-md-6">
                <label class="form-label">Sudut Video (Border Radius: <span class="radius-text">{{ $content->video_radius ?? 12 }}</span>px)</label>
                <input
                    type="range"
                    min="0"
                    max="40"
                    value="{{ $content->video_radius ?? 12 }}"
                    class="form-range video-radius-slider"
                    oninput="this.nextElementSibling.value = this.value; this.parentElement.querySelector('.radius-text').innerText = this.value">

                <input
                    type="hidden"
                    name="contents[{{ $i }}][video_radius]"
                    value="{{ $content->video_radius ?? 12 }}"
                    class="video-radius-input">
            </div>

            {{-- Youtube URL --}}
            <div class="col-md-6">
                <label class="form-label">Youtube URL</label>
                <input
                    type="url"
                    name="contents[{{ $i }}][youtube_url]"
                    value="{{ $content->youtube_url }}"
                    class="form-control"
                    placeholder="https://youtube.com/watch?v=xxxxx">
            </div>

            {{-- Caption --}}
            <div class="col-12">
                <label class="form-label">Caption Video</label>
                <input
                    type="text"
                    name="contents[{{ $i }}][caption]"
                    value="{{ $content->caption }}"
                    class="form-control"
                    placeholder="Caption Video">
            </div>

        </div>

        @endif

        {{-- ================= RELATED ================= --}}
        @if($content->type === 'related')

        <div class="border-start border-primary ps-3">
            <label class="fw-bold text-primary">Baca juga:</label>

            <input type="text"
                name="contents[{{ $i }}][related_title]"
                value="{{ $content->related_title }}"
                class="form-control mb-2"
                placeholder="Judul berita">

            <input type="text"
                name="contents[{{ $i }}][related_url]"
                value="{{ $content->related_url }}"
                class="form-control"
                placeholder="URL berita">
        </div>

        @endif

        {{-- DELETE BUTTON --}}
        <button type="button"
            class="btn btn-sm btn-danger mt-2 remove-content"
            data-id="{{ $content->id }}">
            Hapus
        </button>

    </div>
    @endforeach

</div>

{{-- BUTTON ADD --}}
<div class="d-flex gap-2 mt-3 mb-5">
    <button type="button" class="btn btn-outline-primary" id="addText">+ Text</button>
    <button type="button" class="btn btn-outline-success" id="addImage">+ Gambar</button>
    <button type="button" class="btn btn-outline-warning" id="addVideo">+ Video</button>
    <button type="button" class="btn btn-outline-info" id="addRelated">+ Baca Juga</button>
</div>