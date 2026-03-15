<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h2 style="font-size:17px;font-weight:900;color:#0f172a;line-height:1.2;letter-spacing:-.02em;">Dashboard</h2>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;font-weight:500;">
                    Ringkasan data pendataan warga {{ Auth::user()->rt->dusun->nama_dusun ?? '' }}
                </p>
            </div>
            <div style="display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid #e2e8f0;border-radius:11px;padding:7px 12px;font-size:11px;color:#64748b;font-weight:500;box-shadow:0 1px 4px rgba(0,0,0,.04);">
                <svg width="13" height="13" fill="none" stroke="#3b82f6" viewBox="0 0 24 24">
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

    @php
        $avgProb = \App\Models\PrediksiKelayakan::whereHas('calonPenerima', function ($q) {
            $q->where('user_id', Auth::id());
        })->avg('probability');

        $avgProb = $avgProb ?? 0;

        if ($avgProb <= 1) {
            $avgProb = $avgProb * 100;
        }
    @endphp

    <style>
        .db-card{background:#fff;border-radius:20px;border:1.5px solid #f1f5f9;box-shadow:0 2px 8px rgba(0,0,0,.04);overflow:hidden;}
        .sc-blob1{position:absolute;top:-16px;right:-16px;width:80px;height:80px;background:rgba(255,255,255,.12);border-radius:50%;}
        .sc-blob2{position:absolute;bottom:-20px;right:8px;width:56px;height:56px;background:rgba(255,255,255,.09);border-radius:50%;}

        .aksi-btn{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:14px;border:1.5px solid;text-decoration:none;transition:filter .15s,transform .1s;}
        .aksi-btn:hover{filter:brightness(.96);transform:translateY(-1px);}
        .aksi-icon{width:38px;height:38px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .aksi-icon svg{width:16px;height:16px;stroke:#fff;}

        .recent-item{display:flex;align-items:center;justify-content:space-between;padding:11px 16px;border-bottom:1px solid #f8fafc;transition:background .1s;}
        .recent-item:hover{background:#f0f7ff;}
        .recent-item:last-child{border-bottom:none;}
    </style>

    <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- GREETING (asli tidak diubah) --}}
        <div style="display:flex;align-items:center;gap:12px;background:#fff;border:1.5px solid #f1f5f9;border-radius:18px;padding:14px 18px;box-shadow:0 1px 4px rgba(0,0,0,.04);">
            <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-size:15px;font-weight:800;flex-shrink:0;box-shadow:0 4px 12px rgba(37,99,235,.28);">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <p style="font-size:13.5px;font-weight:700;color:#0f172a;">
                    Selamat datang, <span style="color:#2563eb;">{{ Auth::user()->name ?? 'Pengguna' }}</span> 👋
                </p>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;font-weight:500;">Berikut ringkasan data terkini yang perlu Anda pantau.</p>
            </div>
        </div>

        {{-- STAT CARDS (asli tidak diubah) --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">

            <div style="position:relative;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;border-radius:18px;padding:18px;display:flex;align-items:center;justify-content:space-between;overflow:hidden;box-shadow:0 6px 20px rgba(37,99,235,.3);">
                <div class="sc-blob1"></div><div class="sc-blob2"></div>
                <div style="position:relative;z-index:1;">
                    <p style="font-size:10.5px;font-weight:600;color:rgba(191,219,254,.9);">Total Warga</p>
                    <p style="font-size:32px;font-weight:900;margin-top:2px;line-height:1;letter-spacing:-.04em;">{{ $stats['total_input'] }}</p>
                </div>
                <div style="position:relative;z-index:1;width:44px;height:44px;background:rgba(255,255,255,.18);border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2);">
                    <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>

            <div style="position:relative;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border-radius:18px;padding:18px;display:flex;align-items:center;justify-content:space-between;overflow:hidden;box-shadow:0 6px 20px rgba(245,158,11,.3);">
                <div class="sc-blob1"></div><div class="sc-blob2"></div>
                <div style="position:relative;z-index:1;">
                    <p style="font-size:10.5px;font-weight:600;color:rgba(254,243,199,.9);">Pending</p>
                    <p style="font-size:32px;font-weight:900;margin-top:2px;line-height:1;letter-spacing:-.04em;">{{ $stats['pending'] }}</p>
                </div>
                <div style="position:relative;z-index:1;width:44px;height:44px;background:rgba(255,255,255,.18);border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2);">
                    <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div style="position:relative;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border-radius:18px;padding:18px;display:flex;align-items:center;justify-content:space-between;overflow:hidden;box-shadow:0 6px 20px rgba(16,185,129,.3);">
                <div class="sc-blob1"></div><div class="sc-blob2"></div>
                <div style="position:relative;z-index:1;">
                    <p style="font-size:10.5px;font-weight:600;color:rgba(167,243,208,.9);">Diterima</p>
                    <p style="font-size:32px;font-weight:900;margin-top:2px;line-height:1;letter-spacing:-.04em;">{{ $stats['disetujui'] }}</p>
                </div>
                <div style="position:relative;z-index:1;width:44px;height:44px;background:rgba(255,255,255,.18);border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2);">
                    <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div style="position:relative;background:linear-gradient(135deg,#f43f5e,#e11d48);color:#fff;border-radius:18px;padding:18px;display:flex;align-items:center;justify-content:space-between;overflow:hidden;box-shadow:0 6px 20px rgba(244,63,94,.3);">
                <div class="sc-blob1"></div><div class="sc-blob2"></div>
                <div style="position:relative;z-index:1;">
                    <p style="font-size:10.5px;font-weight:600;color:rgba(254,205,211,.9);">Ditolak</p>
                    <p style="font-size:32px;font-weight:900;margin-top:2px;line-height:1;letter-spacing:-.04em;">{{ $stats['ditolak'] }}</p>
                </div>
                <div style="position:relative;z-index:1;width:44px;height:44px;background:rgba(255,255,255,.18);border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2);">
                    <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

        </div>

        {{-- GRID: Pendataan Terbaru + Aksi Cepat (logika asli 100% tidak diubah) --}}
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:14px;">

            {{-- Pendataan Terbaru --}}
            <div class="db-card">
                <div style="padding:12px 18px;border-bottom:1px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:7px;">
                        <div style="width:3px;height:16px;background:linear-gradient(180deg,#3b82f6,#2563eb);border-radius:4px;"></div>
                        <h3 style="font-size:13px;font-weight:700;color:#1e293b;">Pendataan Terbaru</h3>
                    </div>
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:10px;color:#10b981;background:#f0fdf4;border:1px solid #bbf7d0;padding:2px 9px;border-radius:20px;font-weight:700;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#10b981;animation:pulse 2s infinite;"></span>
                        Live
                    </span>
                </div>

                <div>
                    @forelse($recentCalonPenerima as $calon)
                        <div class="recent-item">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:12px;font-weight:800;color:#fff;box-shadow:0 3px 8px rgba(37,99,235,.22);">
                                    {{ strtoupper(substr($calon->nama_lengkap, 0, 1)) }}
                                </div>
                                <div>
                                    <p style="font-size:12.5px;font-weight:700;color:#0f172a;">{{ $calon->nama_lengkap }}</p>
                                    <p style="font-size:10.5px;color:#94a3b8;margin-top:1px;font-family:monospace;">NIK: {{ $calon->nik }}</p>
                                </div>
                            </div>
                            <div style="text-align:right;">
                                @if($calon->status_verifikasi == 'pending')
                                    <span style="display:inline-flex;align-items:center;gap:3px;padding:3px 9px;font-size:10.5px;font-weight:700;border-radius:20px;background:#fffbeb;color:#b45309;border:1px solid #fde68a;">
                                        <span style="width:5px;height:5px;border-radius:50%;background:#f59e0b;flex-shrink:0;"></span>
                                        Pending
                                    </span>
                                @elseif($calon->status_verifikasi == 'disetujui')
                                    <span style="display:inline-flex;align-items:center;gap:3px;padding:3px 9px;font-size:10.5px;font-weight:700;border-radius:20px;background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;">
                                        <span style="width:5px;height:5px;border-radius:50%;background:#22c55e;flex-shrink:0;"></span>
                                        Disetujui
                                    </span>
                                @else
                                    <span style="display:inline-flex;align-items:center;gap:3px;padding:3px 9px;font-size:10.5px;font-weight:700;border-radius:20px;background:#fff1f2;color:#be123c;border:1px solid #fecdd3;">
                                        <span style="width:5px;height:5px;border-radius:50%;background:#f43f5e;flex-shrink:0;"></span>
                                        {{ ucfirst($calon->status_verifikasi) }}
                                    </span>
                                @endif
                                <p style="font-size:10px;color:#94a3b8;margin-top:3px;">{{ $calon->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div style="padding:44px 16px;text-align:center;">
                            <div style="width:44px;height:44px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                <svg width="20" height="20" fill="none" stroke="#d1d5db" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <p style="font-size:12.5px;font-weight:600;color:#94a3b8;">Belum ada data pendataan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Aksi Cepat (asli tidak diubah) --}}
            <div class="db-card" style="padding:0;">
                <div style="padding:12px 18px;border-bottom:1px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;gap:7px;">
                    <div style="width:3px;height:16px;background:linear-gradient(180deg,#10b981,#059669);border-radius:4px;"></div>
                    <h3 style="font-size:13px;font-weight:700;color:#1e293b;">Aksi Cepat</h3>
                </div>

                <div style="padding:14px;display:flex;flex-direction:column;gap:10px;">

                    {{-- Daftarkan Warga Baru (asli) --}}
                    <a href="{{ route('rt.calon-penerima.create') }}" class="aksi-btn"
                       style="background:#eff6ff;border-color:#bfdbfe;">
                        <div class="aksi-icon" style="background:linear-gradient(135deg,#3b82f6,#2563eb);box-shadow:0 4px 12px rgba(37,99,235,.28);">
                            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <div>
                            <p style="font-size:12.5px;font-weight:700;color:#1e293b;">Daftarkan Warga Baru</p>
                            <p style="font-size:10.5px;color:#64748b;margin-top:1px;">Tambah data calon penerima</p>
                        </div>
                    </a>

                    {{-- Lihat Laporan (asli) --}}
                    <a href="{{ route('rt.laporan.index') }}" class="aksi-btn"
                       style="background:#f0fdf4;border-color:#bbf7d0;">
                        <div class="aksi-icon" style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 4px 12px rgba(16,185,129,.28);">
                            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <p style="font-size:12.5px;font-weight:700;color:#1e293b;">Lihat Laporan</p>
                            <p style="font-size:10.5px;color:#64748b;margin-top:1px;">Lihat hasil akhir yang dikirim admin</p>
                        </div>
                    </a>

                    {{-- Rata-rata skor (asli, $avgProb tidak diubah) --}}
                    <div style="padding:14px;background:linear-gradient(135deg,#1e40af,#2563eb,#3b82f6);border-radius:14px;box-shadow:0 6px 20px rgba(37,99,235,.3);position:relative;overflow:hidden;">
                        <div style="position:absolute;top:-14px;right:-14px;width:70px;height:70px;background:rgba(255,255,255,.08);border-radius:50%;"></div>
                        <div style="position:absolute;bottom:-18px;left:30%;width:54px;height:54px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
                        <div style="position:relative;z-index:1;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                                <p style="font-size:10.5px;font-weight:700;color:rgba(255,255,255,.85);">Rata-rata Skor Kelayakan</p>
                                <svg width="14" height="14" fill="none" stroke="rgba(191,219,254,.8)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <p style="font-size:30px;font-weight:900;color:#fff;line-height:1;letter-spacing:-.04em;margin-bottom:4px;">
                                {{ number_format($avgProb, 1) }}<span style="font-size:14px;font-weight:600;color:rgba(191,219,254,.8);">%</span>
                            </p>
                            <div style="width:100%;height:4px;background:rgba(255,255,255,.2);border-radius:99px;overflow:hidden;margin-bottom:6px;">
                                <div style="height:100%;border-radius:99px;background:rgba(255,255,255,.7);width:{{ min($avgProb, 100) }}%;"></div>
                            </div>
                            <p style="font-size:10px;color:rgba(191,219,254,.75);font-weight:500;line-height:1.5;">
                                Nilai rata-rata prediksi kelayakan, bukan keputusan akhir bantuan
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .5; transform: scale(.85); }
        }
    </style>

</x-app-layout>