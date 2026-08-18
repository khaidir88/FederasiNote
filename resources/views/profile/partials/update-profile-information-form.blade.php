<section class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Bagian Kiri (Form Nama & Email) -->
    <div class="md:col-span-2 p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300">
        <header>
            <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('Informasi Profile') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                {{ __("Ubah nama atau email akun Anda.") }}
            </p>
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
            @csrf
            @method('patch')
            <!-- NIK -->
            <div>
                <x-input-label for="nik" :value="__('NIK')" />

                <x-text-input id="nik" name="nik" type="text"
                    class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed focus:ring-0 focus:border-gray-300"
                    :value="old('nik', $user->nik)"
                    readonly />

                <x-input-error class="mt-2 text-red-500" :messages="$errors->get('nik')" />
            </div>

            <!-- Nama -->
            <div>
                <x-input-label for="name" :value="__('Nama')" />
                <x-text-input id="name" name="name" type="text"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                    :value="old('name', $user->name)"
                    required autofocus autocomplete="name" />
                <x-input-error class="mt-2 text-red-500" :messages="$errors->get('name')" />
            </div>

            <!-- Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        :value="old('email', $user->email)"
                        required autocomplete="username" />
                    <x-input-error class="mt-2 text-red-500" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 text-sm text-yellow-600">
                        {{ __('Email belum terverifikasi.') }}
                        <button form="send-verification"
                            class="ml-2 underline text-indigo-600 hover:text-indigo-800">
                            {{ __('Kirim ulang verifikasi') }}
                        </button>
                    </div>
                    @endif
                </div>

                <!-- NOHP -->
                <div>
                    <x-input-label for="nohp" :value="__('Nomor HP')" />
                    <x-text-input id="nohp" name="nohp" type="phone"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        :value="old('nohp', $user->nohp)"
                        required autofocus autocomplete="nohp" />
                    <x-input-error class="mt-2 text-red-500" :messages="$errors->get('nohp')" />
                </div>
            </div>
            <!-- Tempat & Tanggal Lahir -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tempat Lahir -->
                <div>
                    <x-input-label for="tmlahir" :value="__('Tempat Lahir')" />
                    <x-text-input id="tmlahir" name="tmlahir" type="text"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        :value="old('tmlahir', $user->tmlahir)"
                        required autofocus autocomplete="tmlahir" />
                    <x-input-error class="mt-2 text-red-500" :messages="$errors->get('tmlahir')" />
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <x-input-label for="tglahir" :value="__('Tanggal Lahir')" />
                    <x-text-input id="tglahir" name="tglahir" type="date"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        :value="old('tglahir', $user->tglahir)"
                        required autofocus autocomplete="tglahir" />
                    <x-input-error class="mt-2 text-red-500" :messages="$errors->get('tglahir')" />
                </div>
            </div>

            <!-- Tinggi & Berat -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <!-- Tinggi -->
                <div>
                    <x-input-label for="tinggi" :value="__('Tinggi (cm)')" />
                    <x-text-input id="tinggi" name="tinggi" type="number"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        :value="old('tinggi', $user->tinggi)"
                        oninput="this.value = Math.abs(this.value).toString().slice(0, 3)"
                        required autofocus autocomplete="tinggi" />
                    <x-input-error class="mt-2 text-red-500" :messages="$errors->get('tinggi')" />
                </div>

                <!-- Berat -->
                <div>
                    <x-input-label for="berat" :value="__('Berat (kg)')" />
                    <x-text-input id="berat" name="berat" type="number"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        :value="old('berat', $user->berat)"
                        oninput="this.value = Math.abs(this.value).toString().slice(0, 3)"
                        required autofocus autocomplete="berat" />
                    <x-input-error class="mt-2 text-red-500" :messages="$errors->get('berat')" />
                </div>
            </div>
            <!-- Tombol Simpan -->
            <div class="flex items-center gap-3">
                <x-primary-button class="px-5 py-2">{{ __('Simpan') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600">✔ {{ __('Tersimpan!') }}</span>
                @endif
            </div>
        </form>
    </div>

    <!-- Bagian Kanan (Foto Profil) -->
    <div class="flex flex-col items-center p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 4.354a4 4 0 110 5.292M15 10.5c2.485 0 4.5 2.015 4.5 4.5S17.485 19.5 15 19.5H9c-2.485 0-4.5-2.015-4.5-4.5S6.515 10.5 9 10.5h6z" />
            </svg>
            {{ __('Foto Profil') }}
        </h2>


        <!-- Foto Saat Ini -->
        <div class="relative group">
            <img id="current-photo"
                src="{{ $user->photo ? asset('public/images/PhotoProfile/' . $user->photo) : asset('images/default-avatar.png') }}"
                alt="Foto Profil"
                class="w-32 h-32 rounded-full object-cover border-4 border-indigo-100 shadow-md group-hover:scale-105 transition duration-300">
        </div>

        <!-- Form Upload Foto -->
        <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="w-full mt-5">
            @csrf
            @method('patch')

            <input type="file" name="profile_photo" id="profile-photo-input" accept="image/*"
                class="mb-3 block w-full text-sm text-gray-600 border rounded-lg p-2 cursor-pointer hover:border-indigo-400 focus:ring focus:ring-indigo-200 transition"
                required>

            <x-primary-button class="px-6 py-2 w-full">{{ __('Ganti Foto') }}</x-primary-button>
        </form>

        <!-- Script Preview -->
        <script>
            document.getElementById('profile-photo-input').addEventListener('change', function(event) {
                const [file] = event.target.files;
                if (file) {
                    const preview = document.getElementById('current-photo');
                    preview.src = URL.createObjectURL(file);
                }
            });
        </script>

    </div>
</section>