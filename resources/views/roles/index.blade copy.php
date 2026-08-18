@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
<x-slot name="header">
    <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles | List') }}
        </h2>
        @can ('Create Role')
        <a href="{{ route('roles.create') }}" class="bg-slate-700 text-sm rounded-md text-white px-3 py-3">Create</a>
        @endcan
    </div>
</x-slot>

<div class="py-0">
    <div class="max-w-7xl mx-auto sm:px-1 lg:px-1">
        <table class="w-full">
            <thead class=" bg-blue-100 text-sm">
                <tr class="border-b">
                    <th class="px-3 py-3 text-center">No</th>
                    <th class="px-3 py-3 text-left">Nama</th>
                    <th class="px-3 py-3 text-left">Permission</th>
                    <th class="px-3 py-3 text-left">Created</th>
                    <th class="px-3 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if($roles->isNotEmpty())
                @foreach ($roles as $role)
                <tr class="border-b">
                    <td class="px-3 py-3 text-sm text-center ">
                        {{ $role->id }}
                    </td>
                    <td class="px-3 py-3 text-sm text-left">
                        {{ $role->name }}
                    </td>
                    <td class="px-3 py-3 text-sm text-left">
                        {{ $role->permissions->pluck('name')->implode(', ') }}
                    </td>
                    <td class="px-3 py-3 text-sm text-left">
                        {{ \carbon\Carbon::parse($role->created_at)->format('d M Y')  }}
                    </td>
                    <td class="flex justify-center space-x-4 px-2 py-2 text-sm text-center">
                        @if($role->name !== 'super admin' || auth()->user()->hasRole('super admin'))
                        @can('Edit Role')
                        <a href="{{ route('roles.edit',$role->id) }}"
                            class="bg-blue-500 text-sm rounded-md text-white px-5 py-2">Edit</a>
                        @endcan

                        @can('Delete Role')
                        <a href="javascript:void(0)" onclick="deleteRole({{ $role->id }})"
                            class="bg-red-500 text-sm rounded-md text-white px-5 py-2">Delete</a>
                        @endcan
                        @else
                        <span class="text-gray-400 italic">Restricted</span>
                        @endif
                    </td>

                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        {{ $roles->Links() }}


    </div>
</div>

<x-slot name="script">
    <script type="text/javascript">
        function deleteRole(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This Role will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/roles/' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Roles has been deleted.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Failed to delete Roles.',
                                'error'
                            );
                        }
                    });
                }
            })
        }
    </script>
</x-slot>

@endsection