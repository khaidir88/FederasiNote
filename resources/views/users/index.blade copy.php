@extends('layouts.app')

@section('title', 'Management Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Management Pengguna</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus me-1"></i>Tambah Pengguna
    </button>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number text-primary">{{ $users->total() }}</div>
                        <div class="text-muted">Total Pengguna</div>
                    </div>
                    <div class="stat-icon text-primary">
                        <i class="bi bi-people"></i>
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
                        <div class="stat-number text-success">{{ $users->where('role', 'admin')->count() }}</div>
                        <div class="text-muted">Administrator</div>
                    </div>
                    <div class="stat-icon text-success">
                        <i class="bi bi-shield-check"></i>
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
                        <div class="stat-number text-warning">{{ $users->where('role', 'author')->count() }}</div>
                        <div class="text-muted">Author</div>
                    </div>
                    <div class="stat-icon text-warning">
                        <i class="bi bi-pencil-square"></i>
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
                        <div class="stat-number text-info">{{ $users->where('role', 'user')->count() }}</div>
                        <div class="text-muted">User</div>
                    </div>
                    <div class="stat-icon text-info">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Pengguna</h5>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari pengguna...">
            <select id="roleFilter" class="form-select form-select-sm">
                <option value="">Semua Role</option>
                <option value="admin">Admin</option>
                <option value="author">Author</option>
                <option value="user">User</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="usersTable">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Bergabung</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr data-role="{{ $user->role }}">
                        <td>
                            <div class="form-check">
                                <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle overflow-hidden" style="width: 40px; height: 40px;">
                                        @if($user->photo && file_exists(public_path('images/photo/' . $user->photo)))
                                        <img src="{{ asset('images/photo/' . $user->photo) }}"
                                            alt="{{ $user->name }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff"
                                            alt="{{ $user->name }}"
                                            style="width: 100%; height: 100%;">
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                @if($user->role === 'admin') bg-danger
                                @elseif($user->role === 'author') bg-warning text-dark
                                @else bg-secondary @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            {{ $user->email }}
                            @if($user->email_verified_at)
                            <i class="bi bi-patch-check-fill text-primary" title="Email Terverifikasi"></i>
                            @else
                            <i class="bi bi-patch-exclamation text-warning" title="Email Belum Terverifikasi"></i>
                            @endif
                        </td>
                        <td>
                            <small>
                                {{ $user->created_at->format('d M Y') }}<br>
                                <span class="text-muted">{{ $user->created_at->diffForHumans() }}</span>
                            </small>
                        </td>
                        <td>
                            @if($user->is_active)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Aktif
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle me-1"></i>Nonaktif
                            </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUserModal"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                    data-user-email="{{ $user->email }}"
                                    data-user-role="{{ $user->role }}"
                                    data-user-active="{{ $user->is_active ? 'true' : 'false' }}"
                                    title="Edit Pengguna">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus pengguna {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-sm btn-outline-secondary" disabled title="Tidak dapat menghapus diri sendiri">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Bulk Actions -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <select id="bulkAction" class="form-select form-select-sm d-inline-block" style="width: auto;">
                    <option value="">Aksi Massal</option>
                    <option value="activate">Aktifkan</option>
                    <option value="deactivate">Nonaktifkan</option>
                    <option value="delete">Hapus</option>
                </select>
                <button id="applyBulkAction" class="btn btn-sm btn-outline-primary ms-2">Terapkan</button>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="author">Author</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">
                            Akun Aktif
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pengguna - <span id="editUserName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_user_id">

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                        <small class="text-muted">Minimal 8 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                    </div>

                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="user">User</option>
                            <option value="author">Author</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <!-- PASTIKAN VALUE="1" -->
                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label fw-bold" for="edit_is_active">
                                <span id="statusText">Status Akun</span>
                            </label>
                        </div>
                        <small class="text-muted" id="statusDescription">Akun dapat login ke sistem</small>
                    </div>

                    <!-- Enhanced Debug Section -->
                    @if(env('APP_DEBUG'))
                    <div class="alert alert-warning alert-sm mt-3">
                        <small>
                            <strong>Debug Info:</strong><br>
                            User ID: <span id="debugUserId">-</span><br>
                            Current Active: <span id="debugCurrentActive">-</span><br>
                            Checkbox State: <span id="debugCheckboxState">-</span><br>
                            Form Action: <span id="debugFormAction">-</span>
                        </small>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitEditBtn">
                        <i class="bi bi-check-circle me-1"></i>Update Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#usersTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    // Role filter
    document.getElementById('roleFilter').addEventListener('change', function() {
        const roleValue = this.value;
        const rows = document.querySelectorAll('#usersTable tbody tr');

        rows.forEach(row => {
            if (roleValue === '') {
                row.style.display = '';
            } else {
                row.style.display = row.getAttribute('data-role') === roleValue ? '' : 'none';
            }
        });
    });

    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk actions
    document.getElementById('applyBulkAction').addEventListener('click', function() {
        const action = document.getElementById('bulkAction').value;
        const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedUsers.length === 0) {
            alert('Pilih setidaknya satu pengguna!');
            return;
        }

        if (!action) {
            alert('Pilih aksi yang akan diterapkan!');
            return;
        }

        if (confirm(`Apakah Anda yakin ingin ${action} ${selectedUsers.length} pengguna?`)) {
            // Implement bulk action here
            console.log('Bulk action:', action, 'on users:', selectedUsers);
        }
    });

    // Edit user modal functionality
    const editUserModal = document.getElementById('editUserModal');
    const editUserForm = document.getElementById('editUserForm');

    editUserModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name');
        const userEmail = button.getAttribute('data-user-email');
        const userRole = button.getAttribute('data-user-role');
        const userActive = button.getAttribute('data-user-active');

        console.log('=== MODAL DEBUG ===');
        console.log('User ID:', userId);
        console.log('User Active from data:', userActive, 'Type:', typeof userActive);

        // Update form action
        editUserForm.action = `/users/${userId}`;

        // Populate form fields
        document.getElementById('edit_user_id').value = userId;
        document.getElementById('edit_name').value = userName;
        document.getElementById('edit_email').value = userEmail;
        document.getElementById('edit_role').value = userRole;

        // Handle status checkbox
        const isActive = (userActive === 'true');
        console.log('Calculated isActive:', isActive);

        document.getElementById('edit_is_active').checked = isActive;
        updateStatusDisplay(isActive);

        // Update modal title
        document.getElementById('editUserName').textContent = userName;

        // Debug info
        if (document.getElementById('debugUserId')) {
            document.getElementById('debugUserId').textContent = userId;
            document.getElementById('debugCurrentActive').textContent = userActive;
            document.getElementById('debugCheckboxState').textContent = isActive;
            document.getElementById('debugFormAction').textContent = editUserForm.action;
        }

        // Clear password fields
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';

        console.log('=== END MODAL DEBUG ===');
    });

    function updateStatusDisplay(isActive) {
        const statusText = document.getElementById('statusText');
        const statusDescription = document.getElementById('statusDescription');

        if (isActive) {
            statusText.textContent = 'Aktif';
            statusText.className = 'text-success';
            statusDescription.textContent = 'Akun dapat login ke sistem';
            statusDescription.className = 'text-success';
        } else {
            statusText.textContent = 'Nonaktif';
            statusText.className = 'text-danger';
            statusDescription.textContent = 'Akun tidak dapat login ke sistem';
            statusDescription.className = 'text-danger';
        }
    }

    // Event listener for status checkbox change
    document.getElementById('edit_is_active').addEventListener('change', function() {
        console.log('Checkbox changed to:', this.checked);
        updateStatusDisplay(this.checked);
    });

    // Form submission dengan debugging
    editUserForm.addEventListener('submit', function(e) {
        console.log('=== FORM SUBMISSION DEBUG ===');

        // Show loading state
        const submitBtn = document.getElementById('submitEditBtn');
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Memproses...';
        submitBtn.disabled = true;

        // Log form data sebelum submit
        const formData = new FormData(this);
        console.log('Form data yang akan dikirim:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }

        console.log('is_active checkbox checked:', document.getElementById('edit_is_active').checked);
        console.log('=== END FORM DEBUG ===');

        // Biarkan form submit normal
    });
</script>

<style>
    .stat-card {
        border-left: 4px solid transparent;
    }

    .stat-card.border-primary {
        border-left-color: #0d6efd;
    }

    .stat-card.border-success {
        border-left-color: #198754;
    }

    .stat-card.border-warning {
        border-left-color: #ffc107;
    }

    .stat-card.border-info {
        border-left-color: #0dcaf0;
    }

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .user-checkbox:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

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
</style>
@endpush