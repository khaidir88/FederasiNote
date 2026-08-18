<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-gradient-to-br from-blue-100 to-cyan-100 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-10">
        <h2 class="text-2xl font-semibold text-center mb-6 text-cyan-600">RESET PASSWORD</h2>

        {{-- Error Global --}}
        {{-- Sukses --}}
@if (session('status'))
<div class="mb-4 p-4 bg-green-50 rounded-lg text-green-600 text-sm">
    {{ session('status') }}
</div>
@endif

{{-- Error --}}
@if ($errors->any())
<div class="mb-4 p-4 bg-red-50 rounded-lg text-red-600 text-sm">
    <ul class="list-disc pl-5 space-y-1">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


        {{-- FORM RESET PASSWORD --}}
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT') {{-- WAJIB pakai PUT --}}

            {{-- Token yang dikirim via email --}}
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            {{-- Email --}}
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-cyan-600 mb-1">Email</label>
                <input id="email" type="email" name="email"
                    value="{{ request()->email ?? old('email') }}"
                    required autofocus
                    class="w-full border border-cyan-300 rounded-lg px-4 py-3
                              focus:outline-none focus:ring-2 focus:ring-cyan-500
                              focus:border-cyan-500"
                    placeholder="Masukkan email Anda">
            </div>

            {{-- Password Baru --}}
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-cyan-600 mb-1">Password Baru</label>
                <input id="password" type="password" name="password"
                    required
                    class="w-full border border-cyan-300 rounded-lg px-4 py-3
                              focus:outline-none focus:ring-2 focus:ring-cyan-500
                              focus:border-cyan-500"
                    placeholder="Password baru">
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-5">
                <label for="password_confirmation" class="block text-sm font-medium text-cyan-600 mb-1">
                    Konfirmasi Password
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    required
                    class="w-full border border-cyan-300 rounded-lg px-4 py-3
                              focus:outline-none focus:ring-2 focus:ring-cyan-500
                              focus:border-cyan-500"
                    placeholder="Konfirmasi password">
            </div>

            {{-- Tombol Submit --}}
            <button type="submit"
                class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-3
                           rounded-lg shadow-lg transition duration-150">
                Simpan Password
            </button>
        </form>

        {{-- Link ke Login --}}
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center text-sm text-cyan-500 hover:underline font-semibold">
                🔑 Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>
