@extends('layouts.guest')

@section('title', 'Daftar Kementerian')

@section('content')
<div class="container py-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-2">🏢 Daftar Kementerian</h2>
        <p class="text-muted mb-0">Berikut daftar kementerian yang terdaftar secara nasional.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- Pencarian --}}
            <div class="mb-4 d-flex justify-content-end">
                <div class="input-group" style="max-width: 350px;">
                    <span class="input-group-text bg-primary text-white">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari kementerian...">
                </div>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center" id="kementerianTable">
                    <thead class="table-primary">
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Nama Kementerian</th>
                            <th>Deskripsi</th>
                            <th>Alamat</th>
                            <th>Website</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kementerians as $index => $kementerian)
                        <tr>
                            <td>{{ $kementerians->firstItem() + $index }}</td>
                            <td class="fw-semibold text-start">
                                <a href="{{ route('kementerian.showdinas', $kementerian->slug) }}"
                                    class="text-decoration-none text-primary hover-link">
                                    <i class="bi bi-bank me-1"></i> {{ $kementerian->nama }}
                                </a>
                            </td>
                            <td class="text-start">{{ Str::limit($kementerian->deskripsi, 80) ?: '-' }}</td>
                            <td class="text-start">{{ Str::limit($kementerian->alamat, 60) ?: '-' }}</td>
                            <td>
                                @if($kementerian->link)
                                <a href="{{ $kementerian->link }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-link-45deg"></i> Situs
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-info-circle"></i> Belum ada data kementerian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $kementerians->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Styling & Script --}}
@push('styles')
<style>
    .table thead th {
        vertical-align: middle;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f7ff !important;
        transition: 0.2s ease;
    }

    .hover-link:hover {
        text-decoration: underline;
    }

    #searchInput:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
        border-color: #0d6efd;
    }
</style>
@endpush

@push('scripts')
<script>
    // Fungsi pencarian langsung (client-side)
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