<x-app-layout>

    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
            <div>
                <h2 style="font-size:17px;font-weight:800;color:#111827;line-height:1.2;">Data Warga</h2>
                <p style="font-size:11px;color:#9ca3af;margin-top:2px;">Kelola dan pantau seluruh data warga dari semua dusun</p>
            </div>
            <div style="display:inline-flex;align-items:center;gap:6px;background:#f8fafc;border:1.5px solid #e5e7eb;border-radius:10px;padding:6px 12px;font-size:11px;color:#6b7280;">
                <svg width="13" height="13" fill="none" stroke="#3b82f6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }} — {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB
            </div>
        </div>
    </x-slot>

    <style>
        .fl-box{display:flex;align-items:center;gap:9px;border-radius:12px;padding:9px 14px;margin-bottom:10px;font-size:12px;font-weight:500;}
        .fl-box svg{width:14px;height:14px;flex-shrink:0;}
        .dw-card{background:#fff;border-radius:18px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04),0 4px 16px rgba(0,0,0,.03);overflow:hidden;}
        .sw{position:relative;}
        .sw svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;stroke:#9ca3af;pointer-events:none;}
        .sw input{width:100%;padding:9px 12px 9px 34px;font-size:13px;color:#111827;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:11px;outline:none;transition:border-color .15s,box-shadow .15s;}
        .sw input:focus{border-color:#3b82f6;background:#fff;box-shadow:0 0 0 3px rgba(59,130,246,.10);}
        .sw input::placeholder{color:#9ca3af;}
        table{width:100%;border-collapse:collapse;}
        thead tr{background:#f8fafc;border-bottom:1.5px solid #f1f5f9;}
        thead th{padding:9px 13px;text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap;}
        tbody tr{border-bottom:1px solid #f8fafc;transition:background .1s;}
        tbody tr:hover{background:#f0f7ff;}
        tbody tr:last-child{border-bottom:none;}
        tbody td{padding:10px 13px;vertical-align:middle;}
        .av{width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0;}
        .nik-pill{font-family:monospace;font-size:10.5px;color:#6b7280;background:#f3f4f6;padding:2px 7px;border-radius:6px;}
        .rt-badge{display:inline-flex;align-items:center;padding:2px 7px;border-radius:6px;background:#eff6ff;color:#1d4ed8;font-size:10.5px;font-weight:700;border:1px solid #bfdbfe;margin-top:3px;}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:10.5px;font-weight:700;white-space:nowrap;}
        .dot{width:5px;height:5px;border-radius:50%;flex-shrink:0;}
        .trk-terkirim{background:#eff6ff;color:#1d4ed8;} .trk-terkirim .dot{background:#3b82f6;}
        .trk-validasi{background:#fffbeb;color:#b45309;} .trk-validasi .dot{background:#f59e0b;}
        .trk-selesai {background:#f0fdf4;color:#166534;} .trk-selesai .dot{background:#22c55e;}
        .trk-default {background:#f3f4f6;color:#6b7280;} .trk-default .dot{background:#9ca3af;}

        .stat-pending{background:#fffbeb;color:#b45309;border:1px solid #fde68a;}
        .stat-yes{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
        .stat-no{background:#fff1f2;color:#9f1239;border:1px solid #fecdd3;}

        .btn-act{display:inline-flex;align-items:center;gap:4px;padding:4px 9px;border-radius:8px;font-size:10.5px;font-weight:600;text-decoration:none;border:none;cursor:pointer;white-space:nowrap;line-height:1.4;transition:filter .1s;}
        .btn-act:hover{filter:brightness(.92);}
        .btn-act svg{width:11px;height:11px;flex-shrink:0;}
        .btn-detail{background:#f3f4f6;color:#374151;}
        .btn-validasi{background:#eff6ff;color:#1d4ed8;}
        .btn-kirim{background:#fffbeb;color:#b45309;}
        .btn-reset{display:inline-flex;align-items:center;gap:6px;padding:9px 14px;background:#ef4444;color:#fff;font-size:12px;font-weight:700;border:none;border-radius:11px;cursor:pointer;box-shadow:0 2px 8px rgba(239,68,68,.22);white-space:nowrap;}
        .btn-reset:hover{filter:brightness(.95);}

        .info-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:10.5px;font-weight:700;background:#f8fafc;color:#475569;border:1px solid #e2e8f0;}

        .prob-wrap{display:flex;align-items:center;gap:7px;}
        .prob-bg{flex:1;height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;}
        .prob-bar{height:100%;border-radius:99px;}

        /* ── MODAL FIX — scroll handled by .m-wrap, not .m-body ── */
        .m-backdrop{position:fixed;inset:0;z-index:40;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);}
        .m-wrap{position:fixed;inset:0;z-index:50;overflow-y:auto;pointer-events:none;display:flex;align-items:flex-start;justify-content:center;padding:24px 16px;}
        .m-box{width:100%;max-width:620px;background:#fff;border-radius:22px;box-shadow:0 24px 64px rgba(0,0,0,.18);overflow:hidden;display:flex;flex-direction:column;pointer-events:auto;margin:auto;}
        .mhero{position:relative;overflow:hidden;padding:18px 22px;background:linear-gradient(135deg,#1e40af 0%,#2563eb 55%,#3b82f6 100%);}
        .mhero-b1{position:absolute;top:-20px;right:-20px;width:90px;height:90px;background:rgba(255,255,255,.08);border-radius:50%;}
        .mhero-b2{position:absolute;bottom:-22px;left:35%;width:70px;height:70px;background:rgba(255,255,255,.06);border-radius:50%;}
        .mhero-row{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:12px;}
        .m-av{width:44px;height:44px;border-radius:13px;background:rgba(255,255,255,.18);border:2px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:900;color:#fff;flex-shrink:0;}
        .m-title{font-size:15px;font-weight:800;color:#fff;line-height:1.2;}
        .m-sub{font-size:11px;color:rgba(191,219,254,.85);margin-top:2px;}
        .m-close{width:30px;height:30px;border-radius:9px;background:rgba(255,255,255,.18);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;}
        .m-close:hover{background:rgba(255,255,255,.28);}
        .m-close svg{width:14px;height:14px;stroke:currentColor;}
        .m-st-pill{margin-top:10px;position:relative;display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:20px;background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.25);font-size:11px;font-weight:700;}
        .m-body{overflow-y:visible;flex:1;padding:14px;background:#f8fafc;display:flex;flex-direction:column;gap:10px;}
        .msec{background:#fff;border-radius:14px;border:1.5px solid #f1f5f9;overflow:hidden;}
        .msec-h{padding:8px 14px;border-bottom:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;gap:7px;}
        .msec-h .bar{width:3px;height:13px;border-radius:3px;flex-shrink:0;}
        .msec-h h4{font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.08em;}
        .msec-b{padding:12px 14px;}
        .mg2{display:grid;grid-template-columns:1fr 1fr;gap:10px 20px;}
        .mlbl{font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;margin-bottom:2px;}
        .mval{font-size:12.5px;font-weight:600;color:#111827;}
        .mval-mono{font-family:monospace;font-size:11px;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:7px;padding:3px 8px;color:#374151;display:inline-block;}
        .m-footer{padding:11px 16px;border-top:1px solid #f1f5f9;background:#fff;flex-shrink:0;display:flex;justify-content:flex-end;}
        .btn-close-m{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;font-size:12.5px;font-weight:700;background:#f3f4f6;color:#374151;border:none;border-radius:10px;cursor:pointer;}
        .btn-close-m:hover{background:#e5e7eb;}
    </style>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="fl-box" style="background:#f0fdf4;border:1.5px solid #bbf7d0;color:#166534;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="fl-box" style="background:#fff1f2;border:1.5px solid #fecdd3;color:#9f1239;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- SEARCH --}}
    <div class="dw-card" style="margin-bottom:12px;padding:14px 16px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:10px;">
            <div style="width:3px;height:14px;background:#2563eb;border-radius:4px;"></div>
            <h3 style="font-size:12px;font-weight:700;color:#374151;">Pencarian</h3>
        </div>

        <form method="GET" action="{{ url()->current() }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <div class="sw" style="flex:1;min-width:260px;">
                <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari NIK, Nama, atau Email...">
            </div>

            <button type="submit" style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:#2563eb;color:#fff;font-size:12px;font-weight:700;border:none;border-radius:11px;cursor:pointer;box-shadow:0 2px 8px rgba(37,99,235,.25);white-space:nowrap;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>

            @if(request()->filled('q'))
                <a href="{{ url()->current() }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#f3f4f6;border-radius:10px;color:#6b7280;text-decoration:none;" title="Hapus">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>

        <div style="margin-top:12px;display:flex;justify-content:flex-end;">
            <form method="POST"
                  action="{{ route('admin.data-warga.bersihkan') }}"
                  onsubmit="return confirm('Yakin ingin menghapus semua data warga aktif? Data pada laporan arsip tidak akan terhapus.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-reset">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Bersihkan Data Warga
                </button>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="dw-card">
        <div style="padding:10px 16px;border-bottom:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:7px;">
                <div style="width:3px;height:16px;background:#2563eb;border-radius:4px;"></div>
                <span style="font-size:12.5px;font-weight:700;color:#374151;">
                    Daftar Warga
                    @if(isset($wargas) && method_exists($wargas,'total'))
                        <span style="color:#2563eb;"> {{ $wargas->total() }}</span><span style="color:#9ca3af;font-weight:400;"> data</span>
                    @endif
                </span>
            </div>
            @if(isset($wargas) && method_exists($wargas,'currentPage') && $wargas->lastPage() > 1)
                <span style="font-size:11px;color:#9ca3af;">Hal. {{ $wargas->currentPage() }} / {{ $wargas->lastPage() }}</span>
            @endif
        </div>

        <div style="overflow-x:auto;">
            <table>
                <colgroup>
                    <col style="width:13%"><col style="width:20%"><col style="width:12%">
                    <col style="width:13%"><col style="width:13%"><col style="width:11%"><col style="width:18%">
                </colgroup>
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Dusun / RT</th>
                        <th>Penghasilan</th>
                        <th>Probabilitas</th>
                        <th>Tracking</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wargas ?? [] as $warga)
                        @php
                            $prob    = optional($warga->prediksiKelayakan)->probability ?? null;
                            $probPct = $prob !== null ? ($prob <= 1 ? $prob * 100 : $prob) : null;
                            $sc      = $probPct !== null ? ($probPct >= 70 ? '#10b981' : ($probPct >= 40 ? '#f59e0b' : '#f43f5e')) : '#e5e7eb';

                            $positive=[]; $negative=[];
                            if(strtolower($warga->pekerjaan??'')==='tidak bekerja') $positive[]='Tidak bekerja';
                            elseif(in_array(strtolower($warga->pekerjaan??''),['buruh harian','buruh','petani','nelayan'])) $positive[]='Pekerjaan tergolong rentan';
                            else $negative[]='Memiliki pekerjaan relatif stabil';

                            if(($warga->penghasilan??0)<=1000000) $positive[]='Penghasilan rendah';
                            elseif(($warga->penghasilan??0)<=2000000) $positive[]='Penghasilan tergolong rendah';
                            else $negative[]='Penghasilan relatif tinggi';

                            if(($warga->jumlah_tanggungan??0)>=4) $positive[]='Tanggungan banyak';
                            elseif(($warga->jumlah_tanggungan??0)>=2) $positive[]='Tanggungan cukup banyak';
                            else $negative[]='Tanggungan sedikit';

                            $aset=strtolower($warga->aset_kepemilikan??'');
                            if(str_contains($aset,'mobil')) $negative[]='Memiliki aset mobil';
                            if(str_contains($aset,'motor')) $negative[]='Memiliki kendaraan bermotor';
                            if(str_contains($aset,'rumah')) $negative[]='Memiliki aset rumah';
                            if(in_array($aset,['tidak ada','-','tidak punya'])) $positive[]='Tidak memiliki aset berarti';

                            if(strtolower($warga->bantuan_lain??'')==='ya') $negative[]='Sudah menerima bantuan lain';
                            else $positive[]='Belum menerima bantuan lain';

                            if(($warga->usia??0)>=60) $positive[]='Usia lanjut';
                            elseif(($warga->usia??0)>=45) $positive[]='Usia cukup rentan';

                            $summary='Nilai kelayakan dihitung berdasarkan pekerjaan, penghasilan, tanggungan, aset, bantuan lain, dan usia.';
                            if(count($positive)&&count($negative)) $summary.=' Terdapat faktor pendukung dan pengurang.';
                            elseif(count($positive)) $summary.=' Faktor mayoritas mendukung kelayakan.';
                            else $summary.=' Faktor mayoritas menurunkan kelayakan.';

                            $dp=[
                                'nik'=>$warga->nik,
                                'no_kk'=>$warga->no_kk??'-',
                                'nama_lengkap'=>$warga->nama_lengkap,
                                'jenis_kelamin'=>$warga->jenis_kelamin??'-',
                                'tempat_lahir'=>$warga->tempat_lahir??'-',
                                'tanggal_lahir'=>$warga->tanggal_lahir?\Carbon\Carbon::parse($warga->tanggal_lahir)->translatedFormat('d F Y'):'-',
                                'usia'=>$warga->usia??'-',
                                'status_perkawinan'=>$warga->status_perkawinan??'-',
                                'alamat'=>$warga->alamat??'-',
                                'desa'=>$warga->desa??'-',
                                'aset_kepemilikan'=>$warga->aset_kepemilikan??'-',
                                'pekerjaan'=>$warga->pekerjaan??'-',
                                'penghasilan'=>$warga->penghasilan??0,
                                'jumlah_tanggungan'=>$warga->jumlah_tanggungan??'-',
                                'bantuan_lain'=>$warga->bantuan_lain??'-',
                                'status_verifikasi'=>$warga->status_verifikasi??'-',
                                'tracking_status'=>$warga->tracking_status??'-',
                                'dusun'=>$warga->rt->dusun->nama_dusun??'-',
                                'rt'=>$warga->rt->nomor_rt??'-',
                                'probability'=>optional($warga->prediksiKelayakan)->probability,
                                'recommendation'=>optional($warga->prediksiKelayakan)->recommendation,
                                'positive'=>$positive,
                                'negative'=>$negative,
                                'summary'=>$summary,
                                'input_oleh'=>optional($warga->user)->name??(optional($warga->user)->email??'-'),
                                'created_at'=>optional($warga->created_at)->translatedFormat('d F Y, H:i')??'-'
                            ];
                            $trk=$warga->tracking_status??'';
                        @endphp
                        <tr>
                            <td><span class="nik-pill">{{ $warga->nik }}</span></td>

                            <td>
                                <div style="display:flex;align-items:center;gap:9px;">
                                    <div class="av">{{ strtoupper(substr($warga->nama_lengkap,0,1)) }}</div>
                                    <div style="min-width:0;">
                                        <div style="font-size:12.5px;font-weight:600;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $warga->nama_lengkap }}</div>
                                        <div style="font-size:10.5px;color:#9ca3af;margin-top:1px;">{{ $warga->pekerjaan??'-' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div style="font-size:12px;font-weight:600;color:#374151;">{{ $warga->rt->dusun->nama_dusun??'-' }}</div>
                                <span class="rt-badge">RT {{ str_pad($warga->rt->nomor_rt??'-',3,'0',STR_PAD_LEFT) }}</span>
                            </td>

                            <td style="font-size:12.5px;font-weight:600;color:#374151;">
                                Rp {{ number_format($warga->penghasilan??0,0,',','.') }}
                            </td>

                            <td>
                                @if($probPct !== null)
                                    <div class="prob-wrap">
                                        <div class="prob-bg"><div class="prob-bar" style="width:{{ min($probPct,100) }}%;background:{{ $sc }};"></div></div>
                                        <span style="font-size:11px;font-weight:700;color:{{ $sc }};white-space:nowrap;">{{ number_format($probPct,1) }}%</span>
                                    </div>
                                @else
                                    <span style="font-size:12px;color:#d1d5db;">—</span>
                                @endif
                            </td>

                            <td>
                                @if($trk==='terkirim')
                                    <span class="badge trk-terkirim"><span class="dot"></span>Terkirim</span>
                                @elseif($trk==='sedang_validasi')
                                    <span class="badge trk-validasi"><span class="dot"></span>Validasi</span>
                                @elseif($trk==='selesai')
                                    <span class="badge trk-selesai"><span class="dot"></span>Selesai</span>
                                @else
                                    <span class="badge trk-default"><span class="dot"></span>—</span>
                                @endif
                            </td>

                            <td>
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;flex-wrap:wrap;">
                                    <button type="button" onclick="openDetailModal({{ $warga->id }})" class="btn-act btn-detail">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </button>

                                    @if($trk==='terkirim')
                                        <form method="POST" action="{{ route('admin.data-warga.mulai-validasi',$warga->id) }}">
                                            @csrf
                                            <button type="submit" class="btn-act btn-validasi">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m-1-10l-7 7-4 1 1-4 7-7a2.828 2.828 0 114 4z"/></svg>
                                                Validasi
                                            </button>
                                        </form>
                                    @endif

                                    @if($trk==='sedang_validasi')
                                        <span class="info-chip">Lanjutkan lewat filterisasi</span>
                                    @endif

                                    @if($trk==='selesai')
                                        @if(($warga->status_verifikasi ?? '') === 'disetujui')
                                            <span class="badge stat-yes"><span class="dot" style="background:#22c55e;"></span>Diterima</span>
                                        @elseif(($warga->status_verifikasi ?? '') === 'ditolak')
                                            <span class="badge stat-no"><span class="dot" style="background:#f43f5e;"></span>Tidak Diterima</span>
                                        @else
                                            <span class="badge stat-pending"><span class="dot" style="background:#f59e0b;"></span>Pending</span>
                                        @endif

                                        <form method="POST" action="{{ route('admin.data-warga.selesai-validasi',$warga->id) }}">
                                            @csrf
                                            <button type="submit" class="btn-act btn-kirim">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Kirim Hasil
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <script type="application/json" id="warga-{{ $warga->id }}">{!! json_encode($dp,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">
                            <div style="padding:48px 16px;text-align:center;">
                                <div style="width:44px;height:44px;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                    <svg width="20" height="20" fill="none" stroke="#d1d5db" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <p style="font-size:13px;font-weight:600;color:#6b7280;margin-bottom:4px;">Belum ada data warga</p>
                                @if(request()->filled('q'))
                                    <p style="font-size:11.5px;color:#9ca3af;">Tidak ada hasil untuk "<strong>{{ request('q') }}</strong>"</p>
                                    <a href="{{ url()->current() }}" style="display:inline-block;margin-top:8px;font-size:12px;color:#2563eb;font-weight:600;text-decoration:none;">← Hapus pencarian</a>
                                @endif
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($wargas) && method_exists($wargas,'links') && $wargas->hasPages())
            <div style="padding:10px 16px;border-top:1px solid #f1f5f9;background:#fafafa;">{{ $wargas->links() }}</div>
        @endif
    </div>

    {{-- MODAL --}}
    <div id="detailBackdrop" class="m-backdrop" style="display:none;" onclick="closeDetailModal()"></div>
    <div id="detailModal" class="m-wrap" style="display:none;">
        <div class="m-box">
            <div class="mhero">
                <div class="mhero-b1"></div><div class="mhero-b2"></div>
                <div class="mhero-row">
                    <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                        <div class="m-av"><span id="modal-initial">?</span></div>
                        <div style="min-width:0;"><div class="m-title" id="modal-title">—</div><div class="m-sub" id="modal-subtitle">—</div></div>
                    </div>
                    <button class="m-close" onclick="closeDetailModal()"><svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="m-st-pill"><span style="width:6px;height:6px;border-radius:50%;flex-shrink:0;" id="modal-status-dot"></span><span id="modal-status-text">—</span></div>
            </div>

            <div class="m-body">
                <div class="msec">
                    <div class="msec-h"><div class="bar" style="background:#2563eb;"></div><h4>Data Identitas</h4></div>
                    <div class="msec-b">
                        <div class="mg2">
                            <div><p class="mlbl">NIK</p><span id="d_nik" class="mval mval-mono">-</span></div>
                            <div><p class="mlbl">No. KK</p><span id="d_no_kk" class="mval mval-mono">-</span></div>
                            <div><p class="mlbl">Nama Lengkap</p><p id="d_nama" class="mval">-</p></div>
                            <div><p class="mlbl">Jenis Kelamin</p><p id="d_jk" class="mval">-</p></div>
                            <div><p class="mlbl">Tempat, Tgl Lahir</p><p id="d_lahir" class="mval">-</p></div>
                            <div><p class="mlbl">Usia</p><p id="d_usia" class="mval">-</p></div>
                            <div style="grid-column:span 2;"><p class="mlbl">Status Perkawinan</p><p id="d_kawin" class="mval">-</p></div>
                        </div>
                    </div>
                </div>

                <div class="msec">
                    <div class="msec-h"><div class="bar" style="background:#10b981;"></div><h4>Tempat Tinggal</h4></div>
                    <div class="msec-b">
                        <div class="mg2">
                            <div style="grid-column:span 2;"><p class="mlbl">Alamat</p><p id="d_alamat" class="mval">-</p></div>
                            <div><p class="mlbl">Dusun</p><p id="d_dusun" class="mval">-</p></div>
                            <div><p class="mlbl">RT</p><p id="d_rt" class="mval">-</p></div>
                            <div style="grid-column:span 2;"><p class="mlbl">Aset Kepemilikan</p><p id="d_aset" class="mval">-</p></div>
                        </div>
                    </div>
                </div>

                <div class="msec">
                    <div class="msec-h"><div class="bar" style="background:#f59e0b;"></div><h4>Data Ekonomi</h4></div>
                    <div class="msec-b">
                        <div class="mg2">
                            <div><p class="mlbl">Pekerjaan</p><p id="d_pekerjaan" class="mval">-</p></div>
                            <div><p class="mlbl">Penghasilan</p><p id="d_penghasilan" class="mval">-</p></div>
                            <div><p class="mlbl">Tanggungan</p><p id="d_tanggungan" class="mval">-</p></div>
                            <div><p class="mlbl">Bantuan Lain</p><p id="d_bantuan" class="mval">-</p></div>
                        </div>
                    </div>
                </div>

                <div class="msec">
                    <div class="msec-h"><div class="bar" style="background:#2563eb;"></div><h4>Prediksi Kelayakan</h4></div>
                    <div class="msec-b">
                        <div id="d_pred_empty" style="display:none;padding:10px 0;text-align:center;font-size:11.5px;color:#9ca3af;">Belum ada data prediksi.</div>
                        <div id="d_pred_wrap">
                            <div style="display:flex;align-items:center;gap:14px;margin-bottom:10px;">
                                <div style="font-size:28px;font-weight:900;color:#111827;line-height:1;" id="d_prob">—</div>
                                <div style="flex:1;height:6px;background:#f1f5f9;border-radius:99px;overflow:hidden;"><div id="d_prob_bar" style="height:100%;border-radius:99px;width:0%;transition:width .5s;background:#10b981;"></div></div>
                            </div>
                            <div><p class="mlbl" style="margin-bottom:5px;">Rekomendasi</p><span id="d_reco_badge" style="display:inline-flex;padding:4px 12px;border-radius:9px;font-size:12px;font-weight:700;background:#f3f4f6;color:#374151;">—</span></div>
                        </div>
                    </div>
                </div>

                <div class="msec">
                    <div class="msec-h"><div class="bar" style="background:#10b981;"></div><h4>Penjelasan Prediksi</h4></div>
                    <div class="msec-b" style="display:flex;flex-direction:column;gap:10px;">
                        <div><p class="mlbl" style="margin-bottom:4px;">Faktor Pendukung</p><ul id="d_positive" style="list-style:none;padding:0;margin:0;font-size:12px;color:#166534;display:flex;flex-direction:column;gap:2px;"><li>-</li></ul></div>
                        <div><p class="mlbl" style="margin-bottom:4px;">Faktor Pengurang</p><ul id="d_negative" style="list-style:none;padding:0;margin:0;font-size:12px;color:#9f1239;display:flex;flex-direction:column;gap:2px;"><li>-</li></ul></div>
                        <div><p class="mlbl" style="margin-bottom:4px;">Ringkasan</p><p id="d_summary" style="font-size:12px;color:#374151;line-height:1.7;">-</p></div>
                    </div>
                </div>

                <div style="display:flex;justify-content:space-between;font-size:11px;color:#9ca3af;padding:0 2px;">
                    <span>Input oleh: <strong id="d_input_oleh" style="color:#374151;">-</strong></span>
                    <span>Didaftarkan: <strong id="d_created" style="color:#374151;">-</strong></span>
                </div>
            </div>

            <div class="m-footer"><button onclick="closeDetailModal()" class="btn-close-m">Tutup</button></div>
        </div>
    </div>

    <script>
        function set(id,val){const e=document.getElementById(id);if(e)e.textContent=val;}
        function setList(id,items,empty='-'){
            const e=document.getElementById(id);if(!e)return;
            if(!items||!items.length){e.innerHTML=`<li>${empty}</li>`;return;}
            e.innerHTML=items.map(i=>`<li style="display:flex;gap:5px;"><span>•</span><span>${i}</span></li>`).join('');
        }
        function openDetailModal(id){
            const el=document.getElementById('warga-'+id);if(!el)return;
            const d=JSON.parse(el.textContent);

            set('modal-initial',(d.nama_lengkap||'?').charAt(0).toUpperCase());
            set('modal-title',d.nama_lengkap||'-');
            set('modal-subtitle',(d.pekerjaan?d.pekerjaan+' · ':'')+(d.dusun||'-'));

            const dot=document.getElementById('modal-status-dot');
            const txt=document.getElementById('modal-status-text');
            const st=d.status_verifikasi||'';
            const dotStyles={pending:'background:#fcd34d;',disetujui:'background:#6ee7b7;',default:'background:#fca5a5;'};
            if(dot) dot.style.cssText='width:6px;height:6px;border-radius:50%;flex-shrink:0;'+(dotStyles[st]||dotStyles.default);
            if(txt) txt.textContent=st==='pending'?'Menunggu Hasil Filterisasi':st==='disetujui'?'Diterima':'Tidak Diterima';

            set('d_nik',d.nik||'-');set('d_no_kk',d.no_kk||'-');set('d_nama',d.nama_lengkap||'-');
            set('d_jk',d.jenis_kelamin||'-');set('d_lahir',(d.tempat_lahir||'-')+', '+(d.tanggal_lahir||'-'));
            set('d_usia',d.usia?d.usia+' tahun':'-');set('d_kawin',d.status_perkawinan||'-');
            set('d_alamat', d.alamat || '-');
            set('d_dusun', d.dusun || '-');
            set('d_rt','RT '+String(d.rt||'-').padStart(3,'0'));set('d_aset',d.aset_kepemilikan||'-');
            set('d_pekerjaan',d.pekerjaan||'-');
            set('d_penghasilan','Rp '+Number(d.penghasilan||0).toLocaleString('id-ID'));
            set('d_tanggungan',d.jumlah_tanggungan!=null?d.jumlah_tanggungan+' orang':'-');
            set('d_bantuan',d.bantuan_lain||'-');

            const prob=d.probability;
            const emEl=document.getElementById('d_pred_empty');const wrEl=document.getElementById('d_pred_wrap');
            if(prob===null||prob===undefined){
                if(emEl)emEl.style.display='block';
                if(wrEl)wrEl.style.display='none';
            } else {
                if(emEl)emEl.style.display='none';
                if(wrEl)wrEl.style.display='block';
                const pct=prob<=1?prob*100:Number(prob);
                set('d_prob',pct.toFixed(1)+'%');
                const bar=document.getElementById('d_prob_bar');
                if(bar){bar.style.width=Math.min(pct,100)+'%';bar.style.background=pct>=70?'#10b981':pct>=40?'#f59e0b':'#f43f5e';}
                const rb=document.getElementById('d_reco_badge');
                if(rb){
                    const r=d.recommendation||'—';
                    rb.textContent=r;
                    const l=r.toLowerCase().includes('layak');
                    rb.style.cssText=`display:inline-flex;padding:4px 12px;border-radius:9px;font-size:12px;font-weight:700;${l?'background:#f0fdf4;color:#166534;':'background:#fff1f2;color:#9f1239;'}`;
                }
            }

            setList('d_positive',d.positive||[],'Tidak ada faktor pendukung dominan.');
            setList('d_negative',d.negative||[],'Tidak ada faktor pengurang dominan.');
            set('d_summary',d.summary||'-');
            set('d_input_oleh',d.input_oleh||'-');
            set('d_created',d.created_at||'-');

            document.getElementById('detailBackdrop').style.display='block';
            document.getElementById('detailModal').style.display='flex';
            document.body.style.overflow='hidden';
        }
        function closeDetailModal(){
            document.getElementById('detailBackdrop').style.display='none';
            document.getElementById('detailModal').style.display='none';
            document.body.style.overflow='';
        }
        document.addEventListener('keydown',e=>{if(e.key==='Escape')closeDetailModal();});
    </script>

</x-app-layout>