@extends('layouts.guest')

@section('title', $article->title)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">Berita</a></li>
            <li class="breadcrumb-item"><a href="{{ route('berita.index', ['category' => $article->category->name ?? '']) }}">
                    {{ $article->category->name ?? 'Uncategorized' }}
                </a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 50) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="news-detail">
                <!-- Article Header -->
                <header class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary fs-6">
                            {{ strtoupper($article->category->name ?? 'NEWS') }}
                        </span>
                        <div class="text-muted">
                            <small>
                                <i class="bi bi-calendar me-1"></i>
                                {{ $article->created_at->translatedFormat('l, d F Y') }}
                            </small>
                            <small class="ms-3">
                                <i class="bi bi-clock me-1"></i>
                                {{ $article->created_at->format('H:i') }} WIB
                            </small>
                            <small class="ms-3">
                                <i class="bi bi-eye me-1"></i>
                                {{ $article->views ?? 0 }}x dilihat
                            </small>
                        </div>
                    </div>

                    <h1 class="fw-bold mb-3">{{ $article->title }}</h1>

                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author) }}&background=0D8ABC&color=fff"
                                alt="{{ $article->author }}"
                                class="rounded-circle"
                                width="40"
                                height="40">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold">{{ $article->author }}</div>
                            <small class="text-muted">Reporter</small>
                        </div>
                    </div>
                </header>

                <!-- Featured Image -->
                <div class="featured-image mb-4">
                    <img src="{{ asset('images/articles/'.$article->image) }}"
                        alt="{{ $article->title }}"
                        class="img-fluid rounded w-100"
                        style="max-height: 500px; object-fit: cover;">
                    @if($article->image_caption)
                    <div class="text-center text-muted mt-2">
                        <small>{{ $article->image_caption }}</small>
                    </div>
                    @endif
                </div>

                <!-- Article Content -->
                <div class="article-content mb-5">
                    {!! $article->content !!}
                </div>

                <!-- Article Footer -->
                <footer class="border-top pt-4">
                    <!-- Tags -->
                    @if($article->tags)
                    <div class="tags mb-4">
                        <h6 class="fw-bold mb-2">Tags:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(explode(',', $article->tags) as $tag)
                            <a href="{{ route('berita.index', ['tag' => trim($tag)]) }}"
                                class="badge bg-light text-dark text-decoration-none">
                                #{{ trim($tag) }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Share Buttons -->
                    <div class="share-buttons mb-4">
                        <h6 class="fw-bold mb-2">Bagikan:</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-facebook"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-info">
                                <i class="bi bi-twitter"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . url()->current()) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-success">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </footer>

                <!-- Comments Section -->
                <div class="comments-section mt-5">
                    <h4 class="fw-bold mb-4">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Komentar ({{ $article->comments_count ?? 0 }})
                    </h4>

                    <!-- Comment Form -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">Tinggalkan Komentar</h6>
                            <form action="{{ route('comments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="article_id" value="{{ $article->id }}">
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
                        @foreach($article->comments->where('approved', true) as $comment)
                        <div class="comment-item mb-4 pb-4 border-bottom">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->name) }}&background=6c757d&color=fff"
                                        alt="{{ $comment->name }}"
                                        class="rounded-circle"
                                        width="50"
                                        height="50">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">{{ $comment->name }}</h6>
                                        <small class="text-muted">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if(($article->comments_count ?? 0) === 0)
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
            <!-- Popular News -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-fire text-danger me-2"></i>
                        Berita Populer
                    </h5>

                    @foreach($popularArticles as $popular)
                    <div class="d-flex mb-3 pb-3 border-bottom">
                        <div class="flex-shrink-0 me-3" style="width: 80px; height: 80px;">
                            <img src="{{ asset('images/articles/'.$popular->image) }}"
                                alt="{{ $popular->title }}"
                                class="img-fluid rounded h-100 w-100"
                                style="object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">
                                <a href="{{ route('berita.show', $popular->slug) }}"
                                    class="text-dark text-decoration-none">
                                    {{ Str::limit($popular->title, 50) }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                {{ $popular->created_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Related News -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-link-45deg text-primary me-2"></i>
                        Berita Terkait
                    </h5>

                    @foreach($relatedArticles as $related)
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3" style="width: 60px; height: 60px;">
                            <img src="{{ asset('images/articles/'.$related->image) }}"
                                alt="{{ $related->title }}"
                                class="img-fluid rounded h-100 w-100"
                                style="object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 small">
                                <a href="{{ route('berita.show', $related->slug) }}"
                                    class="text-dark text-decoration-none">
                                    {{ Str::limit($related->title, 40) }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                {{ $related->created_at->format('M d') }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Categories -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-bookmarks text-success me-2"></i>
                        Kategori
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($categories as $category)
                        <a href="{{ route('berita.index', ['category' => $category->name]) }}"
                            class="badge bg-light text-dark text-decoration-none">
                            {{ $category->name }}
                            <span class="badge bg-primary ms-1">{{ $category->articles_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .news-detail {
        line-height: 1.8;
    }

    .article-content {
        font-size: 1.1rem;
        color: #333;
    }

    .article-content p {
        margin-bottom: 1.5rem;
    }

    .article-content h2,
    .article-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #2c3e50;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .article-content blockquote {
        border-left: 4px solid #0d6efd;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6c757d;
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
    }

    .featured-image {
        position: relative;
    }

    .comment-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
</style>
@endpush