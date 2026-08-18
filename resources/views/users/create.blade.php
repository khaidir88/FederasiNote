<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users | Create') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="p-8 text-gray-900">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @csrf

                        {{-- Kolom Kiri (Form User) --}}
                        <div class="md:col-span-2 space-y-6">
                            {{-- NIK --}}
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                                <input type="text" name="nik" value="{{ old('nik') }}" maxlength="18"
                                    placeholder="Masukkan 18 digit angka NIK"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('nik')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    placeholder="Masukkan nama lengkap"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="Masukkan email aktif"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tempat lahir --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="tmlahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                    <input type="text" name="tmlahir" value="{{ old('tmlahir') }}"
                                        placeholder="Masukkan Tempat Lahir Anda"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('tmlahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Tanggal lahir --}}
                                <div>
                                    <label for="tglahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                    <input type="date" name="tglahir" value="{{ old('tglahir') }}"
                                        placeholder="Masukkan Tempat Lahir Anda"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('tglahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Tinggi --}}
                                <div>
                                    <label for="tinggi" class="block text-sm font-medium text-gray-700">Tinggi</label>
                                    <input type="number" name="tinggi" value="{{ old('tinggi') }}"
                                        oninput="this.value = Math.abs(this.value).toString().slice(0, 3)"
                                        placeholder="Masukkan Tinggi Anda"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('tinggi')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Berat --}}
                                <div>
                                    <label for="berat" class="block text-sm font-medium text-gray-700">Berat</label>
                                    <input type="number" name="berat" value="{{ old('berat') }}"
                                        oninput="this.value = Math.abs(this.value).toString().slice(0, 3)"
                                        placeholder="Masukkan Berat Anda"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('berat')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- No HP --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="nohp" class="block text-sm font-medium text-gray-700">No HP</label>
                                    <input type="tel" name="nohp" id="phone"
                                        value="{{ old('nohp') }}"
                                        placeholder="Masukkan Nomor HP Anda"
                                        maxlength="13"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                                        required
                                        class="w-full border border-cyan-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                                    <input type="hidden" name="phone_country_code" id="phone_country_code">
                                    @error('nohp')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Upload Foto --}}
                                <div>
                                    <label class="block mb-2 text-sm font-medium">Foto Profil</label>
                                    <div class="flex items-center space-x-4">
                                        <div class="shrink-0 relative">
                                            <img id="preview-photo" class="h-16 w-16 object-cover rounded-full border-2 border-cyan-300"
                                                src="https://ui-avatars.com/api/?name=User&background=random"
                                                alt="Photo preview">
                                            <div id="remove-photo" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center cursor-pointer hidden">
                                                ×
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <label class="block cursor-pointer">
                                                <input type="file" name="photo" id="photo" accept="image/*"
                                                    class="hidden"
                                                    onchange="previewImage(event)">
                                                <div class="border-2 border-dashed border-cyan-300 rounded-lg p-4 text-center hover:bg-cyan-50 transition">
                                                    <p class="text-sm text-gray-600 mb-1">Klik untuk upload foto</p>
                                                    <p class="text-xs text-gray-500">Format: JPG, PNG (Max 2MB)</p>
                                                </div>
                                            </label>
                                            <div id="file-name" class="text-xs text-gray-500 mt-1 truncate"></div>
                                        </div>
                                    </div>
                                    @error('photo')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" name="password"
                                    placeholder="Masukkan password"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                    placeholder="Ulangi password"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Kolom Kanan (Roles) --}}
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Pilih Role</label>
                            <div class="flex flex-col space-y-3">
                                @foreach ($roles as $role)
                                <label class="flex items-center space-x-2 border p-2 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        class="rounded text-blue-600"
                                        {{ in_array($role->name, $hasRoles) ? 'checked' : '' }}>
                                    <span>{{ ucfirst($role->name) }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="md:col-span-3 flex items-center gap-4">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-2 rounded-lg shadow">
                                Submit
                            </button>
                            <a href="{{ route('users.index') }}"
                                class="bg-gray-600 hover:bg-gray-700 transition text-white px-6 py-2 rounded-lg shadow">
                                Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Validasi Realtime NIK
        // Validasi Realtime NIK
        $('input[name="nik"]').on('blur', function() {
            let nik = $(this).val();
            if (nik.length > 0) {
                $.post("{{ route('check.unique') }}", {
                    nik: nik,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    if (data.exists) {
                        $('#nik-warning').text('NIK sudah terdaftar!').removeClass('hidden');
                    } else {
                        $('#nik-warning').addClass('hidden');
                    }
                });
            }
        });

        // Validasi Realtime Email
        $('input[name="email"]').on('blur', function() {
            let email = $(this).val();
            if (email.length > 0) {
                $.post("{{ route('check.unique') }}", {
                    email: email,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    if (data.exists) {
                        $('#email-warning').text('Email sudah terdaftar!').removeClass('hidden');
                    } else {
                        $('#email-warning').addClass('hidden');
                    }
                });
            }
        });

        // Validasi Realtime No HP
        $('input[name="nohp"]').on('blur', function() {
            let nohp = $(this).val();
            if (nohp.length > 0) {
                $.post("{{ route('check.unique') }}", {
                    nohp: nohp,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    if (data.exists) {
                        $('#nohp-warning').text('Nomor HP sudah terdaftar!').removeClass('hidden');
                    } else {
                        $('#nohp-warning').addClass('hidden');
                    }
                });
            }
        });
    </script>


    <script>
        // Initialize phone number input with Indonesia as default
        const phoneInput = document.querySelector("#phone");
        const iti = window.intlTelInput(phoneInput, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            initialCountry: "id",
            separateDialCode: true,
            customPlaceholder: function() {
                return "Contoh: 81234567890";
            }
        });

        // Force 13 digit limit for Indonesian numbers
        phoneInput.addEventListener('input', function() {
            if (iti.getSelectedCountryData().iso2 === 'id') {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13);
            }
        });

        // Update hidden field with country code
        document.querySelector("form").addEventListener('submit', function() {
            document.querySelector("#phone_country_code").value = iti.getSelectedCountryData().dialCode;
        });

        // Photo upload preview
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview-photo');
            const fileName = document.getElementById('file-name');
            const removeBtn = document.getElementById('remove-photo');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    fileName.textContent = input.files[0].name;
                    removeBtn.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Remove photo functionality
        document.getElementById('remove-photo').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const preview = document.getElementById('preview-photo');
            const input = document.getElementById('photo');
            const fileName = document.getElementById('file-name');
            const removeBtn = document.getElementById('remove-photo');

            preview.src = "https://ui-avatars.com/api/?name=User&background=random";
            input.value = '';
            fileName.textContent = '';
            removeBtn.classList.add('hidden');
        });
    </script>
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert-auto-dismiss');
            if (alert) {
                alert.remove();
            }
        }, 4000); // hilang setelah 4 detik
    </script>


</x-app-layout>