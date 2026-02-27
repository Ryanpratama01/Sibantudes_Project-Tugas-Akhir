<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Ringkasan data pendataran warga {{ Auth::user()->rt->dusun->nama_dusun ?? "" }}
                </p>
            </div>

            {{-- Tanggal di header --}}
            <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs text-gray-500">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span id="live-clock">
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                    —
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB
                </span>
            </div>
        </div>

        <script>
            function updateClock() {
                const now = new Date();
                const options = { timeZone: 'Asia/Jakarta', weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' };
                const dateStr = now.toLocaleDateString('id-ID', options);
                const timeStr = now.toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', hour12: false });
                const el = document.getElementById('live-clock');
                if (el) el.textContent = dateStr + ' — ' + timeStr + ' WIB';
            }
            updateClock();
            setInterval(updateClock, 1000);
        </script>
    </x-slot>

    <div class="space-y-4">

        {{-- Greeting kecil --}}
        <div class="flex items-center gap-3 bg-white border border-gray-100 rounded-xl px-4 py-3 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">
                    Selamat datang, <span class="text-blue-600">{{ Auth::user()->name ?? 'Pengguna' }}</span> 👋
                </p>
                <p class="text-xs text-gray-400">Berikut ringkasan data terkini yang perlu Anda pantau.</p>
            </div>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

            <div class="relative bg-blue-600 text-white rounded-xl p-4 flex items-center justify-between overflow-hidden shadow-md shadow-blue-200">
                <div class="absolute -right-3 -top-3 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="absolute -right-1 -bottom-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <p class="text-xs font-medium text-blue-100">Total Warga</p>
                    <p class="text-3xl font-bold mt-0.5">{{ $stats['total_input'] }}</p>
                </div>
                <div class="relative z-10 w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            <div class="relative bg-amber-500 text-white rounded-xl p-4 flex items-center justify-between overflow-hidden shadow-md shadow-amber-200">
                <div class="absolute -right-3 -top-3 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="absolute -right-1 -bottom-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <p class="text-xs font-medium text-amber-100">Pending</p>
                    <p class="text-3xl font-bold mt-0.5">{{ $stats['pending'] }}</p>
                </div>
                <div class="relative z-10 w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <div class="relative bg-emerald-500 text-white rounded-xl p-4 flex items-center justify-between overflow-hidden shadow-md shadow-emerald-200">
                <div class="absolute -right-3 -top-3 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="absolute -right-1 -bottom-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <p class="text-xs font-medium text-emerald-100">Diterima</p>
                    <p class="text-3xl font-bold mt-0.5">{{ $stats['disetujui'] }}</p>
                </div>
                <div class="relative z-10 w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <div class="relative bg-rose-500 text-white rounded-xl p-4 flex items-center justify-between overflow-hidden shadow-md shadow-rose-200">
                <div class="absolute -right-3 -top-3 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="absolute -right-1 -bottom-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <p class="text-xs font-medium text-rose-100">Ditolak</p>
                    <p class="text-3xl font-bold mt-0.5">{{ $stats['ditolak'] }}</p>
                </div>
                <div class="relative z-10 w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

        </div>

        {{-- Bottom Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Pendataran Terbaru --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                        <h3 class="text-sm font-semibold text-gray-800">Pendataran Terbaru</h3>
                    </div>
                    <span class="text-xs text-gray-400 bg-gray-50 border border-gray-100 px-2.5 py-0.5 rounded-full">Live</span>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($recentCalonPenerima as $calon)
                        <div class="flex items-center justify-between px-4 py-3 hover:bg-blue-50/40 transition-colors duration-150">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm shrink-0">
                                    <span class="text-xs font-bold text-white">
                                        {{ strtoupper(substr($calon->nama_lengkap, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $calon->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-400">NIK: {{ $calon->nik }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full
                                    @if($calon->status_verifikasi == 'pending') bg-amber-100 text-amber-700
                                    @elseif($calon->status_verifikasi == 'disetujui') bg-emerald-100 text-emerald-700
                                    @else bg-rose-100 text-rose-700 @endif">
                                    {{ ucfirst($calon->status_verifikasi) }}
                                </span>
                                <p class="text-xs text-gray-400 mt-1">{{ $calon->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-400">Belum ada data pendataran</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Aksi Cepat --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-800">Aksi Cepat</h3>
                </div>

                <div class="space-y-2.5">
                    <a href="{{ route('rt.calon-penerima.create') }}"
                       class="group flex items-center space-x-3 p-3 bg-blue-50 hover:bg-blue-600 border border-blue-100 hover:border-blue-600 rounded-xl transition-all duration-200">
                        <div class="w-9 h-9 bg-blue-600 group-hover:bg-white/20 rounded-lg flex items-center justify-center shrink-0 transition-colors duration-200">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-white transition-colors">Daftarkan Warga Baru</p>
                            <p class="text-xs text-gray-500 group-hover:text-blue-100 transition-colors">Tambah data calon penerima</p>
                        </div>
                    </a>

                    <a href="{{ route('rt.calon-penerima.index') }}"
                       class="group flex items-center space-x-3 p-3 bg-emerald-50 hover:bg-emerald-600 border border-emerald-100 hover:border-emerald-600 rounded-xl transition-all duration-200">
                        <div class="w-9 h-9 bg-emerald-600 group-hover:bg-white/20 rounded-lg flex items-center justify-center shrink-0 transition-colors duration-200">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-white transition-colors">Cek Status</p>
                            <p class="text-xs text-gray-500 group-hover:text-emerald-100 transition-colors">Lihat status pengajuan</p>
                        </div>
                    </a>

                    {{-- Rata-rata Probabilitas --}}
                    <div class="p-3 bg-gradient-to-br from-blue-600 to-blue-500 rounded-xl shadow-sm shadow-blue-200">
                        <div class="flex items-center justify-between mb-0.5">
                            <p class="text-xs font-semibold text-white">Rata-rata Probabilitas</p>
                            <svg class="w-3.5 h-3.5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>

                        @php
                            $avgProb = \App\Models\PrediksiKelayakan::whereHas('calonPenerima', function($q) {
                                $q->where('user_id', Auth::id());
                            })->avg('probability');
                        @endphp

                        <p class="text-2xl font-bold text-white mt-0.5">
                            {{ number_format($avgProb ?? 0, 2) }}<span class="text-sm text-blue-200">%</span>
                        </p>
                        <p class="text-xs text-blue-200 mt-0.5">Berdasarkan prediksi kelayakan</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>