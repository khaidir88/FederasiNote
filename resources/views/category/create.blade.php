@extends('layouts.app')

@section('title', 'Tambah Kategori Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Tambah Kategori Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="color" class="form-label">Warna <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror"
                                id="color" name="color" value="{{ old('color', '#6c757d') }}" title="Pilih warna">
                            <input type="text" class="form-control @error('color') is-invalid @enderror"
                                id="color_hex" name="color_hex" value="{{ old('color', '#6c757d') }}"
                                maxlength="7" placeholder="#000000">
                        </div>
                        @error('color')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pilih warna untuk kategori ini. Warna akan digunakan sebagai badge pada artikel.</div>
                    </div>

                    {{-- TAMBAHKAN PARENT CATEGORY FIELD --}}
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Kategori</label>
                        <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                            <option value="">-- Tidak Ada (Menu Utama) --</option>

                            @foreach($categories as $parentCategory)
                            @php
                            $level = 0;
                            $tempParent = $parentCategory->parent;
                            while ($tempParent) {
                            $level++;
                            $tempParent = $tempParent->parent;
                            }
                            @endphp

                            <option value="{{ $parentCategory->id }}"
                                {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                {!! str_repeat('&nbsp;&nbsp;&nbsp;', $level) !!}
                                {{ $parentCategory->name }}
                                @if($level > 0)
                                (Level {{ $level }})
                                @endif
                            </option>
                            @endforeach
                        </select>

                        @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            Pilih parent kategori untuk menjadikannya Sub Menu / Child.
                            Jika dikosongkan maka menjadi Menu Utama.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="link" class="form-label">Custom Link</label>
                        <input class="form-control @error('link') is-invalid @enderror"
                            id="link" name="link">{{ old('link') }}</input>
                        @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Masukkan Link untuk mengakses sub menu.
                        </div>
                    </div>
                    {{-- TAMBAHKAN STATUS AKTIF --}}
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Kategori Aktif
                            </label>
                        </div>
                        <div class="form-text">
                            Nonaktifkan jika kategori tidak ingin ditampilkan.
                        </div>
                    </div>


                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Kategori
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
        const colorPicker = document.getElementById('color');
        const colorHex = document.getElementById('color_hex');

        // Sync color picker and hex input
        colorPicker.addEventListener('input', function() {
            colorHex.value = this.value.toUpperCase();
        });

        colorHex.addEventListener('input', function() {
            let value = this.value.trim();

            // Tambah # jika belum ada
            if (value && !value.startsWith('#')) {
                value = '#' + value;
                this.value = value;
            }

            if (value.match(/^#[0-9A-Fa-f]{6}$/i)) {
                colorPicker.value = value;
            }
        });

        // Format hex input saat blur
        colorHex.addEventListener('blur', function() {
            let value = this.value.trim();

            if (!value) {
                value = '#6c757d';
                this.value = value;
                colorPicker.value = value;
                return;
            }

            if (!value.startsWith('#')) {
                value = '#' + value;
            }

            // Convert 3-digit hex ke 6-digit
            if (value.match(/^#[0-9A-Fa-f]{3}$/i)) {
                value = '#' +
                    value[1] + value[1] +
                    value[2] + value[2] +
                    value[3] + value[3];
            }

            // Validasi final
            if (value.match(/^#[0-9A-Fa-f]{6}$/i)) {
                this.value = value.toUpperCase();
                colorPicker.value = this.value;
            } else {
                // Reset ke warna default jika invalid
                this.value = '#6c757d';
                colorPicker.value = '#6c757d';
            }
        });

        // Auto-generate slug dari nama (opsional)
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        if (nameInput && slugInput) {
            nameInput.addEventListener('blur', function() {
                if (!slugInput.value) {
                    // Generate slug dari nama
                    const slug = this.value
                        .toLowerCase()
                        .replace(/[^\w\s]/gi, '')
                        .replace(/\s+/g, '-');
                    slugInput.value = slug;
                }
            });
        }
    });
</script>

<style>
    .form-control-color {
        height: 38px;
        padding: 2px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem 0 0 0.375rem;
    }

    .input-group>.form-control-color {
        flex: 0 0 auto;
        width: 50px;
    }

    /* Style untuk dropdown parent dengan indentasi */
    option {
        padding: 8px 12px;
    }

    option[data-level="1"] {
        padding-left: 30px;
    }

    option[data-level="2"] {
        padding-left: 45px;
    }

    option[data-level="3"] {
        padding-left: 60px;
    }
</style>
@endpush