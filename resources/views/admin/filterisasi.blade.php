<x-app-layout>

    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h2 style="font-size:16px;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Filterisasi</h2>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;">Penetapan penerima BLT-DD berdasarkan probabilitas tertinggi per dusun</p>
            </div>
        </div>
    </x-slot>

    <style>
        .fi-card{background:#fff;border-radius:16px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);overflow:hidden;}
        .fl-box{display:flex;align-items:center;gap:9px;border-radius:12px;padding:10px 14px;font-size:12px;font-weight:500;margin-bottom:10px;}
        .fl-box svg{width:14px;height:14px;flex-shrink:0;}
        .fi-label{display:block;font-size:10.5px;font-weight:600;color:#64748b;margin-bottom:5px;}
        .fi-select,.fi-input{width:100%;padding:9px 12px;font-size:13px;color:#111827;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:11px;outline:none;font-family:inherit;transition:border-color .15s;}
        .fi-select:focus,.fi-input:focus{border-color:#3b82f6;background:#fff;}
        .btn-fi{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;font-size:12.5px;font-weight:700;border:none;border-radius:11px;cursor:pointer;white-space:nowrap;font-family:inherit;transition:filter .1s;}
        .btn-fi:hover{filter:brightness(.93);}
        .btn-tampil{background:#2563eb;color:#fff;box-shadow:0 2px 8px rgba(37,99,235,.2);}
        .btn-reset-fi{background:#f1f5f9;color:#475569;}
        .btn-tetapkan{background:#059669;color:#fff;box-shadow:0 2px 8px rgba(5,150,105,.2);}
        .tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
        table{width:100%;border-collapse:collapse;min-width:580px;}
        thead tr{background:#f8fafc;border-bottom:1.5px solid #f1f5f9;}
        thead th{padding:9px 12px;text-align:left;font-size:9.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;}
        tbody tr{border-bottom:1px solid #f8fafc;transition:background .1s;}
        tbody tr:hover{background:#f0f7ff;}
        tbody tr:last-child{border-bottom:none;}
        tbody td{padding:10px 12px;vertical-align:middle;font-size:12.5px;}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:10.5px;font-weight:700;white-space:nowrap;}
        .dot{width:5px;height:5px;border-radius:50%;flex-shrink:0;}
        .prob-wrap{display:flex;align-items:center;gap:6px;}
        .prob-bg{flex:1;height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;min-width:50px;}
        .prob-bar{height:100%;border-radius:99px;}
        .rank-badge{width:26px;height:26px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;}
        .note-box{background:#fffbeb;border:1.5px solid #fde68a;border-radius:14px;padding:14px 16px;font-size:12px;color:#92400e;line-height:1.8;}
        .note-box b{font-weight:800;}
        .fi-grid{display:grid;grid-template-columns:1fr 1fr auto;gap:10px;align-items:end;}
        .fi-actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center;}
        .mob-fi{display:none;flex-direction:column;gap:8px;padding:12px 14px;}
        .mob-fi-row{background:#f8fafc;border:1.5px solid #f1f5f9;border-radius:12px;padding:11px 13px;}
        @media(max-width:768px){
            .fi-grid{grid-template-columns:1fr 1fr;gap:8px;}
            .fi-grid > *:last-child{grid-column:span 2;}
        }
        @media(max-width:560px){
            .fi-grid{grid-template-columns:1fr;}
            .fi-grid > *:last-child{grid-column:span 1;}
            .tbl-wrap table{display:none;}
            .mob-fi{display:flex;}
        }

        @keyframes fi-spin { to { transform: rotate(360deg); } }
        .fi-spinner {
            display:none;
            width:13px;height:13px;
            border:2px solid rgba(255,255,255,.35);
            border-top-color:#fff;
            border-radius:50%;
            animation:fi-spin .6s linear infinite;
            flex-shrink:0;
        }
        .fi-spinner-dark {
            border-color:rgba(71,85,105,.3);
            border-top-color:#475569;
        }

        /* Overlay loading saat proses tetapkan */
        #fi-overlay {
            display:none;
            position:fixed;inset:0;z-index:9999;
            background:rgba(15,23,42,.45);
            backdrop-filter:blur(2px);
            align-items:center;justify-content:center;
        }
        #fi-overlay.show { display:flex; }
        .fi-overlay-box {
            background:#fff;border-radius:20px;
            padding:28px 32px;
            display:flex;flex-direction:column;align-items:center;gap:16px;
            box-shadow:0 20px 60px rgba(0,0,0,.2);
            min-width:240px;text-align:center;
        }
        .fi-overlay-ring {
            width:48px;height:48px;
            border:4px solid #e5e7eb;
            border-top-color:#059669;
            border-radius:50%;
            animation:fi-spin .7s linear infinite;
        }
        .fi-overlay-ring.blue { border-top-color:#2563eb; }
        .fi-overlay-title { font-size:14px;font-weight:700;color:#0f172a; }
        .fi-overlay-sub   { font-size:11.5px;color:#94a3b8;margin-top:-8px; }

        /* Dana Cair Box */
        .dana-box{background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1.5px solid #bfdbfe;border-radius:14px;padding:14px 16px;margin-bottom:12px;}
        .dana-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;align-items:end;}
        .dana-stat-row{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-top:12px;}
        .dana-stat{background:#fff;border-radius:10px;padding:10px 13px;border:1.5px solid #e0eaff;}
        .dana-stat.green{border-color:#a7f3d0;background:#f0fdf4;}
        .dana-stat.red{border-color:#fecdd3;background:#fff1f2;}
        .dana-stat.amber{border-color:#fde68a;background:#fffbeb;}
        .dana-stat-label{font-size:9.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px;}
        .dana-stat-val{font-size:15px;font-weight:800;color:#1e40af;}
        .dana-stat-val.green{color:#059669;}
        .dana-stat-val.red{color:#e11d48;}
        .dana-stat-val.amber{color:#b45309;}
        .dana-stat-sub{font-size:9.5px;color:#94a3b8;margin-top:1px;}
        .kuota-rec{background:#fff;border:1.5px solid #a7f3d0;border-radius:10px;padding:8px 12px;font-size:11.5px;color:#065f46;font-weight:600;display:flex;align-items:center;gap:6px;}
        .periode-next{background:#fffbeb;border:1.5px solid #fde68a;border-radius:10px;padding:10px 13px;margin-top:10px;font-size:11.5px;color:#92400e;display:none;line-height:1.7;}
        .periode-next b{font-weight:800;}
        @media(max-width:640px){.dana-grid{grid-template-columns:1fr;}.dana-stat-row{grid-template-columns:1fr 1fr;}.dana-stat-row>*:last-child{grid-column:span 2;}}
        @media(max-width:420px){.dana-stat-row{grid-template-columns:1fr;}}

        /* Row warga sudah terpilih (dikunci) */
        tr.row-locked { opacity:.55; }
        tr.row-locked td { pointer-events:none; }
    </style>

    {{-- OVERLAY LOADING --}}
    <div id="fi-overlay">
        <div class="fi-overlay-box">
            <div class="fi-overlay-ring" id="fi-overlay-ring"></div>
            <div class="fi-overlay-title" id="fi-overlay-title">Memuat data...</div>
            <div class="fi-overlay-sub"  id="fi-overlay-sub">Mohon tunggu sebentar</div>
        </div>
    </div>

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

    {{-- ══════════════════════════════════════════════════════
         PANEL DANA & PERIODE BLT-DD
    ══════════════════════════════════════════════════════ --}}
    <div class="dana-box" style="margin-bottom:12px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:11px;">
            <div style="width:3px;height:14px;background:#2563eb;border-radius:4px;"></div>
            <h3 style="font-size:12px;font-weight:700;color:#1e40af;">Kalkulator Dana BLT-DD</h3>
            <span style="margin-left:auto;font-size:10px;font-weight:600;color:#3b82f6;background:#dbeafe;padding:2px 8px;border-radius:20px;">Rp 300.000 / orang / bulan</span>
        </div>

        <div class="dana-grid">
            <div>
                <label class="fi-label" style="color:#1e40af;">Total Dana Cair (Rp)</label>
                <input type="text" id="inputDana" inputmode="numeric"
                       placeholder="Contoh: 300.000.000"
                       value="{{ $danaSisaTerakhir ? number_format($danaSisaTerakhir,0,',','.') : '' }}"
                       class="fi-input"
                       style="border-color:#bfdbfe;background:#fff;"
                       oninput="formatDana(this)">
                @if($danaSisaTerakhir)
                    <div style="font-size:9.5px;color:#059669;margin-top:3px;font-weight:600;">
                        Otomatis diisi dari sisa periode {{ $periodeTerakhir->periode }}
                    </div>
                @else
                    <div style="font-size:9.5px;color:#64748b;margin-top:3px;">Masukkan total dana yang dicairkan pemerintah</div>
                @endif
            </div>
            <div>
                <label class="fi-label" style="color:#1e40af;">Penerima Bulan Ini (orang)</label>
                <div id="penerimaOtomatis" class="fi-input" style="border-color:#bfdbfe;background:#f0f9ff;display:flex;align-items:center;font-weight:800;color:#1e40af;font-size:16px;">—</div>
                <div style="font-size:9.5px;color:#64748b;margin-top:3px;" id="penerimaOtomatisSub">Otomatis: Dana ÷ {{ max(1, 13 - $nextPeriodeNum) }} bulan ÷ Rp 300.000</div>
            </div>
        </div>

        {{-- Stat row --}}
        <div class="dana-stat-row" id="danaStatRow" style="display:none;">
            <div class="dana-stat red">
                <div class="dana-stat-label">Dana Terpakai</div>
                <div class="dana-stat-val red" id="danaTermakai">—</div>
                <div class="dana-stat-sub" id="danaTermakaiSub">—</div>
            </div>
            <div class="dana-stat green">
                <div class="dana-stat-label">Sisa Dana</div>
                <div class="dana-stat-val green" id="danaSisa">—</div>
                <div class="dana-stat-sub" id="danaSisaSub">—</div>
            </div>
            <div class="dana-stat amber">
                <div class="dana-stat-label">Sisa untuk Periode Berikutnya</div>
                <div class="dana-stat-val amber" id="estimasiPeriode">—</div>
                <div class="dana-stat-sub" id="estimasiPeriodeSub">—</div>
            </div>
        </div>

        {{-- Form simpan periode --}}
        <div id="formSimpanWrap" style="display:none;margin-top:12px;background:#fff;border:1.5px solid #bfdbfe;border-radius:12px;padding:12px 14px;">
            <div style="font-size:11px;font-weight:700;color:#1e40af;margin-bottom:9px;">Simpan ke Riwayat Periode</div>
            <form method="POST" action="{{ route('admin.filterisasi.simpanPeriode') }}"
                  style="display:grid;grid-template-columns:1fr 1fr auto;gap:9px;align-items:end;">
                @csrf
                <input type="hidden" name="dana_awal" id="hiddenDanaAwal">
                <input type="hidden" name="jumlah_penerima" id="hiddenPenerima">
                <div>
                    <label class="fi-label">Nama Periode</label>
                    <input type="text" name="periode" value="Bulan {{ $nextPeriodeNum }}"
                           class="fi-input" style="font-size:12px;" placeholder="Misal: Bulan 1">
                </div>
                <div>
                    <label class="fi-label">Keterangan (opsional)</label>
                    <input type="text" name="keterangan" class="fi-input" style="font-size:12px;"
                           placeholder="Misal: Triwulan I 2026">
                </div>
                <button type="submit" class="btn-fi btn-tetapkan" style="padding:9px 14px;"
                        onclick="return syncHidden()">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Simpan
                </button>
            </form>
        </div>

        {{-- Rekomendasi per dusun --}}
        <div id="rekomendasiBox" style="display:none;margin-top:12px;">
            <div style="font-size:10.5px;font-weight:700;color:#1e40af;margin-bottom:7px;">
                Rekomendasi Kuota per Dusun (proporsional berdasarkan jumlah kandidat)
            </div>
            <div id="rekomendasiList" style="display:flex;flex-wrap:wrap;gap:7px;"></div>
            <div style="font-size:9.5px;color:#64748b;margin-top:7px;">* Klik rekomendasi dusun untuk langsung isi kuota di form filter bawah</div>
        </div>
    </div>

    {{-- RIWAYAT PERIODE DANA --}}
    @if($periodeDana->isNotEmpty())
    <div class="fi-card" style="margin-bottom:12px;">
        <div style="padding:10px 16px;border-bottom:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;gap:7px;">
            <div style="width:3px;height:15px;background:#059669;border-radius:4px;"></div>
            <span style="font-size:12.5px;font-weight:700;color:#374151;">Riwayat Periode Dana BLT-DD</span>
            <span style="margin-left:auto;font-size:11px;color:#059669;background:#f0fdf4;padding:2px 9px;border-radius:20px;font-weight:600;border:1px solid #bbf7d0;">
                {{ $periodeDana->count() }} periode
            </span>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Dana Awal</th>
                        <th>Penerima</th>
                        <th>Dana Terpakai</th>
                        <th>Sisa Dana</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($periodeDana as $p)
                    <tr>
                        <td><span style="font-size:12px;font-weight:700;color:#0f172a;">{{ $p->periode }}</span></td>
                        <td style="font-size:12px;color:#374151;">Rp {{ number_format($p->dana_awal,0,',','.') }}</td>
                        <td>
                            <span class="badge" style="background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe;">
                                {{ $p->jumlah_penerima }} orang
                            </span>
                        </td>
                        <td style="font-size:12px;color:#e11d48;font-weight:600;">Rp {{ number_format($p->dana_terpakai,0,',','.') }}</td>
                        <td style="font-size:12px;color:#059669;font-weight:700;">Rp {{ number_format($p->dana_sisa,0,',','.') }}</td>
                        <td style="font-size:11.5px;color:#64748b;">{{ $p->keterangan ?? '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.filterisasi.hapusPeriode', $p->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-fi btn-reset-fi" style="padding:5px 10px;font-size:11px;"
                                    onclick="return confirm('Hapus data periode ini?')">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- FILTER FORM --}}
    <div class="fi-card" style="padding:14px 16px;margin-bottom:12px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:12px;">
            <div style="width:3px;height:14px;background:#2563eb;border-radius:4px;"></div>
            <h3 style="font-size:12px;font-weight:700;color:#374151;">Pilih Dusun & Kuota</h3>
        </div>

        <form method="GET" action="{{ route('admin.filterisasi') }}" id="formTampil">
            <div class="fi-grid">
                <div>
                    <label class="fi-label">Dusun</label>
                    <select name="dusun_id" class="fi-select" id="selectDusun">
                        <option value="0">-- pilih dusun --</option>
                        @foreach($dusuns as $d)
                            <option value="{{ $d->id }}"
                                    data-nama="{{ $d->nama_dusun }}"
                                    {{ (int)($dusunId??0)===$d->id?'selected':'' }}>
                                {{ $d->nama_dusun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="fi-label">Kuota per Dusun</label>
                    <input type="number" name="kuota" id="inputKuota" min="1" max="100"
                           value="{{ (int)($kuota??7) }}" class="fi-input">
                </div>

                <div class="fi-actions">
                    {{-- Tombol Tampilkan --}}
                    <button type="submit" class="btn-fi btn-tampil" id="btnTampil">
                        <span class="fi-spinner" id="spinnerTampil"></span>
                        <svg id="iconTampil" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                        <span id="textTampil">Tampilkan</span>
                    </button>

                    @if(!empty($dusunId))
                        <form method="POST" action="{{ route('admin.filterisasi.reset') }}" style="display:contents;">
                            @csrf
                            <input type="hidden" name="dusun_id" value="{{ $dusunId }}">
                            <button type="submit" class="btn-fi btn-reset-fi"
                                onclick="return confirm('Reset hasil filterisasi dusun ini?')">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Reset
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.filterisasi.tetapkan') }}" style="display:contents;" id="formTetapkan">
                            @csrf
                            <input type="hidden" name="dusun_id" value="{{ $dusunId }}">
                            <input type="hidden" name="kuota" value="{{ (int)($kuota??7) }}">
                            <button type="submit" class="btn-fi btn-tetapkan" id="btnTetapkan">
                                <span class="fi-spinner" id="spinnerTetapkan"></span>
                                <svg id="iconTetapkan" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span id="textTetapkan">Tetapkan {{ (int)($kuota??7) }} Teratas</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- TABLE CARD --}}
    <div class="fi-card" style="margin-bottom:12px;">
        <div style="padding:10px 16px;border-bottom:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;gap:7px;">
            <div style="width:3px;height:15px;background:#2563eb;border-radius:4px;"></div>
            <span style="font-size:12.5px;font-weight:700;color:#374151;">Kandidat Sedang Divalidasi / Sudah Diproses</span>
            @if(isset($candidates) && method_exists($candidates, 'total') && $candidates->total() > 0)
                @php
                    $totalKandidatAktif = $candidates->total() - (isset($pickedNiks) ? $pickedNiks->count() : 0);
                @endphp
                <span style="margin-left:auto;font-size:11px;color:#64748b;background:#f1f5f9;padding:2px 9px;border-radius:20px;font-weight:600;">
                    {{ $candidates->total() }} kandidat
                    @if(isset($pickedNiks) && $pickedNiks->count() > 0)
                        <span style="color:#059669;">({{ $pickedNiks->count() }} sudah terpilih)</span>
                    @endif
                </span>
            @endif
        </div>

        {{-- INFO warga sudah terpilih dikunci --}}
        @if(isset($pickedNiks) && $pickedNiks->count() > 0)
            <div style="padding:8px 16px;background:#f0fdf4;border-bottom:1px solid #bbf7d0;font-size:11.5px;color:#166534;display:flex;align-items:center;gap:6px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span><b>{{ $pickedNiks->count() }} warga</b> sudah ditetapkan sebagai penerima — tidak ikut seleksi ulang (ditampilkan redup)</span>
            </div>
        @endif

        {{-- DESKTOP TABLE --}}
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:52px;">Rank</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Dusun</th>
                        <th style="min-width:130px;">Probabilitas</th>
                        <th>Status</th>
                        <th>Final</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!isset($dusunId) || (int)$dusunId === 0)
                        <tr><td colspan="7">
                            <div style="padding:48px 16px;text-align:center;">
                                <div style="width:44px;height:44px;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                    <svg width="22" height="22" fill="none" stroke="#d1d5db" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                                </div>
                                <p style="font-size:13px;font-weight:600;color:#6b7280;">Pilih dusun untuk menampilkan kandidat</p>
                            </div>
                        </td></tr>
                    @elseif($candidates->isEmpty())
                        <tr><td colspan="7">
                            <div style="padding:48px 16px;text-align:center;">
                                <div style="width:44px;height:44px;background:#fff7ed;border-radius:50%;display:flex;align-items:center;justify-content:middle;margin:0 auto 10px;border:1.5px solid #fed7aa;">
                                    <svg width="22" height="22" fill="none" stroke="#f97316" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <p style="font-size:13px;font-weight:600;color:#6b7280;">Tidak ada kandidat dengan status <strong>sedang_validasi</strong> atau <strong>selesai</strong> di dusun ini</p>
                            </div>
                        </td></tr>
                    @else
                        @foreach($candidates as $index => $w)
                            @php
                                $prob    = $w->prob ?? optional($w->prediksiKelayakan)->probability;
                                $probPct = $prob !== null ? ($prob<=1 ? $prob*100 : $prob) : null;
                                $sc      = $probPct !== null ? ($probPct>=70?'#10b981':($probPct>=40?'#f59e0b':'#f43f5e')) : '#e5e7eb';
                                $picked  = isset($pickedNiks) ? $pickedNiks->contains($w->nik) : false;
                                $rank    = (($candidates->currentPage()-1)*$candidates->perPage())+$index+1;
                                $rk      = ['linear-gradient(135deg,#f59e0b,#d97706)','linear-gradient(135deg,#94a3b8,#64748b)','linear-gradient(135deg,#f97316,#ea580c)'];
                                $rbg     = $rank<=3 ? $rk[$rank-1] : '#f1f5f9';
                                $rtc     = $rank<=3 ? '#fff' : '#64748b';
                                $svMap   = ['pending'=>['#fffbeb','#b45309','#fde68a','Pending'],'disetujui'=>['#f0fdf4','#166534','#bbf7d0','Disetujui'],'ditolak'=>['#fff1f2','#9f1239','#fecdd3','Ditolak']];
                                $sv      = $svMap[$w->status_verifikasi??''] ?? ['#f3f4f6','#6b7280','#e5e7eb',ucfirst($w->status_verifikasi??'-')];
                            @endphp
                            <tr class="{{ $picked ? 'row-locked' : '' }}" style="{{ $picked ? 'background:#f0fdf4;' : '' }}">
                                <td>
                                    <div style="display:flex;align-items:center;gap:5px;">
                                        <div class="rank-badge" style="background:{{ $rbg }};color:{{ $rtc }};">{{ $rank }}</div>
                                        @if($picked)
                                            <svg width="11" height="11" fill="none" stroke="#059669" viewBox="0 0 24 24" title="Sudah terpilih"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div style="width:28px;height:28px;border-radius:8px;background:{{ $picked ? 'linear-gradient(135deg,#059669,#047857)' : 'linear-gradient(135deg,#3b82f6,#2563eb)' }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0;">
                                            {{ strtoupper(substr($w->nama_lengkap,0,1)) }}
                                        </div>
                                        <div>
                                            <span style="font-size:12px;font-weight:600;color:#0f172a;">{{ $w->nama_lengkap }}</span>
                                            @if($picked)
                                                <div style="font-size:9.5px;color:#059669;font-weight:600;">Sudah ditetapkan</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-family:monospace;font-size:10.5px;color:#6b7280;background:#f3f4f6;padding:2px 7px;border-radius:6px;">{{ $w->nik }}</span>
                                </td>
                                <td style="font-size:12px;color:#374151;">
                                    {{ optional(optional($w->rt)->dusun)->nama_dusun ?? '-' }}
                                </td>
                                <td>
                                    @if($probPct !== null)
                                        <div class="prob-wrap">
                                            <div class="prob-bg">
                                                <div class="prob-bar" style="width:{{ min($probPct,100) }}%;background:{{ $sc }};"></div>
                                            </div>
                                            <span style="font-size:11.5px;font-weight:700;color:{{ $sc }};white-space:nowrap;">{{ number_format((float)$probPct,1) }}%</span>
                                        </div>
                                    @else
                                        <span style="color:#d1d5db;">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background:{{ $sv[0] }};color:{{ $sv[1] }};border:1px solid {{ $sv[2] }};">
                                        <span class="dot" style="background:{{ $sv[1] }};"></span>{{ $sv[3] }}
                                    </span>
                                </td>
                                <td>
                                    @if($picked)
                                        <span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;">
                                            <span class="dot" style="background:#10b981;"></span>Terpilih
                                        </span>
                                    @else
                                        <span class="badge" style="background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;">
                                            <span class="dot" style="background:#9ca3af;"></span>Belum
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARD VIEW --}}
        <div class="mob-fi">
            @if(!isset($dusunId) || (int)$dusunId === 0)
                <div style="padding:32px 16px;text-align:center;">
                    <p style="font-size:13px;font-weight:600;color:#6b7280;">Pilih dusun untuk menampilkan kandidat</p>
                </div>
            @elseif($candidates->isEmpty())
                <div style="padding:32px 16px;text-align:center;">
                    <p style="font-size:13px;font-weight:600;color:#6b7280;">Tidak ada kandidat dengan status <strong>sedang_validasi</strong> atau <strong>selesai</strong> di dusun ini</p>
                </div>
            @else
                @foreach($candidates as $index => $w)
                    @php
                        $prob    = $w->prob ?? optional($w->prediksiKelayakan)->probability;
                        $probPct = $prob !== null ? ($prob<=1 ? $prob*100 : $prob) : null;
                        $sc      = $probPct!==null ? ($probPct>=70?'#10b981':($probPct>=40?'#f59e0b':'#f43f5e')) : '#e5e7eb';
                        $picked  = isset($pickedNiks) ? $pickedNiks->contains($w->nik) : false;
                        $rank    = (($candidates->currentPage()-1)*$candidates->perPage())+$index+1;
                        $rk      = ['linear-gradient(135deg,#f59e0b,#d97706)','linear-gradient(135deg,#94a3b8,#64748b)','linear-gradient(135deg,#f97316,#ea580c)'];
                        $rbg     = $rank<=3 ? $rk[$rank-1] : '#f1f5f9';
                        $rtc     = $rank<=3 ? '#fff' : '#64748b';
                        $svMap   = ['pending'=>['#fffbeb','#b45309','Pending'],'disetujui'=>['#f0fdf4','#166534','Disetujui'],'ditolak'=>['#fff1f2','#9f1239','Ditolak']];
                        $sv      = $svMap[$w->status_verifikasi??''] ?? ['#f3f4f6','#6b7280',ucfirst($w->status_verifikasi??'-')];
                    @endphp
                    <div class="mob-fi-row {{ $picked ? 'row-locked' : '' }}" style="{{ $picked ? 'background:#f0fdf4;border-color:#bbf7d0;opacity:.6;' : '' }}">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="rank-badge" style="background:{{ $rbg }};color:{{ $rtc }};flex-shrink:0;">{{ $rank }}</div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $w->nama_lengkap }}</div>
                                <div style="font-size:10.5px;color:#64748b;margin-top:1px;font-family:monospace;">{{ $w->nik }}</div>
                                @if($picked)
                                    <div style="font-size:9.5px;color:#059669;font-weight:600;">🔒 Sudah ditetapkan</div>
                                @endif
                            </div>
                            @if($picked)
                                <span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;flex-shrink:0;">✓ Terpilih</span>
                            @endif
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;margin-top:10px;flex-wrap:wrap;">
                            <span style="font-size:11px;color:#64748b;background:#f1f5f9;padding:2px 8px;border-radius:6px;">
                                {{ optional(optional($w->rt)->dusun)->nama_dusun ?? '-' }}
                            </span>
                            <span class="badge" style="background:{{ $sv[0] }};color:{{ $sv[1] }};border:1px solid {{ $sv[0] }};">
                                <span class="dot" style="background:{{ $sv[1] }};"></span>{{ $sv[2] }}
                            </span>
                            @if($probPct !== null)
                                <div style="display:flex;align-items:center;gap:6px;flex:1;min-width:100px;">
                                    <div style="flex:1;height:5px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
                                        <div style="height:100%;border-radius:99px;background:{{ $sc }};width:{{ min($probPct,100) }}%;"></div>
                                    </div>
                                    <span style="font-size:11px;font-weight:700;color:{{ $sc }};white-space:nowrap;">{{ number_format((float)$probPct,1) }}%</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- PAGINATION --}}
        @if(isset($candidates) && method_exists($candidates,'links') && $candidates->hasPages())
            <div style="padding:10px 16px;border-top:1px solid #f1f5f9;background:#fafafa;">
                {{ $candidates->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    {{-- NOTE --}}
    <div class="note-box">
        <b>Catatan:</b> Kandidat yang tampil adalah data warga pada tahap
        <b>sedang_validasi</b> atau <b>selesai</b>.
        Warga yang sudah ditetapkan sebelumnya <b>tidak ikut seleksi ulang</b> (ditampilkan redup/terkunci).<br>
        Saat tombol <b>Tetapkan Teratas</b> ditekan, sistem otomatis:<br>
        • memilih warga dengan probabilitas tertinggi sesuai kuota<br>
        • menetapkan yang lolos menjadi <b>disetujui</b><br>
        • menetapkan yang tidak lolos menjadi <b>ditolak</b>
    </div>

    <script>
        // ── DATA DUSUN DARI SERVER ──────────────────────────────────────
        // Jumlah kandidat aktif per dusun (exclude yang sudah terpilih)
        // Dikirim dari blade via PHP ke JS sebagai object
        const dusunData = {
            @foreach($dusuns as $d)
            "{{ $d->id }}": {
                nama: "{{ $d->nama_dusun }}",
                // Hitung kandidat aktif: total kandidat dusun ini minus yang sudah picked
                // Kita pakai nilai dari server yang sudah di-query
                kandidat: @php
                    // Hitung kandidat aktif per dusun (tidak sudah terpilih)
                    $jumlahKandidatDusun = \App\Models\CalonPenerima::query()
                        ->whereIn('tracking_status', ['sedang_validasi', 'selesai'])
                        ->whereHas('rt', fn($q) => $q->where('dusun_id', $d->id))
                        ->whereNotIn('nik', \App\Models\PenerimaFinal::where('nama_dusun', $d->nama_dusun)->where('status_verifikasi','disetujui')->pluck('nik')->toArray())
                        ->count();
                    echo $jumlahKandidatDusun;
                @endphp
            },
            @endforeach
        };

        // ── FORMAT INPUT DANA DENGAN TITIK ──────────────────────────────
        function formatDana(el) {
            let raw = el.value.replace(/\D/g, '');
            if (raw.length > 0) {
                el.value = parseInt(raw, 10).toLocaleString('id-ID');
            } else {
                el.value = '';
            }
            hitungKuota();
        }

        function getDanaValue() {
            const raw = document.getElementById('inputDana').value.replace(/\./g, '').replace(/,/g, '');
            return parseFloat(raw) || 0;
        }

        function rupiah(n) {
            return 'Rp ' + Math.round(n).toLocaleString('id-ID');
        }

        // Sisa bulan rencana penyaluran (12 - jumlah periode yang sudah tercatat)
        const sisaBulan = {{ max(1, 13 - $nextPeriodeNum) }};

        // ── HITUNG PENERIMA BULAN INI & SISA DANA ────────────────────────
        function hitungKuota() {
            const dana     = getDanaValue();
            const nilaiPerOrang = 300000;

            // Sembunyikan semua stat kalau input belum lengkap
            if (dana <= 0) {
                document.getElementById('penerimaOtomatis').textContent = '—';
                document.getElementById('danaStatRow').style.display    = 'none';
                document.getElementById('rekomendasiBox').style.display = 'none';
                document.getElementById('formSimpanWrap').style.display = 'none';
                return;
            }

            // Jatah dana untuk periode/bulan ini = total dana dibagi sisa bulan rencana
            const jatahBulanIni = dana / sisaBulan;
            const penerima      = Math.floor(jatahBulanIni / nilaiPerOrang);

            if (penerima <= 0) {
                document.getElementById('penerimaOtomatis').textContent = '0 orang';
                document.getElementById('danaStatRow').style.display    = 'none';
                document.getElementById('rekomendasiBox').style.display = 'none';
                document.getElementById('formSimpanWrap').style.display = 'none';
                return;
            }

            document.getElementById('penerimaOtomatis').textContent = penerima + ' orang';

            const terpakai = penerima * nilaiPerOrang;
            const sisa     = dana - terpakai;
            const periodeBerikutnya = sisa > 0 ? Math.floor((sisa / Math.max(1, sisaBulan - 1)) / nilaiPerOrang) : 0;

            // Update stat row
            document.getElementById('danaStatRow').style.display = 'grid';

            document.getElementById('danaTermakai').textContent    = rupiah(terpakai);
            document.getElementById('danaTermakaiSub').textContent = penerima + ' orang x Rp 300.000';

            if (sisa >= 0) {
                document.getElementById('danaSisa').textContent    = rupiah(sisa);
                document.getElementById('danaSisaSub').textContent = sisa > 0 ? 'Tersisa untuk ' + Math.max(1, sisaBulan - 1) + ' bulan berikutnya' : 'Dana habis tepat';
                document.getElementById('danaSisa').className      = 'dana-stat-val ' + (sisa > 0 ? 'green' : 'amber');
            } else {
                document.getElementById('danaSisa').textContent    = rupiah(sisa);
                document.getElementById('danaSisaSub').textContent = 'Dana tidak mencukupi!';
                document.getElementById('danaSisa').className      = 'dana-stat-val red';
            }

            if (periodeBerikutnya > 0) {
                document.getElementById('estimasiPeriode').textContent    = rupiah(sisa);
                document.getElementById('estimasiPeriodeSub').textContent = 'kira-kira ' + periodeBerikutnya + ' orang/bulan untuk ' + Math.max(1, sisaBulan - 1) + ' bulan ke depan';
            } else {
                document.getElementById('estimasiPeriode').textContent    = sisa > 0 ? rupiah(sisa) : '—';
                document.getElementById('estimasiPeriodeSub').textContent = sisa > 0 ? 'Sisa tidak cukup 1 orang/bulan' : 'Dana habis';
            }

            // Tampilkan form simpan periode
            document.getElementById('formSimpanWrap').style.display = 'block';

            // Hitung rekomendasi per dusun proporsional
            hitungProporsional(penerima);
        }

        function syncHidden() {
            const dana     = getDanaValue();
            const jatahBulanIni = dana / sisaBulan;
            const penerima = Math.floor(jatahBulanIni / 300000);
            if (dana <= 0 || penerima <= 0) {
                alert('Dana belum cukup untuk membiayai minimal 1 penerima bulan ini.');
                return false;
            }
            document.getElementById('hiddenDanaAwal').value  = dana;
            document.getElementById('hiddenPenerima').value  = penerima;
            return true;
        }

        function hitungProporsional(totalKuota) {
            // Total kandidat aktif semua dusun
            let totalKandidat = 0;
            for (const id in dusunData) {
                totalKandidat += dusunData[id].kandidat;
            }

            const listEl = document.getElementById('rekomendasiList');
            listEl.innerHTML = '';

            if (totalKandidat === 0) {
                document.getElementById('rekomendasiBox').style.display = 'none';
                return;
            }

            let sisaKuota = totalKuota;
            const entries = Object.entries(dusunData).filter(([,v]) => v.kandidat > 0);

            // Hitung kuota proporsional dengan metode Hamilton (sisa terbesar)
            const kuotaFloat = entries.map(([id, v]) => ({
                id,
                nama: v.nama,
                kandidat: v.kandidat,
                kuotaFloat: (v.kandidat / totalKandidat) * totalKuota
            }));

            // Assign floor dulu
            kuotaFloat.forEach(e => { e.kuota = Math.floor(e.kuotaFloat); });
            let sisaBagi = totalKuota - kuotaFloat.reduce((s, e) => s + e.kuota, 0);

            // Distribusi sisa ke yang punya desimal terbesar
            kuotaFloat
                .sort((a, b) => (b.kuotaFloat - b.kuota) - (a.kuotaFloat - a.kuota))
                .forEach(e => {
                    if (sisaBagi > 0) { e.kuota++; sisaBagi--; }
                });

            // Pastikan tidak melebihi jumlah kandidat
            kuotaFloat.forEach(e => {
                e.kuota = Math.min(e.kuota, e.kandidat);
            });

            // Sort by nama dusun
            kuotaFloat.sort((a, b) => a.nama.localeCompare(b.nama));

            kuotaFloat.forEach(e => {
                const chip = document.createElement('div');
                chip.className = 'kuota-rec';
                chip.style.cursor = 'pointer';
                chip.title = 'Klik untuk pilih dusun ini & isi kuota ' + e.kuota;
                chip.innerHTML = `
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <b>${e.nama}</b>: <span style="color:#059669;">${e.kuota} orang</span>
                    <span style="color:#94a3b8;font-size:9.5px;">(${e.kandidat} kandidat aktif)</span>
                `;
                chip.addEventListener('click', () => {
                    // Set dusun di select
                    const sel = document.getElementById('selectDusun');
                    for (let i = 0; i < sel.options.length; i++) {
                        if (sel.options[i].getAttribute('data-nama') === e.nama) {
                            sel.selectedIndex = i;
                            break;
                        }
                    }
                    // Set kuota
                    document.getElementById('inputKuota').value = e.kuota;
                });
                listEl.appendChild(chip);
            });

            document.getElementById('rekomendasiBox').style.display = 'block';
        }

        // ── TOMBOL TAMPILKAN ────────────────────────────────────────────
        document.getElementById('formTampil')?.addEventListener('submit', function () {
            const btn     = document.getElementById('btnTampil');
            const spinner = document.getElementById('spinnerTampil');
            const icon    = document.getElementById('iconTampil');
            const text    = document.getElementById('textTampil');

            btn.disabled = true;
            btn.style.opacity = '0.8';
            btn.style.cursor  = 'not-allowed';
            spinner.style.display = 'block';
            icon.style.display    = 'none';
            text.textContent = 'Memuat...';

            // Tampilkan overlay
            const overlay = document.getElementById('fi-overlay');
            document.getElementById('fi-overlay-ring').className  = 'fi-overlay-ring blue';
            document.getElementById('fi-overlay-title').textContent = 'Memuat Kandidat';
            document.getElementById('fi-overlay-sub').textContent   = 'Mengambil data dari server...';
            overlay.classList.add('show');
        });

        // ── TOMBOL TETAPKAN ─────────────────────────────────────────────
        document.getElementById('formTetapkan')?.addEventListener('submit', function (e) {
            const kuota = {{ (int)($kuota??7) }};
            if (!confirm('Tetapkan ' + kuota + ' orang dengan probabilitas tertinggi untuk dusun ini?\n\nWarga yang sudah terpilih sebelumnya tidak akan terpengaruh.')) {
                e.preventDefault();
                return;
            }

            const btn     = document.getElementById('btnTetapkan');
            const spinner = document.getElementById('spinnerTetapkan');
            const icon    = document.getElementById('iconTetapkan');
            const text    = document.getElementById('textTetapkan');

            btn.disabled = true;
            btn.style.opacity = '0.8';
            btn.style.cursor  = 'not-allowed';
            spinner.style.display = 'block';
            icon.style.display    = 'none';
            text.textContent = 'Memproses...';

            // Tampilkan overlay
            const overlay = document.getElementById('fi-overlay');
            document.getElementById('fi-overlay-ring').className  = 'fi-overlay-ring';
            document.getElementById('fi-overlay-title').textContent = 'Menetapkan Penerima';
            document.getElementById('fi-overlay-sub').textContent   = 'Sistem sedang memproses & menetapkan kandidat...';
            overlay.classList.add('show');
        });

        // Init jika ada nilai dana dari query string
        (function(){
            const dana = document.getElementById('inputDana').value;
            if (dana) hitungKuota();
        })();
    </script>

</x-app-layout>