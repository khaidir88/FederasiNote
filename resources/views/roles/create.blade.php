<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles | Create') }}
            </h2>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="" class="text-lg font-medium" for="name">Roles</label>
                            <class="my-3">
                                <input value="{{ old('name') }}" placeholder="Masukkan Roles" name="name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('name')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                        </div>

                        <div class="grid grid-cols-4 gap-4 mb-4">
                           @if($permissions->isNotEmpty())
                            @foreach ($permissions as $permission)
                            <div class="mt-3">
                                <input type="checkbox" id="permission-{{ $permission->id }}"
                                class="rounded" name="permission[]"
                                    value="{{ $permission->name }}">
                                <label for="permission-{{ $permission->id }}">
                                   {{ $permission->name }}</label> 
                                </label>
                            </div>
                            @endforeach
                           @endif 
                        </div>

                        <button type="submit" class="bg-blue-600 text-sm rounded-md mt-6 px-6 py-3 text-white">
                            Submit
                        </button>

                        <a href="{{ route('roles.index') }}" class="bg-slate-700 text-sm rounded-md text-white px-8 py-3">Back</a>
                </div>

                </form>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>