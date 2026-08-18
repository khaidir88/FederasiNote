@extends('layouts.guest')

@section('title', $news->title)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('news.index') }}">News</a></li>

            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($news->title, 50) }}</li>
        </ol>
    </nav>

    <div class="row">

        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="news-detail bg-white p-4 rounded shadow-sm">
                <!-- Article Header -->
                <header class="mb-4">
                    <h1 class="fw-bold mb-3">{{ $news->title }}</h1>
                    @if($news->category)
                    <div class="d-flex justify-content-between ms-auto mb-4">
                        <span class="badge" style="background-color: {{ $news->category->color }}; color: white;">
                            {{ $news->category->name }}
                        </span>
                    </div>
                    @endif
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($news->author) }}&background=0D8ABC&color=fff"
                                alt="{{$news->author }}"
                                class="rounded-circle"
                                width="40"
                                height="40">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-author">
                                {{ $news->author ?? 'Administrator' }}</>
                            </div>

                        </div>
                        <div class="text-muted">
                            <small>
                                <i class="bi bi-calendar me-1"></i>

                                {{ $news->created_at->diffForHumans() }}

                            </small>
                            <small class="ms-3">
                                <i class="bi bi-clock me-1"></i>
                                {{ $news->created_at->format('H:i') }} WIB
                            </small>
                            <small class="ms-3">
                                <i class="bi bi-eye me-1"></i>
                                {{ $news->views ?? 0 }}x dilihat

                            </small>

                        </div>
                    </div>
                </header>
                <!-- Featured Image -->
                <div class="featured-image mb-4">
                    @if($news->video_url)
                    @php
                    // Convert link YouTube menjadi embed link
                    preg_match('/(youtu\.be\/|v=)([^&]+)/', $news->video_url, $matches);
                    $videoId = $matches[2] ?? null;
                    @endphp

                    @if($videoId)
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe
                            src="https://www.youtube.com/embed/{{ $videoId }}"
                            title="{{ $news->title }}"
                            allowfullscreen
                            class="rounded">
                        </iframe>
                    </div>
                    @endif
                    @elseif($news->image)
                    <img src="{{ asset('public/../images/articles/'.$news->image) }}"
                        alt="{{ $news->title }}"
                        class="img-fluid rounded w-100"
                        style="max-height: 500px; object-fit: cover;">
                    @if($news->image_caption)
                    <div class="text-center text-muted mt-2">
                        <small>{{ $news->image_caption }}</small>
                    </div>
                    @endif
                    @endif
                    <div class="text-center text-muted mt-2 mb-0" style="line-height: 1.1;">
                        <small>{{ $news->keterangan }}</small>
                    </div>

                </div>
                <!-- Article Content -->
                <div class="article-content mb-5 pt-5">

                    @forelse($news->contents->sortBy('position') as $content)

                    {{-- ================= TEXT ================= --}}
                    @if($content->type === 'text')
                    <div class="mb-4 article-content">
                        {!! $content->content !!}
                    </div>
                    @endif

                    {{-- ================= IMAGE ================= --}}
                    @if($content->type === 'image')
                    <figure class="mb-4 text-center">

                        <img src="{{ asset('public/../images/articles/' . $content->image_path) }}"
                            alt="{{ $news->title }}"
                            class="img-fluid rounded w-100"
                            style="max-height: 400px; object-fit: cover;">
                        @if(!empty($content->caption))
                        <figcaption class="text-muted small mt-2">
                            {{ $content->caption }}
                        </figcaption>
                        @endif

                    </figure>
                    @endif

                    {{-- ================= VIDEO ================= --}}
                    @if($content->type === 'video')
                    @php
                    $width = $content->video_width ?? 100;
                    $height = $content->video_height ?? 350;
                    $radius = $content->video_radius ?? 12;
                    $align = $content->video_align ?? 'center';

                    // Pengaturan CSS Float & Margin berdasarkan Alignment
                    $floatStyle = '';
                    if ($align === 'left') {
                    $floatStyle = 'float: left; margin-right: 1.5rem; margin-bottom: 1rem;';
                    } elseif ($align === 'right') {
                    $floatStyle = 'float: right; margin-left: 1.5rem; margin-bottom: 1rem;';
                    } else {
                    $floatStyle = 'float: none; margin: 0 auto 1.5rem auto; clear: both; display: block;';
                    }
                    @endphp

                    <figure class="news-video-wrapper" style="
    width: {{ $width }}%; 
    max-width: 100%; 
    {{ $floatStyle }}
