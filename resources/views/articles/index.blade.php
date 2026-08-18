@extends('layouts.app')

@section('title', 'Management Berita')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-newspaper me-2"></i>Management Berita</h2>
                <a href="{{ route('articles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Berita
                </a>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('articles.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari articles..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
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
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Arsip</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-white-50">Total Berita</h6>
                                    <h3>{{ $articles->total() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-newspaper fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-white-50">Published</h6>
                                    <h3>{{ \App\Models\Article::where('status', 'published')->count() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-circle fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-dark-50">Draft</h6>
                                    <h3>{{ \App\Models\Article::where('status', 'draft')->count() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-pencil fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-white-50">Arsip</h6>
                                    <h3>{{ \App\Models\Article::where('status', 'archived')->count() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-archive fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Penulis</th>
                                    <th>Views</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($articles as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($articles->currentPage() - 1) * $articles->perPage() }}</td>
                                    <td>
                                        <strong>{{ Str::limit($item->title, 60) }}</strong>
                                        @if($item->tags)
                                        @php
                                        $tags = $item->tags;

                                        // JSON → array
                                        if (is_string($tags)) {
                                        $decoded = json_decode($tags, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $tags = $decoded;
                                        } else {
                                        // string dipisah koma → jadikan array
                                        $tags = array_filter(array_map('trim', explode(',', $tags)));
                                        }
                                        }

                                        // Collection → array
                                        if ($tags instanceof \Illuminate\Support\Collection) {
                                        $tags = $tags->toArray();
                                        }

                                        // terakhir: pastikan jadi string
                                        $tagsString = is_array($tags) ? implode(', ', $tags) : (string) $tags;
                                        @endphp

                                        <small class="text-muted">
                                            Tags: {{ Str::limit($tagsString, 50) }}
                                        </small>
                                        @endif

                                    </td>
                                    <td>
                                        @if($item->category)
                                        <span class="badge" style="background-color: {{ $item->category->color ?? '#6c757d' }}; color: #fff;">
                                            {{ $item->category->name }}
                                        </span>
                                        @else
                                        <span class="badge bg-secondary">Tanpa Kategori</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status === 'published')
                                        @if($item->publish_at > now())
                                        <span class="badge bg-info">Terjadwal</span>
                                        @else
                                        <span class="badge bg-success">Published</span>
                                        @endif
                                        @elseif($item->status === 'draft')
                                        <span class="badge bg-warning text-dark">Draft</span>
                                        @else
                                        <span class="badge bg-secondary">Arsip</span>
                                        @endif
                                    </td>

                                    <td>{{ $item->author }}</td>
                                    <td>
                                        <i class="bi bi-eye me-1"></i>{{ $item->views }}
                                    </td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>
                                        <small class="text-muted">
                                            Created: {{ $item->created_at->format('d/m/Y') }}</Created:>
                                            @if($item->publish_at) </small>
                                        <br><small class="text-muted">Publish: {{ $item->publish_at->format('d/m/Y H:i') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('articles.show', $item->slug) }}"
                                                class="btn btn-sm btn-outline-primary" target="_blank"
                                                title="Preview">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <!-- Di index.blade.php -->
                                            <a href="{{ route('articles.edit', $item) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            @if($item->status === 'draft')
                                            <form action="{{ route('articles.publish', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    onclick="return confirm('Publish berita ini?')"
                                                    title="Publish">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                            @endif

                                            @if($item->status === 'published')
                                            <form action="{{ route('articles.unpublish', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-warning"
                                                    onclick="return confirm('Jadikan draft?')"
                                                    title="Jadikan Draft">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                            @endif

                                            @if($item->status !== 'archived')
                                            <form action="{{ route('articles.archive', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-info"
                                                    onclick="return confirm('Arsipkan berita ini?')"
                                                    title="Arsipkan">
                                                    <i class="bi bi-archive"></i>
                                                </button>
                                            </form>
                                            @endif

                                            <form action="{{ route('articles.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Hapus berita ini?')"
                                                    title="Hapus">
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