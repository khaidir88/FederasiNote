@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Permission</h2>
    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Permissions
    </a>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Debug Info -->
@if(env('APP_DEBUG'))
<div class="alert alert-info">
    <strong>Debug Info:</strong>
    Permission ID: {{ $permission->id }} |
    Current Name: {{ $permission->name }} |
    Guard: {{ $permission->guard_name }}
</div>
@endif

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Edit Permission: {{ $permission->name }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('permissions.update', $permission->id) }}" method="POST" id="editPermissionForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Permission <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name', $permission->name) }}" required
                            placeholder="contoh: create articles, edit users">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: action resource (lowercase dengan spasi)</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="3"
                            placeholder="Deskripsi singkat tentang permission ini">{{ old('description', $permission->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="module" class="form-label">Module <span class="text-danger">*</span></label>
                                <select class="form-select @error('module') is-invalid @enderror" id="module" name="module" required>
                                    <option value="">Pilih Module</option>
                                    @foreach($modules as $module)
                                    <option value="{{ $module }}" {{ old('module', $permission->module) == $module ? 'selected' : '' }}>
                                        {{ $module }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('module')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="guard_name" class="form-label">Guard <span class="text-danger">*</span></label>
                                <select class="form-select @error('guard_name') is-invalid @enderror" id="guard_name" name="guard_name" required>
                                    <option value="web" {{ old('guard_name', $permission->guard_name) == 'web' ? 'selected' : '' }}>Web</option>
                                    <option value="api" {{ old('guard_name', $permission->guard_name) == 'api' ? 'selected' : '' }}>API</option>
                                </select>
                                @error('guard_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Permission Info Card -->
                    <div class="card bg-light mt-4">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="bi bi-info-circle me-2"></i>Informasi Permission
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Dibuat:</small>
                                    <p class="mb-1">{{ $permission->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Diupdate:</small>
                                    <p class="mb-1">{{ $permission->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Digunakan oleh:</small>
                                <p class="mb-0">
                                    <span class="badge bg-primary">{{ $permission->roles_count }} Role</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-check-circle me-1"></i>Update Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editPermissionForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            console.log('Form submission started...');

            // Show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Memproses...';
            submitBtn.disabled = true;

            // Log form data for debugging
            const formData = new FormData(this);
            console.log('Form Data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            // Allow form to submit normally
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>

<style>
    .spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .card {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
    }

    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.25rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@endpush