<div class="mb-3">
    <label class="form-label">Nama Menu</label>
    <input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Parent Menu</label>
    <select name="parent_id" class="form-control">
        <option value="">Menu Utama</option>
        @foreach($parents as $parent)
        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Posisi</label>
    <select name="position" class="form-control" required>
        <option value="header">Header</option>
        <option value="footer">Footer</option>
        <option value="sidebar">Sidebar</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Urutan</label>
    <input type="number" name="order" class="form-control" value="0">
</div>

<div class="form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
    <label class="form-check-label">Aktif</label>
</div>