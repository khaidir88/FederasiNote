@extends('layouts.guest')

@section('title', 'Berita Terkini')

@section('content')
<div class="container">
    <div class="hero-section py-0">
        <div class="container">
            <div class="row g-4 align-items-start">
                {{-- === KIRI: Carousel === --}}
                <div class="col-lg-12">
                    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-inner">
                            @foreach($publishedArticles->take(3) as $article)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <a href="{{ route('news.show', $article->slug) }}" class="text-decoration-none">
                                    <div class="hero-slide position-relative">
                                        <img src="{{ asset('images/articles/'.$article->image) }}"
                                            class="d-block w-100 hero-img"
                                            alt="{{ $article->title }}">
                                        <div class="hero-caption position-absolute bottom-0 mb-0 start-0 w-100 text-white p-4">
                                            <div class="mb-2">
                                                {{-- Badge dengan warna dari database --}}
                                                @if($article->category)
                                                <span class="badge me-2" style="background-color: {{ $article->category->color }}; color: white;">
                                                    {{ strtoupper($article->category->name) }}
                                                </span>
                                                @else
                                                <span class="badge bg-danger me-2">NEWS</span>
                                                @endif
                                                <small class="me-3"><i class="bi bi-calendar"></i>
                                                    {{ $article->created_at->diffForHumans() }}
                                                </small>
                                                <small><i class="bi bi-chat"></i>
                                                    {{ $article->comments_count ?? 0 }}
                                                </small>
                                            </div>
                                            <h1 class="fw-bold">{{ ucwords(strtolower($article->title)) }}</h1>

                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>

                        {{-- Controls --}}
                        <button class="carousel-control-prev" type="button"
                            data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button"
                            data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>

                {{-- === KANAN: 4 Artikel Kecil === --}}
                <div class="container py-4">
                    <h2 class="section-title mt-5">Berita Trending</h2>
                    <div class="d-flex align-items-center mb-3 position-relative">

                        <div class="d-flex flex-wrap gap-2">
                            @foreach($categories->take(13) as $category)
                            <a href="{{ route('news.index',['category'=>$category->name]) }}"
                                class="tag-cat {{ request('category') == $category->name ? 'active' : '' }}"
                                style="--cat-color: {{ $category->color }};">
                                {{ $category->name }}
                            </a>

                            @endforeach
                            <a href="{{ route('news.index') }}"
                                class="btn btn-circle position-absolute end-0 buttom-0">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            @foreach($publishedArticles as $article)
                            <div class="swiper-slide">
                                <a href="{{ route('news.show',$article->slug) }}" class="text-decoration-none">
                                    <div class="poster-card">
                                        <img src="{{ asset('images/articles/'.$article->image) }}" alt="{{ $article->title }}">

                                        {{-- Badge dengan warna dari database --}}
                                        @if($article->category)
                                        <div class="category-badge" style="background-color: {{ $article->category->color }}; color: white;">
                                            {{ $article->category->name }}
                                        </div>
                                        @else
                                        <div class="category-badge bg-secondary">
                                            NEWS
                                        </div>
                                        @endif

                                        <small class="me-3"><i class="bi bi-calendar"></i>
                                            {{ $article->created_at->diffForHumans() }}
                                        </small>

                                        <div class="poster-title">
                                            {{ Str::limit($article->title, 30) }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container content-section">
        <!-- Featured News -->
        <h2 class="section-title">Berita Utama Hari Ini</h2>
        <div class="row mb-5">
            <div class="col-md-8">
                @if($featuredArticle = $publishedArticles->first())
                <div class="card mb-4">
                    {{-- Badge dengan warna dari database --}}
                    @if($featuredArticle->category)
                    <div class="category-badge" style="background-color: {{ $featuredArticle->category->color }}; color: white;">
                        {{ strtoupper($featuredArticle->category->name) }}
                    </div>
                    @else
                    <div class="category-badge bg-secondary">FEATURED</div>
                    @endif

                    <a href="{{ route('news.show', $featuredArticle->slug) }}">
                        <img src="{{ asset('images/articles/'.$featuredArticle->image) }}"
                            class="card-img-top"
                            alt="{{ $featuredArticle->title }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $featuredArticle->title }}</h5>
                        <p class="card-text">{!! Str::limit($featuredArticle->content, 200) !!}</p>


                        <div class="news-date">{{ $featuredArticle->created_at->format('d M Y') }}</div>
                        <span class="text-muted">{{ $featuredArticle->created_at->diffForHumans() }}</span>
                        <div class="author-info">
                            <span>By {{ $featuredArticle->author }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-3 fw-bold">Berita Terbaru</h5>

                        </div>

                        @foreach($publishedArticles->take(3) as $article)
                        <div class="d-flex mb-3 pb-3 border-bottom">

                            {{-- Thumbnail kiri (ukuran konsisten) --}}
                            <a href="{{ route('news.show', $article->slug) }}" class="d-block me-3">
                                <img src="{{ asset('images/articles/'.$article->image) }}"
                                    alt="{{ $article->title }}"
                                    style="width: 120px; height: 80px; object-fit: cover; border-radius:4px;">
                            </a>
                            {{-- Konten kanan --}}
                            <div class="flex-grow-1">

                                {{-- Judul --}}
                                <h6 class="fw-bold mb-1">
                                    <a href="{{ route('news.show',$article->slug) }}"
                                        class="text-dark text-decoration-none">
                                        {{ Str::limit($article->title, 20) }}
                                    </a>
                                </h6>

                                {{-- Category --}}
                                @if($article->category)
                                <span class="badge mb-1"
                                    style="background-color: {{ $article->category->color }};
           color:white;
           font-size:8px;
           padding:4px 6px;
           border-radius:4px;">
                                    {{ strtoupper($article->category->name) }}
                                </span>

                                @else
                                <span class="badge bg-secondary mb-1">UNCATEGORIZED</span>
                                @endif

                                {{-- Tanggal --}}
                                <div class="text-muted"
                                    style="font-size:11px; line-height:1.1;">
                                    {{ $article->created_at->format('d M Y') }} • {{ $article->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <a href="{{ route('news.index') }}" class="small text-uppercase fw-bold text-decoration-none">
                            Lihat Semua
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <h2 class="section-title">Kategori Berita</h2>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">
                    @foreach($categories as $category)
                    <a href="{{ route('news.index',['category'=>$category->name]) }}"
                        class="tag-item {{ request('category') == $category->name ? 'active' : '' }}"
                        style="--cat-color: {{ $category->color }};">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Latest News -->
        <div class="d-flex justify-content-between align-items-center mb-3 position-relative">
            <h2 class="section-title">Berita Terbaru</h2>
            <a href="{{ route('news.index') }}"
                class="btn btn-circle position-absolute end-0 buttom-0">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="row">
            @foreach($publishedArticles->take(3) as $article)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    {{-- Badge dengan warna dari database --}}
                    @if($article->category)
                    <div class="category-badge" style="background-color: {{ $article->category->color }}; color: white;">
                        {{ strtoupper($article->category->name) }}
                    </div>
                    @else
                    <div class="category-badge bg-secondary">NEWS</div>
                    @endif
                    <a href="{{ route('news.show', $article->slug) }}">
                        <img src="{{ asset('images/articles/'.$article->image) }}"
                            class="card-img-top"
                            alt="{{ $article->title }}">

                    </a>

                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($article->title, 70) }}</h5>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="news-date">{{ $article->created_at->format('d M Y') }}</div>
                        <span class="text-muted">{{ $article->created_at->diffForHumans() }}</span>
                        <br>
                        <small class="text-muted">By {{ $article->author }}</small>
                        <br>
                        <small class="text-muted">
                            <i class="bi bi-eye"></i> {{ $article->views_count ?? 0 }} views
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        new Swiper(".mySwiper", {
            slidesPerView: 6,
            spaceBetween: 5,
            loop: true,
            speed: 1000,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                576: {
                    slidesPerView: 2,
                    spaceBetween: 10
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 15
                },
                992: {
                    slidesPerView: 4,
                    spaceBetween: 15
                },
                1200: {
                    slidesPerView: 5,
                    spaceBetween: 15
                },
                1400: {
                    slidesPerView: 6,
                    spaceBetween: 15
                }
            }
        });
    });
</script>