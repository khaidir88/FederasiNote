{{-- resources/views/permissions/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Management Permissions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Management Permissions</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
        <i class="bi bi-shield-plus me-1"></i>Tambah Permission
    </button>
</div>

<!-- Debug Info -->
@if(env('APP_DEBUG'))
<div class="alert alert-info">
    <strong>Debug Info:</strong>
    Permissions Count: {{ $permissions->count() }} |
    Total: {{ $permissions->total() }} |
    Modules: {{ $modules->count() }}
</div>
@endif

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number text-primary">{{ $permissions->total() }}</div>
                        <div class="text-muted">Total Permissions</div>
                    </div>
                    <div class="stat-icon text-primary">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        @php
                        $totalPermissions = \Spatie\Permission\Models\Permission::count();
                        @endphp
                        <div class="stat-number text-success">{{ $totalPermissions }}</div>
                        <div class="text-muted">All Permissions</div>
                    </div>
                    <div class="stat-icon text-success">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number text-info">{{ $modules->count() }}</div>
                        <div class="text-muted">Total Modules</div>
                    </div>
                    <div class="stat-icon text-info">
                        <i class="bi bi-folder"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        @php
                        $mostUsedPermission = \Spatie\Permission\Models\Permission::withCount('roles')
                        ->orderBy('roles_count', 'desc')
                        ->first();
                        @endphp
                        <div class="stat-number text-warning">{{ $mostUsedPermission ? $mostUsedPermission->roles_count : 0 }}</div>
                        <div class="text-muted">Most Used</div>
                    </div>
                    <div class="stat-icon text-warning">
                        <i class="bi bi-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Permissions Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Permissions</h5>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari permission...">
            <select id="moduleFilter" class="form-select form-select-sm">
                <option value="">Semua Module</option>
                @foreach($modules as $module)
                <option value="{{ $module }}">{{ $module }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="card-body">
        @if($permissions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover" id="permissionsTable">
                <thead>
                    <tr>
                        <th>Permission</th>
                        <th>Module</th>
                        <th>Guard</th>
                        <th>Roles</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                    <tr data-module="{{ $permission->module }}">
                        <td>
                            <div>
                                <strong class="d-block">{{ $permission->name }}</strong>
                                @if($permission->description)
                                <small class="text-muted">{{ Str::limit($permission->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $permission->module ?? 'No Module' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $permission->guard_name }}</span>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $permission->roles_count }} Role</span>
                        </td>
                        <td>
                            <small>
                                {{ $permission->created_at->format('d M Y') }}<br>
                                <span class="text-muted">{{ $permission->created_at->diffForHumans() }}</span>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('permissions.show', $permission) }}"
                                    class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('permissions.edit', $permission) }}"
                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus permission {{ $permission->name }}?')"
                                        title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $permissions->links() }}
        </div>
        @else
        <div class="text-center py-4">
            <div class="text-muted">
                <i class="bi bi-shield-slash display-4 d-block mb-2"></i>
                Belum ada permission yang dibuat.
            </div>
            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                <i class="bi bi-shield-plus me-1"></i>Tambah Permission Pertama
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Permission Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Permission <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}" required
                            placeholder="contoh: create articles, edit users, delete categories">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: action resource (lowercase dengan spasi)</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="2"
                            placeholder="Deskripsi singkat tentang permission ini">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="module" class="form-label">Module <span class="text-danger">*</span></label>
                        <select id="module" name="module" class="form-select" required>
                            <option value="">Pilih Module</option>
                            @foreach($modules as $module)
                            <option value="{{ $module }}" {{ old('module') == $module ? 'selected' : '' }}>
                                {{ ucfirst($module) }}
                            </option>
                            @endforeach
                        </select>

                        @error('module')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="guard_name" class="form-label">Guard <span class="text-danger">*</span></label>
                        <select class="form-select @error('guard_name') is-invalid @enderror" id="guard_name" name="guard_name" required>
                            <option value="web" {{ old('guard_name', 'web') == 'web' ? 'selected' : '' }}>Web</option>
                            <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>API</option>
                        </select>
                        @error('guard_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#permissionsTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    // Module filter
    document.getElementById('moduleFilter').addEventListener('change', function() {
        const moduleValue = this.value;
        const rows = document.querySelectorAll('#permissionsTable tbody tr');

        rows.forEach(row => {
            if (moduleValue === '') {
                row.style.display = '';
            } else {
                const rowModule = row.getAttribute('data-module');
                row.style.display = rowModule === moduleValue ? '' : 'none';
            }
        });
    });
</script>

<style>
    .stat-card {
        border-left: 4px solid transparent;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-card.border-primary {
        border-left-color: #0d6efd;
    }

    .stat-card.border-success {
        border-left-color: #198754;
    }

    .stat-card.border-info {
        border-left-color: #0dcaf0;
    }

    .stat-card.border-warning {
        border-left-color: #ffc107;
    }

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
</style>
@endpush
@endsection