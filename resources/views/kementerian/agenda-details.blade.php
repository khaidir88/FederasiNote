@extends('layouts.guest')

@section('title', $agenda->judul)

@section('content')
<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent px-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dinas.index') }}">Agenda</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $agenda->judul }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <img src="{{ asset($agenda->foto ?? 'images/noimage.jpg') }}"
                    class="card-img-top"
                    style="height:400px;object-fit:cover">

                <div class="card-body">

                    <h1 class="fw-bold mb-5">{{ $agenda->judul ?? '-' }}</h1>

                    <p class="text-muted mb-1">
                        <i class="bi bi-calendar"></i>
                        {{ $agenda->created_at->diffForHumans() }}
                    </p>
                    <p class="text-muted mb-1">
                        <i class="bi bi-building"></i>
                        {{ $agenda->author->name ?? '-' }}
                    </p>
                    <p class="text-muted">
                        <i class="bi bi-globe2"></i>

                        @if(!empty($agenda->dinas->link))
                        <a href="{{ Str::startsWith($agenda->dinas->link, ['http://', 'https://']) 
                    ? $agenda->dinas->link 
                    : 'https://' . $agenda->dinas->link }}"
                            target="_blank"
                            rel="noopener noreferrer">
                            {{ $agenda->dinas->link }}
                        </a>
                        @else
                        -
                        @endif
                    </p>


                    <hr>

                    <p class="fs-6">
                        {!! nl2br(e($agenda->deskripsi)) !!}
                    </p>
                </div>
            </div>
        </div>

        {{-- ================= SIDEBAR ================= --}}
        <div class="col-lg-4">

            {{-- Agenda Lainnya --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-calendar-week me-2"></i> Agenda Lainnya
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($agendaLainnya as $item)
                    <li class="list-group-item">
                        <a href="{{ route('kementerian.agenda-details', ['slug' => $item->slug]) }}"
                            class="text-decoration-none d-block">
                            <strong>{{ $item->judul }}</strong>
                            <br>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i>
                                {{ $item->created_at->format('d M Y') }}
                            </small>
                        </a>
                    </li>
                    @empty
                    <li class="list-group-item text-muted text-center">
                        Tidak ada agenda lain.
                    </li>
                    @endforelse
                </ul>
            </div>

            {{-- Dinas Lainnya --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-buildings me-2"></i> Dinas Lainnya
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($dinasLainnya as $d)
                    <li class="list-group-item">
                        <a href="{{ route('kementerian.dinas', $d->slug) }}"
                            class="text-decoration-none d-flex align-items-center">
                            @if($d->logo)
                            <img src="{{ asset($d->logo) }}"
                                class="rounded me-2"
                                width="30" height="30"
                                style="object-fit:cover;">
                            @else
                            <div class="rounded bg-secondary text-white d-flex align-items-center justify-content-center me-2"
                                style="width:30px;height:30px;">
                                <i class="bi bi-building"></i>
                            </div>
                            @endif

                            <div>
                                <div class="fw-medium">{{ $d->nama }}</div>
                                <small class="text-muted">{{ $d->agendas_count }} agenda</small>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection