@extends('layouts.guest')

@section('title', 'Detail Dinas - ' . $dinas->nama)

@section('content')
<div class="container py-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent px-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kementerian.index') }}">Dinas</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $dinas->nama }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Konten Utama --}}
        <div class="col-lg-8">
            <article class="card border-0 shadow-sm">
                @if($dinas->foto)
                <img src="{{ asset($dinas->foto) }}"
                    class="card-img-top rounded-top"
                    alt="{{ $dinas->nama }}">
                @else
                <img src="{{ asset('images/default-dinas.jpg') }}"
                    class="card-img-top rounded-top"
                    alt="Default Dinas">
                @endif

                <div class="card-body p-4">
                    <h2 class="fw-bold mb-3">{{ $dinas->nama }}</h2>

                    <div class="mb-3 text-muted">
                        <i class="bi bi-tags me-1"></i>
                        <span class="badge {{ $dinas->kategori == 'kota' ? 'bg-primary' : 'bg-success' }}">
                            {{ ucfirst($dinas->kategori) }}
                        </span>
                        <span class="ms-2">
                            <i class="bi bi-calendar-event me-1"></i>
                            {{ $dinas->created_at->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    <hr>

                    @if($dinas->struktur)
                    <div class="mb-4">
                        <h5 class="fw-bold text-secondary mb-2">Struktur Organisasi</h5>
                        <p class="text-justify">{!! nl2br(e($dinas->struktur)) !!}</p>
                    </div>
                    @endif

                    @if($dinas->ket)
                    <div class="mb-4">
                        <h5 class="fw-bold text-secondary mb-2">Keterangan</h5>
                        <p class="text-justify">{!! nl2br(e($dinas->ket)) !!}</p>
                    </div>
                    @endif

                    @if($dinas->link)
                    <div class="mb-3">
                        <a href="{{ $dinas->link }}" target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-link-45deg"></i> Kunjungi Situs Resmi
                        </a>
                    </div>
                    @endif
                </div>
            </article>

            {{-- Daftar Agenda dari Dinas Ini --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-calendar-week me-2"></i> Agenda dari {{ $dinas->nama }}
                </div>

                <div class="card-body">
                    @if($dinas->agendas->isEmpty())
                    <p class="text-muted mb-0">Belum ada agenda dari dinas ini.</p>
                    @else
                    @foreach($dinas->agendas as $agenda)
                    <div class="d-flex mb-3 pb-3 border-bottom">
                        <div class="flex-shrink-0 me-3" style="width:100px; height:80px;">
                            <img src="{{ asset($agenda->foto ?? 'images/noimage.jpg') }}"
                                alt="{{ $agenda->judul }}"
                                class="img-fluid rounded"
                                style="object-fit:cover; width:100%; height:100%;">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">
                                {{ $agenda->judul }}
                            </h6>

                            <p class="mt-2 mb-0 text-secondary small">
                                {{ Str::limit($agenda->deskripsi, 80) }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">
                    Dinas Lainnya
                </div>
                <ul class="list-group list-group-flush">
                    @foreach(\App\Models\Dinas::where('slug', '!=', $dinas->slug)->take(5)->get() as $lain)
                    <li class="list-group-item">
                        <a href="{{ route('kementerian.showdinas', $lain->slug) }}" class="text-decoration-none">
                            <i class="bi bi-building me-1"></i> {{ $lain->nama }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">Kategori</h6>
                    <a href="{{ route('kementerian.kota') }}" class="badge bg-primary text-decoration-none me-1">Dinas Kota</a>
                    <a href="{{ route('kementerian.provinsi') }}" class="badge bg-success text-decoration-none">Dinas Provinsi</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection