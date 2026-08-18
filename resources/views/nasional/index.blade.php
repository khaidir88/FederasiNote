@extends('layouts.guest')

@section('title', 'Berita & Artikel')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">News</li>

            <!-- Jika ada filter category -->
            @if(request()->has('category'))
            <li class="breadcrumb-item active" aria-current="page">
                {{ ucfirst(request('category')) }}
            </li>
            @endif

            <!-- Jika ada filter tag -->
            @if(request()->has('tag'))
            <li class="breadcrumb-item active" aria-current="page">
                Tag: #{{ request('tag') }}
            </li>
            @endif
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-3">
                @if(request()->has('category'))
                Berita {{ ucfirst(request('category')) }}
                @elseif(request()->has('tag'))
                Berita dengan Tag: #{{ request('tag') }}
                @else
                Berita & Artikel Terbaru
                @endif
            </h1>
            <p class="text-muted">
                Temukan berita dan artikel terbaru dari kami
            </p>
        </div>
        <div class="col-lg-4">
            <!-- Search Form -->
            <form action="{{ route('news.index') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari berita..."
                        value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Info -->
    @if(request()->has('category') || request()->has('tag') || request()->has('search'))
    <div class="alert alert-info mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Filter Aktif:</h6>
                @if(request()->has('category'))
                @php
                $currentCategory = $categories->firstWhere('name', request('category'));
                @endphp
                <span class="badge me-2" style="background-color: {{ $currentCategory->color ?? '#6c757d' }}; color: white;">
                    Kategori: {{ request('category') }}
                </span>
                @endif
                @if(request()->has('tag'))
                <span class="badge bg-success me-2">Tag: #{{ request('tag') }}</span>
                @endif
                @if(request()->has('search'))
                <span class="badge bg-warning me-2 text-dark">Pencarian: "{{ request('search') }}"</span>
                @endif
            </div>
            <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-secondary">
                Hapus Filter
            </a>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            @if($articles->count() > 0)
            <!-- Articles List -->
            @foreach($articles as $article)
            <div class="card mb-4 border-0 shadow-sm">
                <div class="row g-0">
                    <div class="col-md-4 position-relative">
                        <a href="{{ route('news.show', $article->slug) }}">
                            <img src="{{ asset('images/articles/' . $article->image) }}"
                                alt="{{ $article->title }}"
                                class="img-fluid rounded-start h-100 w-100"
                                style="object-fit: cover; min-height: 200px;">
                        </a>
                        <!-- Author Info -->
                        <div class="position-absolute bottom-0 start-0 w-100 p-3 text-white"
                            style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <div class="fw-bold small">{{ $article->author }}</div>
                            <small>Reporter</small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                @if($article->category)
                                <span class="badge" style="background-color: {{ $article->category->color }}; color: white;">
                                    {{ $article->category->name }}
                                </span>
                                @else
                                <span class="badge bg-secondary">Uncategorized</span>
                                @endif
                                <small class="text-muted">
                                    {{ $article->created_at->translatedFormat('d M Y') }}
                                </small>
                            </div>

                            <h5 class="card-title fw-bold">
                                <a href="{{ route('news.show', $article->slug) }}"
                                    class="text-dark text-decoration-none">
                                    {{ $article->title }}
                                </a>
                            </h5>

                            <p class="card-text text-muted">
                                {{ Str::limit(strip_tags($article->content), 150) }}
                            </p>

                            <div class="d-flex align-items-center">
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

                            <!-- Tags -->
                            @if($article->tags)
                            <div class="tags-modern-container mt-3">
                                @foreach(explode(',', $article->tags) as $tag)
                                @php
                                // Hilangkan tanda [ ] " dan trim spasi berlebih
                                $cleanTag = trim(str_replace(['[', ']', '"', "'"], '', $tag));
                                $cleanTag = preg_replace('/\s+/', ' ', $cleanTag); // Hilangkan spasi berlebih
                                @endphp

                                <a href="{{ route('news.index', ['tag' => $cleanTag]) }}"
                                    class="badge bg-light text-dark text-decoration-none me-1 mb-1"
                                    title="Lihat semua artikel dengan tag {{ $cleanTag }}">
                                    #{{ $cleanTag }}
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links() }}
            </div>

            @else
            <!-- No Articles Found -->
            <div class="text-center py-5">
                <i class="bi bi-newspaper display-1 text-muted"></i>
                <h3 class="mt-3">Tidak ada berita ditemukan</h3>
                <p class="text-muted">
                    @if(request()->has('search'))
                    Tidak ada berita yang sesuai dengan pencarian "{{ request('search') }}"
                    @elseif(request()->has('category'))
                    Tidak ada berita dalam kategori "{{ request('category') }}"
                    @elseif(request()->has('tag'))
                    Tidak ada berita dengan tag "#{{ request('tag') }}"
                    @else
                    Belum ada berita yang dipublikasikan
                    @endif
                </p>
                <a href="{{ route('news.index') }}" class="btn btn-primary">
                    Lihat Semua Berita
                </a>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Categories -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-bookmarks me-2"></i>
                        Kategori
                    </h5>
                    <div class="list-group list-group-flush">
                        @foreach($categories as $category)
                        <a href="{{ route('news.index', ['category' => $category->name]) }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="color-dot me-2" style="background-color: {{ $category->color }}"></span>
                                {{ $category->name }}
                            </div>
                            <span class="badge rounded-pill" style="background-color: {{ $category->color }}; color: white;">
                                {{ $category->articles_count }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Popular News -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-fire text-danger me-2"></i>
                        Populer
                    </h5>
                    @php
                    // Untuk demo, kita ambil 5 artikel terpopuler
                    $popularArticles = App\Models\Article::with('category')
                    ->where('status', 'published')
                    ->orderBy('views', 'desc')
                    ->limit(5)
                    ->get();
                    @endphp

                    @foreach($popularArticles as $popular)
                    <div class="d-flex mb-3 pb-3 border-bottom">
                        <div class="flex-shrink-0 me-3" style="width: 60px; height: 60px;">
                            <img src="{{ asset('images/articles/' . $popular->image) }}"
                                alt="{{ $popular->title }}"
                                class="img-fluid rounded h-100 w-100"
                                style="object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            @if($popular->category)
                            <span class="badge mb-1 d-inline-block"
                                style="background-color: {{ $popular->category->color }}; color: white; font-size: 0.6rem; padding: 0.2em 0.4em;">
                                {{ $popular->category->name }}
                            </span>
                            @endif
                            <h6 class="fw-bold mb-1 small">
                                <a href="{{ route('news.show', $popular->slug) }}"
                                    class="text-dark text-decoration-none">
                                    {{ Str::limit($popular->title, 40) }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                {{ $popular->created_at->format('d M') }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tags Cloud -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-tags text-success me-2"></i>
                        Tags Populer
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        @php
                        $popularTags = ['news', 'update', 'teknologi', 'olahraga', 'politik', 'kesehatan'];
                        @endphp
                        @foreach($popularTags as $tag)
                        <a href="{{ route('news.index', ['tag' => $tag]) }}"
                            class="badge bg-light text-dark text-decoration-none">
                            #{{ $tag }}
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
    .card:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.7rem;
        font-weight: 500;
    }

    .color-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 2px rgba(0, 0, 0, 0.2);
    }

    /* Style untuk badge kategori di artikel */
    .card .badge {
        font-size: 0.65rem;
        padding: 0.35em 0.65em;
    }

    /* Style untuk badge di sidebar kategori */
    .list-group-item .badge {
        font-size: 0.65rem;
        padding: 0.3em 0.6em;
    }
</style>
@endpush