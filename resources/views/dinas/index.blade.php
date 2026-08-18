@extends('layouts.app')

@section('title', 'Manajemen Dinas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Dinas</h2>
    @can ('Create Dinas')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDinasModal">
        <i class="bi bi-plus-circle me-1"></i>Tambah Dinas
    </button>
    @endcan
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('dinas.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="kota" {{ request('kategori') == 'kota' ? 'selected' : '' }}>Kota</option>
                        <option value="provinsi" {{ request('kategori') == 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="kementerian" {{ request('kategori') == 'kementerian' ? 'selected' : '' }}>Kementerian</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th style="width:40%;">Nama Dinas</th>
                    <th>Kategori</th>
                    <th>Struktur</th>
                    <th style="width:15%;">Keterangan</th>
                    <th>Link</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dinass as $dinas)
                <tr>
                    <td><strong>{{Str::limit( $dinas->nama, 35) }}</strong></td>
                    <td>
                        <span class="badge {{ $dinas->kategori == 'kota' ? 'bg-primary' : 'bg-success' }}">
                            {{ ucfirst($dinas->kategori) }}
                        </span>
                    </td>
                    <td>{{ Str::limit($dinas->struktur, 25) }}</td>
                    <td>{{ Str::limit($dinas->ket, 25) }}</td>
                    <td>
                        @if($dinas->link)
                        <a href="{{ $dinas->link }}" target="_blank" class="text-decoration-none">
                            <i class="bi bi-link-45deg"></i> Buka
                        </a>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @can ('Edit Dinas')
                        <button class="btn btn-sm btn-outline-secondary editBtn"
                            data-id="{{ $dinas->id }}"
                            data-nama="{{ $dinas->nama }}"
                            data-kategori="{{ $dinas->kategori }}"
                            data-struktur="{{ $dinas->struktur }}"
                            data-ket="{{ $dinas->ket }}"
                            data-link="{{ $dinas->link }}"
                            data-bs-toggle="modal" data-bs-target="#editDinasModal">
                            <i class="bi bi-pencil"></i>
                        </button>
                        @endcan
                        @can ('Delete Dinas')
                        <button class="btn btn-sm btn-outline-danger deleteBtn"
                            data-id="{{ $dinas->id }}"
                            data-nama="{{ $dinas->nama }}"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i>
                        </button>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $dinass->links() }}
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addDinasModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('dinas.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Dinas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Dinas</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="kota">Kota</option>
                        <option value="provinsi">Provinsi</option>
                        <option value="kementerian">Kementerian</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Struktur</label>
                    <textarea name="struktur" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="ket" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Link Web</label>
                    <input type="url" name="link" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editDinasModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content" id="editDinasForm">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Dinas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit-id">
                <div class="mb-3">
                    <label class="form-label">Nama Dinas</label>
                    <input type="text" name="nama" id="edit-nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" id="edit-kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="kota">Kota</option>
                        <option value="provinsi">Provinsi</option>
                        <option value="kementerian">Kementerian</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Struktur</label>
                    <textarea name="struktur" id="edit-struktur" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="ket" id="edit-keterangan" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Link Web</label>
                    <input type="url" name="link" id="edit-link" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content" id="deleteForm">
            @csrf @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">Hapus Dinas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus <strong id="deleteNama"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-id').value = this.dataset.id;
                document.getElementById('edit-nama').value = this.dataset.nama;
                document.getElementById('edit-kategori').value = this.dataset.kategori;
                document.getElementById('edit-struktur').value = this.dataset.struktur;
                document.getElementById('edit-keterangan').value = this.dataset.ket;
                document.getElementById('edit-link').value = this.dataset.link;
                document.getElementById('editDinasForm').action = '/dinas/' + this.dataset.id;
            });
        });

        // Delete
        document.querySelectorAll('.deleteBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('deleteNama').innerText = this.dataset.nama;
                document.getElementById('deleteForm').action = '/dinas/' + this.dataset.id;
            });
        });
    });
</script>
@endpush