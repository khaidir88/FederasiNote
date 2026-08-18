<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Category | List') }}
            </h2>
            <a href="{{ route('category.create') }}" class="bg-slate-700 text-sm rounded-md text-white px-3 py-3">Create</a>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-1 lg:px-1">
            <!-- <x-message></x-message>  -->
            <table class="table-fixed w-full">
                <thead class=" bg-blue-100 text-sm">
                    <tr class="border-b">
                        <th class="w-1/4 text-center">No</th>
                        <th class="w-1/4 text-left">Nama</th>
                        <th class="w-1/4 text-left">Referensi</th>
                        <th class="w-1/4 text-left">Created</th>
                        <th class="w-1/4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">

                    @foreach ($categories as $category)
                    <tr class="border-b">
                        <td class="px-3 py-3 text-sm text-center ">
                            {{ $category->id }}
                        </td>
                        <td class="px-3 py-3 text-sm text-left">
                            {{ $category->name }}
                        </td>
                        <td class="px-3 py-3 text-sm text-left">
                            {{ $category->referensi }}
                        </td>
                        <td class="px-3 py-3 text-sm text-left">
                            {{ \carbon\Carbon::parse($category->created_at)->format('d M Y')  }}
                        </td>
                        <td class="flex justify-center space-x-4 px-2 py-2 text-sm text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('category.edit', $category->id) }}"
                                    class="text-blue-600 hover:text-blue-900 p-2 rounded-full hover:bg-blue-50 transition duration-300 ease-in-out"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteCategory({{ $category->id }})"
                                    class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-red-50 transition duration-300 ease-in-out"
                                    title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $categories->Links() }}
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
            function deleteCategory(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This Category will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/category/' + id,
                            type: 'DELETE',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Category has been deleted.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete Category.',
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