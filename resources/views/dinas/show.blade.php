@extends('layouts.app')

@section('title', 'Detail Agenda')

@section('content')
<div class="card">
    <div class="card-body">
        <h4>{{ $dinas->name }}</h4>
        <p><strong>Kategori:</strong> {{ ucfirst($dinas->kategori) }}</p>
        <p><strong>Keterangan:</strong> {{ $dinas->ket ?? '-' }}</p>
        <p><strong>Struktur:</strong> {{ $dinas->struktur ?? '-' }}</p>
        <p><strong>Link Web:</strong>
            @if($dinas->link_web)
            <a href="{{ $dinas->link }}" target="_blank">{{ $dinas->link }}</a>
            @else
            -
            @endif
        </p>
        <a href="{{ route('dinas.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection