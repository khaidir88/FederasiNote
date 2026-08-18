@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $category->name }}</h1>
    <p>{{ $category->description }}</p>
    <p>Status: {{ $category->is_active ? 'Aktif' : 'Non-aktif' }}</p>
    <p>Warna: <span style="color:{{ $category->color }}">{{ $category->color }}</span></p>

    <h3>Artikel dalam kategori ini:</h3>
    <ul>
        @forelse($category->articles as $article)
        <li>{{ $article->title }}</li>
        @empty
        <li>Belum ada artikel.</li>
        @endforelse
    </ul>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection