@extends('layouts.app')

@section('title', 'Tambah Permission Baru')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-shield-plus me-2"></i>Tambah Permission Baru</h2>
        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong><i class="bi bi-shield-lock me-2"></i>Form Permission</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Nama Permission -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Permission <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required
                            placeholder="contoh: create articles, edit users, delete categories">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Gunakan format: <code>action resource</code> (huruf kecil, spasi boleh)</small>
                    </div>

                    <!-- Module -->
                    <div class="col-md-6 mb-3">
                        <label for="module" class="form-label">Module <span class="text-danger">*</span></label>
                        <select id="module" name="module" class="form-select @error('module') is-invalid @enderror" required>
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
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Deskripsi singkat tentang permission ini">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Guard -->
                <div class="mb-3">
                    <label for="guard_name" class="form-label">Guard <span class="text-danger">*</span></label>
                    <select id="guard_name" name="guard_name" class="form-select @error('guard_name') is-invalid @enderror" required>
                        <option value="web" {{ old('guard_name', 'web') == 'web' ? 'selected' : '' }}>Web</option>
                        <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>API</option>
                    </select>
                    @error('guard_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border-radius: 1rem;
    }

    .card-header {
        font-size: 1.1rem;
        font-weight: 600;
    }

    input,
    select,
    textarea {
        border-radius: 0.5rem;
    }

    button i {
        vertical-align: middle;
    }
</style>
@endpush
@endsection