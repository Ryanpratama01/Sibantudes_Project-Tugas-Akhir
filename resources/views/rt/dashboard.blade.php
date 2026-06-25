<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;">
            <div>
                <h2 style="font-size:16px;font-weight:900;color:#0f172a;letter-spacing:-.02em;">Dashboard</h2>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;font-weight:500;">
                    Pendataan warga {{ Auth::user()->rt->dusun->nama_dusun ?? '' }}
                </p>
            </div>
            <div style="display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid #e2e8f0;border-radius:11px;padding:7px 12px;font-size:11px;color:#64748b;font-weight:500;flex-shrink:0;min-width:0;overflow:hidden;">
                <svg width="13" height="13" fill="none" stroke="#3b82f6" viewBox="0 0 24 24" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span id="live-clock" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }} — {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB</span>
            </div>
        </div>
        <script>
            function updateClock(){
                var now=new Date();
                var d=now.toLocaleDateString('id-ID',{timeZone:'Asia/Jakarta',weekday:'long',day:'2-digit',month:'long',year:'numeric'});
                var t=now.toLocaleTimeString('id-ID',{timeZone:'Asia/Jakarta',hour:'2-digit',minute:'2-digit',hour12:false});
                var el=document.getElementById('live-clock');
                if(el) el.textContent=d+' — '+t+' WIB';
            }
            updateClock();
            setInterval(updateClock,1000);
        </script>
    </x-slot>

    @php
        $avgProb = \App\Models\PrediksiKelayakan::whereHas('calonPenerima', function ($q) {
            $q->where('user_id', Auth::id());
        })->avg('probability') ?? 0;
        if ($avgProb <= 1) $avgProb = $avgProb * 100;
    @endphp

    <style>
        .db-card{background:#fff;border-radius:18px;border:1.5px solid #f1f5f9;box-shadow:0 2px 8px rgba(0,0,0,.04);overflow:hidden;}
        .sc-blob1{position:absolute;top:-16px;right:-16px;width:80px;height:80px;background:rgba(255,255,255,.12);border-radius:50%;pointer-events:none;}
        .sc-blob2{position:absolute;bottom:-20px;right:8px;width:56px;height:56px;background:rgba(255,255,255,.09);border-radius:50%;pointer-events:none;}

        .aksi-btn{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:13px;border:1.5px solid;text-decoration:none;transition:filter .15s,transform .1s;}
        .aksi-btn:hover{filter:brightness(.96);transform:translateY(-1px);}
        .aksi-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .aksi-icon svg{width:16px;height:16px;stroke:#fff;}

        .recent-item{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:11px 14px;border-bottom:1px solid #f8fafc;transition:background .1s;}
        .recent-item:hover{background:#f0f7ff;}
        .recent-item:last-child{border-bottom:none;}

        .rt-stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
        @media(max-width:860px){.rt-stat-grid{grid-template-columns:repeat(2,1fr);}}

        .rt-main-grid{display:grid;grid-template-columns:2fr 1fr;gap:14px;align-items:start;}
        @media(max-width:1024px){.rt-main-grid{grid-template-columns:1fr;}}

        @media(max-width:480px){
            .recent-item{flex-wrap:wrap;gap:6px;}
            .recent-item-right{width:100%;display:flex;justify-content:flex-end;}
        }
        @media(max-width:500px){#live-clock{font-size:10px;}}

        @keyframes pulse{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.5;transform:scale(.85);}}

        /* ── ALUR BANNER ── */
        .alur-banner{background:linear-gradient(135deg,#1e40af,#2563eb);border-radius:18px;padding:16px;box-shadow:0 6px 24px rgba(37,99,235,.3);position:relative;overflow:hidden;}
        .alur-banner::before{content:'';position:absolute;top:-30px;right:-30px;width:120px;height:120px;background:rgba(255,255,255,.06);border-radius:50%;}
        .alur-banner::after{content:'';position:absolute;bottom:-20px;right:60px;width:80px;height:80px;background:rgba(255,255,255,.04);border-radius:50%;}

        .alur-title{font-size:13px;font-weight:900;color:#fff;margin-bottom:12px;display:flex;align-items:center;gap:7px;}
        .alur-title svg{width:16px;height:16px;stroke:#93c5fd;flex-shrink:0;}

        /* DESKTOP: flex 1 baris */
        .alur-steps-wrap{display:flex;align-items:stretch;position:relative;z-index:1;}
        .alur-arrow-wrap{display:flex;align-items:center;padding:0 5px;flex-shrink:0;}
        .alur-arrow-wrap svg{width:16px;height:16px;stroke:rgba(255,255,255,.4);}
        .alur-step-box{flex:1;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 8px;display:flex;flex-direction:column;align-items:center;text-align:center;gap:5px;}

        .alur-step-num{width:22px;height:22px;border-radius:50%;background:#fff;color:#1e40af;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .alur-step-icon{width:34px;height:34px;background:rgba(255,255,255,.15);border-radius:9px;display:flex;align-items:center;justify-content:center;}
        .alur-step-icon svg{width:17px;height:17px;stroke:#fff;}
        .alur-step-label{font-size:11.5px;font-weight:800;color:#fff;line-height:1.3;}
        .alur-step-sub{font-size:10px;color:rgba(191,219,254,.85);line-height:1.4;}

        /* MOBILE: grid 2x2, panah disembunyikan */
        @media(max-width:640px){
            .alur-banner{padding:13px 12px;}
            .alur-title{font-size:11px;margin-bottom:10px;}
            .alur-steps-wrap{display:grid;grid-template-columns:1fr 1fr;gap:7px;}
            .alur-arrow-wrap{display:none;}
            .alur-step-box{flex:none;padding:10px 7px;gap:4px;border-radius:10px;}
            .alur-step-num{width:19px;height:19px;font-size:10px;}
            .alur-step-icon{width:28px;height:28px;border-radius:7px;}
            .alur-step-icon svg{width:14px;height:14px;}
            .alur-step-label{font-size:10.5px;}
            .alur-step-sub{font-size:9px;color:rgba(191,219,254,.8);}
        }

        .alur-step-box.active{background:rgba(255,255,255,.22);border-color:rgba(255,255,255,.5);box-shadow:0 0 0 2px rgba(255,255,255,.25);}
        .alur-step-box.done{background:rgba(16,185,129,.25);border-color:rgba(167,243,208,.4);}
    </style>

    <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- GREETING --}}
        <div style="display:flex;align-items:center;gap:12px;background:#fff;border:1.5px solid #f1f5f9;border-radius:16px;padding:14px 16px;box-shadow:0 1px 4px rgba(0,0,0,.04);">
            <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-size:15px;font-weight:800;flex-shrink:0;box-shadow:0 4px 12px rgba(37,99,235,.28);">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <p style="font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Selamat datang, <span style="color:#2563eb;">{{ Auth::user()->name ?? 'Pengguna' }}</span> 👋</p>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;font-weight:500;">Berikut ringkasan data terkini yang perlu Anda pantau.</p>
            </div>
        </div>

        {{-- ── BANNER ALUR PENDATAAN ── --}}
        <div class="alur-banner">
            <div class="alur-title">
                <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                Panduan: Cara Mendaftarkan &amp; Melihat Hasil Warga
            </div>
            <div class="alur-steps-wrap">

                <div class="alur-step-box">
                    <div class="alur-step-num">1</div>
                    <div class="alur-step-icon">
                        <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div class="alur-step-label">Input Data Warga</div>
                    <div class="alur-step-sub">Klik "Pendataan" lalu "Tambah Data" untuk mendaftarkan warga</div>
                </div>

                <div class="alur-arrow-wrap">
                    <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>

                <div class="alur-step-box">
                    <div class="alur-step-num">2</div>
                    <div class="alur-step-icon">
                        <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div class="alur-step-label">Cek Detail Warga</div>
                    <div class="alur-step-sub">Klik tombol "Detail" pada data warga untuk melihat informasi lengkap</div>
                </div>

                <div class="alur-arrow-wrap">
                    <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>

                <div class="alur-step-box">
                    <div class="alur-step-num">3</div>
                    <div class="alur-step-icon">
                        <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="alur-step-label">Ajukan ke perangkat desa</div>
                    <div class="alur-step-sub">Jika data sudah benar, klik "Ajukan" untuk dikirim ke admin perangkat desa</div>
                </div>

                <div class="alur-arrow-wrap">
                    <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>

                <div class="alur-step-box">
                    <div class="alur-step-num">4</div>
                    <div class="alur-step-icon">
                        <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div class="alur-step-label">Lihat Hasil di Laporan</div>
                    <div class="alur-step-sub">Hasil akhir Diterima / Ditolak bisa dilihat di menu "Laporan"</div>
                </div>

            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="rt-stat-grid">
            @php
                $rtCards = [
                    ['label'=>'Total Warga','val'=>$stats['total_input'],'grad'=>'#2563eb,#1d4ed8','shadow'=>'rgba(37,99,235,.3)','tc'=>'rgba(191,219,254,.9)','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['label'=>'Pending','val'=>$stats['pending'],'grad'=>'#f59e0b,#d97706','shadow'=>'rgba(245,158,11,.3)','tc'=>'rgba(254,243,199,.9)','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label'=>'Diterima','val'=>$stats['disetujui'],'grad'=>'#10b981,#059669','shadow'=>'rgba(16,185,129,.3)','tc'=>'rgba(167,243,208,.9)','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label'=>'Ditolak','val'=>$stats['ditolak'],'grad'=>'#f43f5e,#e11d48','shadow'=>'rgba(244,63,94,.3)','tc'=>'rgba(254,205,211,.9)','icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp
            @foreach($rtCards as $c)
            <div style="position:relative;background:linear-gradient(135deg,{{ $c['grad'] }});color:#fff;border-radius:16px;padding:16px;display:flex;align-items:center;justify-content:space-between;overflow:hidden;box-shadow:0 6px 20px {{ $c['shadow'] }};">
                <div class="sc-blob1"></div><div class="sc-blob2"></div>
                <div style="position:relative;z-index:1;">
                    <p style="font-size:10px;font-weight:600;color:{{ $c['tc'] }};">{{ $c['label'] }}</p>
                    <p style="font-size:28px;font-weight:900;margin-top:2px;line-height:1;letter-spacing:-.04em;">{{ $c['val'] }}</p>
                </div>
                <div style="position:relative;z-index:1;width:38px;height:38px;background:rgba(255,255,255,.18);border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2);">
                    <svg width="18" height="18" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $c['icon'] }}"/></svg>
                </div>
            </div>
            @endforeach
        </div>

        {{-- GRID: Terbaru + Aksi --}}
        <div class="rt-main-grid">

            {{-- Pendataan Terbaru --}}
            <div class="db-card">
                <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:7px;">
                        <div style="width:3px;height:15px;background:linear-gradient(180deg,#3b82f6,#2563eb);border-radius:4px;"></div>
                        <h3 style="font-size:13px;font-weight:700;color:#1e293b;">Pendataan Terbaru</h3>
                    </div>
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:10px;color:#10b981;background:#f0fdf4;border:1px solid #bbf7d0;padding:2px 9px;border-radius:20px;font-weight:700;flex-shrink:0;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#10b981;animation:pulse 2s infinite;display:inline-block;"></span>Live
                    </span>
                </div>
                <div>
                    @forelse($recentCalonPenerima as $calon)
                    <div class="recent-item">
                        <div style="display:flex;align-items:center;gap:10px;min-width:0;flex:1;">
                            <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:12px;font-weight:800;color:#fff;">
                                {{ strtoupper(substr($calon->nama_lengkap,0,1)) }}
                            </div>
                            <div style="min-width:0;">
                                <p style="font-size:12px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;">{{ $calon->nama_lengkap }}</p>
                                <p style="font-size:10px;color:#94a3b8;font-family:monospace;">{{ $calon->nik }}</p>
                            </div>
                        </div>
                        <div class="recent-item-right" style="text-align:right;flex-shrink:0;">
                            @php
                                $sv=['pending'=>['#fffbeb','#b45309','#fde68a','Pending'],'disetujui'=>['#f0fdf4','#166534','#bbf7d0','Disetujui']];
                                $sv=$sv[$calon->status_verifikasi]??['#fff1f2','#be123c','#fecdd3',ucfirst($calon->status_verifikasi)];
                            @endphp
                            <span style="display:inline-flex;align-items:center;gap:3px;padding:3px 9px;font-size:10px;font-weight:700;border-radius:20px;background:{{ $sv[0] }};color:{{ $sv[1] }};border:1px solid {{ $sv[2] }};white-space:nowrap;">
                                <span style="width:5px;height:5px;border-radius:50%;background:{{ $sv[1] }};flex-shrink:0;display:inline-block;"></span>{{ $sv[3] }}
                            </span>
                            <p style="font-size:10px;color:#94a3b8;margin-top:3px;">{{ $calon->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div style="padding:44px 16px;text-align:center;">
                        <p style="font-size:12.5px;font-weight:600;color:#94a3b8;">Belum ada data pendataan</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Aksi Cepat --}}
            <div class="db-card" style="padding:0;">
                <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;gap:7px;">
                    <div style="width:3px;height:15px;background:linear-gradient(180deg,#10b981,#059669);border-radius:4px;"></div>
                    <h3 style="font-size:13px;font-weight:700;color:#1e293b;">Aksi Cepat</h3>
                </div>
                <div style="padding:12px;display:flex;flex-direction:column;gap:10px;">
                    <a href="{{ route('rt.calon-penerima.create') }}" class="aksi-btn" style="background:#eff6ff;border-color:#bfdbfe;">
                        <div class="aksi-icon" style="background:linear-gradient(135deg,#3b82f6,#2563eb);box-shadow:0 4px 12px rgba(37,99,235,.28);">
                            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <div style="min-width:0;">
                            <p style="font-size:12px;font-weight:700;color:#1e293b;">Daftarkan Warga Baru</p>
                            <p style="font-size:10.5px;color:#64748b;margin-top:1px;">Tambah data calon penerima</p>
                        </div>
                    </a>
                    <a href="{{ route('rt.laporan.index') }}" class="aksi-btn" style="background:#f0fdf4;border-color:#bbf7d0;">
                        <div class="aksi-icon" style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 4px 12px rgba(16,185,129,.28);">
                            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div style="min-width:0;">
                            <p style="font-size:12px;font-weight:700;color:#1e293b;">Lihat Laporan</p>
                            <p style="font-size:10.5px;color:#64748b;margin-top:1px;">Hasil akhir dari admin</p>
                        </div>
                    </a>
                    <div style="padding:14px;background:linear-gradient(135deg,#1e40af,#2563eb,#3b82f6);border-radius:13px;box-shadow:0 6px 20px rgba(37,99,235,.3);position:relative;overflow:hidden;">
                        <div style="position:absolute;top:-14px;right:-14px;width:70px;height:70px;background:rgba(255,255,255,.08);border-radius:50%;pointer-events:none;"></div>
                        <div style="position:relative;z-index:1;">
                            <p style="font-size:10px;font-weight:700;color:rgba(255,255,255,.85);margin-bottom:4px;">Rata-rata Skor Kelayakan</p>
                            <p style="font-size:28px;font-weight:900;color:#fff;line-height:1;letter-spacing:-.04em;margin-bottom:4px;">
                                {{ number_format($avgProb,1) }}<span style="font-size:13px;font-weight:600;color:rgba(191,219,254,.8);">%</span>
                            </p>
                            <div style="width:100%;height:4px;background:rgba(255,255,255,.2);border-radius:99px;overflow:hidden;margin-bottom:6px;">
                                <div style="height:100%;border-radius:99px;background:rgba(255,255,255,.7);width:{{ min($avgProb,100) }}%;"></div>
                            </div>
                            <p style="font-size:10px;color:rgba(191,219,254,.75);font-weight:500;line-height:1.5;">Rata-rata prediksi, bukan keputusan akhir</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>