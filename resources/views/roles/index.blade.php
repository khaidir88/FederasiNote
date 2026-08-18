@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('Roles Management') }}</h2>
                        <p class="text-gray-600 mt-1">Manage user roles and permissions system</p>
                    </div>
                    @can('Create Role')
                    <a href="{{ route('roles.create') }}"
                        class="bg-slate-700 hover:bg-slate-800 transition duration-200 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        {{ __('Create Role') }}
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Total Roles</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $roles->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-green-600 text-sm font-medium">Active Roles</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $roles->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-key text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-purple-600 text-sm font-medium">Permissions</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalPermissions ?? '0' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                <div class="flex items-center gap-4">
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-orange-600 text-sm font-medium">Last Updated</p>
                        <p class="text-sm font-medium text-gray-800">
                            @if($roles->isNotEmpty() && $roles->first()->updated_at)
                            {{ \Carbon\Carbon::parse($roles->first()->updated_at)->diffForHumans() }}
                            @else
                            N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                    <div class="relative flex-1 max-w-md">
                        <input type="text"
                            id="searchInput"
                            placeholder="Search roles by name..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <div class="flex gap-2">
                        <select id="filterSelect" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm transition duration-200">
                            <option value="all">All Roles</option>
                            <option value="system">System Roles</option>
                            <option value="custom">Custom Roles</option>
                        </select>

                        <button id="resetFilters" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg transition duration-200 flex items-center gap-2">
                            <i class="fas fa-refresh"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2 cursor-pointer sort-header" data-sort="id">
                                    ID
                                    <i class="fas fa-sort text-gray-400"></i>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2 cursor-pointer sort-header" data-sort="name">
                                    Role Name
                                    <i class="fas fa-sort text-gray-400"></i>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Permissions
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Users
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2 cursor-pointer sort-header" data-sort="created_at">
                                    Created Date
                                    <i class="fas fa-sort text-gray-400"></i>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="rolesTableBody">
                        @forelse ($roles as $role)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out role-row"
                            data-name="{{ strtolower($role->name) }}"
                            data-type="{{ in_array($role->name, ['super admin', 'admin']) ? 'system' : 'custom' }}"
                            data-created="{{ $role->created_at->timestamp }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">#{{ $role->id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-full 
                                        {{ $role->name === 'super admin' ? 'bg-red-100 text-red-600' : 
                                           ($role->name === 'admin' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600') }}">
                                        <i class="fas 
                                            {{ $role->name === 'super admin' ? 'fa-crown' : 
                                               ($role->name === 'admin' ? 'fa-user-shield' : 'fa-user-tie') }} 
                                            text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 capitalize">{{ $role->name }}</span>
                                        <p class="text-xs text-gray-500">
                                            @if($role->name === 'Super Admin')
                                            Full System Access
                                            @elseif($role->name === 'admin')
                                            Administrator
                                            @else
                                            Custom Role
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @forelse($role->permissions->take(3) as $permission)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                        <i class="fas fa-key mr-1 text-xs"></i>
                                        {{ Str::limit($permission->name, 15) }}
                                    </span>
                                    @empty
                                    <span class="text-gray-400 text-xs italic">No permissions assigned</span>
                                    @endforelse
                                    @if($role->permissions->count() > 3)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 cursor-help"
                                        title="{{ $role->permissions->skip(3)->pluck('name')->implode(', ') }}">
                                        +{{ $role->permissions->count() - 3 }} more
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ ($role->users_count ?? 0) > 0 ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $role->users_count ?? 0 }} users
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">
                                    {{ $role->created_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $role->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center gap-2">
                                    @php
                                    $isSystemRole = in_array($role->name, ['Super Admin', 'Admin']);
                                    $canManageSystem = auth()->user()->hasRole('Super Admin');
                                    @endphp

                                    <!-- Edit Button -->
                                    @if(auth()->user()->can('Edit Role') && (!$isSystemRole || $canManageSystem))
                                    <a href="{{ route('roles.edit', $role->id) }}"
                                        class="action-btn edit-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @else
                                    <span class="action-btn disabled-btn" title="Edit not allowed">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    @endif

                                    <!-- Delete Button -->
                                    @if(auth()->user()->can('Delete Role') && !$isSystemRole)
                                    <button onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')"
                                        class="action-btn delete-btn">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @else
                                    <span class="action-btn disabled-btn" title="Delete not allowed">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    @endif

                                    <!-- View Button -->
                                    <button onclick="showRoleDetails({{ $role->id }})"
                                        class="action-btn view-btn">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>


                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-400 py-8">
                                    <i class="fas fa-users-slash text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No roles found</p>
                                    <p class="text-sm mt-1 mb-4">Get started by creating your first role</p>
                                    @can('Create Role')
                                    <a href="{{ route('roles.create') }}"
                                        class="inline-flex items-center gap-2 bg-slate-700 text-white px-6 py-2 rounded-lg hover:bg-slate-800 transition duration-200">
                                        <i class="fas fa-plus"></i>
                                        Create First Role
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($roles->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-700">
                        Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of {{ $roles->total() }} results
                    </div>
                    <div class="flex space-x-2">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Role Details Modal -->
<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Role Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-xl transition duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalContent" class="space-y-4">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.role-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show no results message if needed
        toggleNoResultsMessage(visibleCount);
    });

    // Filter functionality
    document.getElementById('filterSelect')?.addEventListener('change', function(e) {
        const filterValue = e.target.value;
        const rows = document.querySelectorAll('.role-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const type = row.getAttribute('data-type');
            if (filterValue === 'all' || type === filterValue) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        toggleNoResultsMessage(visibleCount);
    });

    // Reset filters
    document.getElementById('resetFilters')?.addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterSelect').value = 'all';

        const rows = document.querySelectorAll('.role-row');
        rows.forEach(row => row.style.display = '');

        toggleNoResultsMessage(rows.length);
    });

    function toggleNoResultsMessage(visibleCount) {
        let noResultsRow = document.getElementById('noResultsRow');
        const tbody = document.getElementById('rolesTableBody');

        if (visibleCount === 0) {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.id = 'noResultsRow';
                noResultsRow.innerHTML = `
                <td colspan="6" class="px-6 py-8 text-center">
                    <div class="text-gray-400">
                        <i class="fas fa-search text-3xl mb-2"></i>
                        <p class="font-medium">No roles found</p>
                        <p class="text-sm">Try adjusting your search or filter</p>
                    </div>
                </td>
            `;
                tbody.appendChild(noResultsRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    // Role details modal
    function showRoleDetails(roleId) {
        // Simulate loading
        document.getElementById('modalContent').innerHTML = `
        <div class="flex justify-center py-4">
            <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
        </div>
    `;

        document.getElementById('roleModal').classList.remove('hidden');
        document.getElementById('roleModal').classList.add('flex');

        // Simulate API call - replace with actual endpoint
        setTimeout(() => {
            const modalContent = `
            <div class="space-y-4">
                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Role Information</h4>
                        <p class="text-sm text-gray-600">Detailed role data</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="p-2 bg-gray-50 rounded">
                        <span class="font-medium text-gray-700">Created:</span>
                        <p class="text-gray-600">Just now</p>
                    </div>
                    <div class="p-2 bg-gray-50 rounded">
                        <span class="font-medium text-gray-700">Users:</span>
                        <p class="text-green-600">5 users</p>
                    </div>
                </div>
                
                <div class="p-2 bg-gray-50 rounded">
                    <span class="font-medium text-gray-700">Permissions:</span>
                    <div class="mt-2 flex flex-wrap gap-1">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">user.create</span>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">user.edit</span>
                    </div>
                </div>
            </div>
        `;
            document.getElementById('modalContent').innerHTML = modalContent;
        }, 500);
    }

    function closeModal() {
        document.getElementById('roleModal').classList.add('hidden');
        document.getElementById('roleModal').classList.remove('flex');
    }

    // Delete role function
    function deleteRole(id, roleName) {
        Swal.fire({
            title: 'Delete Role?',
            html: `You are about to delete <strong>"${roleName}"</strong>. This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                confirmButton: 'bg-red-600 hover:bg-red-700 px-4 py-2',
                cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the role',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ url('
                    roles ') }}/' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Role has been deleted successfully.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to delete role.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            }
        });
    }

    // Tooltip functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltips = document.querySelectorAll('[data-tooltip]');
        tooltips.forEach(element => {
            element.addEventListener('mouseenter', function(e) {
                const tooltipText = this.getAttribute('data-tooltip');
                if (tooltipText) {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'fixed z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg';
                    tooltip.textContent = tooltipText;
                    document.body.appendChild(tooltip);

                    const rect = this.getBoundingClientRect();
                    tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
                    tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';

                    this._currentTooltip = tooltip;
                }
            });

            element.addEventListener('mouseleave', function(e) {
                if (this._currentTooltip) {
                    this._currentTooltip.remove();
                    this._currentTooltip = null;
                }
            });
        });
    });

    // Close modal on outside click
    document.getElementById('roleModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>

<style>
    .role-row {
        transition: all 0.2s ease-in-out;
    }

    .role-row:hover {
        transform: translateX(4px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .sort-header:hover {
        color: #4f46e5;
    }

    .sort-header:hover i {
        color: #4f46e5;
    }

    .tooltip {
        position: relative;
        cursor: pointer;
    }

    /* Animation for modal */
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    #roleModal>div {
        animation: modalFadeIn 0.3s ease-out;
    }
</style>
<style>
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .edit-btn {
        background-color: #dbeafe;
        color: #2563eb;
    }

    .edit-btn:hover {
        background-color: #bfdbfe;
        color: #1d4ed8;
    }

    .delete-btn {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .delete-btn:hover {
        background-color: #fecaca;
        color: #b91c1c;
    }

    .view-btn {
        background-color: #dcfce7;
        color: #16a34a;
    }

    .view-btn:hover {
        background-color: #bbf7d0;
        color: #15803d;
    }

    .disabled-btn {
        background-color: #f3f4f6;
        color: #9ca3af;
        cursor: not-allowed;
    }
</style>
@endsection