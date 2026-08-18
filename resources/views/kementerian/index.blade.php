@extends('layouts.guest')

@section('title', 'Daftar Kementerian')

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-2 text-gradient">
            🏢 Daftar Kementerian Republik Indonesia
        </h2>
        <p class="text-secondary">
            Temukan informasi kementerian beserta alamat, deskripsi & website resmi.
        </p>
    </div>

    <div class="card modern-card shadow border-0">
        <div class="card-body p-4">

            {{-- SEARCH --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h5 class="fw-bold mb-0">📋 Data Kementerian</h5>

                <div class="search-wrapper">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchInput"
                        class="search-input"
                        placeholder="Cari kementerian...">
                </div>
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table modern-table align-middle text-center" id="kementerianTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kementerian</th>
                            <th>Deskripsi</th>
                            <th>Alamat</th>
                            <th>Website</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($kementerians as $index => $kementerian)
                        <tr class="table-row">
                            <td class="fw-bold">{{ $kementerians->firstItem() + $index }}</td>

                            <td class="fw-semibold text-start">
                                <a href="{{ route('kementerian.showdinas', $kementerian->slug) }}"
                                    class="link-item">
                                    <i class="bi bi-bank2 me-1"></i>
                                    {{ $kementerian->nama }}
                                </a>
                            </td>

                            <td class="text-start text-secondary">
                                {{ Str::limit($kementerian->deskripsi, 90) ?: '-' }}
                            </td>

                            <td class="text-start text-secondary">
                                {{ Str::limit($kementerian->alamat, 70) ?: '-' }}
                            </td>

                            <td>
                                @if($kementerian->link)
                                <a href="{{ $kementerian->link }}"
                                    target="_blank"
                                    class="btn btn-sm btn-visit">
                                    <i class="bi bi-globe2"></i> Kunjungi
                                </a>
                                @else
                                <span class="badge bg-light text-muted">Tidak Ada</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5">
                                <div class="empty-box">
                                    <i class="bi bi-clipboard-data"></i>
                                    <p>Tidak ada data kementerian</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $kementerians->links('pagination::bootstrap-5') }}

            </div>
        </div>
    </div>
</div>

{{-- STYLE MODERN --}}
@push('styles')
<style>
    body {
        background: linear-gradient(140deg, #eef3ff, #ffffff);
    }

    /* Gradient Title */
    .text-gradient {
        background: linear-gradient(45deg, #0d6efd, #00b3ff);
        -webkit-background-clip: text;
        color: transparent;
    }

    /* Modern Card (Glass Feel) */
    .modern-card {
        border-radius: 18px;
        background: rgba(255, 255, 255, .9);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(220, 230, 255, .6);
    }

    /* Search */
    .search-wrapper {
        position: relative;
        width: 360px;
        max-width: 100%;
    }

    .search-icon {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 15px;
    }

    .search-input {
        width: 100%;
        padding: 10px 14px 10px 40px;
        border-radius: 50px;
        border: 1px solid #d1d9e6;
        font-size: 14px;
        transition: .25s;
        outline: none;
        background: #ffffff;
    }

    .search-input:hover {
        border-color: #8fb8ff;
    }

    .search-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .15);
    }

    /* Table */
    .modern-table {
        border-radius: 12px;
        overflow: hidden;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #e9f0ff, #f5f9ff);
    }

    .modern-table thead th {
        font-weight: 800;
        padding: 14px;
        color: #303b6b;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: .6px;
        border: none;
    }

    .modern-table tbody tr {
        transition: .25s;
    }

    .modern-table tbody tr:hover {
        background: #f3f8ff !important;
        transform: translateX(3px);
        box-shadow: 0 4px 14px rgba(13, 110, 253, .06);
    }

    /* Link Kementerian */
    .link-item {
        text-decoration: none;
        color: #0d6efd;
        transition: .2s;
        font-weight: 600;
    }

    .link-item:hover {
        text-decoration: underline;
        color: #0a58ca;
    }

    /* Button Website */
    .btn-visit {
        border-radius: 30px;
        border: 1px solid #0d6efd;
        color: #0d6efd;
        padding: 6px 14px;
        transition: .25s;
        font-size: 13px;
    }

    .btn-visit:hover {
        background: #0d6efd;
        color: white;
        transform: scale(1.05);
    }

    /* Empty Area */
    .empty-box {
        color: #9aa0b7;
    }

    .empty-box i {
        font-size: 45px;
    }

    /* PAGINATION CUSTOM BOOSTRAP */
    .page-link {
        border-radius: 50px !important;
        margin: 0 3px;
        border: none;
        padding: 8px 14px;
        color: #0d6efd;
        transition: .2s;
    }

    .page-item.active .page-link {
        background: linear-gradient(45deg, #0d6efd, #2ea6ff);
        border: none;
        color: white;
    }

    .page-link:hover {
        background: rgba(13, 110, 253, .12);
        color: #0d6efd;
    }

    /* Small shadow on table */
    .table-row {
        border-bottom: 1px solid #eef2ff;
    }
</style>
@endpush


{{-- SEARCH SCRIPT --}}
@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#kementerianTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection