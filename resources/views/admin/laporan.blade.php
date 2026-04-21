<x-app-layout>

    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h2 style="font-size:16px;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Laporan Penerima BLT-DD</h2>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;">Kelurahan Ngerong — warga yang telah ditetapkan sebagai penerima final</p>
            </div>
        </div>
    </x-slot>

    <style>
        .lp-card{background:#fff;border-radius:16px;border:1.5px solid #f1f5f9;box-shadow:0 2px 8px rgba(0,0,0,.05);overflow:hidden;margin-bottom:14px;}
        .sec-hd{padding:11px 16px;border-bottom:1.5px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;}
        .sec-hd-l{display:flex;align-items:center;gap:8px;}
        .sec-bar{width:3px;height:15px;background:linear-gradient(180deg,#3b82f6,#2563eb);border-radius:4px;flex-shrink:0;}
        .sec-title{font-size:13px;font-weight:800;color:#1e293b;}
        .sec-count{font-size:11px;color:#94a3b8;background:#f1f5f9;padding:2px 8px;border-radius:20px;font-weight:600;white-space:nowrap;}

        /* stat */
        .sum-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:14px;}
        @media(max-width:860px){.sum-grid{grid-template-columns:1fr 1fr;}}
        @media(max-width:480px){.sum-grid{grid-template-columns:1fr 1fr;gap:8px;}}
        .sum-card{background:#fff;border-radius:16px;border:1.5px solid #f1f5f9;box-shadow:0 1px 6px rgba(0,0,0,.04);padding:14px 16px;}
        @media(max-width:480px){.sum-card{padding:12px 13px;border-radius:13px;}}
        .sum-icon{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:8px;}
        .sum-icon svg{width:17px;height:17px;}
        .sum-val{font-size:22px;font-weight:900;color:#0f172a;line-height:1;letter-spacing:-.03em;}
        .sum-val-sm{font-size:14px;font-weight:900;color:#0f172a;line-height:1.3;letter-spacing:-.01em;}
        @media(max-width:480px){.sum-val{font-size:18px;}.sum-val-sm{font-size:12px;}}
        .sum-lbl{font-size:10px;color:#94a3b8;font-weight:700;margin-top:3px;text-transform:uppercase;letter-spacing:.04em;}
        @media(max-width:480px){.sum-lbl{font-size:9px;}}

        /* export buttons */
        .exp-row{display:flex;flex-wrap:wrap;gap:8px;padding:14px 16px;align-items:center;}
        .exp-btn{display:inline-flex;align-items:center;gap:7px;padding:10px 18px;border-radius:12px;font-size:12.5px;font-weight:700;text-decoration:none;border:none;cursor:pointer;transition:filter .15s,transform .1s;line-height:1;white-space:nowrap;font-family:inherit;}
        .exp-btn svg{width:14px;height:14px;flex-shrink:0;}
        .exp-btn:hover{filter:brightness(.93);transform:translateY(-1px);}
        @media(max-width:480px){
            .exp-btn{padding:10px 14px;font-size:12px;border-radius:10px;flex:1;justify-content:center;}
        }
        .btn-pdf{background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;box-shadow:0 3px 12px rgba(220,38,38,.3);}
        .btn-excel{background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;box-shadow:0 3px 12px rgba(22,163,74,.3);}
        .btn-send{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;box-shadow:0 3px 12px rgba(37,99,235,.3);}
        .btn-gray{display:inline-flex;align-items:center;padding:8px 14px;border:none;border-radius:10px;background:#e5e7eb;color:#374151;font-size:12px;font-weight:700;cursor:pointer;font-family:inherit;}

        /* upload form */
        .pub-wrap{padding:14px 16px;}
        .pub-form{display:grid;grid-template-columns:1fr 1fr auto;gap:10px;align-items:end;}
        @media(max-width:768px){.pub-form{grid-template-columns:1fr 1fr;}}
        @media(max-width:500px){.pub-form{grid-template-columns:1fr;}}
        @media(max-width:500px){.pub-form .exp-btn{justify-content:center;}}
        .pub-input{padding:10px 13px;border:1.5px solid #e2e8f0;border-radius:11px;font-size:16px;/* prevent iOS zoom */outline:none;background:#fff;color:#0f172a;width:100%;font-family:inherit;}
        .pub-input:focus{border-color:#93c5fd;box-shadow:0 0 0 3px rgba(37,99,235,.08);}
        .pub-list{margin-top:14px;display:flex;flex-direction:column;gap:10px;}
        .pub-item{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:12px 14px;border:1.5px solid #f1f5f9;border-radius:13px;background:#fafbfc;flex-wrap:wrap;}
        .pub-title{font-size:13px;font-weight:700;color:#0f172a;}
        .pub-meta{font-size:11px;color:#94a3b8;margin-top:2px;}
        .pub-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap;}
        @media(max-width:480px){.pub-actions{width:100%;}}
        @media(max-width:480px){.pub-actions .exp-btn,.pub-actions .btn-gray{flex:1;justify-content:center;}}

        /* desktop table */
        .lp-tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
        table.lp-tbl{width:100%;border-collapse:collapse;min-width:1000px;}
        table.lp-tbl thead tr{background:#f8fafc;border-bottom:1.5px solid #f1f5f9;}
        table.lp-tbl thead th{padding:9px 11px;text-align:left;font-size:9.5px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;}
        table.lp-tbl tbody tr{border-bottom:1px solid #f8fafc;transition:background .1s;}
        table.lp-tbl tbody tr:hover{background:#f0f7ff;}
        table.lp-tbl tbody tr:last-child{border-bottom:none;}
        table.lp-tbl tbody td{padding:9px 11px;vertical-align:middle;font-size:12px;color:#374151;}

        /* mobile card */
        .mob-list{display:none;flex-direction:column;gap:10px;padding:14px;}
        @media(max-width:640px){
            .lp-tbl-wrap{display:none;}
            .mob-list{display:flex;}
        }
        .mob-card{background:#fafbfc;border:1.5px solid #f1f5f9;border-radius:13px;padding:13px;display:flex;flex-direction:column;gap:10px;}
        .mob-top{display:flex;align-items:center;gap:10px;}
        .mob-nomor{width:22px;height:22px;border-radius:6px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;color:#94a3b8;flex-shrink:0;}
        .mob-name{font-size:13px;font-weight:800;color:#0f172a;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
        .mob-rows{display:grid;grid-template-columns:1fr 1fr;gap:6px 10px;}
        .mob-kv{display:flex;flex-direction:column;gap:2px;}
        .mob-k{font-size:9px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;}
        .mob-v{font-size:11.5px;font-weight:600;color:#374151;}
        .mob-nik{font-family:'Courier New',monospace;font-size:10px;color:#64748b;background:#f1f5f9;padding:2px 5px;border-radius:4px;display:inline-block;}
        .mob-bottom{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;padding-top:8px;border-top:1px solid #f1f5f9;}

        /* shared */
        .av{width:30px;height:30px;border-radius:9px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0;}
        .nik-pill{font-family:'Courier New',monospace;font-size:10px;color:#64748b;background:#f1f5f9;padding:2px 6px;border-radius:5px;}
        .rt-badge{display:inline-flex;padding:2px 7px;border-radius:6px;background:#eff6ff;color:#1d4ed8;font-size:10px;font-weight:800;border:1px solid #bfdbfe;}
        .prob-cell{display:flex;align-items:center;gap:6px;}
        .prob-bg{width:38px;height:4px;background:#f1f5f9;border-radius:99px;overflow:hidden;flex-shrink:0;}
        .prob-fill{height:100%;border-radius:99px;}
        .prob-txt{font-size:11px;font-weight:800;white-space:nowrap;}
        .st-pill{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:10.5px;font-weight:700;}
        .st-dot{width:5px;height:5px;border-radius:50%;flex-shrink:0;}
        .bantuan-ya{display:inline-flex;padding:2px 8px;border-radius:20px;background:#fffbeb;color:#b45309;font-size:10px;font-weight:700;border:1px solid #fde68a;}
        .bantuan-tdk{display:inline-flex;padding:2px 8px;border-radius:20px;background:#f8fafc;color:#94a3b8;font-size:10px;font-weight:600;}
        .flash-ok{display:flex;align-items:center;gap:8px;background:#f0fdf4;border:1.5px solid #bbf7d0;color:#166534;padding:11px 16px;border-radius:13px;font-size:12.5px;font-weight:600;margin-bottom:14px;}
        .flash-ok svg{width:15px;height:15px;flex-shrink:0;}
        .empty-state{padding:52px 16px;text-align:center;}
        .empty-icon{width:46px;height:46px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;}
        .tbl-foot{padding:9px 16px;border-top:1.5px solid #f1f5f9;background:#fafbfc;text-align:center;}
        .credit{font-size:11px;color:#cbd5e1;font-weight:500;}
    </style>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="flash-ok">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @php
        $totalPenerima = $laporans->count();
        $totalBantuan  = $laporans->sum('jumlah_bantuan');
        $avgProb = $laporans->avg(function($i){
            $p = $i->probability ?? 0;
            return $p <= 1 ? $p * 100 : $p;
        });
        $periodes = $laporans->pluck('periode_bantuan')->filter()->unique()->count();
    @endphp

    {{-- STAT CARDS --}}
    <div class="sum-grid">
        <div class="sum-card">
            <div class="sum-icon" style="background:#eff6ff;">
                <svg fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="sum-val">{{ $totalPenerima }}</div>
            <div class="sum-lbl">Total Penerima</div>
        </div>
        <div class="sum-card">
            <div class="sum-icon" style="background:#f0fdf4;">
                <svg fill="none" stroke="#16a34a" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="sum-val-sm">Rp {{ number_format($totalBantuan,0,',','.') }}</div>
            <div class="sum-lbl">Total Dana</div>
        </div>
        <div class="sum-card">
            <div class="sum-icon" style="background:#fffbeb;">
                <svg fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div class="sum-val">{{ number_format($avgProb,1) }}<span style="font-size:13px;font-weight:700;">%</span></div>
            <div class="sum-lbl">Rata-rata Prob</div>
        </div>
        <div class="sum-card">
            <div class="sum-icon" style="background:#fff1f2;">
                <svg fill="none" stroke="#e11d48" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="sum-val">{{ $periodes }}</div>
            <div class="sum-lbl">Periode Aktif</div>
        </div>
    </div>

    {{-- EKSPOR --}}
    <div class="lp-card">
        <div class="sec-hd">
            <div class="sec-hd-l">
                <div class="sec-bar"></div>
                <span class="sec-title">Ekspor & Distribusi Data</span>
            </div>
        </div>
        <div class="exp-row">
            <a href="{{ route('admin.laporan.pdf') }}" class="exp-btn btn-pdf">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Unduh PDF
            </a>
            <a href="{{ route('admin.laporan.excel') }}" class="exp-btn btn-excel">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Unduh Excel
            </a>
            <form action="{{ route('admin.laporan.kirim-ke-rt') }}" method="POST" style="display:contents;">
                @csrf
                <button type="submit" class="exp-btn btn-send"
                        onclick="return confirm('Kirim semua data penerima final ke RT dusun?')">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim ke RT
                </button>
            </form>
            <span style="font-size:11px;color:#94a3b8;width:100%;padding-top:2px;">{{ $totalPenerima }} penerima terdaftar</span>
        </div>
    </div>

    {{-- UPLOAD PDF PUBLIK --}}
    <div class="lp-card">
        <div class="sec-hd">
            <div class="sec-hd-l">
                <div class="sec-bar"></div>
                <span class="sec-title">Upload PDF Laporan Publik</span>
            </div>
        </div>
        <div class="pub-wrap">
            <form action="{{ route('admin.laporan.upload-publik-pdf') }}" method="POST"
                  enctype="multipart/form-data" class="pub-form">
                @csrf
                <input type="text" name="judul" placeholder="Judul laporan publik" required class="pub-input">
                <input type="file" name="file_pdf" accept="application/pdf" required class="pub-input" style="font-size:13px;">
                <button type="submit" class="exp-btn btn-send">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Upload
                </button>
            </form>

            @if(isset($laporanPubliks) && $laporanPubliks->count() > 0)
                <div class="pub-list">
                    @foreach($laporanPubliks as $pdf)
                        <div class="pub-item">
                            <div style="flex:1;min-width:0;">
                                <div class="pub-title">{{ $pdf->judul }}</div>
                                <div class="pub-meta">Diupload: {{ $pdf->created_at->format('d-m-Y H:i') }}</div>
                            </div>
                            <div class="pub-actions">
                                <a href="{{ asset('storage/'.$pdf->file_path) }}" target="_blank" class="exp-btn btn-pdf" style="padding:8px 14px;">
                                    Lihat PDF
                                </a>
                                <form action="{{ route('admin.laporan.hapus-publik-pdf',$pdf->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus PDF publik ini?')" style="display:contents;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-gray">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- DAFTAR PENERIMA FINAL --}}
    <div class="lp-card">
        <div class="sec-hd">
            <div class="sec-hd-l">
                <div class="sec-bar"></div>
                <span class="sec-title">Daftar Penerima Final</span>
            </div>
            <span class="sec-count">{{ $totalPenerima }} data</span>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="lp-tbl-wrap">
            <table class="lp-tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>No KK</th>
                        <th>RT</th>
                        <th>Dusun</th>
                        <th>Pekerjaan</th>
                        <th>Penghasilan</th>
                        <th>Tanggungan</th>
                        <th>Aset</th>
                        <th>Bantuan</th>
                        <th>Usia</th>
                        <th>Prob</th>
                        <th>Status</th>
                        <th>Penetapan</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($laporans as $index => $item)
                    @php
                        $nama = $item->nama_lengkap ?? '-';
                        $init = strtoupper(substr($nama,0,1));
                        $prob = $item->probability ?? 0;
                        if($prob<=1) $prob=$prob*100;
                        $sc   = $prob>=70?'#10b981':($prob>=40?'#f59e0b':'#f43f5e');
                        $scBg = $prob>=70?'#f0fdf4':($prob>=40?'#fffbeb':'#fff1f2');
                        $scBd = $prob>=70?'#bbf7d0':($prob>=40?'#fde68a':'#fecdd3');
                    @endphp
                    <tr>
                        <td style="text-align:center;font-size:10.5px;color:#94a3b8;font-weight:700;">{{ $index+1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div class="av">{{ $init }}</div>
                                <span style="font-size:12px;font-weight:700;color:#0f172a;white-space:nowrap;">{{ $nama }}</span>
                            </div>
                        </td>
                        <td><span class="nik-pill">{{ $item->nik??'-' }}</span></td>
                        <td><span class="nik-pill">{{ $item->no_kk??'-' }}</span></td>
                        <td><span class="rt-badge">RT {{ str_pad($item->nomor_rt??'0',3,'0',STR_PAD_LEFT) }}</span></td>
                        <td style="font-size:11.5px;white-space:nowrap;">{{ $item->nama_dusun??'-' }}</td>
                        <td style="font-size:11.5px;">{{ $item->pekerjaan??'-' }}</td>
                        <td style="font-size:12px;font-weight:600;white-space:nowrap;">Rp {{ number_format($item->penghasilan??0,0,',','.') }}</td>
                        <td style="text-align:center;font-size:12px;">{{ $item->jumlah_tanggungan??0 }}</td>
                        <td style="font-size:11.5px;">{{ $item->aset_kepemilikan??'-' }}</td>
                        <td>
                            @if(strtolower($item->bantuan_lain??'')=='ya')
                                <span class="bantuan-ya">Ya</span>
                            @else
                                <span class="bantuan-tdk">Tidak</span>
                            @endif
                        </td>
                        <td style="text-align:center;font-size:12px;">{{ $item->usia??0 }}</td>
                        <td>
                            <div class="prob-cell">
                                <div class="prob-bg"><div class="prob-fill" style="width:{{ min($prob,100) }}%;background:{{ $sc }};"></div></div>
                                <span class="prob-txt" style="color:{{ $sc }};">{{ number_format($prob,1) }}%</span>
                            </div>
                        </td>
                        <td>
                            <span class="st-pill" style="background:{{ $scBg }};color:{{ $sc }};border:1px solid {{ $scBd }};">
                                <span class="st-dot" style="background:{{ $sc }};"></span>
                                {{ ucfirst($item->status_verifikasi??'-') }}
                            </span>
                        </td>
                        <td style="font-size:11px;white-space:nowrap;">{{ $item->tanggal_penetapan?->format('d-m-Y')??'-' }}</td>
                        <td style="font-size:11.5px;white-space:nowrap;">{{ $item->periode_bantuan??'-' }}</td>
                        <td style="font-size:12px;font-weight:700;color:#166534;white-space:nowrap;">Rp {{ number_format($item->jumlah_bantuan??300000,0,',','.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="17">
                        <div class="empty-state">
                            <div class="empty-icon"><svg fill="none" stroke="#d1d5db" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                            <p style="font-size:13px;font-weight:600;color:#94a3b8;">Belum ada data penerima final</p>
                        </div>
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARD LIST --}}
        <div class="mob-list">
        @forelse($laporans as $index => $item)
            @php
                $nama = $item->nama_lengkap ?? '-';
                $init = strtoupper(substr($nama,0,1));
                $prob = $item->probability ?? 0;
                if($prob<=1) $prob=$prob*100;
                $sc   = $prob>=70?'#10b981':($prob>=40?'#f59e0b':'#f43f5e');
                $scBg = $prob>=70?'#f0fdf4':($prob>=40?'#fffbeb':'#fff1f2');
                $scBd = $prob>=70?'#bbf7d0':($prob>=40?'#fde68a':'#fecdd3');
            @endphp
            <div class="mob-card">
                {{-- Header --}}
                <div class="mob-top">
                    <div class="mob-nomor">{{ $index+1 }}</div>
                    <div class="av">{{ $init }}</div>
                    <div class="mob-name">{{ $nama }}</div>
                </div>

                {{-- NIK & KK --}}
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <div class="mob-kv">
                        <span class="mob-k">NIK</span>
                        <span class="mob-nik">{{ $item->nik??'-' }}</span>
                    </div>
                    <div class="mob-kv">
                        <span class="mob-k">No KK</span>
                        <span class="mob-nik">{{ $item->no_kk??'-' }}</span>
                    </div>
                </div>

                {{-- Grid info --}}
                <div class="mob-rows">
                    <div class="mob-kv">
                        <span class="mob-k">RT</span>
                        <span class="rt-badge" style="width:fit-content;">RT {{ str_pad($item->nomor_rt??'0',3,'0',STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="mob-kv">
                        <span class="mob-k">Dusun</span>
                        <span class="mob-v">{{ $item->nama_dusun??'-' }}</span>
                    </div>
                    <div class="mob-kv">
                        <span class="mob-k">Pekerjaan</span>
                        <span class="mob-v">{{ $item->pekerjaan??'-' }}</span>
                    </div>
                    <div class="mob-kv">
                        <span class="mob-k">Usia</span>
                        <span class="mob-v">{{ $item->usia??0 }} thn</span>
                    </div>
                    <div class="mob-kv">
                        <span class="mob-k">Penghasilan</span>
                        <span class="mob-v">Rp {{ number_format($item->penghasilan??0,0,',','.') }}</span>
                    </div>
                    <div class="mob-kv">
                        <span class="mob-k">Tanggungan</span>
                        <span class="mob-v">{{ $item->jumlah_tanggungan??0 }} orang</span>
                    </div>
                    <div class="mob-kv">
                        <span class="mob-k">Aset</span>
                        <span class="mob-v">{{ $item->aset_kepemilikan??'-' }}</span>
                    </div>
                    <div class="mob-kv">
                        <span class="mob-k">Bantuan Lain</span>
                        @if(strtolower($item->bantuan_lain??'')=='ya')
                            <span class="bantuan-ya" style="width:fit-content;">Ya</span>
                        @else
                            <span class="bantuan-tdk" style="width:fit-content;">Tidak</span>
                        @endif
                    </div>
                </div>

                {{-- Bottom: prob + status + jumlah bantuan --}}
                <div class="mob-bottom">
                    <div class="prob-cell">
                        <div class="prob-bg"><div class="prob-fill" style="width:{{ min($prob,100) }}%;background:{{ $sc }};"></div></div>
                        <span class="prob-txt" style="color:{{ $sc }};">{{ number_format($prob,1) }}%</span>
                    </div>
                    <span class="st-pill" style="background:{{ $scBg }};color:{{ $sc }};border:1px solid {{ $scBd }};">
                        <span class="st-dot" style="background:{{ $sc }};"></span>{{ ucfirst($item->status_verifikasi??'-') }}
                    </span>
                    <span style="font-size:12px;font-weight:800;color:#166534;background:#f0fdf4;border:1px solid #bbf7d0;padding:3px 9px;border-radius:8px;white-space:nowrap;">
                        Rp {{ number_format($item->jumlah_bantuan??300000,0,',','.') }}
                    </span>
                </div>

                {{-- Periode & penetapan --}}
                <div style="font-size:10.5px;color:#94a3b8;">
                    Periode: <strong style="color:#475569;">{{ $item->periode_bantuan??'-' }}</strong>
                    &bull; Penetapan: <strong style="color:#475569;">{{ $item->tanggal_penetapan?->format('d-m-Y')??'-' }}</strong>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon"><svg fill="none" stroke="#d1d5db" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                <p style="font-size:13px;font-weight:600;color:#94a3b8;">Belum ada data penerima final</p>
            </div>
        @endforelse
        </div>

        <div class="tbl-foot"><span class="credit">SiBantuDes · Kelurahan Ngerong</span></div>
    </div>

</x-app-layout>