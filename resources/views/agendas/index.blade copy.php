@extends('layouts.app')

@section('title', 'Daftar Agenda')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-event me-2"></i> Daftar Agenda</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAgendaModal">
            <i class="bi bi-plus-circle me-1"></i> Tambah Agenda
        </button>
    </div>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th width="5%">#</th>
                        <th>Judul</th>
                        <th>Dinas</th>
                        <th>Keterangan</th>
                        <th>Kategori</th>
                        <th width="12%">Tanggal</th>
                        <th>Author</th>
                        <th>Foto</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agendas as $agenda)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $agenda->judul }}</td>
                        <td>{{ $agenda->dinas->nama ?? '-' }}</td>
                        <td>{{ Str::limit($agenda->deskripsi, 20) }}</td>
                        <td>
                            @if($agenda->dinas)
                            {{ $agenda->dinas->kota ?? '-' }} / {{ $agenda->dinas->provinsi ?? '-' }}
                            @else
                            -
                            @endif
                        </td>


                        <td class="text-center">{{ \Carbon\Carbon::parse($agenda->tanggal_mulai)->format('d/m/Y') }}</td>
                        <td>{{ $agenda->author->name ?? '-' }}</td>
                        <td class="text-center">
                            @if($agenda->foto)
                            <div style="width:50px; height:50px; overflow:hidden; border-radius:6px; margin:auto;">
                                <img src="{{ asset($agenda->foto) }}" alt="Foto Agenda"
                                    style="width:100%; height:100%; object-fit:cover;">
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning btn-edit"
                                data-id="{{ $agenda->id }}"
                                data-dinas="{{ $agenda->dinas_id }}"
                                data-judul="{{ $agenda->judul }}"
                                data-deskripsi="{{ $agenda->deskripsi }}"
                                data-foto="{{ $agenda->foto }}"
                                data-link="{{ $agenda->link }}"
                                data-video="{{ $agenda->video }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete"
                                data-id="{{ $agenda->id }}"
                                data-judul="{{ $agenda->judul }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">
                            <i class="bi bi-info-circle me-1"></i> Belum ada agenda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $agendas->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Modal Create --}}
<div class="modal fade" id="createAgendaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('agendas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Agenda Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('agendas.form', ['dinasList' => $dinasList])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}

<div class="modal fade" id="editAgendaModal" tabindex="-1" aria-labelledby="editAgendaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editAgendaForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editAgendaLabel">Edit Agenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @include('agendas.form', ['dinasList' => $dinasList])
                    {{-- Preview foto lama --}}
                    <div class="mt-3" id="previewFotoContainer" style="display:none;">
                        <label class="form-label fw-semibold">Foto Sebelumnya:</label>
                        <div class="border rounded p-2 text-center">
                            <img id="previewFoto" src="" alt="Foto Sebelumnya"
                                class="img-fluid rounded"
                                style="max-height:150px; object-fit:cover;">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 4px 8px;
    }

    .select2-selection__arrow {
        height: 34px !important;
    }

    .select2-container--default .select2-results__option--highlighted {
        background-color: #0d6efd;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ✅ Aktifkan Select2 di semua select dinas jika ada
        $('select[name="dinas_id"]').select2({
            placeholder: "-- Pilih Dinas --",
            allowClear: true,
            width: '100%'
        });

        // ✅ Tombol Edit Agenda
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();

                const modal = new bootstrap.Modal(document.getElementById('editAgendaModal'));
                const form = document.getElementById('editAgendaForm');

                const id = btn.dataset.id;
                const dinasId = btn.dataset.dinas;
                const judul = btn.dataset.judul;
                const deskripsi = btn.dataset.deskripsi;
                const link = btn.dataset.link;
                const video = btn.dataset.video;
                const foto = btn.dataset.foto;

                // Ganti action form ke URL update
                form.action = `/agendas/${id}`;

                // Isi field form
                form.querySelector('input[name="judul"]').value = judul;
                form.querySelector('textarea[name="deskripsi"]').value = deskripsi || '';
                form.querySelector('input[name="link"]').value = link || '';
                form.querySelector('input[name="video"]').value = video || '';

                // ✅ Update field Dinas (versi Live Search)
                const hiddenDinas = form.querySelector('#selectedDinasId');
                const inputSearch = form.querySelector('#searchDinas');
                const dinasList = @json($dinasList);
                const selected = dinasList.find(d => d.id == dinasId);

                if (selected) {
                    hiddenDinas.value = selected.id;
                    inputSearch.value = selected.nama;
                    inputSearch.disabled = true; // ⛔ Disable agar tidak bisa diganti
                }

                // ✅ Preview foto lama
                const previewContainer = document.getElementById('previewFotoContainer');
                const previewImg = document.getElementById('previewFoto');
                if (foto) {
                    previewContainer.style.display = 'block';
                    previewImg.src = `/${foto}`;
                } else {
                    previewContainer.style.display = 'none';
                }

                // ✅ Pastikan dropdown Dinas tertutup saat modal dibuka
                const dropdown = form.querySelector('#dinasDropdown');
                if (dropdown) dropdown.style.display = 'none';

                modal.show();
            });
        });

        // ✅ Reset disable field saat modal edit ditutup
        const editModalEl = document.getElementById('editAgendaModal');
        editModalEl.addEventListener('hidden.bs.modal', function() {
            const inputSearch = this.querySelector('#searchDinas');
            const hiddenDinas = this.querySelector('#selectedDinasId');
            if (inputSearch) {
                inputSearch.disabled = false;
                inputSearch.value = '';
            }
            if (hiddenDinas) hiddenDinas.value = '';
        });

        // ✅ Tombol Hapus Agenda (SweetAlert)
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const id = btn.dataset.id;
                const judul = btn.dataset.judul;

                Swal.fire({
                    title: `Hapus agenda "${judul}"?`,
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then(result => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/agendas/${id}`;
                        form.innerHTML = `@csrf @method('DELETE')`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush