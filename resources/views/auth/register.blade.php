<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-black min-h-screen flex items-center justify-center px-4">

    <div class="bg-gray-900 rounded-3xl shadow-xl w-full max-w-md p-8 border border-yellow-600">
        <h2 class="text-2xl sm:text-3xl font-semibold text-center mb-6 text-yellow-400">REGISTER</h2>

        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-900 border border-red-700 rounded-lg text-yellow-300 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label class="block mb-1 text-sm text-yellow-400">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="Masukkan Nama Anda"
                    required
                    class="w-full bg-gray-800 text-yellow-300 border border-yellow-600 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 placeholder-yellow-700">
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-1 text-sm text-yellow-400">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="Masukkan Email Anda"
                    required
                    class="w-full bg-gray-800 text-yellow-300 border border-yellow-600 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 placeholder-yellow-700">
            </div>

            <!-- Nomor HP -->
            <div>
                <label class="block mb-1 text-sm text-yellow-400">Nomor HP</label>
                <input type="tel" name="nohp" maxlength="13"
                    value="{{ old('nohp') }}"
                    placeholder="Masukkan Nomor HP"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,13)"
                    required
                    class="w-full bg-gray-800 text-yellow-300 border border-yellow-600 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 placeholder-yellow-700">
            </div>

            <!-- Password -->
            <div class="relative">
                <label class="block mb-1 text-sm text-yellow-400">Password</label>
                <input type="password" id="password" name="password"
                    placeholder="Minimal 8 karakter"
                    required
                    class="w-full bg-gray-800 text-yellow-300 border border-yellow-600 rounded-lg px-4 py-3 text-sm pr-10 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 placeholder-yellow-700">
                <button type="button" onclick="togglePassword('password', this)"
                    class="absolute right-3 top-9 text-yellow-500 hover:text-yellow-300">
                    👁️
                </button>
            </div>

            <!-- Konfirmasi Password -->
            <div class="relative">
                <label class="block mb-1 text-sm text-yellow-400">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Ulangi Password"
                    required
                    class="w-full bg-gray-800 text-yellow-300 border border-yellow-600 rounded-lg px-4 py-3 text-sm pr-10 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 placeholder-yellow-700">
                <button type="button" onclick="togglePassword('password_confirmation', this)"
                    class="absolute right-3 top-9 text-yellow-500 hover:text-yellow-300">
                    👁️
                </button>
            </div>

            <!-- Foto Profil -->
            <div>
                <label class="block mb-2 text-sm font-medium text-yellow-400">Foto Profil</label>
                <div class="flex items-center space-x-4">
                    <div class="shrink-0 relative">
                        <img id="preview-photo" class="h-16 w-16 object-cover rounded-full border-2 border-yellow-600"
                            src="https://ui-avatars.com/api/?name=User&background=random" alt="Photo preview">
                        <div id="remove-photo"
                            class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center cursor-pointer hidden">
                            ×
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block cursor-pointer">
                            <input type="file" name="photo" id="photo" accept="image/*"
                                class="hidden" onchange="previewImage(event)">
                            <div class="border-2 border-dashed border-yellow-600 rounded-lg p-4 text-center hover:bg-gray-800 transition">
                                <p class="text-sm text-yellow-400 mb-1">Klik untuk upload foto</p>
                                <p class="text-xs text-yellow-300">Format: JPG, PNG (Max 2MB)</p>
                            </div>
                        </label>
                        <div id="file-name" class="text-xs text-yellow-300 mt-1 truncate"></div>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <button type="submit"
                class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-3 rounded-lg shadow-lg transition transform hover:scale-[1.01] mt-2">
                Register
            </button>

            <!-- Link ke Login -->
            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-sm text-yellow-400 hover:underline">
                    Sudah punya akun? Login
                </a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                btn.textContent = "🙈";
            } else {
                input.type = "password";
                btn.textContent = "👁️";
            }
        }

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview-photo');
            const fileName = document.getElementById('file-name');
            const removeBtn = document.getElementById('remove-photo');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    fileName.textContent = input.files[0].name;
                    removeBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('remove-photo').addEventListener('click', function() {
            const preview = document.getElementById('preview-photo');
            const fileInput = document.getElementById('photo');
            const fileName = document.getElementById('file-name');

            fileInput.value = '';
            preview.src = 'https://ui-avatars.com/api/?name=User&background=random';
            fileName.textContent = '';
            this.classList.add('hidden');
        });
    </script>

</body>

</html>