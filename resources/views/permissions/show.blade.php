@extends('layouts.app')

@section('title', 'Detail Permission')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-shield-check me-2"></i>
        Detail Permission
    </h2>

    <div>
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>

        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i>Edit
        </a>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Informasi Permission
                </h5>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted">Nama Permission</label>
                    <h5 class="mb-0">{{ $permission->name }}</h5>
                </div>

                <div class="mb-3">
                    <label class="text-muted">Deskripsi</label>
                    <p class="mb-0">
                        {{ $permission->description ?? 'Tidak ada deskripsi' }}
                    </p>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted">Module</label>
                        <p class="mb-0">
                            <span class="badge bg-info">{{ $permission->module ?? 'No Module' }}</span>
                        </p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="text-muted">Guard</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary">{{ $permission->guard_name }}</span>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row text-muted">
                    <div class="col-md-6">
                        <small>Dibuat:</small>
                        <p class="mb-0">
                            {{ $permission->created_at->format('d M Y H:i') }}
                            <br>
                            <span class="text-muted">{{ $permission->created_at->diffForHumans() }}</span>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <small>Diupdate:</small>
                        <p class="mb-0">
                            {{ $permission->updated_at->format('d M Y H:i') }}
                            <br>
                            <span class="text-muted">{{ $permission->updated_at->diffForHumans() }}</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Roles Yang Menggunakan Permission -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people me-1"></i>
                    Digunakan Oleh Roles
                </h5>
                <span class="badge bg-primary">{{ $permission->roles_count }} Roles</span>
            </div>

            <div class="card-body">
                @if($permission->roles->count() > 0)
                <ul class="list-group">
                    @foreach($permission->roles as $role)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person-badge me-1"></i>{{ $role->name }}</span>
                        <span class="badge bg-success">Active</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center text-muted py-3">
                    <i class="bi bi-exclamation-circle d-block mb-2 fs-2"></i>
                    Belum digunakan oleh Role manapun
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection