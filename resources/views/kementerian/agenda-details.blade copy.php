@extends('layouts.guest')

@section('title', $agenda->judul)

@section('content')
<div class="container py-5">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent px-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dinas.index') }}">Agenda</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $agenda->judul }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <img src="{{ asset($agenda->foto ?? 'images/noimage.jpg') }}"
            class="card-img-top" style="max-height:350px;object-fit:cover">

        <div class="card-body">
            <h2 class="fw-bold">{{ $agenda->judul }}</h2>

            <p class="text-muted mb-1">
                <i class="bi bi-calendar"></i>
                {{ $agenda->created_at->diffForHumans() }}
            </p>

            <p class="text-muted">
                <i class="bi bi-building"></i>
                {{ $agenda->dinas->nama ?? '-' }}
            </p>

            <hr>

            <p class="fs-6">
                {!! nl2br(e($agenda->deskripsi)) !!}
            </p>
        </div>
    </div>
</div>
@endsection