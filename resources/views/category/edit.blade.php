@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                {{-- Tampilkan hierarki seperti di index --}}
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            {!! str_repeat('&mdash; ', $category->level) !!} {{ $category->name }}
                        </h4>
                        @if($category->parent)
                        <small class="text-muted">
                            Parent: {{ $category->parent->name }}
                        </small>
                        @endif
                    </div>
                    <div>
                        <span class="badge bg-warning">{{ $category->level_name }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="color" class="form-label">Warna <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror"
                                id="color" name="color" value="{{ old('color', $category->color) }}" title="Pilih warna">
                            <input type="text" class="form-control @error('color') is-invalid @enderror"
                                id="color_hex" value="{{ old('color', $category->color) }}" maxlength="7"
                                pattern="^#[0-9A-Fa-f]{6}$" placeholder="#000000">
                        </div>
                        @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pilih warna untuk kategori ini. Warna akan digunakan sebagai badge pada artikel.</div>
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Kategori</label>
                        <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                            <option value="">-- Tidak Ada (Menu Utama) --</option>

                            @php
                            // Query langsung di view (tidak direkomendasikan untuk production)
                            $parentCategories = \App\Models\Category::where('id', '!=', $category->id)
                            ->orderBy('name')
                            ->get();
                            @endphp

                            @foreach($parentCategories as $parentCat)
                            @php
                            // Hitung level untuk indentasi
                            $level = 0;
                            $tempParent = $parentCat->parent;
                            while ($tempParent) {
                            $level++;
                            $tempParent = $tempParent->parent;
                            }
                            @endphp

                            <option value="{{ $parentCat->id }}"
                                {{ old('parent_id', $category->parent_id) == $parentCat->id ? 'selected' : '' }}
                                data-level="{{ $level }}"
                                style="padding-left: {{ ($level * 20) + 10 }}px">
                                {{ $parentCat->name }}
                                @if($level > 0)
                                <small class="text-muted">(Level {{ $level }})</small>
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Pilih parent kategori untuk menjadikannya Sub Menu / Child.
                            Jika dikosongkan maka menjadi Menu utama.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="link" class="form-label">Custom Link</label>
                        <input class="form-control @error('link') is-invalid @enderror"
                            id="link" name="link">{{ old('link', $category->link) }}</input>
                        @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Tambahkan field untuk status aktif --}}
                    <div class="mb-3">
                        <div class="form-check form-switch">

                            <input type="hidden" name="is_active" value="0"> {{-- wajib --}}

                            <input class="form-check-input"
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}>

                            <label class="form-check-label" for="is_active">
                                Kategori Aktif
                            </label>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Perbarui Kategori
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
        // Validasi parent selection
        const parentSelect = document.getElementById('parent_id');
        const categoryId = {
            {
                $category - > id
            }
        };

        if (parentSelect) {
            parentSelect.addEventListener('change', function() {
                const selectedParentId = parseInt(this.value);

                if (selectedParentId === categoryId) {
                    alert('Error: Tidak bisa memilih diri sendiri sebagai parent!');
                    this.value = '';
                    return;
                }

                // Optional: Cek jika memilih descendant (jika data tersedia)
                const selectedOption = this.options[this.selectedIndex];
                const selectedLevel = parseInt(selectedOption.dataset.level || 0);

                if (selectedLevel >= 3) {
                    const confirm = window.confirm(
                        'Anda memilih kategori level ' + (selectedLevel + 1) +
                        '. Struktur terlalu dalam mungkin tidak optimal untuk SEO. Lanjutkan?'
                    );

                    if (!confirm) {
                        this.value = '';
                    }
                }
            });
        }

        // Color picker sync - PERBAIKAN
        document.addEventListener('DOMContentLoaded', function() {
            const colorPicker = document.getElementById('color');
            const colorHex = document.getElementById('color_hex');

            // Sync color picker and hex input
            colorPicker.addEventListener('input', function() {
                colorHex.value = this.value;
            });

            colorHex.addEventListener('input', function() {
                if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                    colorPicker.value = this.value;
                }
            });
        });

        // Format hex input saat blur
        colorHex.addEventListener('blur', function() {
            let value = this.value.trim();

            if (!value) {
                // Jika kosong, set ke warna default
                value = '#000000';
            }

            // Tambah # jika belum ada
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
                // Reset ke warna saat ini jika invalid
                this.value = '{{ $category->color }}';
                colorPicker.value = '{{ $category->color }}';
            }
        });

        // Inisialisasi warna saat halaman dimuat
        function initializeColor() {
            let currentColor = colorHex.value.trim();

            if (!currentColor) {
                currentColor = '#000000';
            }

            if (!currentColor.startsWith('#')) {
                currentColor = '#' + currentColor;
            }

            // Convert 3-digit ke 6-digit jika perlu
            if (currentColor.match(/^#[0-9A-Fa-f]{3}$/i)) {
                currentColor = '#' +
                    currentColor[1] + currentColor[1] +
                    currentColor[2] + currentColor[2] +
                    currentColor[3] + currentColor[3];
            }

            // Pastikan format valid
            if (currentColor.match(/^#[0-9A-Fa-f]{6}$/i)) {
                colorHex.value = currentColor.toUpperCase();
                colorPicker.value = currentColor;
            }
        }

        // Panggil inisialisasi saat halaman dimuat
        initializeColor();
    }

    // Auto-generate slug dari nama (opsional)
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('blur', function() {
            // Anda bisa menambahkan auto-slug generation di sini
            // atau biarkan di handle oleh model
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
        padding-left: 24px;
    }

    option[data-level="2"] {
        padding-left: 36px;
    }

    option[data-level="3"] {
        padding-left: 48px;
    }
</style>
@endpush