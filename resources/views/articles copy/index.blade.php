@extends('layouts.app')

@section('title', 'Management Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Management Artikel</h2>
    <a href="{{ route('articles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Tambah Artikel
    </a>
</div>

<!-- Debug Info -->
@if(env('APP_DEBUG'))
<div class="alert alert-info">
    <strong>Debug Info:</strong>
    Current URL: {{ url()->current() }} | Route: {{ Route::currentRouteName() }}
</div>
@endif

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('articles.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari artikel..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Articles Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Sub Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Video</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $article)
                    <tr>
                        <td>
                            <strong>{{ Str::limit($article->title, 50) }}</strong><br>
                            <small class="text-muted">Oleh: {{ $article->author }}</small>
                        </td>
                        <td>
                            <strong>{{ Str::limit($article->subtitle, 50) }}</strong><br>

                        </td>

                        <td>
                            @if($article->category)
                            <span class="badge" style="background-color: {{ $article->category->color }}; color: #fff;">
                                {{ $article->category->name }}
                            </span>
                            @else
                            <span class="badge bg-secondary">Uncategorized</span>
                            @endif
                        </td>

                        <td>
                            @if($article->status === 'published')
                            <span class="badge bg-success">Published</span>
                            @if($article->published_at > now())
                            <br><small class="text-warning">(Scheduled)</small>
                            @endif
                            @else
                            <span class="badge bg-warning">Draft</span>
                            @endif
                        </td>

                        <td>{{ $article->views ?? 0 }}</td>

                        <!-- Kolom Video -->
                        <td>
                            @if (!empty($article->video_url))
                            @php
                            $videoId = null;
                            // Tangani berbagai format URL YouTube
                            if (preg_match('/(?:youtu\.be\/|v=|embed\/)([^&?]+)/', $article->video_url, $matches)) {
                            $videoId = $matches[1];
                            }
                            @endphp

                            @if ($videoId)
                            <a href="https://www.youtube.com/watch?v={{ $videoId }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-danger"
                                title="Lihat di YouTube">
                                <i class="bi bi-youtube"></i>
                            </a>
                            @else
                            <span class="badge bg-warning text-dark">Link tidak valid</span>
                            @endif
                            @else
                            <span class="badge bg-secondary">-</span>
                            @endif
                        </td>


                        <td>
                            <small>Created: {{ $article->created_at->format('d M Y') }}</small>
                            @if($article->published_at)
                            <br><small class="text-muted">Publish: {{ $article->published_at->format('d M Y H:i') }}</small>
                            @endif
                        </td>

                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Preview">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                @if($article->status === 'draft')
                                <form action="{{ route('articles.publish', $article) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Publish artikel {{ $article->title }}?')" title="Publish Artikel">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('articles.unpublish', $article) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Jadikan draft artikel {{ $article->title }}?')" title="Jadikan Draft">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus artikel {{ $article->title }}?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links() }}
        </div>
    </div>
</div>

<!-- Toast Notification -->
@if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('successToast')) new bootstrap.Toast('#successToast').show();
        if (document.getElementById('errorToast')) new bootstrap.Toast('#errorToast').show();
    });
</script>
@endpush