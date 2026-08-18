@extends('layouts.guest')
@section('content')

<!-- Halaman Profile -->
<div class="basic-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-container">
                    <section class="py-5 bg-light">
                        <div class="container">
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <h2 class="fw-bold">Berita Terkini</h2>
                                    <p class="text-muted">Update terbaru dari kami</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ route('berita.index') }}" class="btn btn-outline-primary">
                                        Lihat Semua Berita
                                    </a>
                                </div>
                            </div>

                            <div class="row g-4">
                                @foreach($latestArticles as $article)
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm">
                                        {{-- Badge Kategori dengan Warna dari Database --}}
                                        @if($article->category)
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span class="badge" style="background-color: {{ $article->category->color }}; color: white;">
                                                {{ $article->category->name }}
                                            </span>
                                        </div>
                                        @endif

                                        <img src="{{ asset('public/images/articles/' . $article->image) }}"
                                            class="card-img-top"
                                            alt="{{ $article->title }}"
                                            style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted small">
                                                    <i class="far fa-calendar me-1"></i>
                                                    {{ $article->published_at->format('d M Y') ?? $article->created_at->format('d M Y') }}
                                                </span>
                                                <span class="text-muted small">
                                                    <i class="far fa-user me-1"></i>
                                                    {{ $article->author }}
                                                </span>
                                            </div>
                                            <h5 class="card-title">{{ Str::limit($article->title, 50) }}</h5>
                                            <p class="card-text text-muted">
                                                {{ Str::limit(strip_tags($article->content), 100) }}
                                            </p>
                                        </div>
                                        <div class="card-footer bg-white border-0 pt-0">
                                            <a href="{{ route('tampil', $article->id) }}"
                                                class="btn btn-sm btn-primary">
                                                Baca Selengkapnya
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of basic-2 -->

@endsection