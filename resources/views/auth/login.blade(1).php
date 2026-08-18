<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-black min-h-screen flex items-center justify-center px-4">

    <div class="bg-gray-900 rounded-3xl shadow-xl w-full max-w-md p-10 border border-yellow-600">
        <h2 class="text-2xl font-semibold text-center mb-6 text-yellow-400">LOGIN</h2>

        @if (session('status'))
        <div class="mb-4 p-4 bg-green-900 rounded-lg text-yellow-400 text-sm border border-green-700">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- NIK atau Email -->
            <div class="mb-5">
                <label for="login" class="block text-sm font-medium text-yellow-400 mb-1">NIK atau Email</label>
                <input type="text" name="login" id="login" value="{{ old('login') }}" required
                    class="w-full bg-gray-800 text-yellow-300 border border-yellow-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 placeholder-yellow-700"
                    placeholder="Masukkan NIK atau email">
            </div>

            <!-- Password -->
            <div class="mb-5 relative">
                <label for="password" class="block text-sm font-medium text-yellow-400 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full bg-gray-800 text-yellow-300 border border-yellow-600 rounded-lg px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 placeholder-yellow-700"
                    placeholder="Masukkan password">
                <button type="button" onclick="togglePassword('password', this)"
                    class="absolute right-3 top-9 text-yellow-500 hover:text-yellow-300">
                    👁️
                </button>
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm text-yellow-400">
                    <input type="checkbox" name="remember" class="rounded text-yellow-500 bg-gray-800 border-yellow-600 focus:ring-yellow-400">
                    <span class="ml-2">Ingat saya</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-yellow-400 hover:underline">
                    Lupa password?
                </a>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-3 rounded-lg shadow-lg transition duration-150">
                Masuk
            </button>
        </form>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <span class="text-sm text-yellow-400">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-sm text-yellow-500 hover:underline font-semibold">
                Daftar sekarang
            </a>
        </div>

        <!-- Kembali ke home -->
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-yellow-500 hover:underline font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-4 h-4 mr-1">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 
                     .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 
                     1.125-1.125h2.25c.621 0 1.125.504 
                     1.125 1.125V21h4.125c.621 0 1.125-.504 
                     1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Kembali
            </a>
        </div>

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
    </script>
</body>

</html>