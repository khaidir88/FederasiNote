@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-xl font-semibold mb-4">Manajemen Testimoni</h2>

    @if(session('success'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('
            success ') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

    <table class="table-auto w-full text-sm border">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Pesan</th>
                <th class="px-4 py-2">Foto</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($testimonis as $testimoni)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $testimoni->name }}</td>
                <td class="px-4 py-2">{{ $testimoni->message }}</td>
                <td class="px-4 py-2">
                    <img src="{{ asset('images/testimoni/' . $testimoni->photo) }}" width="60">
                </td>
                <td class="px-4 py-2">
                    @if($testimoni->is_approved)
                    <span class="text-green-600">Approved</span>
                    @elseif($testimoni->is_rejected)
                    <span class="text-red-600">Rejected</span>
                    @else
                    <span class="text-yellow-600">Pending</span>
                    @endif
                </td>
                <td class="px-4 py-2 flex gap-2">
                    @if(!$testimoni->is_approved)
                    <form method="POST" action="{{ route('admin.testimoni.approve', $testimoni->id) }}">
                        @csrf
                        <button onclick="return confirm('Approve testimoni ini?')" class="bg-green-500 text-white px-3 py-1 rounded">Approve</button>
                    </form>
                    @endif

                    @if(!$testimoni->is_rejected)
                    <form method="POST" action="{{ route('admin.testimoni.reject', $testimoni->id) }}">
                        @csrf
                        <button onclick="return confirm('Reject testimoni ini?')" class="bg-red-500 text-white px-3 py-1 rounded">Reject</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $testimonis->links() }}
    </div>
</div>
@endsection