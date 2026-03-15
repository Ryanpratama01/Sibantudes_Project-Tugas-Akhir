<x-app-layout>

<style>
.lp-wrap{max-width:1400px;margin:0 auto;padding:24px 20px 40px;}
.lp-card{background:#fff;border-radius:20px;border:1.5px solid #f1f5f9;box-shadow:0 2px 8px rgba(0,0,0,.05);overflow:hidden;margin-bottom:14px;}
.sum-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:14px;}
@media(max-width:860px){.sum-grid{grid-template-columns:1fr 1fr;}}
@media(max-width:560px){.sum-grid{grid-template-columns:1fr;}}
.sum-card{background:#fff;border-radius:18px;border:1.5px solid #f1f5f9;box-shadow:0 1px 6px rgba(0,0,0,.04);padding:16px 18px;}
.sum-icon{width:36px;height:36px;border-radius:11px;display:flex;align-items:center;justify-content:center;margin-bottom:10px;}
.sum-icon svg{width:18px;height:18px;}
.sum-val{font-size:24px;font-weight:900;color:#0f172a;line-height:1;font-family:'Georgia',serif;letter-spacing:-1px;}
.sum-val.sm{font-size:16px;letter-spacing:-.5px;line-height:1.3;}
.sum-lbl{font-size:10.5px;color:#94a3b8;font-weight:600;margin-top:3px;text-transform:uppercase;letter-spacing:.04em;}
.sec-hd{padding:12px 18px;border-bottom:1.5px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;justify-content:space-between;gap:10px;}
.sec-hd-l{display:flex;align-items:center;gap:8px;}
.sec-bar{width:3px;height:16px;background:linear-gradient(180deg,#3b82f6,#2563eb);border-radius:4px;}
.sec-title{font-size:13px;font-weight:800;color:#1e293b;}
.sec-count{font-size:11px;color:#94a3b8;background:#f1f5f9;padding:2px 8px;border-radius:20px;font-weight:600;}
.exp-row{display:flex;flex-wrap:wrap;gap:10px;align-items:center;padding:16px 18px;}
.exp-btn{display:inline-flex;align-items:center;gap:8px;padding:11px 20px;border-radius:13px;font-size:13px;font-weight:700;text-decoration:none;border:none;cursor:pointer;transition:filter .15s,transform .12s,box-shadow .15s;line-height:1;white-space:nowrap;letter-spacing:-.01em;}
.exp-btn svg{width:15px;height:15px;flex-shrink:0;}
.exp-btn:hover{filter:brightness(.93);transform:translateY(-1px);}
.btn-pdf{background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;box-shadow:0 4px 14px rgba(220,38,38,.35);}
.btn-excel{background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;box-shadow:0 4px 14px rgba(22,163,74,.35);}
.btn-send{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;box-shadow:0 4px 14px rgba(37,99,235,.35);}
.exp-meta{font-size:11px;color:#94a3b8;padding-left:4px;}
.pub-wrap{padding:16px 18px;}
.pub-form{display:grid;grid-template-columns:2fr 2fr auto;gap:10px;align-items:center;}
@media(max-width:860px){.pub-form{grid-template-columns:1fr;}}
.pub-input{padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:13px;outline:none;background:#fff;color:#0f172a;}
.pub-input:focus{border-color:#93c5fd;box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.pub-list{margin-top:16px;display:flex;flex-direction:column;gap:10px;}
.pub-item{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:12px 14px;border:1.5px solid #f1f5f9;border-radius:14px;background:#fafbfc;}
@media(max-width:760px){.pub-item{flex-direction:column;align-items:flex-start;}}
.pub-title{font-size:13px;font-weight:700;color:#0f172a;}
.pub-meta{font-size:11px;color:#94a3b8;margin-top:2px;}
.pub-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap;}
.btn-gray{padding:9px 14px;border:none;border-radius:12px;background:#e5e7eb;color:#374151;font-size:12px;font-weight:700;cursor:pointer;}
.lp-tbl-wrap{overflow-x:auto;}
table.lp-tbl{width:100%;border-collapse:collapse;}
table.lp-tbl thead tr{background:#f8fafc;border-bottom:1.5px solid #f1f5f9;}
table.lp-tbl thead th{padding:10px 12px;text-align:left;font-size:9.5px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap;}
table.lp-tbl tbody tr{border-bottom:1px solid #f8fafc;transition:background .1s;}
table.lp-tbl tbody tr:hover{background:#f0f7ff;}
table.lp-tbl tbody tr:last-child{border-bottom:none;}
table.lp-tbl tbody td{padding:10px 12px;vertical-align:middle;font-size:12.5px;color:#374151;}
.av{width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0;}
.name-cell{display:flex;align-items:center;gap:9px;}
.name-txt{font-size:12.5px;font-weight:700;color:#0f172a;white-space:nowrap;}
.nik-pill{font-family:'Courier New',monospace;font-size:10.5px;color:#64748b;background:#f1f5f9;padding:3px 7px;border-radius:6px;letter-spacing:.03em;}
.rt-badge{display:inline-flex;padding:3px 8px;border-radius:7px;background:#eff6ff;color:#1d4ed8;font-size:10.5px;font-weight:800;border:1px solid #bfdbfe;}
.bantuan-ya{display:inline-flex;padding:2px 9px;border-radius:20px;background:#fffbeb;color:#b45309;font-size:10.5px;font-weight:700;border:1px solid #fde68a;}
.bantuan-tdk{display:inline-flex;padding:2px 9px;border-radius:20px;background:#f8fafc;color:#94a3b8;font-size:10.5px;font-weight:600;}
.prob-cell{display:flex;align-items:center;gap:7px;}
.prob-bg{width:42px;height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;flex-shrink:0;}
.prob-fill{height:100%;border-radius:99px;}
.prob-txt{font-size:11.5px;font-weight:800;white-space:nowrap;}
.st-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:10.5px;font-weight:700;}
.st-dot{width:5px;height:5px;border-radius:50%;}
.empty-state{padding:56px 16px;text-align:center;}
.empty-icon{width:48px;height:48px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.empty-icon svg{width:22px;height:22px;}
.empty-txt{font-size:13px;font-weight:600;color:#94a3b8;}
.tbl-foot{padding:10px 18px;border-top:1.5px solid #f1f5f9;background:#fafbfc;text-align:center;}
.credit{font-size:11px;color:#cbd5e1;font-weight:500;letter-spacing:.03em;}
.flash-ok{display:flex;align-items:center;gap:8px;background:#f0fdf4;border:1.5px solid #bbf7d0;color:#166534;padding:12px 16px;border-radius:13px;font-size:12.5px;font-weight:600;margin-bottom:14px;}
.flash-ok svg{width:16px;height:16px;flex-shrink:0;}
</style>

<div class="lp-wrap">

    <div style="margin-bottom:18px;">
        <h1 style="font-size:20px;font-weight:900;color:#0f172a;line-height:1.2;letter-spacing:-.03em;">
            Laporan Penerima BLT-DD
        </h1>
        <p style="font-size:11.5px;color:#94a3b8;margin-top:4px;">
            Kelurahan Ngerong — warga yang telah ditetapkan sebagai penerima final BLT Dana Desa
        </p>
    </div>

    @if(session('success'))
        <div class="flash-ok">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
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

    <div class="sum-grid">
        <div class="sum-card">
            <div class="sum-icon" style="background:#eff6ff;">
                <svg fill="none" stroke="#2563eb" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="sum-val">{{ $totalPenerima }}</div>
            <div class="sum-lbl">Total Penerima</div>
        </div>

        <div class="sum-card">
            <div class="sum-icon" style="background:#f0fdf4;">
                <svg fill="none" stroke="#16a34a" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="sum-val sm">Rp {{ number_format($totalBantuan, 0, ',', '.') }}</div>
            <div class="sum-lbl">Total Dana Tersalurkan</div>
        </div>

        <div class="sum-card">
            <div class="sum-icon" style="background:#fffbeb;">
                <svg fill="none" stroke="#d97706" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="sum-val">{{ number_format($avgProb, 1) }}<span style="font-size:14px;font-weight:700;">%</span></div>
            <div class="sum-lbl">Rata-rata Probabilitas</div>
        </div>

        <div class="sum-card">
            <div class="sum-icon" style="background:#fff1f2;">
                <svg fill="none" stroke="#e11d48" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="sum-val">{{ $periodes }}</div>
            <div class="sum-lbl">Periode Aktif</div>
        </div>
    </div>

    <div class="lp-card">
        <div class="sec-hd">
            <div class="sec-hd-l">
                <div class="sec-bar"></div>
                <span class="sec-title">Ekspor & Distribusi Data</span>
            </div>
        </div>

        <div class="exp-row">
            <a href="{{ route('admin.laporan.pdf') }}" class="exp-btn btn-pdf">Unduh PDF</a>
            <a href="{{ route('admin.laporan.excel') }}" class="exp-btn btn-excel">Unduh Excel</a>

            <form action="{{ route('admin.laporan.kirim-ke-rt') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit"
                        onclick="return confirm('Kirim semua data penerima final ke RT dusun?')"
                        class="exp-btn btn-send">
                    Kirim ke RT Dusun
                </button>
            </form>

            <span class="exp-meta">{{ $totalPenerima }} penerima terdaftar</span>
        </div>
    </div>

    <div class="lp-card">
        <div class="sec-hd">
            <div class="sec-hd-l">
                <div class="sec-bar"></div>
                <span class="sec-title">Upload PDF Laporan Publik</span>
            </div>
        </div>

        <div class="pub-wrap">
            <form action="{{ route('admin.laporan.upload-publik-pdf') }}" method="POST" enctype="multipart/form-data" class="pub-form">
                @csrf
                <input type="text" name="judul" placeholder="Judul laporan publik" required class="pub-input">
                <input type="file" name="file_pdf" accept="application/pdf" required class="pub-input">
                <button type="submit" class="exp-btn btn-send" style="justify-content:center;">
                    Upload PDF Publik
                </button>
            </form>

            @if(isset($laporanPubliks) && $laporanPubliks->count() > 0)
                <div class="pub-list">
                    @foreach($laporanPubliks as $pdf)
                        <div class="pub-item">
                            <div>
                                <div class="pub-title">{{ $pdf->judul }}</div>
                                <div class="pub-meta">
                                    Diupload: {{ $pdf->created_at->format('d-m-Y H:i') }}
                                </div>
                            </div>

                            <div class="pub-actions">
                                <a href="{{ asset('storage/' . $pdf->file_path) }}"
                                   target="_blank"
                                   class="exp-btn btn-pdf"
                                   style="padding:9px 14px;">
                                    Lihat PDF
                                </a>

                                <form action="{{ route('admin.laporan.hapus-publik-pdf', $pdf->id) }}" method="POST" onsubmit="return confirm('Hapus PDF publik ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-gray">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="lp-card">
        <div class="sec-hd">
            <div class="sec-hd-l">
                <div class="sec-bar"></div>
                <span class="sec-title">Daftar Penerima Final</span>
            </div>
            <span class="sec-count">{{ $totalPenerima }} data</span>
        </div>

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
                        <th>Bantuan Lain</th>
                        <th>Usia</th>
                        <th>Probabilitas</th>
                        <th>Status</th>
                        <th>Tgl Penetapan</th>
                        <th>Periode</th>
                        <th>Jumlah Bantuan</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($laporans as $index => $item)
                    @php
                        $nama = $item->nama_lengkap ?? '-';
                        $init = strtoupper(substr($nama, 0, 1));
                        $prob = $item->probability ?? 0;
                        if ($prob <= 1) $prob = $prob * 100;

                        $sc   = $prob >= 70 ? '#10b981' : ($prob >= 40 ? '#f59e0b' : '#f43f5e');
                        $scBg = $prob >= 70 ? '#f0fdf4' : ($prob >= 40 ? '#fffbeb' : '#fff1f2');
                        $scBd = $prob >= 70 ? '#bbf7d0' : ($prob >= 40 ? '#fde68a' : '#fecdd3');

                        $bantuanLain = strtolower($item->bantuan_lain ?? 'tidak');
                    @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            <div class="name-cell">
                                <div class="av">{{ $init }}</div>
                                <span class="name-txt">{{ $nama }}</span>
                            </div>
                        </td>

                        <td><span class="nik-pill">{{ $item->nik ?? '-' }}</span></td>
                        <td><span class="nik-pill">{{ $item->no_kk ?? '-' }}</span></td>

                        <td>
                            <span class="rt-badge">
                                RT {{ str_pad($item->nomor_rt ?? '0', 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <td>{{ $item->nama_dusun ?? '-' }}</td>
                        <td>{{ $item->pekerjaan ?? '-' }}</td>
                        <td>Rp {{ number_format($item->penghasilan ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $item->jumlah_tanggungan ?? 0 }}</td>
                        <td>{{ $item->aset_kepemilikan ?? '-' }}</td>

                        <td>
                            @if($bantuanLain === 'ya')
                                <span class="bantuan-ya">Ya</span>
                            @else
                                <span class="bantuan-tdk">Tidak</span>
                            @endif
                        </td>

                        <td>{{ $item->usia ?? 0 }}</td>

                        <td>
                            <div class="prob-cell">
                                <div class="prob-bg">
                                    <div class="prob-fill" style="width:{{ min($prob, 100) }}%;background:{{ $sc }};"></div>
                                </div>
                                <span class="prob-txt" style="color:{{ $sc }};">{{ number_format($prob, 1) }}%</span>
                            </div>
                        </td>

                        <td>
                            <span class="st-pill" style="background:{{ $scBg }};color:{{ $sc }};border:1px solid {{ $scBd }};">
                                <span class="st-dot" style="background:{{ $sc }};"></span>
                                {{ ucfirst($item->status_verifikasi ?? '-') }}
                            </span>
                        </td>

                        <td>{{ $item->tanggal_penetapan?->format('d-m-Y') ?? '-' }}</td>
                        <td>{{ $item->periode_bantuan ?? '-' }}</td>
                        <td>Rp {{ number_format($item->jumlah_bantuan ?? 300000, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="17">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="#d1d5db" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="empty-txt">Belum ada data penerima final</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="tbl-foot">
            <span class="credit">SiBantuDes · Kelurahan Ngerong</span>
        </div>
    </div>

</div>

</x-app-layout> 