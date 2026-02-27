<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem BLT-DD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-blue-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
            <div class="mb-8">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-primary-600 rounded-xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 text-center mb-2">Daftar Akun RT</h2>
                <p class="text-gray-600 text-center">Lengkapi data untuk mendaftar</p>
            </div>

            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                        class="input-field" placeholder="Masukkan nama lengkap">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="input-field" placeholder="nama@email.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Dusun</label>
                    <input type="text" name="dusun" value="{{ old('dusun') }}" required 
                        class="input-field" placeholder="Contoh: Ngronggo">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required 
                        class="input-field" placeholder="Minimal 6 karakter">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required 
                        class="input-field" placeholder="Ulangi password">
                </div>

                <button type="submit" class="w-full btn-primary py-3 text-lg font-semibold mt-6">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>