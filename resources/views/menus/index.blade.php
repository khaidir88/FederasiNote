@extends('layouts.app')

@section('title', 'Manajemen Menu')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-list me-2"></i> Manajemen Menu</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMenuModal">
            <i class="bi bi-plus-circle me-1"></i> Tambah Menu
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>

                        <th>Nama</th>
                        <th>Parent</th>
                        <th>Posisi</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                    @include('menus.partials.row', [
                    'menu' => $menu,
                    'level' => 0
                    ])
                    @endforeach
                    @forelse($menus as $menu)

                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            <i class="bi bi-info-circle me-1"></i> Belum ada menu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>


        </div>
    </div>
</div>

{{-- ================= MODAL CREATE ================= --}}
<div class="modal fade" id="createMenuModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('menus.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Menu</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @include('menus.form')
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div class="modal fade" id="editMenuModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editMenuForm" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Menu</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @include('menus.form')
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('editMenuModal'));
            const form = document.getElementById('editMenuForm');

            form.action = `/menus/${btn.dataset.id}`;

            form.querySelector('[name=name]').value = btn.dataset.name;
            form.querySelector('[name=parent_id]').value = btn.dataset.parent ?? '';
            form.querySelector('[name=position]').value = btn.dataset.position;
            form.querySelector('[name=order]').value = btn.dataset.order;
            form.querySelector('[name=is_active]').checked = btn.dataset.active == 1;

            modal.show();
        });
    });

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => {
            Swal.fire({
                title: `Hapus menu "${btn.dataset.name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, hapus'
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/menus/${btn.dataset.id}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush