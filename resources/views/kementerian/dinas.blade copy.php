@extends('layouts.guest')

@section('title', 'Detail Dinas - ' . $dinas->nama)

@section('content')
<div class="container py-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent px-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dinas.index') }}">Dinas</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $dinas->nama }}</li>
        </ol>
    </nav>

    {{-- Header Dinas --}}


    <div class="row g-4">
        {{-- Konten Utama - Daftar Agenda --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-calendar-week me-2"></i> Agenda dari {{ $dinas->nama }}
                    <span class="badge bg-primary ms-2">{{ $dinas->agendas->count() }}</span>
                </div>

                <div class="card-body p-0">
                    @if($dinas->agendas->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada agenda dari dinas ini.</p>
                    </div>
                    @else
                    <div class="list-group list-group-flush">
                        @foreach($dinas->agendas as $agenda)
                        <div class="list-group-item border-0 py-4">
                            <div class="row g-3">
                                {{-- Gambar Agenda --}}
                                <div class="col-md-3">
                                    <a href="{{ route('kementerian.agenda-details', ['slug' => $agenda->slug]) }}" class="d-block">
                                        <img src="{{ asset($agenda->foto ?? 'images/noimage.jpg') }}"
                                            alt="{{ $agenda->judul }}"
                                            class="img-fluid rounded"
                                            style="object-fit: cover; width: 100%; height: 150px;">
                                    </a>

                                </div>

                                {{-- Konten Agenda --}}
                                <div class="col-md-9">
                                    <div class="d-flex flex-column h-100">
                                        {{-- Judul dengan link ke agenda-details --}}
                                        <h4 class="fw-bold mb-2">
                                            <a href="{{ route('kementerian.agenda-details', $agenda->slug) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $agenda->judul }}
                                            </a>
                                        </h4>

                                        {{-- Info Singkat --}}
                                        <div class="mb-2">
                                            @if($agenda->tanggal)
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($agenda->tanggal)->format('d M Y') }}
                                            </span>
                                            @endif
                                            @if($agenda->lokasi)
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                {{ Str::limit($agenda->lokasi, 30) }}
                                            </span>
                                            @endif
                                        </div>

                                        {{-- Deskripsi Singkat --}}
                                        <p class="text-secondary mb-3 flex-grow-1">
                                            {{ Str::limit(strip_tags($agenda->deskripsi), 120) }}
                                        </p>

                                        {{-- Footer dengan link ke agenda-details --}}
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <a href="{{ route('kementerian.agenda-details', $agenda->slug) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-arrow-right me-1"></i> Detail Agenda
                                            </a>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $agenda->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pagination jika menggunakan paginate() --}}
                    @if($dinas->agendas instanceof \Illuminate\Pagination\LengthAwarePaginator && $dinas->agendas->hasPages())
                    <div class="card-footer border-0 bg-light">
                        {{ $dinas->agendas->links() }}
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Dinas Lainnya --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-buildings me-2"></i> Dinas Lainnya
                </div>
                <ul class="list-group list-group-flush">
                    @foreach(\App\Models\Dinas::where('slug', '!=', $dinas->slug)->take(5)->get() as $lain)
                    <li class="list-group-item">
                        <a href="{{ route('kementerian.dinas', $lain->slug) }}"
                            class="text-decoration-none d-flex align-items-center">
                            @if($lain->logo)
                            <img src="{{ asset($lain->logo) }}"
                                alt="{{ $lain->nama }}"
                                class="rounded me-2"
                                width="30" height="30" style="object-fit: cover;">
                            @else
                            <div class="rounded bg-secondary me-2 d-flex align-items-center justify-content-center"
                                style="width:30px; height:30px;">
                                <i class="bi bi-building text-white"></i>
                            </div>
                            @endif
                            <div>
                                <div class="fw-medium">{{ $lain->nama }}</div>
                                <small class="text-muted">{{ $lain->agendas_count ?? 0 }} agenda</small>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Kategori --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-tags me-2"></i> Kategori Dinas
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('kementerian.kota') }}"
                            class="btn btn-outline-primary btn-sm px-3 py-2">
                            <i class="bi bi-building me-1"></i> Dinas Kota
                        </a>
                        <a href="{{ route('kementerian.provinsi') }}"
                            class="btn btn-outline-success btn-sm px-3 py-2">
                            <i class="bi bi-building-fill me-1"></i> Dinas Provinsi
                        </a>
                    </div>
                </div>
            </div>

            {{-- Info Kontak Dinas --}}
            @if($dinas->telepon || $dinas->email || $dinas->website)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-info-circle me-2"></i> Informasi Kontak
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @if($dinas->telepon)
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2 text-primary"></i>
                            <small>{{ $dinas->telepon }}</small>
                        </li>
                        @endif
                        @if($dinas->email)
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2 text-primary"></i>
                            <small>{{ $dinas->email }}</small>
                        </li>
                        @endif
                        @if($dinas->website)
                        <li>
                            <i class="bi bi-globe me-2 text-primary"></i>
                            <small>
                                <a href="{{ $dinas->website }}" target="_blank" class="text-decoration-none">
                                    Kunjungi Website
                                </a>
                            </small>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            @endif

            {{-- Cari Agenda --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Cari Agenda</h6>
                    <form action="{{ route('kementerian.search-agenda') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari agenda..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s;
    }

    .card-img-link {
        display: block;
        overflow: hidden;
        border-radius: 8px;
    }

    .card-img-link img {
        transition: transform 0.3s ease;
    }

    .card-img-link:hover img {
        transform: scale(1.05);
    }
</style>
@endsection