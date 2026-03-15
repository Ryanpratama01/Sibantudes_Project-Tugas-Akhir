<x-app-layout>

<style>
.lk-wrap{max-width:1200px;margin:0 auto;padding:24px 20px 40px;}

/* Card */
.lk-card{background:#fff;border-radius:20px;border:1.5px solid #f1f5f9;box-shadow:0 2px 8px rgba(0,0,0,.05);overflow:hidden;margin-bottom:14px;}

/* Section header */
.sec-hd{padding:12px 18px;border-bottom:1.5px solid #f1f5f9;background:#fafbfc;display:flex;align-items:center;justify-content:space-between;}
.sec-hd-l{display:flex;align-items:center;gap:8px;}
.sec-bar{width:3px;height:16px;background:linear-gradient(180deg,#3b82f6,#2563eb);border-radius:4px;}
.sec-title{font-size:13px;font-weight:800;color:#1e293b;}
.sec-count{font-size:11px;color:#94a3b8;background:#f1f5f9;padding:2px 8px;border-radius:20px;font-weight:600;}

/* Summary */
.sum-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:14px;}
@media(max-width:700px){.sum-grid{grid-template-columns:1fr 1fr;}}
.sum-card{background:#fff;border-radius:18px;border:1.5px solid #f1f5f9;box-shadow:0 1px 6px rgba(0,0,0,.04);padding:14px 16px;}
.sum-icon{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:9px;}
.sum-icon svg{width:17px;height:17px;}
.sum-val{font-size:22px;font-weight:900;color:#0f172a;line-height:1;letter-spacing:-.03em;}
.sum-lbl{font-size:10px;color:#94a3b8;font-weight:700;margin-top:3px;text-transform:uppercase;letter-spacing:.04em;}

/* Table */
.lk-tbl-wrap{overflow-x:auto;}
table.lk-tbl{width:100%;border-collapse:collapse;}
table.lk-tbl thead tr{background:#f8fafc;border-bottom:1.5px solid #f1f5f9;}
table.lk-tbl thead th{padding:10px 11px;text-align:left;font-size:9.5px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;}
table.lk-tbl tbody tr{border-bottom:1px solid #f8fafc;transition:background .1s;}
table.lk-tbl tbody tr:hover{background:#f0f7ff;}
table.lk-tbl tbody tr:last-child{border-bottom:none;}
table.lk-tbl tbody td{padding:9px 11px;vertical-align:middle;font-size:12px;color:#374151;}

/* Cells */
.av{width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0;}
.name-cell{display:flex;align-items:center;gap:8px;}
.nik-pill{font-family:'Courier New',monospace;font-size:10px;color:#64748b;background:#f1f5f9;padding:2px 6px;border-radius:5px;}
.rt-badge{display:inline-flex;padding:2px 7px;border-radius:6px;background:#eff6ff;color:#1d4ed8;font-size:10px;font-weight:800;border:1px solid #bfdbfe;}
.prob-cell{display:flex;align-items:center;gap:6px;}
.prob-bg{width:38px;height:4px;background:#f1f5f9;border-radius:99px;overflow:hidden;flex-shrink:0;}
.prob-fill{height:100%;border-radius:99px;}
.st-pill{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:10.5px;font-weight:700;}
.st-dot{width:5px;height:5px;border-radius:50%;flex-shrink:0;}

/* Empty */
.empty-state{padding:52px 16px;text-align:center;}
.empty-icon{width:46px;height:46px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;}
.empty-icon svg{width:20px;height:20px;}

/* Footer */
.tbl-foot{padding:9px 18px;border-top:1.5px solid #f1f5f9;background:#fafbfc;text-align:center;}
.credit{font-size:11px;color:#cbd5e1;font-weight:500;}
</style>

<div class="lk-wrap">

    {{-- ── HEADER ── --}}
    <div style="margin-bottom:18px;">
        <h1 style="font-size:20px;font-weight:900;color:#0f172a;letter-spacing:-.03em;line-height:1.2;">
            Laporan Hasil Kelayakan
        </h1>
        <p style="font-size:11.5px;color:#94a3b8;margin-top:4px;">
            Data hasil akhir verifikasi warga yang telah dikirim oleh admin kelurahan
        </p>
    </div>

    {{-- ── SUMMARY ── --}}
    @php
        $total    = $stats['total'] ?? $laporans->count();
        $diterima = $stats['diterima'] ?? $laporans->where('status_verifikasi', 'disetujui')->count();
        $ditolak  = $stats['ditolak'] ?? $laporans->where('status_verifikasi', 'ditolak')->count();
    @endphp

    <div class="sum-grid">
        <div class="sum-card">
            <div class="sum-icon" style="background:#eff6ff;">
                <svg fill="none" stroke="#2563eb" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="sum-val">{{ $total }}</div>
            <div class="sum-lbl">Total Warga</div>
        </div>
        <div class="sum-card">
            <div class="sum-icon" style="background:#f0fdf4;">
                <svg fill="none" stroke="#16a34a" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="sum-val" style="color:#16a34a;">{{ $diterima }}</div>
            <div class="sum-lbl">Diterima</div>
        </div>
        <div class="sum-card">
            <div class="sum-icon" style="background:#fff1f2;">
                <svg fill="none" stroke="#e11d48" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="sum-val" style="color:#e11d48;">{{ $ditolak }}</div>
            <div class="sum-lbl">Ditolak</div>
        </div>
    </div>

    {{-- ── TABLE ── --}}
    <div class="lk-card">
        <div class="sec-hd">
            <div class="sec-hd-l">
                <div class="sec-bar"></div>
                <span class="sec-title">Daftar Hasil Verifikasi Warga</span>
            </div>
            <span class="sec-count">{{ $total }} data</span>
        </div>

        <div class="lk-tbl-wrap">
            <table class="lk-tbl">
                <thead>
                    <tr>
                        <th style="width:32px;">#</th>
                        <th>Nama</th>
                        <th>NIK</th>
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
                    </tr>
                </thead>
                <tbody>
                @forelse($laporans as $i => $item)
                    @php
                        $prob = $item->probability ?? 0;
                        if ($prob <= 1) $prob = $prob * 100;

                        $nama    = $item->nama_lengkap ?? '-';
                        $init    = strtoupper(substr($nama, 0, 1));
                        $sc      = $prob >= 70 ? '#10b981' : ($prob >= 40 ? '#f59e0b' : '#f43f5e');

                        $colors  = ['#3b82f6','#8b5cf6','#ec4899','#f59e0b','#10b981','#ef4444','#06b6d4'];
                        $avColor = $colors[ord($init) % count($colors)];

                        $diterima = $item->status_verifikasi === 'disetujui';
                    @endphp
                    <tr>
                        <td style="text-align:center;font-size:10.5px;color:#94a3b8;font-weight:700;">{{ $i + 1 }}</td>

                        <td>
                            <div class="name-cell">
                                <div class="av" style="background:{{ $avColor }};">{{ $init }}</div>
                                <div>
                                    <div style="font-size:12.5px;font-weight:700;color:#0f172a;">{{ $nama }}</div>
                                </div>
                            </div>
                        </td>

                        <td><span class="nik-pill">{{ $item->nik ?? '-' }}</span></td>

                        <td>
                            <span class="rt-badge">
                                RT {{ str_pad($item->nomor_rt ?? '-', 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <td style="font-size:11.5px;white-space:nowrap;">
                            {{ $item->nama_dusun ?? '-' }}
                        </td>

                        <td style="font-size:11.5px;max-width:110px;">{{ $item->pekerjaan ?? '-' }}</td>

                        <td style="font-size:12px;font-weight:600;color:#0f172a;white-space:nowrap;">
                            Rp {{ number_format($item->penghasilan ?? 0, 0, ',', '.') }}
                        </td>

                        <td style="text-align:center;font-size:12px;font-weight:600;">
                            {{ $item->jumlah_tanggungan ?? '-' }}
                        </td>

                        <td style="font-size:11.5px;max-width:110px;">{{ $item->aset_kepemilikan ?? '-' }}</td>

                        <td>
                            @if(strtolower($item->bantuan_lain ?? '') === 'ya')
                                <span style="display:inline-flex;padding:2px 8px;border-radius:20px;background:#fffbeb;color:#b45309;font-size:10px;font-weight:700;border:1px solid #fde68a;">Ya</span>
                            @else
                                <span style="display:inline-flex;padding:2px 8px;border-radius:20px;background:#f8fafc;color:#94a3b8;font-size:10px;font-weight:600;">Tidak</span>
                            @endif
                        </td>

                        <td style="text-align:center;font-size:12px;color:#374151;">{{ $item->usia ?? '-' }}</td>

                        <td>
                            <div class="prob-cell">
                                <div class="prob-bg">
                                    <div class="prob-fill" style="width:{{ min($prob,100) }}%;background:{{ $sc }};"></div>
                                </div>
                                <span style="font-size:11px;font-weight:800;color:{{ $sc }};white-space:nowrap;">
                                    {{ number_format($prob, 1) }}%
                                </span>
                            </div>
                        </td>

                        <td>
                            @if($diterima)
                                <span class="st-pill" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;">
                                    <span class="st-dot" style="background:#22c55e;"></span>
                                    Diterima
                                </span>
                            @else
                                <span class="st-pill" style="background:#fff1f2;color:#be123c;border:1px solid #fecdd3;">
                                    <span class="st-dot" style="background:#f43f5e;"></span>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="#d1d5db" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p style="font-size:13px;font-weight:600;color:#94a3b8;">Belum ada laporan yang dikirim admin</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="tbl-foot">
            <span class="credit">SiBantuDes &middot; Kelurahan Ngerong</span>
        </div>
    </div>

</div>

</x-app-layout>