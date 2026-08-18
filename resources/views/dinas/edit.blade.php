@extends('layouts.app')

@section('title', 'Edit Agenda')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Edit Agenda</h4>
        <form action="{{ route('dinas.update', $dinas) }}" method="POST">
            @csrf
            @method('PUT')
            @include('dinas.form', ['dinas' => $dinas])
            <button class="btn btn-primary mt-3">Perbarui</button>
            <a href="{{ route('dinas.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
</div>
@endsection