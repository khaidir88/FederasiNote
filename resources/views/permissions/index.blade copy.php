@extends('layouts.app')

@section('title', 'Permissions Management')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('Permissions Management') }}</h2>
                        <p class="text-gray-600 mt-1">Manage user permissions and access controls</p>
                    </div>
                    <a href="{{ route('permissions.create') }}"
                        class="bg-slate-700 hover:bg-slate-800 transition duration-200 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        {{ __('Create Permission') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Total Permissions</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $permissions->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-6 rounded-lg border border-green-100">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-green-600 text-sm font-medium">Active</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $permissions->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 p-6 rounded-lg border border-purple-100">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-clock text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-purple-600 text-sm font-medium">Last Updated</p>
                        <p class="text-sm font-medium text-gray-800">{{ $permissions->isNotEmpty() ? \Carbon\Carbon::parse($permissions->first()->updated_at)->diffForHumans() : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                    <div class="relative flex-1 max-w-md">
                        <input type="text"
                            id="searchInput"
                            placeholder="Search permissions..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <div class="flex gap-2">
                        <select id="sortSelect" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="name_asc">Name A-Z</option>
                            <option value="name_desc">Name Z-A</option>
                        </select>

                        <button id="filterBtn" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span>ID</span>
                                    <button class="sortable" data-sort="id">
                                        <i class="fas fa-sort text-gray-400"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span>Permission Name</span>
                                    <button class="sortable" data-sort="name">
                                        <i class="fas fa-sort text-gray-400"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span>Created Date</span>
                                    <button class="sortable" data-sort="created_at">
                                        <i class="fas fa-sort text-gray-400"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="permissionsTable">
                        @forelse ($permissions as $permission)
                        <tr class="hover:bg-gray-50 transition duration-150 permission-row" data-name="{{ strtolower($permission->name) }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">#{{ $permission->id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-100 p-2 rounded-full">
                                        <i class="fas fa-shield-alt text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">{{ $permission->name }}</span>
                                        <p class="text-xs text-gray-500">Permission</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($permission->created_at)->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($permission->created_at)->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('permissions.edit', $permission->id) }}"
                                        class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition duration-200"
                                        title="Edit Permission">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button onclick="deletePermission({{ $permission->id }})"
                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition duration-200"
                                        title="Delete Permission">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <button onclick="showPermissionDetails({{ $permission->id }})"
                                        class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-2 rounded-lg transition duration-200"
                                        title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p class="text-lg">No permissions found</p>
                                    <p class="text-sm mt-1">Create your first permission to get started</p>
                                    <a href="{{ route('permissions.create') }}" class="inline-block mt-4 bg-slate-700 text-white px-6 py-2 rounded-lg hover:bg-slate-800 transition duration-200">
                                        Create Permission
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($permissions->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $permissions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="permissionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Permission Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.permission-row');

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Sort functionality
    document.querySelectorAll('.sortable').forEach(button => {
        button.addEventListener('click', function() {
            const sortBy = this.getAttribute('data-sort');
            // Implement sorting logic here
            console.log('Sort by:', sortBy);
        });
    });

    // Permission details modal
    function showPermissionDetails(id) {
        // Simulate AJAX call - replace with actual API call
        const modalContent = `
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-shield-alt text-blue-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold" id="modalPermissionName">Permission #${id}</h4>
                    <p class="text-sm text-gray-600">Detailed information</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium">Created:</span>
                    <p class="text-gray-600">Just now</p>
                </div>
                <div>
                    <span class="font-medium">Status:</span>
                    <p class="text-green-600">Active</p>
                </div>
            </div>
        </div>
    `;

        document.getElementById('modalContent').innerHTML = modalContent;
        document.getElementById('permissionModal').classList.remove('hidden');
        document.getElementById('permissionModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('permissionModal').classList.add('hidden');
        document.getElementById('permissionModal').classList.remove('flex');
    }

    // Close modal on outside click
    document.getElementById('permissionModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Delete permission function
    function deletePermission(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This permission will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/permissions/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON?.message || 'Failed to delete permission.',
                            'error'
                        );
                    }
                });

            }
        });
    }

    // Add some animations
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.permission-row');
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.1}s`;
            row.classList.add('animate-fade-in');
        });
    });
</script>

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .permission-row:hover {
        transform: translateX(4px);
        transition: transform 0.2s ease;
    }

    .sortable:hover {
        color: #4f46e5;
    }
</style>
@endsection