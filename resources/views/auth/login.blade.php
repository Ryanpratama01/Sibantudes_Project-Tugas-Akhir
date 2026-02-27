<!-- TEST-LOGIN-CUSTOM -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem BLT-DD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-blue-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-5xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden flex">
            <!-- Left Side - Image -->
            <div class="hidden lg:block lg:w-1/2 bg-gradient-to-br from-primary-600 to-primary-800 p-12">
                <div class="h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-3 mb-8">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">SIBANTUDDES</h1>
                                <p class="text-primary-200 text-sm">Sistem Bantuan Tunai Desa</p>
                            </div>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-4">Selamat Datang!</h2>
                        <p class="text-primary-100 text-lg leading-relaxed">
                            Sistem Informasi Kelayakan Penerima Bantuan Langsung Tunai Dana Desa (BLT-DD) berbasis Machine Learning
                        </p>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-primary-300 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-primary-100">Pendataan warga calon penerima bantuan</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-primary-300 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-primary-100">Prediksi kelayakan dengan Machine Learning</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-primary-300 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-primary-100">Laporan dan monitoring penyaluran bantuan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="w-full lg:w-1/2 p-12">
                <div class="max-w-md mx-auto">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Masuk</h2>
                        <p class="text-gray-600">Silakan masuk dengan akun Anda</p>
                    </div>

                    @if($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <p class="text-red-700 text-sm">{{ $errors->first() }}</p>
                    </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}" required 
                                    class="input-field pl-10" placeholder="nama@email.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input type="password" name="password" required 
                                    class="input-field pl-10" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full btn-primary py-3 text-lg font-semibold">
                            Masuk
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">Daftar sebagai RT</a>
                        </p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-xs text-gray-500 text-center">
                            © 2025 Sistem BLT-DD. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>