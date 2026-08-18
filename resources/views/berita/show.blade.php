@extends('layouts.app')

@section('title', $news->title)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Berita
                </a>
            </div>

            <!-- News Card -->
            <div class="card shadow-lg">
                <!-- Image -->
                @if($news->image)
                <div class="card-img-top text-center p-3 bg-light">
                    <img src="{{ Storage::exists('public/' . $news->image) ? asset('storage/' . $news->image) : asset('storage/news/' . $news->image) }}"
                        alt="{{ $news->title }}"
                        class="img-fluid rounded"
                        style="max-height: 400px; object-fit: cover;">
                </div>
                @endif

                <div class="card-body p-4">
                    <!-- Meta Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            @if($news->category)
                            <span class="badge" style="background-color: {{ $news->category->color ?? '#6c757d' }}; color: #fff;">
                                {{ $news->category->name }}
                            </span>
                            @endif

                            <span class="badge ms-2 
                                @if($news->status === 'published') bg-success
                                @elseif($news->status === 'draft') bg-warning text-dark
                                @else bg-secondary @endif">
                                {{ strtoupper($news->status) }}
                            </span>
                        </div>

                        <div class="text-muted">
                            <i class="bi bi-eye me-1"></i>{{ $news->views }} views
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="card-title display-6 mb-3">{{ $news->title }}</h1>

                    <!-- Author & Date -->
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <div>
                            <i class="bi bi-person-circle me-1"></i>
                            <strong>{{ $news->author }}</strong>
                        </div>
                        <div class="text-muted">
                            <i class="bi bi-calendar-event me-1"></i>
                            @if($news->publish_at)
                            {{ $news->publish_at->format('d F Y H:i') }}
                            @else
                            {{ $news->created_at->format('d F Y H:i') }}
                            @endif
                        </div>
                    </div>

                    <!-- Video (if any) -->
                    @if($news->video_url)
                    <div class="mb-4">
                        <div class="ratio ratio-16x9">
                            @php
                            $videoId = null;
                            if (preg_match('/(?:youtu\.be\/|v=|embed\/)([^&?]+)/', $news->video_url, $matches)) {
                            $videoId = $matches[1];
                            }
                            @endphp

                            @if($videoId)
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                title="{{ $news->title }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                            @else
                            <a href="{{ $news->video_url }}" target="_blank" class="btn btn-danger">
                                <i class="bi bi-youtube me-1"></i> Tonton di YouTube
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="news-content mb-4">
                        {!! $news->content !!}
                    </div>

                    <!-- Tags -->
                    @if($news->tags && count($news->tags) > 0)
                    <div class="mb-4">
                        <h6 class="mb-2">Tags:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($news->tags as $tag)
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-tag me-1"></i>{{ $tag }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Meta Description -->
                    @if($news->meta_description)
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Meta Description:</h6>
                            <p class="card-text">{{ $news->meta_description }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Additional Info -->
                    <div class="row mt-4 pt-4 border-top">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Informasi Berita</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>ID:</strong> {{ $news->id }}</li>
                                        <li><strong>Slug:</strong> {{ $news->slug }}</li>
                                        <li><strong>Dibuat:</strong> {{ $news->created_at->format('d/m/Y H:i') }}</li>
                                        <li><strong>Diupdate:</strong> {{ $news->updated_at->format('d/m/Y H:i') }}</li>
                                        @if($news->deleted_at)
                                        <li class="text-danger"><strong>Dihapus:</strong> {{ $news->deleted_at->format('d/m/Y H:i') }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Statistik</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Status:</strong>
                                            @if($news->status === 'published')
                                            @if($news->publish_at > now())
                                            <span class="text-info">Terjadwal</span>
                                            @else
                                            <span class="text-success">Published</span>
                                            @endif
                                            @elseif($news->status === 'draft')
                                            <span class="text-warning">Draft</span>
                                            @else
                                            <span class="text-secondary">Arsip</span>
                                            @endif
                                        </li>
                                        <li><strong>Views:</strong> {{ $news->views }}</li>
                                        <li><strong>Penulis:</strong> {{ $news->author }}</li>
                                        <li><strong>Kategori:</strong> {{ $news->category->name ?? 'Tidak ada' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center mt-4">
                        <div class="btn-group" role="group">
                            <a href="{{ route('news.edit', $news) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>

                            @if($news->status === 'draft')
                            <form action="{{ route('news.publish', $news) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Publish
                                </button>
                            </form>
                            @endif

                            @if($news->status === 'published')
                            <form action="{{ route('news.unpublish', $news) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Draft
                                </button>
                            </form>
                            @endif

                            @if($news->status !== 'archived')
                            <form action="{{ route('news.archive', $news) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-info">
                                    <i class="bi bi-archive me-1"></i>Arsipkan
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('news.destroy', $news) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Hapus berita ini?')">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>

</script>

@push('styles')
<style>
    .news-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .news-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .news-content p {
        margin-bottom: 1.5rem;
    }

    .news-content h2,
    .news-content h3,
    .news-content h4 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
</style>
@endpush