@extends('layouts.app')

@section('title', 'Management Menu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Management Menu</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Tambah Menu
    </a>
</div>

<!-- Debug Info (Hanya untuk development) -->
@if(env('APP_DEBUG'))
<div class="alert alert-info">
    <strong>Debug Info:</strong>
    Current URL: {{ url()->current() }} |
    Route: {{ Route::currentRouteName() }}
</div>
@endif

<!-- Categories Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Level</th>
                        <th>Jumlah Artikel</th>
                        <th>Warna</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>
                            <div>
                                <strong>
                                    {!! str_repeat('&mdash; ', $category->level) !!} {{ $category->name }}
                                </strong>

                                @if($category->parent)
                                <br>
                                <small class="text-muted">
                                    Parent: {{ $category->parent->name }}
                                </small>
                                @endif

                            </div>
                        </td>
                        <td>
                            <span class="badge 
    {{ $category->level == 1 ? 'bg-danger' : 'bg-primary' }}">
                                {{ $category->level_name }}
                            </span>

                        </td>

                        <td>
                            <span class="badge bg-primary">{{ $category->news_count }} Artikel</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="color-preview me-2"
                                    style="width: 20px; height: 20px; background-color: {{ $category->color }}; border: 1px solid #ddd; border-radius: 3px;"></div>
                                <span>{{ $category->color }}</span>
                            </div>
                        </td>
                        <td>
                            @if($category->is_active)
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $category->created_at->format('d M Y') }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <!-- Lihat Artikel dalam Kategori -->
                                <a href="{{ route('category.show', $category) }}"
                                    class="btn btn-sm btn-outline-info" title="Lihat Artikel">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <!-- Edit Kategori -->
                                <a href="{{ route('categories.edit', $category) }}"
                                    class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <!-- Hapus Kategori -->
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus kategori {{ $category->name }}?')"
                                        title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                Belum ada kategori yang dibuat.
                            </div>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle me-1"></i>Tambah Kategori Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Success/Error Messages -->
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
        // Initialize toasts
        const successToast = document.getElementById('successToast');
        const errorToast = document.getElementById('errorToast');

        if (successToast) {
            new bootstrap.Toast(successToast).show();
        }

        if (errorToast) {
            new bootstrap.Toast(errorToast).show();
        }

        // Add loading state to buttons
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Processing...';
                    submitBtn.disabled = true;
                }
            });
        });
    });
</script>

<style>
    .spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .color-preview {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush