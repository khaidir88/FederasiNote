<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-dark">Laporan Skrining
            @unless(in_array(auth()->user()->role, ['admin', 'super admin','petugas']))
            - {{ auth()->user()->name }}
            @endunless
        </h2>
    </x-slot>

    <div class="container py-4">
        {{-- Filter Form --}}
        @if(in_array(auth()->user()->role, ['admin', 'super admin','petugas']))
        <form method="GET" class="row g-2 mb-3">
            <!-- Form filter untuk admin (sama seperti sebelumnya) -->
            <div class="col-md-3">
                <label class="form-label">Filter Waktu</label>
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Nama User</label>
                <select name="user" class="form-control select2">
                    <option value="">-- Semua User --</option>
                    @foreach($users as $user)
                    <option value="{{ $user->name }}" {{ request('user') == $user->name ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100">Terapkan Filter</button>
            </div>
        </form>
        @endif

        {{-- Tabel Laporan --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle table-sm text-sm">
                <thead class="table-light small">
                    <tr>
                        @if(in_array(auth()->user()->role, ['admin', 'super admin','petugas']))
                        <th class="text-sm">User</th>
                        @endif
                        <th class="text-sm">Tanggal</th>
                        <th class="text-sm">Nama</th>
                        <th class="text-sm">Skor</th>
                        <th class="text-sm">Sesi</th>
                        <th class="text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($results as $item)
                    <tr>
                        @if(in_array(auth()->user()->role, ['admin', 'super admin','petugas']))
                        <td class="text-sm">{{ $item->user->name ?? 'N/A' }}</td>
                        @endif
                        <td class="text-sm">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-sm">{{ $item->user->name ?? 'N/A' }}</td>
                        <td class="text-sm">{{ $item->score_total }}</td>
                        <td class="text-sm">{{ $item->screening_session }}</td>
                        <td>
                            {{-- Tombol Lihat (semua role bisa lihat) --}}
                            <a href="{{ route('reports.show', $item->id) }}"
                                class="btn btn-info btn-xs me-1" title="Lihat">
                                <i class="bi bi-eye-fill fs-6"></i>
                            </a>

                            {{-- Tombol lain hanya untuk admin, super admin, operator --}}
                            @if(in_array(auth()->user()->role, ['admin', 'super admin','petugas']))
                            <a href="{{ route('reports.excel', $item->id) }}"
                                class="btn btn-success btn-xs me-1" title="Excel">
                                <i class="bi bi-file-earmark-excel small"></i>
                            </a>

                            <a href="{{ route('reports.edit', $item->id) }}"
                                class="btn btn-warning btn-xs me-1" title="Edit">
                                <i class="bi bi-pencil-square small"></i>
                            </a>

                            <form action="{{ route('reports.destroy', $item->id) }}"
                                method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-danger btn-xs btn-delete" title="Hapus">
                                    <i class="bi bi-trash small"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="text-center text-muted small">
                            Tidak ada data laporan skrining.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Tangkap semua tombol delete
        const deleteButtons = document.querySelectorAll(".btn-delete");

        deleteButtons.forEach(button => {
            button.addEventListener("click", function(e) {
                e.preventDefault();

                let form = this.closest("form");

                Swal.fire({
                    title: 'Yakin hapus data ini?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>