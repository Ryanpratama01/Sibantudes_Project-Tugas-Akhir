<x-app-layout>

    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h2 style="font-size:16px;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Data Warga</h2>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;">Kelola dan pantau seluruh data warga dari semua dusun</p>
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <a href="{{ route('admin.data-warga.arsip') }}" style="display:inline-flex;align-items:center;gap:6px;background:#f1f5f9;border:1.5px solid #e2e8f0;border-radius:10px;padding:6px 12px;font-size:11px;color:#475569;font-weight:600;text-decoration:none;white-space:nowrap;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Lihat Arsip
                </a>
                <div style="display:inline-flex;align-items:center;gap:6px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:6px 12px;font-size:11px;color:#64748b;white-space:nowrap;">
                    <svg width="13" height="13" fill="none" stroke="#3b82f6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }} — {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        /* ── LIST VIEW ── */
        .dw-card{background:#fff;border-radius:16px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04),0 4px 16px rgba(0,0,0,.03);overflow:hidden;}
        .fl-box{display:flex;align-items:center;gap:9px;border-radius:12px;padding:10px 14px;font-size:12px;font-weight:500;}
        .fl-box svg{width:14px;height:14px;flex-shrink:0;}
        .sw{position:relative;}
        .sw svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;stroke:#9ca3af;pointer-events:none;}
        .sw input{width:100%;padding:9px 12px 9px 34px;font-size:13px;color:#111827;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:11px;outline:none;transition:border-color .15s,box-shadow .15s;font-family:inherit;}
        .sw input:focus{border-color:#3b82f6;background:#fff;box-shadow:0 0 0 3px rgba(59,130,246,.10);}
        .sw input::placeholder{color:#9ca3af;}
        .tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
        table{width:100%;border-collapse:collapse;min-width:700px;}
        thead tr{background:#f8fafc;border-bottom:1.5px solid #f1f5f9;}
        thead th{padding:9px 12px;text-align:left;font-size:9.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;}
        tbody tr{border-bottom:1px solid #f8fafc;transition:background .1s;}
        tbody tr:hover{background:#f0f7ff;}
        tbody tr:last-child{border-bottom:none;}
        tbody td{padding:10px 12px;vertical-align:middle;}
        .av{width:30px;height:30px;border-radius:9px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0;}
        .nik-pill{font-family:monospace;font-size:10px;color:#6b7280;background:#f3f4f6;padding:2px 6px;border-radius:6px;white-space:nowrap;}
        .rt-badge{display:inline-flex;align-items:center;padding:2px 7px;border-radius:6px;background:#eff6ff;color:#1d4ed8;font-size:10px;font-weight:700;border:1px solid #bfdbfe;margin-top:2px;}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:10.5px;font-weight:700;white-space:nowrap;}
        .dot{width:5px;height:5px;border-radius:50%;flex-shrink:0;}
        .trk-terkirim{background:#eff6ff;color:#1d4ed8;}.trk-terkirim .dot{background:#3b82f6;}
        .trk-validasi{background:#fffbeb;color:#b45309;}.trk-validasi .dot{background:#f59e0b;}
        .trk-selesai{background:#f0fdf4;color:#166534;}.trk-selesai .dot{background:#22c55e;}
        .trk-default{background:#f3f4f6;color:#6b7280;}.trk-default .dot{background:#9ca3af;}
        .stat-yes{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
        .stat-no{background:#fff1f2;color:#9f1239;border:1px solid #fecdd3;}
        .stat-pending{background:#fffbeb;color:#b45309;border:1px solid #fde68a;}
        .prob-wrap{display:flex;align-items:center;gap:6px;}
        .prob-bg{flex:1;height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;min-width:50px;}
        .prob-bar{height:100%;border-radius:99px;}
        .btn-act{display:inline-flex;align-items:center;gap:4px;padding:4px 9px;border-radius:8px;font-size:10.5px;font-weight:600;text-decoration:none;border:none;cursor:pointer;white-space:nowrap;line-height:1.4;transition:filter .1s;font-family:inherit;}
        .btn-act:hover{filter:brightness(.92);}
        .btn-act svg{width:11px;height:11px;flex-shrink:0;}
        .btn-detail{background:#f3f4f6;color:#374151;}
        .btn-validasi{background:#eff6ff;color:#1d4ed8;}
        .btn-kirim{background:#fffbeb;color:#b45309;}
        .info-chip{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:99px;font-size:10px;font-weight:700;background:#f8fafc;color:#475569;border:1px solid #e2e8f0;white-space:nowrap;}

        /* ── DETAIL PANEL ── */
        #detailPanel{display:none;margin-top:16px;}
        .hero-banner{background:linear-gradient(135deg,#1e40af 0%,#2563eb 60%,#3b82f6 100%);border-radius:1rem;padding:1.5rem;position:relative;overflow:hidden;margin-bottom:1.25rem;}
        .hero-bubble{position:absolute;border-radius:50%;pointer-events:none;}
        .detail-card{background:#fff;border-radius:1rem;border:1px solid #f1f5f9;margin-bottom:1rem;overflow:hidden;}
        .detail-card-head{padding:.65rem 1.1rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px;background:#f9fafb;}
        .detail-card-accent{width:3px;height:16px;border-radius:2px;flex-shrink:0;}
        .detail-card-title{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;}
        .detail-card-body{padding:1rem 1.1rem;}
        .data-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:1rem;}
        .data-lbl{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;margin-bottom:3px;}
        .data-val{font-size:13px;color:#111827;font-weight:500;}
        .badge-rt{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:12px;background:#dbeafe;color:#1e40af;font-weight:600;}
        .faktor-item{display:flex;align-items:flex-start;gap:8px;padding:7px 10px;border-radius:8px;font-size:13px;margin-bottom:5px;}
        .faktor-pos{background:#f0fdf4;color:#166534;}
        .faktor-neg{background:#fff1f2;color:#9f1239;}
        .penjelasan-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;font-size:13px;border-bottom:1px solid #f1f5f9;}
        .penjelasan-row:last-child{border-bottom:none;}
        .ringkasan-box{background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:12px 14px;font-size:13px;color:#0c4a6e;line-height:1.6;}
        .foto-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;}
        @media(max-width:600px){.foto-grid{grid-template-columns:repeat(2,1fr);}}
        .foto-thumb{aspect-ratio:1;border-radius:8px;overflow:hidden;border:1px solid #e5e7eb;position:relative;cursor:pointer;}
        .foto-thumb img{width:100%;height:100%;object-fit:cover;display:block;}
        .foto-placeholder{width:100%;height:100%;background:#f9fafb;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;}
        .foto-label{position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.5);color:#fff;font-size:10px;text-align:center;padding:3px 4px;}
        .detail-layout{display:grid;grid-template-columns:1fr 300px;gap:1rem;align-items:start;}
        @media(max-width:900px){.detail-layout{grid-template-columns:1fr;}}
        .badge-layak{background:#dcfce7;color:#15803d;padding:5px 14px;border-radius:20px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:5px;}
        .badge-tl{background:#fee2e2;color:#b91c1c;padding:5px 14px;border-radius:20px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:5px;}
        .progress-track{height:6px;border-radius:3px;background:#e5e7eb;overflow:hidden;}
        .donut-ring{transform:rotate(-90deg);transform-origin:36px 36px;}

        /* lightbox */
        #dw-lightbox{position:fixed;inset:0;z-index:999;background:rgba(0,0,0,.85);display:none;align-items:center;justify-content:center;padding:16px;}
        #dw-lightbox img{max-width:100%;max-height:100%;border-radius:12px;object-fit:contain;}

        @media(max-width:640px){
            .detail-card-body{padding:.75rem;}
            .data-grid{grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:.75rem;}
            .hero-banner{padding:1rem;}
        }
    </style>

    @if(session('success'))
        <div class="fl-box" style="background:#f0fdf4;border:1.5px solid #bbf7d0;color:#166534;margin-bottom:10px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="fl-box" style="background:#fff1f2;border:1.5px solid #fecdd3;color:#9f1239;margin-bottom:10px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- SEARCH & FILTER --}}
    <div class="dw-card" style="padding:14px 16px;margin-bottom:12px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:10px;">
            <div style="width:3px;height:14px;background:#2563eb;border-radius:4px;"></div>
            <h3 style="font-size:12px;font-weight:700;color:#374151;">Pencarian & Filter</h3>
        </div>
        <form method="GET" action="{{ url()->current() }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <div class="sw" style="flex:1;min-width:200px;">
                <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari NIK, Nama, atau Email...">
            </div>
            <button type="submit" style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:#2563eb;color:#fff;font-size:12px;font-weight:700;border:none;border-radius:11px;cursor:pointer;white-space:nowrap;font-family:inherit;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>
            @if(request()->filled('q'))
                <a href="{{ url()->current() }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#f3f4f6;border-radius:10px;color:#6b7280;text-decoration:none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>

    </div>

    {{-- TABLE --}}
    <div class="dw-card" id="tableCard">
        <div style="padding:10px 16px;border-bottom:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;">
            <div style="display:flex;align-items:center;gap:7px;">
                <div style="width:3px;height:15px;background:#2563eb;border-radius:4px;"></div>
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

        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama / Pekerjaan</th>
                        <th>Dusun · RT</th>
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
                            elseif(in_array(strtolower($warga->pekerjaan??''),['buruh harian lepas','buruh harian','buruh','petani kecil','petani','mengurus rumah tangga'])) $positive[]='Pekerjaan tergolong rentan';
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
                            if(strtolower($warga->kondisi_rumah??'')==='tidak layak') $positive[]='Kondisi rumah tidak layak huni';
                            elseif(strtolower($warga->kondisi_rumah??'')==='sedang') $positive[]='Kondisi rumah sedang';
                            else $negative[]='Kondisi rumah layak';
                            if(($warga->meteran_listrik??'')==='450VA') $positive[]='Meteran listrik 450VA';
                            elseif(($warga->meteran_listrik??'')==='900VA') $positive[]='Meteran listrik 900VA';
                            else $negative[]='Meteran listrik 1300VA ke atas';
                            if(strtolower($warga->sumber_air??'')==='sungai') $positive[]='Sumber air sungai';
                            elseif(strtolower($warga->sumber_air??'')==='sumur') $positive[]='Sumber air sumur';
                            else $negative[]='Sumber air PDAM';
                            $summary='Nilai kelayakan dihitung berdasarkan pekerjaan, penghasilan, tanggungan, aset, bantuan lain, usia, kondisi rumah, meteran listrik, dan sumber air.';
                            if(count($positive)&&count($negative)) $summary.=' Terdapat faktor pendukung dan pengurang.';
                            elseif(count($positive)) $summary.=' Faktor mayoritas mendukung kelayakan.';
                            else $summary.=' Faktor mayoritas menurunkan kelayakan.';

                            $dp=[
                                'nik'=>$warga->nik,'no_kk'=>$warga->no_kk??'-',
                                'nama_lengkap'=>$warga->nama_lengkap,'jenis_kelamin'=>$warga->jenis_kelamin??'-',
                                'tempat_lahir'=>$warga->tempat_lahir??'-',
                                'tanggal_lahir'=>$warga->tanggal_lahir?\Carbon\Carbon::parse($warga->tanggal_lahir)->translatedFormat('d F Y'):'-',
                                'usia'=>$warga->usia??'-','status_perkawinan'=>$warga->status_perkawinan??'-',
                                'alamat'=>$warga->alamat??'-','desa'=>$warga->desa??'-',
                                'aset_kepemilikan'=>$warga->aset_kepemilikan??'-',
                                'kondisi_rumah'=>$warga->kondisi_rumah??'-',
                                'lantai_rumah'=>$warga->lantai_rumah??'-',
                                'dinding_rumah'=>$warga->dinding_rumah??'-',
                                'atap_rumah'=>$warga->atap_rumah??'-',
                                'luas_rumah_m2'=>$warga->luas_rumah_m2??'-',
                                'status_kepemilikan_rumah'=>$warga->status_kepemilikan_rumah??'-',
                                'meteran_listrik'=>$warga->meteran_listrik??'-',
                                'sumber_air'=>$warga->sumber_air??'-',
                                'pekerjaan'=>$warga->pekerjaan??'-','penghasilan'=>$warga->penghasilan??0,
                                'jumlah_tanggungan'=>$warga->jumlah_tanggungan??'-','bantuan_lain'=>$warga->bantuan_lain??'-',
                                'status_verifikasi'=>$warga->status_verifikasi??'-','tracking_status'=>$warga->tracking_status??'-',
                                'dusun'=>$warga->rt->dusun->nama_dusun??'-','rt'=>$warga->rt->nomor_rt??'-',
                                'probability'=>optional($warga->prediksiKelayakan)->probability,
                                'recommendation'=>optional($warga->prediksiKelayakan)->recommendation,
                                'positive'=>$positive,'negative'=>$negative,'summary'=>$summary,
                                'input_oleh'=>optional($warga->user)->name??(optional($warga->user)->email??'-'),
                                'created_at'=>optional($warga->created_at)->translatedFormat('d F Y, H:i')??'-',
                                'foto_rumah_depan'      => $warga->foto_rumah_depan      ? asset('storage/'.$warga->foto_rumah_depan)      : null,
                                'foto_rumah_belakang'   => $warga->foto_rumah_belakang   ? asset('storage/'.$warga->foto_rumah_belakang)   : null,
                                'foto_rumah_kanan'      => $warga->foto_rumah_kanan      ? asset('storage/'.$warga->foto_rumah_kanan)      : null,
                                'foto_rumah_kiri'       => $warga->foto_rumah_kiri       ? asset('storage/'.$warga->foto_rumah_kiri)       : null,
                                'foto_kk'               => $warga->foto_kk               ? asset('storage/'.$warga->foto_kk)               : null,
                                'foto_ktp'              => $warga->foto_ktp              ? asset('storage/'.$warga->foto_ktp)              : null,
                                'foto_rekening_listrik' => $warga->foto_rekening_listrik ? asset('storage/'.$warga->foto_rekening_listrik) : null,
                                'foto_meteran_air'      => $warga->foto_meteran_air      ? asset('storage/'.$warga->foto_meteran_air)      : null,
                                'dokumen_pendukung'     => $warga->dokumen_pendukung     ? asset('storage/'.$warga->dokumen_pendukung)     : null,
                            ];
                            $trk=$warga->tracking_status??'';
                        @endphp
                        <tr>
                            <td><span class="nik-pill">{{ $warga->nik }}</span></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div class="av">{{ strtoupper(substr($warga->nama_lengkap,0,1)) }}</div>
                                    <div style="min-width:0;">
                                        <div style="font-size:12px;font-weight:600;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px;">{{ $warga->nama_lengkap }}</div>
                                        <div style="font-size:10.5px;color:#9ca3af;margin-top:1px;">{{ $warga->pekerjaan??'-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:11.5px;font-weight:600;color:#374151;white-space:nowrap;">{{ $warga->rt->dusun->nama_dusun??'-' }}</div>
                                <span class="rt-badge">RT {{ str_pad($warga->rt->nomor_rt??'-',3,'0',STR_PAD_LEFT) }}</span>
                            </td>
                            <td style="font-size:12px;font-weight:600;color:#374151;white-space:nowrap;">
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
                                @if($trk==='terkirim') <span class="badge trk-terkirim"><span class="dot"></span>Terkirim</span>
                                @elseif($trk==='sedang_validasi') <span class="badge trk-validasi"><span class="dot"></span>Validasi</span>
                                @elseif($trk==='selesai') <span class="badge trk-selesai"><span class="dot"></span>Selesai</span>
                                @elseif($trk==='ditinjau_ulang') <span class="badge" style="background:#f5f3ff;color:#6d28d9;border:1px solid #ddd6fe;"><span class="dot" style="background:#7c3aed;"></span>Ditinjau Ulang</span>
                                @else <span class="badge trk-default"><span class="dot"></span>—</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;flex-wrap:wrap;">

                                    {{-- Tombol Detail (selalu muncul) --}}
                                    <button type="button" onclick="openDetail({{ $warga->id }})" class="btn-act btn-detail">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </button>

                                    {{-- Status: terkirim → Validasi + Terima Langsung + Tolak Langsung --}}
                                    @if($trk === 'terkirim')
                                        <form method="POST" action="{{ route('admin.data-warga.mulai-validasi', $warga->id) }}">
                                            @csrf
                                            <button type="submit" class="btn-act btn-validasi">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m-1-10l-7 7-4 1 1-4 7-7a2.828 2.828 0 114 4z"/></svg>
                                                Validasi
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.data-warga.terima', $warga->id) }}" onsubmit="return confirm('Terima langsung pengajuan {{ addslashes($warga->nama_lengkap) }}?')">
                                            @csrf
                                            <button type="submit" class="btn-act" style="background:#dcfce7;color:#15803d;">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Terima
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.data-warga.tolak', $warga->id) }}" onsubmit="return confirm('Tolak langsung pengajuan {{ addslashes($warga->nama_lengkap) }}?')">
                                            @csrf
                                            <button type="submit" class="btn-act" style="background:#fee2e2;color:#b91c1c;">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Tolak
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Status: sedang_validasi → info + Terima/Tolak Langsung --}}
                                    @if($trk === 'sedang_validasi')
                                        <span class="info-chip">Lanjut filterisasi</span>
                                        <form method="POST" action="{{ route('admin.data-warga.terima', $warga->id) }}" onsubmit="return confirm('Terima langsung pengajuan {{ addslashes($warga->nama_lengkap) }}?')">
                                            @csrf
                                            <button type="submit" class="btn-act" style="background:#dcfce7;color:#15803d;">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Terima
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.data-warga.tolak', $warga->id) }}" onsubmit="return confirm('Tolak langsung pengajuan {{ addslashes($warga->nama_lengkap) }}?')">
                                            @csrf
                                            <button type="submit" class="btn-act" style="background:#fee2e2;color:#b91c1c;">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Tolak
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Status: ditinjau_ulang → sama seperti terkirim, bisa Validasi/Terima/Tolak --}}
                                    @if($trk === 'ditinjau_ulang')
                                        <form method="POST" action="{{ route('admin.data-warga.mulai-validasi', $warga->id) }}">
                                            @csrf
                                            <button type="submit" class="btn-act btn-validasi">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m-1-10l-7 7-4 1 1-4 7-7a2.828 2.828 0 114 4z"/></svg>
                                                Validasi
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.data-warga.terima', $warga->id) }}" onsubmit="return confirm('Terima langsung pengajuan {{ addslashes($warga->nama_lengkap) }}?')">
                                            @csrf
                                            <button type="submit" class="btn-act" style="background:#dcfce7;color:#15803d;">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Terima
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.data-warga.tolak', $warga->id) }}" onsubmit="return confirm('Tolak langsung pengajuan {{ addslashes($warga->nama_lengkap) }}?')">
                                            @csrf
                                            <button type="submit" class="btn-act" style="background:#fee2e2;color:#b91c1c;">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Tolak
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Status: selesai → badge hasil + Kirim Hasil + Aktifkan Kembali + Arsipkan --}}
                                    @if($trk === 'selesai')
                                        @if(($warga->status_verifikasi??'')==='disetujui')
                                            <span class="badge stat-yes"><span class="dot" style="background:#22c55e;"></span>Diterima</span>
                                        @elseif(($warga->status_verifikasi??'')==='ditolak')
                                            <span class="badge stat-no"><span class="dot" style="background:#f43f5e;"></span>Ditolak</span>
                                        @else
                                            <span class="badge stat-pending"><span class="dot" style="background:#f59e0b;"></span>Pending</span>
                                        @endif

                                        <form method="POST" action="{{ route('admin.data-warga.selesai-validasi', $warga->id) }}">
                                            @csrf
                                            <button type="submit" class="btn-act btn-kirim">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Kirim Hasil
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.data-warga.aktifkan-kembali', $warga->id) }}" onsubmit="return confirm('Aktifkan kembali pengajuan {{ addslashes($warga->nama_lengkap) }}?')">
                                            @csrf
                                            <button type="submit" class="btn-act" style="background:#fffbeb;color:#b45309;">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Aktifkan Kembali
                                            </button>
                                        </form>

                                        @if(!$warga->arsip_tahun)
                                            <form method="POST" action="{{ route('admin.data-warga.arsipkan', $warga->id) }}" onsubmit="return confirm('Arsipkan data {{ addslashes($warga->nama_lengkap) }} ke tahun {{ now()->year }}?')">
                                                @csrf
                                                <button type="submit" class="btn-act" style="background:#f3f4f6;color:#6b7280;">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                                    Arsipkan
                                                </button>
                                            </form>
                                        @endif
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
            <div style="padding:10px 16px;border-top:1px solid #f1f5f9;background:#fafafa;">
                {{ $wargas->links() }}
            </div>
        @endif
    </div>

    {{-- DETAIL PANEL --}}
    <div id="detailPanel">

        {{-- Tombol kembali --}}
        <div style="margin-bottom:12px;">
            <button onclick="closeDetail()" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;font-size:12px;font-weight:600;color:#374151;cursor:pointer;font-family:inherit;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar
            </button>
        </div>

        {{-- HERO --}}
        <div class="hero-banner">
            <div class="hero-bubble" style="width:180px;height:180px;background:rgba(255,255,255,.06);top:-40px;right:-30px;"></div>
            <div class="hero-bubble" style="width:90px;height:90px;background:rgba(255,255,255,.04);top:50px;right:100px;"></div>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div id="dp-avatar" style="width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;font-weight:700;flex-shrink:0;">?</div>
                    <div>
                        <div id="dp-nama" style="color:#fff;font-size:18px;font-weight:700;line-height:1.2;">—</div>
                        <div id="dp-nik" style="color:rgba(255,255,255,.7);font-size:12px;margin-top:2px;">—</div>
                        <div style="margin-top:6px;">
                            <span id="dp-status-pill" style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.25);">—</span>
                        </div>
                    </div>
                </div>
                <div style="text-align:right;">
                    <div id="dp-prob-big" style="color:#fff;font-size:40px;font-weight:800;line-height:1;">—</div>
                    <div style="color:rgba(255,255,255,.6);font-size:11px;margin-top:2px;">probabilitas</div>
                    <div style="margin-top:6px;" id="dp-reco-hero"></div>
                </div>
            </div>
        </div>

        {{-- DETAIL LAYOUT --}}
        <div class="detail-layout">

            {{-- KOLOM KIRI --}}
            <div>

                {{-- Identitas --}}
                <div class="detail-card">
                    <div class="detail-card-head"><div class="detail-card-accent" style="background:#2563eb;"></div><span class="detail-card-title">Identitas</span></div>
                    <div class="detail-card-body">
                        <div class="data-grid">
                            <div><div class="data-lbl">No. KK</div><div class="data-val" id="dp-no_kk">—</div></div>
                            <div><div class="data-lbl">Jenis Kelamin</div><div class="data-val" id="dp-jk">—</div></div>
                            <div><div class="data-lbl">Tempat Lahir</div><div class="data-val" id="dp-tempat_lahir">—</div></div>
                            <div><div class="data-lbl">Tanggal Lahir</div><div class="data-val" id="dp-tanggal_lahir">—</div></div>
                            <div><div class="data-lbl">Usia</div><div class="data-val" id="dp-usia">—</div></div>
                            <div><div class="data-lbl">Status Kawin</div><div class="data-val" id="dp-kawin">—</div></div>
                            <div><div class="data-lbl">Input Oleh</div><div class="data-val" id="dp-input_oleh">—</div></div>
                            <div><div class="data-lbl">Didaftarkan</div><div class="data-val" id="dp-created_at">—</div></div>
                        </div>
                    </div>
                </div>

                {{-- Tempat Tinggal --}}
                <div class="detail-card">
                    <div class="detail-card-head"><div class="detail-card-accent" style="background:#10b981;"></div><span class="detail-card-title">Tempat Tinggal</span></div>
                    <div class="detail-card-body">
                        <div class="data-grid">
                            <div style="grid-column:span 2;"><div class="data-lbl">Alamat</div><div class="data-val" id="dp-alamat">—</div></div>
                            <div><div class="data-lbl">Dusun</div><div class="data-val" id="dp-dusun">—</div></div>
                            <div><div class="data-lbl">RT</div><div class="data-val" id="dp-rt">—</div></div>
                            <div><div class="data-lbl">Aset Kepemilikan</div><div class="data-val" id="dp-aset">—</div></div>
                            <div><div class="data-lbl">Lantai</div><div class="data-val" id="dp-lantai">—</div></div>
                            <div><div class="data-lbl">Dinding</div><div class="data-val" id="dp-dinding">—</div></div>
                            <div><div class="data-lbl">Atap</div><div class="data-val" id="dp-atap">—</div></div>
                            <div><div class="data-lbl">Luas Rumah</div><div class="data-val" id="dp-luas">—</div></div>
                            <div><div class="data-lbl">Status Kepemilikan</div><div class="data-val" id="dp-status_rumah">—</div></div>
                            <div><div class="data-lbl">Meteran Listrik</div><div class="data-val" id="dp-meteran">—</div></div>
                            <div><div class="data-lbl">Sumber Air</div><div class="data-val" id="dp-air">—</div></div>
                        </div>
                    </div>
                </div>

                {{-- Ekonomi --}}
                <div class="detail-card">
                    <div class="detail-card-head"><div class="detail-card-accent" style="background:#f59e0b;"></div><span class="detail-card-title">Data Ekonomi</span></div>
                    <div class="detail-card-body">
                        <div class="data-grid">
                            <div><div class="data-lbl">Pekerjaan</div><div class="data-val" id="dp-pekerjaan">—</div></div>
                            <div><div class="data-lbl">Penghasilan</div><div class="data-val" id="dp-penghasilan">—</div></div>
                            <div><div class="data-lbl">Tanggungan</div><div class="data-val" id="dp-tanggungan">—</div></div>
                            <div><div class="data-lbl">Bantuan Lain</div><div class="data-val" id="dp-bantuan">—</div></div>
                        </div>
                    </div>
                </div>

                {{-- Foto & Dokumen --}}
                <div class="detail-card">
                    <div class="detail-card-head"><div class="detail-card-accent" style="background:#e11d48;"></div><span class="detail-card-title">Foto & Dokumen</span></div>
                    <div class="detail-card-body">
                        <div class="foto-grid" id="dp-foto-grid"></div>
                        <div id="dp-dokumen-wrap" style="display:none;margin-top:10px;padding-top:10px;border-top:1px solid #f1f5f9;">
                            <a id="dp-dokumen-link" href="#" target="_blank" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#2563eb;font-weight:600;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                Lihat Dokumen Pendukung
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN --}}
            <div>
                <div style="position:sticky;top:1rem;">

                    {{-- Prediksi --}}
                    <div class="detail-card">
                        <div class="detail-card-head"><div class="detail-card-accent" style="background:#22c55e;"></div><span class="detail-card-title">Prediksi Kelayakan</span></div>
                        <div class="detail-card-body">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                                <svg width="80" height="80" viewBox="0 0 72 72">
                                    <circle cx="36" cy="36" r="28" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                                    <circle id="dp-donut" cx="36" cy="36" r="28" fill="none" stroke="#d1d5db" stroke-width="8"
                                        stroke-dasharray="0 175.93" stroke-linecap="round"
                                        style="transform:rotate(-90deg);transform-origin:36px 36px;transition:stroke-dasharray .5s,stroke .3s;"/>
                                    <text x="36" y="40" text-anchor="middle" font-size="13" font-weight="600" fill="#111827" id="dp-donut-text">—</text>
                                </svg>
                                <div>
                                    <div class="data-lbl" style="margin-bottom:6px;">Rekomendasi</div>
                                    <div id="dp-reco-badge" style="display:inline-flex;padding:5px 14px;border-radius:20px;font-size:13px;font-weight:600;background:#f3f4f6;color:#374151;">—</div>
                                    <div class="progress-track" style="width:140px;margin-top:8px;">
                                        <div id="dp-prob-bar" style="height:100%;border-radius:3px;background:#d1d5db;width:0%;transition:width .5s;"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Faktor Pendukung --}}
                            <div style="margin-bottom:12px;">
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#166534;margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Faktor Pendukung
                                </div>
                                <div id="dp-positive"></div>
                            </div>

                            {{-- Faktor Pengurang --}}
                            <div style="margin-bottom:12px;">
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9f1239;margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Faktor Pengurang
                                </div>
                                <div id="dp-negative"></div>
                            </div>

                            {{-- Penjelasan --}}
                            <div style="margin-bottom:12px;">
                                <div class="data-lbl" style="margin-bottom:6px;">Penjelasan Lengkap</div>
                                <div style="border:1px solid #f1f5f9;border-radius:10px;overflow:hidden;">
                                    @php
                                        $penRows = [
                                            'dp-pen-pekerjaan'   => 'Pekerjaan',
                                            'dp-pen-penghasilan' => 'Penghasilan',
                                            'dp-pen-tanggungan'  => 'Tanggungan',
                                            'dp-pen-aset'        => 'Aset',
                                            'dp-pen-bantuan'     => 'Bantuan Lain',
                                            'dp-pen-usia'        => 'Usia',
                                            'dp-pen-kondisi'     => 'Kondisi Rumah',
                                            'dp-pen-meteran'     => 'Meteran Listrik',
                                            'dp-pen-air'         => 'Sumber Air',
                                        ];
                                    @endphp
                                    @foreach($penRows as $pid => $plbl)
                                    <div class="penjelasan-row" style="padding:6px 10px;">
                                        <span style="font-size:12px;color:#9ca3af;">{{ $plbl }}</span>
                                        <span id="{{ $pid }}" style="font-size:12px;color:#111827;font-weight:500;text-align:right;margin-left:8px;">—</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Ringkasan --}}
                            <div class="ringkasan-box">
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#0369a1;margin-bottom:6px;">Ringkasan</div>
                                <p id="dp-summary" style="font-size:12px;color:#0c4a6e;line-height:1.6;margin:0;">—</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- LIGHTBOX --}}
    <div id="dw-lightbox" onclick="this.style.display='none'">
        <img id="dw-lightbox-img" src="" alt="Foto">
    </div>

    <script>
        const fotoLabels = {
            foto_rumah_depan:    'Rumah Depan',
            foto_rumah_belakang: 'Rumah Belakang',
            foto_rumah_kanan:    'Rumah Kanan',
            foto_rumah_kiri:     'Rumah Kiri',
            foto_kk:             'Foto KK',
            foto_ktp:            'Foto KTP',
            foto_rekening_listrik:'Rekening Listrik',
            foto_meteran_air:    'Meteran Air',
        };

        function s(id, val) {
            const e = document.getElementById(id);
            if (e) e.textContent = val ?? '—';
        }

        function openDetail(id) {
            const el = document.getElementById('warga-' + id);
            if (!el) return;
            const d = JSON.parse(el.textContent);

            // Hero
            document.getElementById('dp-avatar').textContent = (d.nama_lengkap || '?').charAt(0).toUpperCase();
            s('dp-nama', d.nama_lengkap);
            s('dp-nik', 'NIK: ' + (d.nik || '—'));

            const stMap = {
                pending:   ['#fbbf24', 'Menunggu Filterisasi'],
                disetujui: ['#22c55e', 'Diterima'],
                ditolak:   ['#f43f5e', 'Ditolak'],
            };
            const [stColor, stLabel] = stMap[d.status_verifikasi] ?? ['#9ca3af', d.status_verifikasi || '—'];
            const pill = document.getElementById('dp-status-pill');
            if (pill) { pill.textContent = stLabel; pill.style.borderColor = stColor + '66'; }

            // Probabilitas
            const prob = d.probability;
            const pct  = prob != null ? (prob <= 1 ? prob * 100 : Number(prob)) : null;
            const color = pct != null ? (pct >= 70 ? '#10b981' : (pct >= 40 ? '#f59e0b' : '#f43f5e')) : '#d1d5db';
            const circ  = 175.93;

            s('dp-prob-big', pct != null ? pct.toFixed(1) + '%' : '—');

            const donut = document.getElementById('dp-donut');
            const donutTxt = document.getElementById('dp-donut-text');
            if (donut) {
                donut.style.stroke = color;
                donut.setAttribute('stroke-dasharray', pct != null ? `${(circ * pct / 100).toFixed(2)} ${circ}` : `0 ${circ}`);
            }
            if (donutTxt) donutTxt.textContent = pct != null ? pct.toFixed(1) + '%' : '—';

            const probBar = document.getElementById('dp-prob-bar');
            if (probBar) { probBar.style.width = (pct ?? 0) + '%'; probBar.style.background = color; }

            const reco = d.recommendation || '—';
            const isLayak = reco.toLowerCase().includes('layak') && !reco.toLowerCase().includes('tidak');
            const recoStyle = isLayak
                ? 'background:#dcfce7;color:#15803d;'
                : (reco === '—' ? 'background:#f3f4f6;color:#374151;' : 'background:#fee2e2;color:#b91c1c;');

            ['dp-reco-badge','dp-reco-hero'].forEach(rid => {
                const rb = document.getElementById(rid);
                if (rb) {
                    rb.style.cssText = `display:inline-flex;align-items:center;gap:5px;padding:5px 14px;border-radius:20px;font-size:13px;font-weight:600;${recoStyle}`;
                    rb.textContent = reco;
                }
            });

            // Identitas
            s('dp-no_kk', d.no_kk);
            s('dp-jk', d.jenis_kelamin);
            s('dp-tempat_lahir', d.tempat_lahir);
            s('dp-tanggal_lahir', d.tanggal_lahir);
            s('dp-usia', d.usia ? d.usia + ' tahun' : '—');
            s('dp-kawin', d.status_perkawinan);
            s('dp-input_oleh', d.input_oleh);
            s('dp-created_at', d.created_at);

            // Tempat Tinggal
            s('dp-alamat', d.alamat);
            s('dp-dusun', d.dusun);
            s('dp-rt', 'RT ' + String(d.rt || '—').padStart(3, '0'));
            s('dp-aset', d.aset_kepemilikan);
            s('dp-lantai', d.lantai_rumah);
            s('dp-dinding', d.dinding_rumah);
            s('dp-atap', d.atap_rumah);
            s('dp-luas', d.luas_rumah_m2 ? d.luas_rumah_m2 + ' m²' : '—');
            s('dp-status_rumah', d.status_kepemilikan_rumah);
            s('dp-meteran', d.meteran_listrik);
            s('dp-air', d.sumber_air);

            // Ekonomi
            s('dp-pekerjaan', d.pekerjaan);
            s('dp-penghasilan', 'Rp ' + Number(d.penghasilan || 0).toLocaleString('id-ID'));
            s('dp-tanggungan', d.jumlah_tanggungan != null ? d.jumlah_tanggungan + ' orang' : '—');
            s('dp-bantuan', d.bantuan_lain);

            // Penjelasan rows
            s('dp-pen-pekerjaan', d.pekerjaan);
            s('dp-pen-penghasilan', 'Rp ' + Number(d.penghasilan || 0).toLocaleString('id-ID'));
            s('dp-pen-tanggungan', d.jumlah_tanggungan != null ? d.jumlah_tanggungan + ' orang' : '—');
            s('dp-pen-aset', d.aset_kepemilikan);
            s('dp-pen-bantuan', d.bantuan_lain);
            s('dp-pen-usia', d.usia ? d.usia + ' tahun' : '—');
            s('dp-pen-kondisi', d.kondisi_rumah);
            s('dp-pen-meteran', d.meteran_listrik);
            s('dp-pen-air', d.sumber_air);

            // Faktor
            const posEl = document.getElementById('dp-positive');
            const negEl = document.getElementById('dp-negative');
            if (posEl) posEl.innerHTML = (d.positive || []).length
                ? d.positive.map(f => `<div class="faktor-item faktor-pos"><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>${f}</div>`).join('')
                : '<div style="font-size:12px;color:#9ca3af;padding:4px 0;">Tidak ada faktor pendukung dominan.</div>';
            if (negEl) negEl.innerHTML = (d.negative || []).length
                ? d.negative.map(f => `<div class="faktor-item faktor-neg"><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>${f}</div>`).join('')
                : '<div style="font-size:12px;color:#9ca3af;padding:4px 0;">Tidak ada faktor pengurang dominan.</div>';

            s('dp-summary', d.summary);

            // Foto
            const fotoGrid = document.getElementById('dp-foto-grid');
            if (fotoGrid) {
                fotoGrid.innerHTML = Object.entries(fotoLabels).map(([key, label]) => {
                    const url = d[key];
                    return `<div class="foto-thumb" ${url ? `onclick="openLightbox('${url}')"` : ''}>
                        ${url
                            ? `<img src="${url}" alt="${label}" loading="lazy">`
                            : `<div class="foto-placeholder">
                                <svg width="20" height="20" fill="none" stroke="#d1d5db" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span style="font-size:9px;color:#9ca3af;">Belum ada</span>
                               </div>`
                        }
                        <div class="foto-label">${label}</div>
                    </div>`;
                }).join('');
            }

            const dokWrap = document.getElementById('dp-dokumen-wrap');
            const dokLink = document.getElementById('dp-dokumen-link');
            if (dokWrap && dokLink) {
                if (d.dokumen_pendukung) { dokLink.href = d.dokumen_pendukung; dokWrap.style.display = 'block'; }
                else dokWrap.style.display = 'none';
            }

            // Tampilkan panel, sembunyikan tabel
            document.getElementById('tableCard').style.display = 'none';
            document.getElementById('detailPanel').style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function closeDetail() {
            document.getElementById('detailPanel').style.display = 'none';
            document.getElementById('tableCard').style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function openLightbox(src) {
            const lb = document.getElementById('dw-lightbox');
            document.getElementById('dw-lightbox-img').src = src;
            lb.style.display = 'flex';
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') document.getElementById('dw-lightbox').style.display = 'none'; });
    </script>

</x-app-layout>