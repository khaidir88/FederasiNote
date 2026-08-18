@extends('layouts.guest')

@section('title', 'Daftar Dinas Provinsi')

@section('content')
<div class="container py-5">

    {{-- Header Section --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-2">
            <i class="bi bi-buildings me-2"></i>Daftar Dinas Provinsi
        </h2>
        <p class="text-muted">Berikut daftar instansi pemerintahan tingkat provinsi yang terdaftar.</p>
    </div>

    {{-- Search Bar --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-primary text-white">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-primary"
                    placeholder="Cari nama dinas, struktur, atau keterangan...">
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card shadow border-0 rounded-4">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle text-center" id="dinasTable">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="w-1/8 text-center">No</th>
                        <th class="w-1/6 text-left">Dinas</th>
                        <th class="w-1/6 text-left">Keterangan</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($dinass as $index => $dinas)
                    <tr>
                        <td>{{ $dinass->firstItem() + $index }}</td>
                        <td class="fw-semibold text-start">
                            <a href="{{ route('kementerian.showdinas', $dinas->slug) }}"
                                class="text-decoration-none text-prim hover-underline">
                                {{ $dinas->nama }}
                            </a>
                        </td>
                        <td class="text-start text-muted">
                            <a href="{{ route('kementerian.showdinas', $dinas->slug) }}" class="text-decoration-none text-prim hover-underline">
                                {{ Str::limit($dinas->ket, 80) ?: '-' }}
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-info-circle me-1"></i> Belum ada data dinas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4 custom-pagination">
            @if ($dinass->hasPages())
            <nav>
                <ul class="pagination justify-content-center flex-wrap mb-0">
                    {{-- Tombol Sebelumnya --}}
                    @if ($dinass->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link dyn-page disabled-btn">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link dyn-page prev-btn" href="{{ $dinass->previousPageUrl() }}" rel="prev">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($dinass->links()->elements[0] ?? [] as $page => $url)
                    @if ($page == $dinass->currentPage())
                    <li class="page-item active">
                        <span class="page-link dyn-page active-btn">{{ $page }}</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link dyn-page" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endif
                    @endforeach

                    {{-- Tombol Berikutnya --}}
                    @if ($dinass->hasMorePages())
                    <li class="page-item">
                        <a class="page-link dyn-page next-btn" href="{{ $dinass->nextPageUrl() }}" rel="next">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link dyn-page disabled-btn">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </li>
                    @endif
                </ul>
            </nav>
            @endif
        </div>


    </div>
</div>

{{-- Custom Styles --}}
@push('styles')
<style>
    /* Header */
    h2 {
        letter-spacing: 0.5px;
    }

    /* Table */
    .table thead th {
        vertical-align: middle;
        font-weight: 600;
    }

    .table-hover tbody tr {
        transition: all 0.25s ease-in-out;
    }

    .table-hover tbody tr:hover {
        background-color: #f0f6ff !important;
        transform: scale(1.01);
    }

    /* Search Bar */
    #searchInput:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
        border-color: #0d6efd;
    }

    .hover-underline:hover {
        text-decoration: underline;
    }

    /* Pagination Styling */
    .custom-pagination nav {
        display: inline-block;
    }

    .pagination {
        gap: 8px;
        flex-wrap: wrap;
    }

    .dyn-page {
        border-radius: 50px !important;
        border: none;
        font-weight: 600;
        color: #0d6efd;
        background: #fff;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.25s ease;
        min-width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    .dyn-page:hover {
        background: linear-gradient(135deg, #0d6efd, #5a9bff);
        color: #fff;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 14px rgba(13, 110, 253, 0.35);
    }

    .active-btn {
        background: linear-gradient(135deg, #0d6efd, #3b82f6);
        color: #fff !important;
        box-shadow: 0 6px 12px rgba(13, 110, 253, 0.4);
        transform: scale(1.05);
    }

    .prev-btn i,
    .next-btn i {
        font-size: 1.1rem;
        line-height: 1;
    }

    .disabled-btn {
        background: #f1f3f5;
        color: #bbb !important;
        box-shadow: none;
        cursor: not-allowed;
    }


    .pagination .page-link {
        border-radius: 30px !important;
        border: none;
        color: #0d6efd;
        font-weight: 500;
        transition: all 0.25s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .pagination .page-link:hover {
        background-color: #0d6efd;
        color: #fff;
        transform: translateY(-2px);
    }

    .pagination .active .page-link {
        background-color: #0d6efd;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 3px 6px rgba(13, 110, 253, 0.3);
    }


    .pagination .disabled .page-link {
        color: #aaa;
        background-color: #f8f9fa;
        box-shadow: none;
    }

    /* Responsive */
    @media (max-width: 768px) {

        .table th,
        .table td {
            font-size: 0.9rem;
        }

        #searchInput {
            font-size: 0.9rem;
        }

        .pagination .page-link {
            padding: 6px 10px;
            font-size: 0.85rem;
        }

        .dyn-page {
            min-width: 36px;
            height: 36px;
            font-size: 0.85rem;
        }
    }
</style>
@endpush

{{-- Search Script --}}
@push('scripts')
<script>
    // Live Search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#dinasTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection