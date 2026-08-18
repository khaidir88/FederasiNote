<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-dark">Laporan Skrining {{ $user->name }}</h2>
    </x-slot>

    <div class="container py-4">
        <!-- Filter Form -->
        <form method="GET" class="row g-2 mb-3">
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
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="mb-3">
            <a href="{{ route('reports.user.export', ['user' => $user->id, 'type' => 'excel']) }}"
                class="btn btn-success me-2">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('reports.user.export', ['user' => $user->id, 'type' => 'pdf']) }}"
                class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Skor</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                    <tr>
                        <td>{{ $result->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $result->category?->name ?? 'N/A' }}</td>
                        <td>{{ $result->score_total }}</td>
                        <td>
                            <span class="badge bg-{{ $result->risk_status == 'Tinggi' ? 'danger' : ($result->risk_status == 'Sedang' ? 'warning' : 'success') }}">
                                {{ $result->risk_status ?? 'Belum Dinilai' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('reports.show', $result->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data skrining</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $results->links() }}
            </div>
        </div>
    </div>
</x-app-layout>