<div class="mb-3">
    <label class="form-label">Nama Dinas</label>
    <input type="text" name="nama" class="form-control" value="{{ old('nama', $dinas->nama ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Kategori</label>
    <select name="kategori" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        
        <option value="kota" {{ old('kategori', $dinas->kategori ?? '') == 'kota' ? 'selected' : '' }}>Kota</option>
        <option value="provinsi" {{ old('kategori', $dinas->kategori ?? '') == 'provinsi' ? 'selected' : '' }}>Provinsi</option>
    <option value="kementerian" {{ old('kategori', $dinas->kategori ?? '') == 'kementerian' ? 'selected' : '' }}>Kementerian</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Struktur</label>
    <textarea name="struktur" class="form-control">{{ old('struktur', $dinas->struktur ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Keterangan</label>
    <textarea name="ket" class="form-control" rows="3">{{ old('ket', $dinas->ket ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Link Web</label>
    <input type="url" name="link" class="form-control" value="{{ old('link', $dinas->link ?? '') }}">
</div>