@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number text-primary">{{ $totalArticles }}</div>
                        <div class="text-muted">Total Artikel</div>
                    </div>
                    <div class="stat-icon text-primary">
                        <i class="bi bi-file-text"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number text-success">{{ $totalCategories }}</div>
                        <div class="text-muted">Kategori</div>
                    </div>
                    <div class="stat-icon text-success">
                        <i class="bi bi-bookmarks"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number text-warning">{{ $totalComments }}</div>
                        <div class="text-muted">Komentar</div>
                    </div>
                    <div class="stat-icon text-warning">
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number text-info">{{ $totalUsers }}</div>
                        <div class="text-muted">Pengguna</div>
                    </div>
                    <div class="stat-icon text-info">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Articles -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Artikel Terbaru</h5>
                <a href="{{ route('berita.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($recentArticles as $news)
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('berita.show', $news->slug) }}" class="text-decoration-none" target="_blank">
                                        {{ Str::limit($news->title, 50) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    {{ $news->created_at->diffForHumans() }} •
                                    <span class="badge bg-secondary">{{ $news->category->name ?? 'Uncategorized' }}</span>
                                </small>
                            </div>
                            <span class="badge {{ $news->publish_at && $news->publish_at <= now() ? 'bg-primary' : 'bg-warning' }}">
                                {{ $news->publish_at && $news->publish_at <= now() ? 'Published' : 'Draft' }}
                            </span>


                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Comments -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Komentar Terbaru</h5>
                <a href="{{ route('comments.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($recentComments as $comment)
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $comment->name }}</h6>
                                <p class="mb-1 small">{{ Str::limit($comment->content, 60) }}</p>
                                <small class="text-muted">
                                    Pada: @if($comment->article)
                                    <a href="{{ route('news.show', $comment->news->slug) }}" target="_blank">
                                        Lihat Artikel
                                    </a>
                                    @else
                                    <span class="text-muted">Artikel sudah dihapus</span>
                                    @endif
                                </small>
                            </div>
                            <span class="badge {{ $comment->approved ? 'bg-success' : 'bg-warning' }}">
                                {{ $comment->approved ? 'Approved' : 'Pending' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Popular Articles -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Artikel Populer</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($popularArticles as $news)
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none" target="_blank">
                                        {{ Str::limit($news->title, 50) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    {{ $news->views_count }} views •
                                    {{ $news->category->name ?? 'Uncategorized' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Articles by Category -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Artikel per Kategori</h5>
            </div>
            <div class="card-body">

                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

@if(session('akses_ditolak'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: 'Maaf, anda tidak dapat mengakses halaman admin!',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endpush
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const categoryLabels = !!json_encode($articlesByCategory - > pluck('name')) !!;
        const categoryData = !!json_encode($articlesByCategory - > pluck('news_count')) !!;

        const ctxCategory = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxCategory, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Jumlah Artikel',
                    data: categoryData,
                    backgroundColor: '#0d6efd',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>

@endsection