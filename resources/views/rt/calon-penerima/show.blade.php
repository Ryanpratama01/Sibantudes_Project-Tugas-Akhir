<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <a href="{{ route('rt.calon-penerima.index') }}"
                   style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:10px; background:#fff; border:1.5px solid #e5e7eb; color:#6b7280; text-decoration:none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 style="font-size:17px; font-weight:800; color:#111827; line-height:1.2;">Detail Calon Penerima</h2>
                    <p style="font-size:11px; color:#9ca3af; margin-top:2px;">Data pendataan warga &amp; hasil prediksi kelayakan</p>
                </div>
            </div>
            @if(($calonPenerima->tracking_status ?? 'draft') === 'draft')
                <a href="{{ route('rt.calon-penerima.edit', $calonPenerima->id) }}"
                   style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; background:#2563eb; color:#fff; font-size:12px; font-weight:700; border-radius:10px; text-decoration:none; box-shadow:0 2px 8px rgba(37,99,235,.25);">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Data
                </a>
            @endif
        </div>
    </x-slot>

    <style>
        .sp-card{background:#fff;border-radius:16px;border:1.5px solid #f1f5f9;box-shadow:0 1px 3px rgba(0,0,0,.04);overflow:hidden;}
        .sp-head{padding:9px 16px;border-bottom:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;gap:8px;}
        .sp-head .bar{width:3px;height:14px;border-radius:4px;flex-shrink:0;}
        .sp-head h3{font-size:10.5px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.07em;}
        .sp-body{padding:13px 16px;}
        .lbl{font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;margin-bottom:2px;}
        .val{font-size:12.5px;font-weight:600;color:#111827;}
        .g2{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
        .g4{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
        .pill{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:20px;font-size:11px;font-weight:700;}
        .dot{width:6px;height:6px;border-radius:50%;flex-shrink:0;}
        .fl{list-style:none;padding:0;margin:0;}
        .fl li{display:flex;align-items:flex-start;gap:5px;font-size:11.5px;line-height:1.5;margin-bottom:2px;}
        .fi{width:13px;height:13px;flex-shrink:0;margin-top:1px;}
        details.acc>summary{list-style:none;display:flex;justify-content:space-between;align-items:center;padding:9px 16px;cursor:pointer;background:#f9fafb;border-top:1px solid #f1f5f9;font-size:11px;font-weight:700;color:#374151;user-select:none;}
        details.acc>summary::-webkit-details-marker{display:none;}
        details.acc>summary .chev{transition:transform .2s;}
        details.acc[open]>summary .chev{transform:rotate(180deg);}
        details.acc .ab{padding:12px 16px;display:flex;flex-direction:column;gap:9px;}
        .trk-wrap{display:flex;flex-direction:column;gap:10px;}
        .trk-step{display:flex;align-items:flex-start;gap:10px;}
        .trk-point{width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:11px;font-weight:800;}
        .trk-line{width:2px;height:18px;margin-left:10px;border-radius:999px;background:#e5e7eb;}
        @media(max-width:1024px){.mg{grid-template-columns:1fr!important;}.g4{grid-template-columns:1fr 1fr!important;}}
        @media(max-width:600px){.g2,.g4{grid-template-columns:1fr!important;}}
    </style>

    @php
        $tracking = $calonPenerima->tracking_status ?? 'draft';
        $st       = $calonPenerima->status_verifikasi ?? 'pending';
        $pred     = $calonPenerima->prediksiKelayakan ?? null;
        $prob     = $pred ? (float)$pred->probability : null;
        $rec      = $pred ? ($pred->recommendation ?? '-') : null;
        $probPct  = $prob !== null ? ($prob <= 1 ? $prob * 100 : $prob) : null;
        $posTop   = array_slice($explanation['positive'] ?? [], 0, 3);
        $negTop   = array_slice($explanation['negative'] ?? [], 0, 2);
        $circum   = round(2 * M_PI * 24, 2);
        $offset   = $probPct !== null ? round($circum * (1 - $probPct / 100), 2) : $circum;
        $sc       = $probPct !== null ? ($probPct >= 70 ? '#10b981' : ($probPct >= 40 ? '#f59e0b' : '#f43f5e')) : '#e5e7eb';
        $recBg    = $probPct !== null ? ($probPct >= 70 ? 'background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;' : ($probPct >= 40 ? 'background:#fffbeb;color:#b45309;border:1px solid #fde68a;' : 'background:#fff1f2;color:#9f1239;border:1px solid #fecdd3;')) : '';

        $trackingLabel = match($tracking) {
            'draft' => 'Draft',
            'terkirim' => 'Terkirim ke Kelurahan',
            'sedang_validasi' => 'Sedang Divalidasi',
            'selesai' => 'Data Sudah Tervalidasi',
            default => '-'
        };

        $hasilLabel = match($st) {
            'disetujui' => 'Diterima',
            'ditolak' => 'Tidak Diterima',
            default => 'Menunggu Hasil'
        };
    @endphp

    {{-- FLASH --}}
    @if(session('success'))
        <div style="display:flex;align-items:center;gap:9px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:9px 14px;margin-bottom:10px;font-size:12px;color:#166534;font-weight:500;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="display:flex;align-items:center;gap:9px;background:#fff1f2;border:1.5px solid #fecdd3;border-radius:12px;padding:9px 14px;margin-bottom:10px;font-size:12px;color:#9f1239;font-weight:500;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- BANNER --}}
    <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#1e40af 0%,#2563eb 55%,#3b82f6 100%);border-radius:18px;padding:16px 20px;margin-bottom:12px;display:flex;align-items:center;gap:14px;box-shadow:0 6px 20px rgba(37,99,235,.22);">
        <div style="position:absolute;top:-24px;right:-24px;width:110px;height:110px;background:rgba(255,255,255,.08);border-radius:50%;pointer-events:none;"></div>
        <div style="position:absolute;bottom:-28px;left:42%;width:80px;height:80px;background:rgba(255,255,255,.06);border-radius:50%;pointer-events:none;"></div>

        <div style="width:46px;height:46px;border-radius:13px;background:rgba(255,255,255,.18);border:2px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:900;color:#fff;flex-shrink:0;">
            {{ strtoupper(substr($calonPenerima->nama_lengkap ?? 'U', 0, 1)) }}
        </div>

        <div style="flex:1;min-width:0;">
            <h3 style="font-size:16px;font-weight:900;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1.2;">{{ $calonPenerima->nama_lengkap ?? '-' }}</h3>
            <p style="font-size:11px;color:rgba(191,219,254,.85);font-family:monospace;margin-top:2px;">NIK: {{ $calonPenerima->nik ?? '-' }}</p>
            <div style="margin-top:6px;">
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;background:rgba(255,255,255,.15);color:#fff;font-size:10.5px;font-weight:700;border:1px solid rgba(255,255,255,.22);">
                    <span style="width:6px;height:6px;border-radius:50%;background:#fff;opacity:.9;"></span>
                    {{ $trackingLabel }}
                </span>
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
            @if($probPct !== null)
                <div style="text-align:right;">
                    <div style="font-size:26px;font-weight:900;color:#fff;line-height:1;">{{ number_format($probPct,0) }}<span style="font-size:13px;color:rgba(191,219,254,.7);">%</span></div>
                    <div style="font-size:10px;color:rgba(191,219,254,.6);margin-top:1px;">probabilitas</div>
                </div>
            @endif

            @if($tracking === 'selesai')
                @if($st === 'disetujui')
                    <span class="pill" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;"><span class="dot" style="background:#22c55e;"></span>Diterima</span>
                @elseif($st === 'ditolak')
                    <span class="pill" style="background:#fff1f2;color:#9f1239;border:1px solid #fecdd3;"><span class="dot" style="background:#f43f5e;"></span>Tidak Diterima</span>
                @else
                    <span class="pill" style="background:#fffbeb;color:#b45309;border:1px solid #fde68a;"><span class="dot" style="background:#f59e0b;"></span>Menunggu Hasil</span>
                @endif
            @else
                <span class="pill" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;"><span class="dot" style="background:#3b82f6;"></span>{{ $trackingLabel }}</span>
            @endif
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="mg" style="display:grid;grid-template-columns:1fr 310px;gap:12px;align-items:start;">

        {{-- KIRI --}}
        <div style="display:flex;flex-direction:column;gap:12px;">

            <div class="g2">
                {{-- Identitas --}}
                <div class="sp-card">
                    <div class="sp-head"><div class="bar" style="background:#2563eb;"></div><h3>Identitas</h3></div>
                    <div class="sp-body">
                        <div class="g2">
                            <div><p class="lbl">No. KK</p><p class="val" style="font-family:monospace;font-size:11px;">{{ $calonPenerima->no_kk ?? '-' }}</p></div>
                            <div><p class="lbl">Jenis Kelamin</p><p class="val">{{ $calonPenerima->jenis_kelamin ?? '-' }}</p></div>
                            <div><p class="lbl">Tempat Lahir</p><p class="val">{{ $calonPenerima->tempat_lahir ?? '-' }}</p></div>
                            <div><p class="lbl">Tanggal Lahir</p><p class="val">{{ $calonPenerima->tanggal_lahir ? \Carbon\Carbon::parse($calonPenerima->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p></div>
                            <div><p class="lbl">Usia</p><p class="val">{{ $calonPenerima->usia ?? '-' }} tahun</p></div>
                            <div><p class="lbl">Status Kawin</p><p class="val">{{ $calonPenerima->status_perkawinan ?? '-' }}</p></div>
                        </div>
                    </div>
                </div>

                {{-- Tempat Tinggal --}}
                <div class="sp-card">
                    <div class="sp-head"><div class="bar" style="background:#10b981;"></div><h3>Tempat Tinggal</h3></div>
                    <div class="sp-body" style="display:flex;flex-direction:column;gap:11px;">
                        <div><p class="lbl">Alamat</p><p class="val">{{ $calonPenerima->alamat ?? '-' }}</p></div>
                        <div class="g2">
                            <div><p class="lbl">Dusun</p><p class="val">{{ $calonPenerima->desa ?? '-' }}</p></div>
                            <div>
                                <p class="lbl">RT</p>
                                <span style="display:inline-flex;align-items:center;padding:2px 9px;border-radius:8px;background:#eff6ff;color:#1d4ed8;font-size:11px;font-weight:700;border:1px solid #bfdbfe;">RT {{ str_pad($calonPenerima->rt->nomor_rt ?? 0,3,'0',STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                        <div><p class="lbl">Aset Kepemilikan</p><p class="val">{{ $calonPenerima->aset_kepemilikan ?? '-' }}</p></div>
                    </div>
                </div>
            </div>

            {{-- Ekonomi --}}
            <div class="sp-card">
                <div class="sp-head"><div class="bar" style="background:#f59e0b;"></div><h3>Data Ekonomi</h3></div>
                <div class="sp-body">
                    <div class="g4">
                        <div><p class="lbl">Pekerjaan</p><p class="val">{{ $calonPenerima->pekerjaan ?? '-' }}</p></div>
                        <div><p class="lbl">Penghasilan</p><p class="val">Rp {{ number_format((float)($calonPenerima->penghasilan??0),0,',','.') }}</p></div>
                        <div><p class="lbl">Tanggungan</p><p class="val">{{ $calonPenerima->jumlah_tanggungan ?? '-' }} orang</p></div>
                        <div>
                            <p class="lbl">Bantuan Lain</p>
                            @if(($calonPenerima->bantuan_lain??'') === 'ya')
                                <span style="display:inline-flex;padding:2px 9px;border-radius:20px;background:#fffbeb;color:#b45309;font-size:11px;font-weight:700;">Ya</span>
                            @else
                                <span style="display:inline-flex;padding:2px 9px;border-radius:20px;background:#f3f4f6;color:#6b7280;font-size:11px;font-weight:700;">Tidak</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tracking Pengajuan --}}
            <div class="sp-card">
                <div class="sp-head"><div class="bar" style="background:#6b7280;"></div><h3>Tracking Pengajuan</h3></div>
                <div class="sp-body">
                    <div class="trk-wrap">
                        <div class="trk-step">
                            <div class="trk-point" style="{{ in_array($tracking, ['draft','terkirim','sedang_validasi','selesai']) ? 'background:#dbeafe;color:#1d4ed8;' : 'background:#f3f4f6;color:#9ca3af;' }}">1</div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:#111827;">Data dibuat RT</div>
                                <div style="font-size:11px;color:#9ca3af;">Data masih bisa diubah dan dihapus sebelum diajukan.</div>
                            </div>
                        </div>
                        <div class="trk-line"></div>

                        <div class="trk-step">
                            <div class="trk-point" style="{{ in_array($tracking, ['terkirim','sedang_validasi','selesai']) ? 'background:#dbeafe;color:#1d4ed8;' : 'background:#f3f4f6;color:#9ca3af;' }}">2</div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:#111827;">Data diajukan ke kelurahan</div>
                                <div style="font-size:11px;color:#9ca3af;">
                                    @if(in_array($tracking, ['terkirim','sedang_validasi','selesai']))
                                        Data sudah dikirim dan tidak bisa diubah lagi.
                                    @else
                                        Menunggu tindakan RT untuk mengajukan data.
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="trk-line"></div>

                        <div class="trk-step">
                            <div class="trk-point" style="{{ in_array($tracking, ['sedang_validasi','selesai']) ? 'background:#fef3c7;color:#b45309;' : 'background:#f3f4f6;color:#9ca3af;' }}">3</div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:#111827;">Proses validasi & filterisasi</div>
                                <div style="font-size:11px;color:#9ca3af;">
                                    @if(in_array($tracking, ['sedang_validasi','selesai']))
                                        Admin sedang atau sudah memproses hasil bantuan berdasarkan kuota dan probabilitas.
                                    @else
                                        Menunggu admin memulai proses validasi.
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="trk-line"></div>

                        <div class="trk-step">
                            <div class="trk-point" style="{{ $tracking === 'selesai' ? 'background:#dcfce7;color:#166534;' : 'background:#f3f4f6;color:#9ca3af;' }}">4</div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:#111827;">Hasil akhir ditetapkan</div>
                                <div style="font-size:11px;color:#9ca3af;">
                                    @if($tracking === 'selesai')
                                        Hasil akhir bantuan telah ditetapkan: <strong style="color:#374151;">{{ $hasilLabel }}</strong>.
                                    @else
                                        Menunggu hasil akhir dari admin kelurahan.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Pengajuan --}}
            <div class="sp-card">
                <div class="sp-head"><div class="bar" style="background:#6b7280;"></div><h3>Status Pengajuan</h3></div>
                <div class="sp-body" style="display:flex;flex-wrap:wrap;align-items:center;gap:14px;">
                    @if($tracking === 'draft')
                        <span class="pill" style="background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;"><span class="dot" style="background:#9ca3af;"></span>Draft</span>
                    @elseif($tracking === 'terkirim')
                        <span class="pill" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;"><span class="dot" style="background:#3b82f6;"></span>Terkirim ke Kelurahan</span>
                    @elseif($tracking === 'sedang_validasi')
                        <span class="pill" style="background:#fffbeb;color:#b45309;border:1px solid #fde68a;"><span class="dot" style="background:#f59e0b;"></span>Sedang Divalidasi</span>
                    @elseif($tracking === 'selesai')
                        @if($st === 'disetujui')
                            <span class="pill" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;"><span class="dot" style="background:#22c55e;"></span>Diterima</span>
                        @elseif($st === 'ditolak')
                            <span class="pill" style="background:#fff1f2;color:#9f1239;border:1px solid #fecdd3;"><span class="dot" style="background:#f43f5e;"></span>Tidak Diterima</span>
                        @else
                            <span class="pill" style="background:#fffbeb;color:#b45309;border:1px solid #fde68a;"><span class="dot" style="background:#f59e0b;"></span>Menunggu Hasil</span>
                        @endif
                    @endif

                    <div style="display:flex;gap:18px;">
                        <div><p class="lbl">Dibuat</p><p class="val">{{ $calonPenerima->created_at ? \Carbon\Carbon::parse($calonPenerima->created_at)->translatedFormat('d M Y, H:i') : '-' }}</p></div>
                        <div><p class="lbl">Diperbarui</p><p class="val">{{ $calonPenerima->updated_at ? \Carbon\Carbon::parse($calonPenerima->updated_at)->translatedFormat('d M Y, H:i') : '-' }}</p></div>
                    </div>

                    @if(!empty($calonPenerima->catatan_admin))
                        <div style="flex:1;min-width:160px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:7px 11px;font-size:11.5px;color:#6b7280;">
                            <strong style="color:#374151;">Catatan: </strong>{{ $calonPenerima->catatan_admin }}
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- KANAN --}}
        <div style="display:flex;flex-direction:column;gap:12px;">

            {{-- Prediksi --}}
            <div class="sp-card">
                <div class="sp-head"><div class="bar" style="background:#2563eb;"></div><h3>Prediksi Kelayakan</h3></div>
                @if($pred)
                    <div class="sp-body" style="display:flex;flex-direction:column;gap:11px;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="position:relative;width:60px;height:60px;flex-shrink:0;">
                                <svg width="60" height="60" viewBox="0 0 56 56" style="transform:rotate(-90deg);">
                                    <circle cx="28" cy="28" r="24" fill="none" stroke="#f1f5f9" stroke-width="6"/>
                                    <circle cx="28" cy="28" r="24" fill="none" stroke="{{ $sc }}" stroke-width="6" stroke-dasharray="{{ $circum }}" stroke-dashoffset="{{ $offset }}" stroke-linecap="round"/>
                                </svg>
                                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:900;color:#111827;">
                                    {{ number_format($probPct,0) }}<span style="font-size:9px;color:#9ca3af;">%</span>
                                </div>
                            </div>
                            <div style="flex:1;">
                                <p class="lbl" style="margin-bottom:4px;">Rekomendasi</p>
                                <span style="{{ $recBg }}display:inline-block;padding:3px 10px;border-radius:8px;font-size:11.5px;font-weight:700;">{{ $rec }}</span>
                                <div style="margin-top:7px;height:4px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                                    <div style="height:100%;border-radius:99px;background:{{ $sc }};width:{{ min($probPct,100) }}%;"></div>
                                </div>
                            </div>
                        </div>

                        @if(!empty($posTop))
                            <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:9px 12px;">
                                <p style="font-size:10px;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:.07em;margin-bottom:5px;">Faktor Pendukung</p>
                                <ul class="fl">@foreach($posTop as $item)<li><svg class="fi" fill="none" stroke="#22c55e" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg><span style="color:#166534;">{{ $item }}</span></li>@endforeach</ul>
                            </div>
                        @endif

                        @if(!empty($negTop))
                            <div style="background:#fff1f2;border:1.5px solid #fecdd3;border-radius:12px;padding:9px 12px;">
                                <p style="font-size:10px;font-weight:700;color:#9f1239;text-transform:uppercase;letter-spacing:.07em;margin-bottom:5px;">Faktor Pengurang</p>
                                <ul class="fl">@foreach($negTop as $item)<li><svg class="fi" fill="none" stroke="#f43f5e" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg><span style="color:#9f1239;">{{ $item }}</span></li>@endforeach</ul>
                            </div>
                        @endif

                        <details class="acc">
                            <summary>Penjelasan Lengkap <svg class="chev" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></summary>
                            <div class="ab">
                                <div style="display:flex;flex-direction:column;gap:5px;">
                                    @foreach(['Pekerjaan'=>$calonPenerima->pekerjaan??'-','Penghasilan'=>'Rp '.number_format((float)($calonPenerima->penghasilan??0),0,',','.'),'Tanggungan'=>($calonPenerima->jumlah_tanggungan??'-').' orang','Aset'=>$calonPenerima->aset_kepemilikan??'-','Bantuan Lain'=>ucfirst($calonPenerima->bantuan_lain??'-'),'Usia'=>($calonPenerima->usia??'-').' tahun'] as $lbl=>$val)
                                        <div style="display:flex;justify-content:space-between;gap:8px;font-size:11px;"><span style="color:#9ca3af;">{{ $lbl }}</span><span style="font-weight:600;color:#374151;text-align:right;">{{ $val }}</span></div>
                                    @endforeach
                                </div>
                                @if(!empty($explanation['positive']))
                                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:8px 11px;">
                                        <p style="font-size:10px;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Semua Pendukung</p>
                                        <ul class="fl">@foreach($explanation['positive'] as $i)<li style="font-size:11px;color:#166534;"><span>•</span>{{ $i }}</li>@endforeach</ul>
                                    </div>
                                @endif
                                @if(!empty($explanation['negative']))
                                    <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:10px;padding:8px 11px;">
                                        <p style="font-size:10px;font-weight:700;color:#9f1239;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Semua Pengurang</p>
                                        <ul class="fl">@foreach($explanation['negative'] as $i)<li style="font-size:11px;color:#9f1239;"><span>•</span>{{ $i }}</li>@endforeach</ul>
                                    </div>
                                @endif
                                @if(!empty($explanation['summary']))
                                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:8px 11px;">
                                        <p style="font-size:10px;font-weight:700;color:#1e40af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px;">Ringkasan</p>
                                        <p style="font-size:11.5px;color:#1e3a8a;line-height:1.6;">{{ $explanation['summary'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </details>
                    </div>
                @else
                    <div style="padding:28px 16px;text-align:center;">
                        <div style="width:38px;height:38px;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 7px;">
                            <svg width="17" height="17" fill="none" stroke="#d1d5db" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <p style="font-size:11px;color:#9ca3af;">Belum ada data prediksi</p>
                    </div>
                @endif
            </div>

            {{-- Hasil Akhir --}}
            @if($tracking === 'selesai')
                <div class="sp-card">
                    <div class="sp-head"><div class="bar" style="background:#10b981;"></div><h3>Hasil Akhir Bantuan</h3></div>
                    <div class="sp-body" style="display:flex;flex-direction:column;gap:10px;">
                        @if($st === 'disetujui')
                            <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:10px 12px;">
                                <div style="font-size:12px;font-weight:800;color:#166534;">Selamat, data Anda diterima.</div>
                                <div style="font-size:11px;color:#166534;margin-top:3px;">Warga ini termasuk penerima bantuan setelah proses validasi dan filterisasi kuota.</div>
                            </div>
                        @elseif($st === 'ditolak')
                            <div style="background:#fff1f2;border:1.5px solid #fecdd3;border-radius:12px;padding:10px 12px;">
                                <div style="font-size:12px;font-weight:800;color:#9f1239;">Data tidak masuk penerima final.</div>
                                <div style="font-size:11px;color:#9f1239;margin-top:3px;">Warga ini belum terpilih pada penetapan penerima bantuan untuk periode ini.</div>
                            </div>
                        @else
                            <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:10px 12px;">
                                <div style="font-size:12px;font-weight:800;color:#b45309;">Hasil akhir belum ditetapkan.</div>
                                <div style="font-size:11px;color:#b45309;margin-top:3px;">Masih menunggu keputusan akhir dari admin kelurahan.</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Zona Bahaya --}}
            @if($tracking === 'draft')
                <div class="sp-card" style="border-color:#fecdd3;">
                    <div class="sp-head" style="background:#fff1f2;border-color:#fecdd3;"><div class="bar" style="background:#f43f5e;"></div><h3 style="color:#9f1239;">Zona Bahaya</h3></div>
                    <div class="sp-body">
                        <p style="font-size:11.5px;color:#6b7280;margin-bottom:10px;">Hapus data ini secara permanen. Tindakan ini <strong style="color:#374151;">tidak dapat dibatalkan</strong>.</p>
                        <form action="{{ route('rt.calon-penerima.destroy', $calonPenerima->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data {{ $calonPenerima->nama_lengkap }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:100%;display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:9px 16px;background:#e11d48;color:#fff;font-size:12px;font-weight:700;border:none;border-radius:10px;cursor:pointer;box-shadow:0 2px 8px rgba(225,29,72,.22);">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>

</x-app-layout>