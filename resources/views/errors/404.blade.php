@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 text-center">
    <div>
        <h1 class="text-5xl font-bold text-red-600 mb-4">403</h1>
        <p class="text-xl font-semibold mb-2">Maaf, anda tidak dapat mengakses halaman ini.</p>
        <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Kembali ke Dashboard</a>
    </div>
</div>
@endsection