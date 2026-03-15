<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('Lambang_Kabupaten_Pasuruan.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

            <div class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                    <div class="flex items-center justify-between gap-4">

                        @php
                            $role = Auth::user()->role ?? null;
                        @endphp

                        <div class="flex items-center gap-2 flex-1">

                            @if($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                   class="flex-1 text-center px-4 py-2 rounded-xl text-sm font-medium border
                                   {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                    Dashboard
                                </a>

                                <a href="{{ route('admin.data-warga') }}"
                                   class="flex-1 text-center px-4 py-2 rounded-xl text-sm font-medium border
                                   {{ request()->routeIs('admin.data-warga') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                    Data Warga
                                </a>

                                <a href="{{ route('admin.data-akun') }}"
                                   class="flex-1 text-center px-4 py-2 rounded-xl text-sm font-medium border
                                   {{ request()->routeIs('admin.data-akun') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                    Data Akun
                                </a>

                                <a href="{{ route('admin.filterisasi') }}"
                                   class="flex-1 text-center px-4 py-2 rounded-xl text-sm font-medium border
                                   {{ request()->routeIs('admin.filterisasi') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                    Filterisasi
                                </a>

                                <a href="{{ route('admin.laporan') }}"
                                   class="flex-1 text-center px-4 py-2 rounded-xl text-sm font-medium border
                                   {{ request()->routeIs('admin.laporan') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                    Laporan
                                </a>

                            @else
                                <a href="{{ route('dashboard') }}"
                                   class="flex-1 text-center px-4 py-2 rounded-xl text-sm font-medium border
                                   {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                    Dashboard
                                </a>

                                <a href="{{ route('rt.calon-penerima.create') }}"
                                   class="flex-1 text-center px-4 py-2 rounded-xl text-sm font-medium border
                                   {{ request()->routeIs('rt.calon-penerima.create') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                    Pendataan
                                </a>

                                <a href="{{ route('rt.calon-penerima.index') }}"
                                   class="flex-1 text-center px-4 py-2 rounded-xl text-sm font-medium border
                                   {{ request()->routeIs('rt.calon-penerima.index') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                    Riwayat
                                </a>

                                <a href="{{ route('rt.laporan.index') }}"
                                   class="flex-1 text-center px-4 py-2 rounded-xl text-sm font-medium border
                                   {{ request()->routeIs('rt.laporan.*') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                    Laporan
                                </a>
                            @endif

                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </span>
                            </div>

                            <div class="text-sm text-gray-700 whitespace-nowrap">
                                {{ Auth::user()->name ?? '' }}
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-red-500 hover:text-red-600">
                                    Keluar
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                <div class="py-8">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>