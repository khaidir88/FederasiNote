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
                                        <img src="{{ asset('public/images/articles/' . $article->image) }}"
                                            class="card-img-top"
                                            alt="{{ $article->title }}"
                                            style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <div class="d-flex mb-2">
                                                <span class="badge bg-secondary me-2">
                                                    <i class="far fa-calendar me-1"></i>
                                                    {{ $article->published_at->format('d M') ?? '' }}
                                                </span>
                                                <span class="text-muted">
                                                    <i class="far fa-user me-1"></i>
                                                    {{ $article->author }}
                                                </span>
                                            </div>
                                            <h5 class="card-title">{{ Str::limit($article->title, 50) }}</h5>
                                            <p class="card-text text-muted">
                                                {{ Str::limit(strip_tags($article->content), 100) }}
                                            </p>
                                        </div>
                                        <div class="card-footer bg-white">
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
            </div> <!-- end of container -->
        </div> <!-- end of Halaman Profil -->

        @endsection