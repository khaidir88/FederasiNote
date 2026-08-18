<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users | List') }}
            </h2>
            <a href="{{ route('users.create') }}" class="bg-slate-700 text-sm rounded-md text-white px-3 py-3">Create</a>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-1 lg:px-1">
            <table class="w-full">
                <thead class=" bg-blue-100 text-sm">
                    <tr class="border-b">
                        <th class="w-1/12 px-3 py-3 text-center">No</th>
                        <th class="w-2/12 px-3 py-3 text-center">Foto</th>
                        <th class="w-2/12 px-3 py-3 text-left">Name</th>
                        <th class="w-2/12 px-3 py-3 text-left">Email</th>
                        <th class="w-2/12 px-3 py-3 text-left">No HP</th>
                        <th class="w-2/12 px-3 py-3 text-left">Role</th>
                        <th class="w-2/12 px-3 py-3 text-left">Created</th>
                        <th class="w-2/12 px-3 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if($users->isNotEmpty())
                    @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="px-3 py-3 text-sm text-center">
                            {{ $user->id }}
                        </td>
                        <td class="px-3 py-3 text-sm text-center">
                            <img src="{{ $user->photo ? asset('public/images/PhotoProfile/' . $user->photo) : asset('public/images/default-avatar.png') }}"
                                alt="{{ $user->name }}"
                                class="w-10 h-10 rounded-full mx-auto object-cover transition-transform duration-300 hover:scale-125">

                        </td>
                        <td class="px-3 py-3 text-sm text-left">
                            {{ $user->name }}
                        </td>
                        <td class="px-3 py-3 text-sm text-left">
                            {{ $user->email }}
                        </td>
                        <td class="px-3 py-3 text-sm text-left">
                            {{ $user->nohp }}
                        </td>
                        <td class="px-3 py-3 text-sm text-left">
                            {{ $user->roles->pluck('name')->implode(', ') }}
                        </td>
                        <td class="px-3 py-3 text-sm text-left">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                        </td>
                        <td class="px-3 py-3 text-sm text-center">
                            <div class="flex justify-center space-x-2">
                                <!-- Tombol Edit -->
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-md text-sm"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Tombol Delete -->
                                <a href="javascript:void(0)" onclick="deleteUser({{ $user->id }})"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm"
                                    title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
            function deleteUser(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This User will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/users/' + id,
                            type: 'DELETE',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Users has been deleted.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete Users.',
                                    'error'
                                );
                            }
                        });
                    }
                })
            }
        </script>
    </x-slot>
</x-app-layout>