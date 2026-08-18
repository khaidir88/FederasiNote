@extends('layouts.app')

@section('title', 'Management Komentar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Management Komentar</h2>
    <div>
        <span class="badge bg-warning me-2">Pending: {{ $pendingCount }}</span>
        <a href="{{ route('comments.index', ['status' => 'pending']) }}" class="btn btn-outline-warning btn-sm">Lihat Pending</a>
        <a href="{{ route('comments.index', ['status' => 'approved']) }}" class="btn btn-outline-success btn-sm">Lihat Approved</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Komentar</th>
                        <th>Artikel</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comments as $comment)
                    <tr>
                        <td>
                            <div>
                                <p class="mb-1">{{ Str::limit($comment->content, 80) }}</p>
                                <small class="text-muted">{{ $comment->email }}</small>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('berita.show', $comment->news->slug) }}" target="_blank" class="text-decoration-none">
                                {{ Str::limit($comment->news->title, 40) }}
                            </a>
                        </td>
                        <td>{{ $comment->name }}</td>
                        <td>
                            <span class="badge {{ $comment->approved ? 'bg-success' : 'bg-warning' }}">
                                {{ $comment->approved ? 'Approved' : 'Pending' }}
                            </span>
                        </td>
                        <td>
                            <small>{{ $comment->created_at->format('d M Y H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group">
                                @if(!$comment->approved)
                                <form action="{{ route('comments.approve', $comment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('comments.unapprove', $comment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-x-circle"></i> Batal Approve
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('comments.reject', $comment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus komentar ini?')">
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
            {{ $comments->links() }}
        </div>
    </div>
</div>
@endsection