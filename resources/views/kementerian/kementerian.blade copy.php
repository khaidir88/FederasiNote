@extends('layouts.guest')

@section('title', 'Daftar Kementerian')

@section('content')
<div class="container py-5">

    {{-- Header Section --}}
    <div class="text-center mb-3">
        <h2 class="mb-2">
            <i class="bi bi-buildings me-2"></i>Daftar Kementerian
        </h2>
        <p class="text-muted">Berikut daftar instansi pemerintahan tingkat kota yang terdaftar.</p>
    </div>

    {{-- Search Bar --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="input-group shadow-sm">
                <span class="input-group-text text-white">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control "
                    placeholder="Cari nama dinas, struktur, atau keterangan...">
                <button class="btn btn-outline-secondary d-none" type="button" id="clearSearch">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card shadow border-0 rounded-0">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle text-center" id="dinasTable">
                <thead>
                    <tr>
                        <th style="width:5%;">#</th>
                        <th style="width:40%;">Nama Kementerian</th>
                        <th style="width:10%;">Struktur</th>
                        <th style="width:40%;">Keterangan</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($dinass as $index => $dinas)
                    <tr>
                        <td>{{ $dinass->firstItem() + $index }}</td>
                        <td class="fw-semibold text-start">
                            <a href="{{ route('kementerian.dinas', $dinas->slug) }}"
                                class="text-decoration-none text-prim hover-underline">
                                {{ $dinas->nama }}
                            </a>
                        </td>
                        <td class="text-start text-muted">
                            <a href="{{ route('kementerian.dinas', $dinas->slug) }}" class="text-decoration-none text-prim hover-underline">
                                {{ Str::limit($dinas->struktur, 30) ?: '-' }}
                            </a>
                        </td>
                        <td class="text-start text-muted">
                            <a href="{{ route('kementerian.dinas', $dinas->slug) }}" class="text-decoration-none text-prim hover-underline">
                                {{ Str::limit($dinas->ket, 50) ?: '-' }}
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
                        <a class="dyn-page prev-btn" href="{{ $dinass->previousPageUrl() }}" rel="prev">
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
                        <a class="dyn-page next-btn" href="{{ $dinass->nextPageUrl() }}" rel="next">
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
    /* Header Text */
    h2 {
        color: #acacacff;
    }

    .text-muted {
        color: #b9c2d0 !important;
    }

    /* === TABLE DARK STYLE === */
    .table {
        color: #dcdcdc;
    }

    /* .table thead {
        background: linear-gradient(135deg, #0d6efd, #1f53ff);
        border-radius: 12px !important;
    } */

    .table thead th {
        /* color: white !important; */
        color: #0b0f19;
        background: #9e9e9e;
        font-size: 1rem;
        padding: 10px 0;
        border: none;
    }

    .table tbody tr {
        background: transparent;
        border-bottom: 1px solid #1f2a44;
        transition: all .3s ease;
    }

    /* .table tbody tr:hover {
        background: rgba(13, 110, 253, 0.15) !important;
        transform: translateY(-1px) scale(1.01);
    } */

    /* Nama dinas */
    .table a {
        color: #6ea8ff;
    }

    .table a:hover {
        color: #ffffff;
    }

    /* === SEARCH BAR DARK === */
    .input-group-text {
        /* background: #111b2e; */
        border: 1px solid #9e9e9e;
        color: #cecdcdff;
    }

    #searchInput {
        background: #ffffffff;
        color: #4b4b4bff;
        border: 1px solid #9e9e9e;
        box-shadow: 0 0 10px rgba(5, 5, 5, 0.4);
    }

    #searchInput::placeholder {
        color: #acacacff;
    }

    #searchInput:focus {
        border-color: #fcff36ff;
        box-shadow: 0 0 10px rgba(253, 249, 2, 0.4);
    }

    #clearSearch {
        background: #111b2e;
        color: #ccc;
        border: 1px solid fcff36ff;
    }

    #clearSearch:hover {
        background: #ff4d4d;
        border-color: #ff4d4d;
    }

    /* === PAGINATION DARK === */

    .dyn-page {
        background: #6d6d6dff;
        color: #bdbdbdff !important;
        border: 1px solid #aaaaaaff;
        margin: 15px 5px;

    }

    .dyn-page:hover {
        background: linear-gradient(135deg, #0d6efd, #5a9bff);
        color: white !important;
        box-shadow: 0 8px 18px rgba(13, 110, 253, 0.5);
        transform: translateY(-1px) scale(1.01);
    }

    .active-btn {
        background: linear-gradient(135deg, #0d6efd, #1a4dff);
        color: white !important;
        border: none;
        box-shadow: 0 8px 18px rgba(22, 22, 22, 0.6);
    }

    .disabled-btn {
        background: #d1d1d1ff !important;
        width: 40px;
        height: 40px;
        border-radius: 50% !important;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 !important;
        color: #0a0a0aff;
        /* biar icon kelihatan */
        border: none !important;
        margin-right: 15px;
        /* hilangkan border bootstrap */
    }

    .prev-btn {
        background: #0b0f19 !important;
        width: 40px;
        height: 40px;
        border-radius: 50% !important;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 !important;
        color: #fff;
        /* biar icon kelihatan */
        border: none !important;
        margin-right: 15px;
        /* hilangkan border bootstrap */
    }


    .next-btn {
        background: #0b0f19 !important;
        width: 40px;
        height: 40px;
        border-radius: 50% !important;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 !important;
        color: #fff;
        /* biar icon kelihatan */
        border: none !important;
        margin-left: 10px;
        /* hilangkan border bootstrap */
    }

    /* Breadcrumb / text kecil agar tetap kebaca */
    .breadcrumb a {
        color: #82a7ff;
    }

    .breadcrumb a:hover {
        color: white;
    }

    /* Responsive tweak dark */
    @media (max-width: 768px) {
        .dyn-page {
            background: #0f1629;
            border: 1px solid #22335c;
        }

        .table thead th {
            /* color: white !important; */
            color: #0b0f19;
            background: #9e9e9e;
            font-size: 0.5rem;
            padding: 10px 0;
            border: none;
        }
    }
</style>
@endpush
@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    const rows = document.querySelectorAll('#dinasTable tbody tr');
    const pagination = document.querySelector('.custom-pagination');

    function performSearch() {
        const filter = searchInput.value.toLowerCase();
        let visibleCount = 0;

        rows.forEach(row => {
            const nama = row.children[1].innerText.toLowerCase();
            const struktur = row.children[2].innerText.toLowerCase();
            const ket = row.children[3].innerText.toLowerCase();

            if (nama.includes(filter) || struktur.includes(filter) || ket.includes(filter)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // tombol clear
        clearSearch.classList.toggle('d-none', searchInput.value.length === 0);

        // kalau searching → sembunyikan pagination
        if (filter.length > 0) {
            pagination.style.display = 'none';
        } else {
            pagination.style.display = 'flex';
        }

        // jika tidak ada hasil, tampilkan pesan
        if (visibleCount === 0) {
            if (!document.getElementById('noResultRow')) {
                const tbody = document.querySelector('#dinasTable tbody');
                const tr = document.createElement('tr');
                tr.id = 'noResultRow';
                tr.innerHTML = `
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="bi bi-info-circle me-1"></i> Data tidak ditemukan
                </td>`;
                tbody.appendChild(tr);
            }
        } else {
            const noResultRow = document.getElementById('noResultRow');
            if (noResultRow) noResultRow.remove();
        }
    }

    searchInput.addEventListener('keyup', performSearch);

    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        performSearch();
        searchInput.focus();
    });

    // hide clear button saat load
    document.addEventListener('DOMContentLoaded', function() {
        clearSearch.classList.add('d-none');
    });
</script>

@endpush
@endsection