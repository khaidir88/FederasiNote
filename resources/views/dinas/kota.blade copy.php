@extends('layouts.guest')

@section('title', 'Daftar Dinas Kota')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Daftar Dinas Kota</h2>
        <p class="text-muted mb-0">Berikut adalah daftar instansi pemerintahan tingkat kota.</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width:5%">#</th>
                        <th>Nama Dinas</th>
                        <th>Struktur</th>
                        <th>Keterangan</th>
                        <th>Link Website</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dinass as $index => $dinas)
                    <tr>
                        <td class="text-center">{{ $dinass->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('dinas.showdinas', $dinas->slug) }}" class="text-decoration-none fw-bold text-primary">
                                {{ $dinas->nama }}
                            </a>


                        </td>
                        <td>{{ Str::limit($dinas->struktur, 60) ?: '-' }}</td>
                        <td>{{ Str::limit($dinas->ket, 80) ?: '-' }}</td>
                        <td class="text-center">
                            @if($dinas->link)
                            <a href="{{ $dinas->link }}" target="_blank" class="btn btn-sm btn-outline-primary">
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
                            <i class="bi bi-info-circle"></i> Belum ada data dinas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $dinass->links() }}
            </div>
        </div>
    </div>
</div>
@endsection