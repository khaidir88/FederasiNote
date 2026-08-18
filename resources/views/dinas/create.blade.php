@extends('layouts.app')

@section('title', 'Tambah Agenda')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Tambah Agenda</h4>
        <form action="{{ route('dinas.store') }}" method="POST">
            @csrf
            @include('dinas.form')
            <button class="btn btn-primary mt-3">Simpan</button>
            <a href="{{ route('dinas.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
</div>
@endsection