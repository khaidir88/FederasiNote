<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User | Edit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Bagian kiri (informasi profil) --}}
                        <div class="md:col-span-2 space-y-4">
                            <h3 class="text-lg font-semibold mb-4">🧑 Informasi Profil</h3>

                            <div>
                                <label class="block text-sm font-medium">NIK</label>
                                <input type="text" value="{{ $user->nik }}" readonly
                                    class="mt-1 w-full border-gray-300 rounded-lg shadow-sm bg-gray-100 cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Nama</label>
                                <input type="text" name="name" value="{{ $user->name }}"
                                    class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium">Email</label>
                                    <input type="email" name="email" value="{{ $user->email }}"
                                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Nomor HP</label>
                                    <input type="tel" name="nohp" value="{{ $user->nohp }}"
                                        maxlength="13"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium">Tempat Lahir</label>
                                    <input type="text" name="tmplahir" value="{{ $user->tmlahir }}"
                                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Tanggal Lahir</label>
                                    <input type="date" name="tglahir" value="{{ $user->tglahir }}"
                                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium">Tinggi (cm)</label>
                                    <input type="number" name="tinggi" value="{{ $user->tinggi }}"
                                        oninput="this.value = Math.abs(this.value).toString().slice(0, 3)"
                                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Berat (kg)</label>
                                    <input type="number" name="berat" value="{{ $user->berat }}"
                                        oninput="this.value = Math.abs(this.value).toString().slice(0, 3)"
                                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                                </div>
                                <div>
                                    <input type="file" name="photo" id="photo-input" accept="image/*"
                                        class="block w-full text-sm text-gray-500">
                                </div>
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block mb-1 text-sm">Password</label>
                                <input type="password" name="password"
                                    placeholder="Password minimal 8 karakter"

                                    class="w-full border border-cyan-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                                @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label class="block mb-1 text-sm">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                    placeholder="Masukkan Password yang sama seperti di atas"
                                    class="w-full border border-cyan-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                            </div>


                        </div>

                        {{-- Bagian kanan (foto profil) --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">📷 Foto Profil</h3>

                            {{-- Foto lama --}}
                            <div class="flex justify-center">
                                <img src="{{ $user->photo ? asset('images/PhotoProfile/'.$user->photo) : 'https://via.placeholder.com/150' }}"
                                    alt="Foto Profil"
                                    class="w-32 h-32 object-cover rounded-full border"
                                    id="old-photo">
                            </div>

                            {{-- Preview foto baru --}}
                            <div class="flex justify-center">
                                <img id="preview-photo" class="w-32 h-32 object-cover rounded-full border hidden">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Role</label>
                                <div class="flex flex-wrap gap-4 mt-2">
                                    @foreach ($roles as $role)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="role[]" value="{{ $role->name }}"
                                            class="rounded"
                                            {{ ($hasRoles->contains($role->name)) ? 'checked' : '' }}>
                                        <span>{{ $role->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="flex justify-center gap-4 mt-6">
                        <button type="submit"
                            class="bg-blue-600 text-sm rounded-md px-6 py-2 text-white w-1/3 md:w-1/4">
                            💾 Update
                        </button>

                        <a href="{{ route('users.index') }}"
                            class="bg-gray-700 text-sm rounded-md text-white px-6 py-2 w-1/3 md:w-1/4 text-center">
                            ⬅ Kembali
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- JS Preview foto baru --}}
    <script>
        document.getElementById('photo-input').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('preview-photo').src = URL.createObjectURL(file);
                document.getElementById('preview-photo').classList.remove('hidden');
                document.getElementById('old-photo').classList.add('hidden');
            }
        });
    </script>
</x-app-layout>