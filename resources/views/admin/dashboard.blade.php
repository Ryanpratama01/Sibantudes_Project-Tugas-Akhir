<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Kelurahan</h2>
                <p class="text-sm text-gray-500 mt-0.5">Ringkasan data kelayakan penerima BLT-DD seluruh dusun</p>
            </div>
            <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs text-gray-500">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="admin-clock"></span>
            </div>
        </div>
    </x-slot>

    <script>
        function updateAdminClock() {
            const now = new Date();
            const dateStr = now.toLocaleDateString('id-ID', { timeZone:'Asia/Jakarta', weekday:'long', day:'2-digit', month:'long', year:'numeric' });
            const timeStr = now.toLocaleTimeString('id-ID', { timeZone:'Asia/Jakarta', hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false });
            const el = document.getElementById('admin-clock');
            if (el) el.textContent = dateStr + ' — ' + timeStr + ' WIB';
        }
        updateAdminClock();
        setInterval(updateAdminClock, 1000);
    </script>

    <div class="space-y-4">

        {{-- GREETING + NOTIFIKASI --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex items-center gap-3 bg-white border border-gray-100 rounded-xl px-4 py-3 shadow-sm flex-1">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Selamat datang, <span class="text-blue-600">{{ Auth::user()->name ?? 'Admin' }}</span> 👋</p>
                    <p class="text-xs text-gray-400">Panel admin — kelola dan pantau seluruh data warga.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.33-4.976A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-amber-800">{{ $totalPending ?? 0 }} data menunggu verifikasi</p>
                    <a href="{{ route('admin.data-warga') }}" class="text-xs text-amber-600 hover:underline">Proses sekarang →</a>
                </div>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

            <div class="relative bg-blue-600 text-white rounded-xl p-4 flex items-center justify-between overflow-hidden shadow-md shadow-blue-200">
                <div class="absolute -right-3 -top-3 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="absolute -right-1 -bottom-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <p class="text-xs font-medium text-blue-100">Total Warga Masuk</p>
                    <p class="text-3xl font-bold mt-0.5">{{ $totalWarga ?? 0 }}</p>
                    <p class="text-xs text-blue-200 mt-0.5">Dari semua dusun</p>
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
                    <p class="text-xs font-medium text-amber-100">Pending Verifikasi</p>
                    <p class="text-3xl font-bold mt-0.5">{{ $totalPending ?? 0 }}</p>
                    <p class="text-xs text-amber-200 mt-0.5">Belum diproses</p>
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
                    <p class="text-xs font-medium text-emerald-100">Diterima / Ditetapkan</p>
                    <p class="text-3xl font-bold mt-0.5">{{ $totalPenerima ?? 0 }}</p>
                    <p class="text-xs text-emerald-200 mt-0.5">Sudah ditetapkan</p>
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
                    <p class="text-3xl font-bold mt-0.5">{{ $totalDitolak ?? 0 }}</p>
                    <p class="text-xs text-rose-200 mt-0.5">Tidak memenuhi syarat</p>
                </div>
                <div class="relative z-10 w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

        </div>

        {{-- GRAFIK + DATA PER DUSUN --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- ✅ GRAFIK POLIGON LINE PER DUSUN (SVG murni, seperti referensi) --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                        <h3 class="text-sm font-semibold text-gray-700">Jumlah Warga per Dusun</h3>
                    </div>
                    <span class="text-xs text-gray-400 bg-white border border-gray-100 px-2.5 py-0.5 rounded-full">
                        {{ $wargaPerDusun->sum('total') }} total warga
                    </span>
                </div>
                <div class="p-5">
                    @php
                        $dusuns = $wargaPerDusun->values();
                        $n      = max($dusuns->count(), 1);
                        $maxV   = max((int)$dusuns->max('total'), 1);

                        // Canvas
                        $W  = 560; $H  = 210;
                        $pL = 40;  $pR = 20;
                        $pT = 30;  $pB = 45;
                        $cW = $W - $pL - $pR;
                        $cH = $H - $pT - $pB;

                        // Y ticks (0 .. maxV, max 5 steps)
                        $step   = max(1, (int)ceil($maxV / 4));
                        $yTicks = range(0, $maxV + $step, $step);

                        // Compute point coords
                        $pts = [];
                        foreach ($dusuns as $i => $d) {
                            $x = $pL + ($n === 1 ? $cW / 2 : ($i / ($n - 1)) * $cW);
                            $y = $pT + $cH - ($d->total / $maxV) * $cH;
                            $pts[] = ['x' => round($x, 1), 'y' => round($y, 1), 'val' => $d->total, 'name' => $d->dusun];
                        }

                        $poly     = implode(' ', array_map(fn($p) => "{$p['x']},{$p['y']}", $pts));
                        $firstPt  = $pts[0];
                        $lastPt   = $pts[count($pts)-1];
                        $botY     = $pT + $cH;
                        $areaPath = "M {$firstPt['x']},{$firstPt['y']} "
                                  . implode(' ', array_map(fn($p) => "L {$p['x']},{$p['y']}", array_slice($pts, 1)))
                                  . " L {$lastPt['x']},{$botY} L {$firstPt['x']},{$botY} Z";
                    @endphp

                    <svg viewBox="0 0 {{ $W }} {{ $H }}" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="fillGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%"   stop-color="#3b82f6" stop-opacity="0.18"/>
                                <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                            </linearGradient>
                        </defs>

                        {{-- Y grid + label --}}
                        @foreach($yTicks as $t)
                            @if($t <= $maxV + $step)
                                @php $gy = $pT + $cH - min($t, $maxV) / $maxV * $cH; @endphp
                                <line x1="{{ $pL }}" y1="{{ $gy }}" x2="{{ $W - $pR }}" y2="{{ $gy }}"
                                      stroke="#f1f5f9" stroke-width="1"/>
                                <text x="{{ $pL - 5 }}" y="{{ $gy + 4 }}" text-anchor="end"
                                      font-size="10" fill="#94a3b8">{{ $t }}</text>
                            @endif
                        @endforeach

                        {{-- Label sumbu Y --}}
                        <text x="10" y="{{ $pT + $cH / 2 }}" text-anchor="middle"
                              font-size="9" fill="#94a3b8"
                              transform="rotate(-90,10,{{ $pT + $cH / 2 }})">Jumlah Warga</text>

                        {{-- Sumbu X --}}
                        <line x1="{{ $pL }}" y1="{{ $botY }}" x2="{{ $W - $pR }}" y2="{{ $botY }}"
                              stroke="#cbd5e1" stroke-width="1.5"/>
                        {{-- Panah X --}}
                        <polygon points="{{ $W - $pR }},{{ $botY - 4 }} {{ $W - $pR + 8 }},{{ $botY }} {{ $W - $pR }},{{ $botY + 4 }}"
                                 fill="#94a3b8"/>

                        {{-- Sumbu Y --}}
                        <line x1="{{ $pL }}" y1="{{ $pT }}" x2="{{ $pL }}" y2="{{ $botY }}"
                              stroke="#cbd5e1" stroke-width="1.5"/>
                        {{-- Panah Y --}}
                        <polygon points="{{ $pL - 4 }},{{ $pT }} {{ $pL }},{{ $pT - 8 }} {{ $pL + 4 }},{{ $pT }}"
                                 fill="#94a3b8"/>

                        {{-- Area fill --}}
                        <path d="{{ $areaPath }}" fill="url(#fillGrad)"/>

                        {{-- Garis poligon biru --}}
                        <polyline points="{{ $poly }}"
                                  fill="none" stroke="#2563eb" stroke-width="2.5"
                                  stroke-linejoin="round" stroke-linecap="round"/>

                        {{-- Titik & label per dusun --}}
                        @foreach($pts as $p)
                            {{-- Dot --}}
                            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="5"
                                    fill="white" stroke="#2563eb" stroke-width="2.5"/>
                            {{-- Nilai di atas titik --}}
                            <text x="{{ $p['x'] }}" y="{{ $p['y'] - 10 }}" text-anchor="middle"
                                  font-size="11" font-weight="700" fill="#2563eb">{{ $p['val'] }}</text>
                            {{-- Nama dusun di bawah sumbu X --}}
                            <text x="{{ $p['x'] }}" y="{{ $botY + 16 }}" text-anchor="middle"
                                  font-size="10" fill="#64748b">{{ Str::limit($p['name'], 9) }}</text>
                        @endforeach

                        {{-- Label "Nama Dusun" sumbu X --}}
                        <text x="{{ $W - $pR + 10 }}" y="{{ $botY + 4 }}" text-anchor="start"
                              font-size="9" fill="#94a3b8">Dusun</text>
                    </svg>
                </div>
            </div>

            {{-- DATA PER DUSUN (tidak diubah) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                    <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-700">Data Per Dusun</h3>
                </div>
                <div class="p-4 space-y-2.5">
                    @php $maxDusun = $wargaPerDusun->max('total') ?: 1; @endphp
                    @foreach($wargaPerDusun as $data)
                        <div class="p-3 bg-gray-50 hover:bg-blue-50 border border-gray-100 hover:border-blue-100 rounded-xl transition-all duration-150">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-800">{{ $data->dusun }}</span>
                                </div>
                                <span class="text-base font-bold text-blue-600">{{ $data->total }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 bg-blue-500 rounded-full" style="width: {{ ($data->total / $maxDusun) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- TOP 10 (tidak diubah) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-700">Top 10 Probabilitas Tertinggi</h3>
                </div>
                <a href="{{ route('admin.filterisasi') }}" class="text-xs text-blue-600 hover:underline font-medium">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide w-12">Rank</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">NIK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Dusun</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Penghasilan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Probabilitas</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($topWarga as $index => $warga)
                            <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                                <td class="px-4 py-3">
                                    @if($index == 0)
                                        <div class="w-7 h-7 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center text-xs font-bold">1</div>
                                    @elseif($index == 1)
                                        <div class="w-7 h-7 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs font-bold">2</div>
                                    @elseif($index == 2)
                                        <div class="w-7 h-7 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center text-xs font-bold">3</div>
                                    @else
                                        <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-400 flex items-center justify-center text-xs">{{ $index + 1 }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shrink-0">
                                            <span class="text-xs font-bold text-white">{{ strtoupper(substr($warga->nama, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-800">{{ $warga->nama }}</div>
                                            <div class="text-xs text-gray-400">{{ $warga->pekerjaan }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs font-mono text-gray-500">{{ $warga->nik }}</td>
                                <td class="px-4 py-3 text-xs text-gray-600">{{ $warga->dusun }}</td>
                                <td class="px-4 py-3 text-xs text-gray-600">Rp {{ number_format($warga->penghasilan, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5" style="min-width:60px">
                                            <div class="h-1.5 rounded-full {{ $warga->probabilitas >= 70 ? 'bg-emerald-500' : ($warga->probabilitas >= 40 ? 'bg-amber-400' : 'bg-rose-400') }}"
                                                 style="width: {{ min($warga->probabilitas, 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold {{ $warga->probabilitas >= 70 ? 'text-emerald-600' : ($warga->probabilitas >= 40 ? 'text-amber-600' : 'text-rose-500') }}">
                                            {{ number_format($warga->probabilitas, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($warga->status == 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-700"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Pending</span>
                                    @elseif($warga->status == 'diterima')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Diterima</span>
                                    @elseif($warga->status == 'ditolak')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold rounded-full bg-rose-100 text-rose-700"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Ditolak</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>{{ ucfirst($warga->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center">
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-400">Belum ada data warga</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>