">
                        <div class="custom-video-player" style="
        width: 100%; 
        height: {{ $height }}px; 
        max-height: 800px;
        border-radius: {{ $radius }}px; 
        overflow: hidden; 
        position: relative;
        background-color: #000;
    ">
                            <video class="video-element" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                <source src="{{ asset('videos/articles/'.$content->video_path) }}" type="video/mp4">
                                Browser Anda tidak mendukung tag video.
                            </video>

                            <!-- PLAY BUTTON CENTER -->
                            <button class="play-center" type="button">▶</button>

                            <!-- CONTROLS -->
                            <div class="video-controls">
                                <button class="play-pause" type="button">▶</button>
                                <span class="current-time">0:00</span>
                                <input type="range" class="progress" value="0" step="0.1">
                                <span class="duration">0:00</span>
                                <button class="mute" type="button">🔊</button>
                                <input type="range" class="volume" min="0" max="1" step="0.1" value="1">
                                <button class="fullscreen" type="button">⛶</button>
                            </div>
                        </div>

                        {{-- VIDEO CAPTION --}}
                        @if(!empty($content->caption))
                        <figcaption class="video-caption mt-2 text-center text-muted small">
                            {{ $content->caption }}
                        </figcaption>
                        @endif
                    </figure>
                    @endif
                    {{-- ================= BACA JUGA ================= --}}
                    @if($content->type === 'related')
                    <div class="related-card mb-4" style="clear: both;">

                        <div class="related-label">
                            Baca Juga
                        </div>

                        <a href="{{ $content->related_url }}" target="_blank" class="related-link">
                            {{ $content->related_title }}
                        </a>

                    </div>
                    @endif

                    @empty
                    <div class="alert alert-warning">
                        Konten berita belum tersedia.
                    </div>
                    @endforelse

                    <!-- PEMBERSIH FLOAT: Memaksa elemen di bawahnya turun ke baris baru -->
                    <div class="clearfix" style="clear: both;"></div>

                </div> <!-- End Article Body / Contents Wrapper -->

                <!-- Article Footer -->
                <footer class="border-top pt-4 mt-4" style="clear: both;">
                    <!-- Tags -->
                    @if($news->tags && !empty($news->tags))
                    <div class="tags-modern-container mt-3">
                        @php
                        // Cek apakah tags adalah array atau string
                        if (is_string($news->tags)) {
                        // Jika string, coba decode JSON
                        $decodedTags = json_decode($news->tags, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedTags)) {
                        $tagsArray = $decodedTags;
                        } else {
                        // Jika bukan JSON, split dengan koma
                        $tagsArray = array_map('trim', explode(',', $news->tags));
                        }
                        } elseif (is_array($news->tags)) {
                        // Jika sudah array, langsung pakai
                        $tagsArray = $news->tags;
                        } else {
                        $tagsArray = [];
                        }

                        // Filter array kosong
                        $tagsArray = array_filter($tagsArray, function($tag) {
                        return !empty(trim($tag));
                        });
                        @endphp

                        @foreach($tagsArray as $tag)
                        @php
                        // Hilangkan tanda [ ] " dan trim spasi berlebih
                        $cleanTag = trim(str_replace(['[', ']', '"', "'"], '', $tag));
                        $cleanTag = preg_replace('/\s+/', ' ', $cleanTag); // Hilangkan spasi berlebih

                        $tagClass = 'tag-modern ';

                        // Smart color assignment
                        $tagLower = strtolower($cleanTag);
                        if (str_contains($tagLower, 'budaya') || str_contains($tagLower, 'culture') || str_contains($tagLower, 'seni')) {
                        $tagClass .= 'tag-culture';
                        } elseif (str_contains($tagLower, 'hindu') || str_contains($tagLower, 'religi') || str_contains($tagLower, 'agama')) {
                        $tagClass .= 'tag-hindu';
                        } elseif (str_contains($tagLower, 'teknologi') || str_contains($tagLower, 'tech') || str_contains($tagLower, 'digital')) {
                        $tagClass .= 'tag-tech';
                        } else {
                        $tagClass .= 'tag-news';
                        }
                        @endphp

                        <a href="{{ route('news.index', ['tag' => urlencode($cleanTag)]) }}"
                            class="{{ $tagClass }}"
                            title="Lihat semua artikel dengan tag {{ $cleanTag }}">
                            {{ $cleanTag }}
                        </a>
                        @endforeach
                    </div>
                    @endif

                    <!-- Share Buttons -->
                    <div class="share-buttons py-3 mb-4">
                        <h6 class="fw-bold mb-2">Bagikan:</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-facebook"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($news->title) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-info">
                                <i class="bi bi-twitter"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . url()->current()) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-success">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </footer>

                <!-- Comments Section -->
                <div class="comments-section mt-5" style="clear: both;">
                    <h4 class="fw-bold mb-4">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Komentar ({{ $news->comments_count ?? 0 }})
                    </h4>

                    <!-- Comment Form -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">Tinggalkan Komentar</h6>
                            <form action="{{ route('comments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="news_id" value="{{ $news->id }}">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" name="email" class="form-control" placeholder="Email Anda" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <textarea name="content" class="form-control" rows="4" placeholder="Tulis komentar Anda..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                            </form>
                        </div>
                    </div>

                    <!-- Comments List -->
                    <div class="comments-list">
                        @foreach($news->comments->where('approved', true) as $comment)
                        <div class="comment-item d-flex mb-4 pb-4 border-bottom">

                            <!-- Avatar -->
                            <div class="comment-avatar">
                                {{ strtoupper(substr($comment->name, 0, 1)) }}
                            </div>

                            <!-- Content -->
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="comment-name mb-0">
                                        {{ $comment->name }}
                                    </h6>
                                    <small class="comment-time">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>

                                <p class="comment-content mb-0">
                                    {{ $comment->content }}
                                </p>
                            </div>

                        </div>
                        @endforeach
                        @if(($news->comments_count ?? 0) === 0)
                        <div class="text-center py-4">
                            <i class="bi bi-chat-left-text display-4 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                        </div>
                        @endif
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Berita Populer -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-fire text-danger me-2"></i>
                        Berita Populer
                    </h5>
                    @foreach($popularArticles as $popular)
                    <div class="mb-4 pb-3 border-bottom text-center">
                        <a href="{{ route('news.show', $popular->slug) }}" class="text-decoration-none d-block">
                            <div class="ratio ratio-16x9 mb-2">
                                <img src="{{ asset('images/articles/'.$popular->image) }}"
                                    alt="{{ $popular->title }}"
                                    class="img-fluid rounded w-100 h-100"
                                    style="object-fit: cover;">
                            </div>
                            <h6 class="fw-bold text-dark mb-1">
                                {{ Str::limit($popular->title, 60) }}
                            </h6>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($popular->created_at)->translatedFormat('d F Y') }}
                            </small>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Berita Terkait -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-link-45deg text-primary me-2"></i>
                        Berita Terkait
                    </h5>

                    @foreach($relatedArticles as $related)
                    <div class="mb-4 pb-3 border-bottom text-center">
                        <a href="{{ route('news.show', $related->slug) }}" class="text-decoration-none d-block">
                            <div class="ratio ratio-16x9 mb-2">
                                <img src="{{ asset('images/articles/'.$related->image) }}"
                                    alt="{{ $related->title }}"
                                    class="img-fluid rounded w-100 h-100"
                                    style="object-fit: cover;">
                            </div>
                            <h6 class="fw-bold text-dark mb-1">
                                {{ Str::limit($related->title, 60) }}
                            </h6>
                            <small class="text-primary">
                                {{ \Carbon\Carbon::parse($related->created_at)->translatedFormat('d F Y') }}
                            </small>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Kategori -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-bookmarks text-success me-2"></i>
                        Kategori
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($categories as $category)
                        <a href="{{ route('news.index', ['category' => $category->name]) }}"
                            class="badge bg-light text-dark text-decoration-none">
                            {{ $category->name }}
                            <span class="badge bg-primary ms-1">{{ $category->news_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.custom-video-player').forEach(player => {

        const video = player.querySelector('.video-element');
        const playCenter = player.querySelector('.play-center');
        const playPause = player.querySelector('.play-pause');
        const progress = player.querySelector('.progress');
        const mute = player.querySelector('.mute');
        const volume = player.querySelector('.volume');
        const fullscreen = player.querySelector('.fullscreen');
        const currentTime = player.querySelector('.current-time');
        const duration = player.querySelector('.duration');

        // FORMAT TIME
        function formatTime(time) {
            const minutes = Math.floor(time / 60);
            const seconds = Math.floor(time % 60);
            return minutes + ":" + (seconds < 10 ? "0" + seconds : seconds);
        }

        // LOAD METADATA
        video.addEventListener('loadedmetadata', () => {
            progress.max = video.duration;
            duration.textContent = formatTime(video.duration);
        });

        // PLAY / PAUSE FUNCTION
        function togglePlay() {

            if (video.paused) {
                video.play();
                playPause.textContent = "❚❚";
                playCenter.style.display = "none";
            } else {
                video.pause();
                playPause.textContent = "▶";
                playCenter.style.display = "flex";
            }

        }

        // BUTTON PLAY
        playCenter.addEventListener('click', togglePlay);
        playPause.addEventListener('click', togglePlay);

        // ✅ CLICK VIDEO TO PLAY / PAUSE
        video.addEventListener('click', togglePlay);

        // UPDATE PROGRESS
        video.addEventListener('timeupdate', () => {
            progress.value = video.currentTime;
            currentTime.textContent = formatTime(video.currentTime);
        });

        // SEEK
        progress.addEventListener('input', () => {
            video.currentTime = progress.value;
        });

        // MUTE
        mute.addEventListener('click', () => {
            video.muted = !video.muted;
            mute.textContent = video.muted ? "🔇" : "🔊";
        });

        // VOLUME
        volume.addEventListener('input', () => {
            video.volume = volume.value;
        });

        // FULLSCREEN
        fullscreen.addEventListener('click', () => {

            if (!document.fullscreenElement) {
                player.requestFullscreen();
            } else {
                document.exitFullscreen();
            }

        });

    });
</script>
@endsection