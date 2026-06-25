<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <div>
                <h2 style="font-size:17px;font-weight:800;color:#111827;line-height:1.2;">Data Calon Penerima</h2>
                <p style="font-size:11px;color:#9ca3af;margin-top:2px;">Daftar seluruh warga yang telah didaftarkan</p>
            </div>
            <a href="{{ route('rt.calon-penerima.create') }}"
               style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:#2563eb;color:#fff;font-size:13px;font-weight:700;border-radius:12px;text-decoration:none;box-shadow:0 3px 10px rgba(37,99,235,.28);">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Data Warga
            </a>
        </div>
    </x-slot>

    <style>
        .flash{display:flex;align-items:center;gap:10px;border-radius:12px;padding:9px 14px;margin-bottom:10px;font-size:12px;font-weight:500;}
        .flash svg{width:14px;height:14px;flex-shrink:0;}
        .idx-card{background:#fff;border-radius:18px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04),0 4px 16px rgba(0,0,0,.03);overflow:hidden;}
        .tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
        table.idx-tbl{width:100%;border-collapse:collapse;min-width:680px;}
        table.idx-tbl thead tr{background:#f8fafc;border-bottom:1.5px solid #f1f5f9;}
        table.idx-tbl thead th{padding:10px 14px;text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap;}
        table.idx-tbl tbody tr{border-bottom:1px solid #f8fafc;transition:background .12s;}
        table.idx-tbl tbody tr:hover{background:#f0f7ff;}
        table.idx-tbl tbody tr:last-child{border-bottom:none;}
        table.idx-tbl tbody td{padding:11px 14px;vertical-align:middle;}
        .mob-list{display:none;flex-direction:column;gap:12px;padding:14px;}
        @media(max-width:640px){.tbl-wrap{display:none;}.mob-list{display:flex;}}
        .mob-card{background:#fafbfc;border:1.5px solid #f1f5f9;border-radius:16px;padding:14px;}
        .mob-top{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
        .mob-name{font-size:14px;font-weight:800;color:#111827;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
        .mob-sub{font-size:10.5px;color:#94a3b8;margin-top:1px;font-family:monospace;}
        .mob-row{display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:10px;}
        .mob-actions{display:flex;gap:8px;flex-wrap:wrap;padding-top:10px;border-top:1px solid #f1f5f9;}
        .mob-actions .btn-act{flex:1;justify-content:center;padding:9px 10px;font-size:12px;}
        .av{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff;flex-shrink:0;background:linear-gradient(135deg,#3b82f6,#2563eb);}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap;}
        .dot{width:6px;height:6px;border-radius:50%;flex-shrink:0;}
        .badge-rt{display:inline-flex;align-items:center;padding:3px 9px;border-radius:7px;background:#eff6ff;color:#1d4ed8;font-size:11px;font-weight:700;border:1px solid #bfdbfe;}
        .trk-draft{background:#f3f4f6;color:#374151;}.trk-draft .dot{background:#9ca3af;}
        .trk-terkirim{background:#eff6ff;color:#1d4ed8;}.trk-terkirim .dot{background:#3b82f6;}
        .trk-validasi{background:#fffbeb;color:#b45309;}.trk-validasi .dot{background:#f59e0b;}
        .trk-selesai{background:#f0fdf4;color:#166534;}.trk-selesai .dot{background:#22c55e;}
        .trk-ditinjau{background:#f5f3ff;color:#6d28d9;border:1px solid #ddd6fe;}.trk-ditinjau .dot{background:#7c3aed;}
        .st-pending{background:#fffbeb;color:#b45309;}.st-pending .dot{background:#f59e0b;}
        .st-disetujui{background:#f0fdf4;color:#166534;}.st-disetujui .dot{background:#22c55e;}
        .st-ditolak{background:#fff1f2;color:#9f1239;}.st-ditolak .dot{background:#f43f5e;}
        .btn-act{display:inline-flex;align-items:center;gap:5px;padding:7px 12px;border-radius:9px;font-size:12px;font-weight:700;text-decoration:none;border:none;cursor:pointer;white-space:nowrap;line-height:1.4;}
        .btn-act svg{width:13px;height:13px;flex-shrink:0;}
        .btn-detail{background:#2563eb;color:#fff;box-shadow:0 2px 8px rgba(37,99,235,.25);}
        .btn-detail:hover{background:#1d4ed8;}
        .btn-edit{background:#eff6ff;color:#1d4ed8;}.btn-edit:hover{background:#dbeafe;}
        .btn-ajukan{background:#f0fdf4;color:#166534;}.btn-ajukan:hover{background:#dcfce7;}
        .btn-hapus{background:#fff1f2;color:#9f1239;}.btn-hapus:hover{background:#ffe4e6;}
        .empty-state{padding:48px 16px;text-align:center;}
        .empty-icon{width:44px;height:44px;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;}
        .empty-icon svg{width:20px;height:20px;stroke:#d1d5db;}
        .pagi{padding:10px 16px;background:#fafafa;border-top:1px solid #f1f5f9;}
        .hasil-hint{display:flex;align-items:center;gap:8px;background:#fef9c3;border:1.5px solid #fde047;border-radius:10px;padding:8px 12px;margin-top:6px;}
        .hasil-hint svg{width:14px;height:14px;stroke:#ca8a04;flex-shrink:0;}
        .hasil-hint span{font-size:11px;font-weight:700;color:#92400e;}
        .btn-detail-selesai{background:linear-gradient(135deg,#10b981,#059669);color:#fff;box-shadow:0 3px 10px rgba(16,185,129,.3);animation:glow 2s ease-in-out infinite;}
        .btn-detail-selesai:hover{filter:brightness(1.1);}
        @keyframes glow{0%,100%{box-shadow:0 3px 10px rgba(16,185,129,.3);}50%{box-shadow:0 3px 18px rgba(16,185,129,.6);}}
    </style>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="flash" style="background:#f0fdf4;border:1.5px solid #bbf7d0;color:#166534;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flash" style="background:#fff1f2;border:1.5px solid #fecdd3;color:#9f1239;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="idx-card">

        {{-- HEADER BAR --}}
        <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:3px;height:16px;background:#2563eb;border-radius:4px;"></div>
                <span style="font-size:13px;font-weight:700;color:#374151;">
                    Total <span style="color:#2563eb;">{{ $calonPenerimas->total() }}</span> data terdaftar
                </span>
            </div>
            <span style="font-size:11px;color:#9ca3af;">
                Halaman {{ $calonPenerimas->currentPage() }} / {{ $calonPenerimas->lastPage() }}
            </span>
        </div>

        {{-- ── DESKTOP TABLE ── --}}
        <div class="tbl-wrap">
            <table class="idx-tbl">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>RT</th>
                        <th>Dusun</th>
                        <th>Tracking</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($calonPenerimas as $index => $item)
                        @php $trk = $item->tracking_status ?? 'draft'; @endphp
                        <tr>
                            <td style="font-size:11px;color:#9ca3af;font-weight:600;text-align:center;">
                                {{ $calonPenerimas->firstItem() + $index }}
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:9px;">
                                    <div class="av">{{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}</div>
                                    <span style="font-size:13px;font-weight:600;color:#111827;">{{ $item->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td style="font-family:monospace;font-size:11.5px;color:#6b7280;">{{ $item->nik }}</td>
                            <td><span class="badge-rt">RT {{ str_pad($item->rt->nomor_rt ?? '-', 3, '0', STR_PAD_LEFT) }}</span></td>
                            <td style="font-size:12px;color:#6b7280;">{{ $item->rt->dusun->nama_dusun ?? '-' }}</td>
                            <td>
                                @if($trk==='draft') <span class="badge trk-draft"><span class="dot"></span>Draft</span>
                                @elseif($trk==='terkirim') <span class="badge trk-terkirim"><span class="dot"></span>Terkirim</span>
                                @elseif($trk==='sedang_validasi') <span class="badge trk-validasi"><span class="dot"></span>Validasi</span>
                                @elseif($trk==='selesai') <span class="badge trk-selesai"><span class="dot"></span>Selesai</span>
                                @elseif($trk==='ditinjau_ulang') <span class="badge trk-ditinjau"><span class="dot"></span>Ditinjau Ulang</span>
                                @else <span class="badge trk-draft"><span class="dot"></span>—</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status_verifikasi==='pending') <span class="badge st-pending"><span class="dot"></span>Pending</span>
                                @elseif($item->status_verifikasi==='disetujui') <span class="badge st-disetujui"><span class="dot"></span>Disetujui</span>
                                @else <span class="badge st-ditolak"><span class="dot"></span>Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;flex-direction:column;gap:5px;align-items:flex-start;">
                                    <div style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;">

                                        <a href="{{ route('rt.calon-penerima.show', $item->id) }}"
                                           class="btn-act {{ in_array($trk,['selesai','sedang_validasi']) ? 'btn-detail-selesai' : 'btn-detail' }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Lihat Detail
                                        </a>

                                        @if($trk==='draft')
                                            <a href="{{ route('rt.calon-penerima.edit', $item->id) }}" class="btn-act btn-edit">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('rt.calon-penerima.ajukan', $item->id) }}"
                                                  onsubmit="return confirm('Yakin ajukan data {{ addslashes($item->nama_lengkap) }}? Setelah diajukan data tidak bisa diubah.')">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn-act btn-ajukan">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Ajukan
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('rt.calon-penerima.destroy', $item->id) }}"
                                                  onsubmit="return confirm('Yakin hapus data {{ addslashes($item->nama_lengkap) }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-act btn-hapus">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    {{-- Hint teks di bawah tombol --}}
                                    @if($trk==='selesai')
                                        <div class="hasil-hint">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                                            <span>Hasil sudah ada — klik Lihat Detail</span>
                                        </div>
                                    @elseif($trk==='sedang_validasi')
                                        <div class="hasil-hint" style="background:#eff6ff;border-color:#bfdbfe;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke:#2563eb;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span style="color:#1e40af;">Sedang diproses admin</span>
                                        </div>
                                    @elseif($trk==='ditinjau_ulang')
                                        <div class="hasil-hint" style="background:#f5f3ff;border-color:#ddd6fe;">
                                            <svg fill="none" stroke="#7c3aed" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span style="color:#6d28d9;">Data sedang ditinjau ulang oleh admin</span>
                                        </div>
                                    @elseif($trk==='draft')
                                        <div style="font-size:10.5px;color:#94a3b8;margin-top:2px;">← Cek detail dulu, lalu ajukan</div>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <p style="font-size:13px;font-weight:600;color:#6b7280;margin-bottom:6px;">Belum ada data calon penerima</p>
                                    <a href="{{ route('rt.calon-penerima.create') }}" style="font-size:12px;color:#2563eb;font-weight:600;text-decoration:none;">+ Tambah sekarang</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── MOBILE CARD LIST ── --}}
        <div class="mob-list">
        @forelse($calonPenerimas as $index => $item)
            @php $trk = $item->tracking_status ?? 'draft'; @endphp
            <div class="mob-card">
                <div class="mob-top">
                    <div class="av">{{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div class="mob-name">{{ $item->nama_lengkap }}</div>
                        <div class="mob-sub">{{ $item->nik }}</div>
                    </div>
                    <span style="font-size:10px;color:#94a3b8;font-weight:700;">#{{ $calonPenerimas->firstItem() + $index }}</span>
                </div>

                <div class="mob-row">
                    <span class="badge-rt">RT {{ str_pad($item->rt->nomor_rt ?? '-', 3, '0', STR_PAD_LEFT) }}</span>
                    <span style="font-size:11px;color:#6b7280;">{{ $item->rt->dusun->nama_dusun ?? '-' }}</span>
                    @if($trk==='draft') <span class="badge trk-draft"><span class="dot"></span>Draft</span>
                    @elseif($trk==='terkirim') <span class="badge trk-terkirim"><span class="dot"></span>Terkirim</span>
                    @elseif($trk==='sedang_validasi') <span class="badge trk-validasi"><span class="dot"></span>Sedang Diproses</span>
                    @elseif($trk==='selesai') <span class="badge trk-selesai"><span class="dot"></span>Selesai</span>
                    @elseif($trk==='ditinjau_ulang') <span class="badge trk-ditinjau"><span class="dot"></span>Ditinjau Ulang</span>
                    @endif
                    @if($item->status_verifikasi==='pending') <span class="badge st-pending"><span class="dot"></span>Pending</span>
                    @elseif($item->status_verifikasi==='disetujui') <span class="badge st-disetujui"><span class="dot"></span>Disetujui ✓</span>
                    @else <span class="badge st-ditolak"><span class="dot"></span>Ditolak</span>
                    @endif
                </div>

                {{-- Kotak info kontekstual --}}
                @if($trk==='selesai')
                    <div style="background:#fef9c3;border:1.5px solid #fde047;border-radius:10px;padding:10px 12px;margin-bottom:10px;display:flex;align-items:center;gap:8px;">
                        <span style="font-size:20px;">📋</span>
                        <div>
                            <div style="font-size:12px;font-weight:800;color:#92400e;">Hasil sudah keluar!</div>
                            <div style="font-size:11px;color:#a16207;margin-top:1px;">Tekan tombol <b>Lihat Detail</b> di bawah untuk melihat hasilnya</div>
                        </div>
                    </div>
                @elseif($trk==='sedang_validasi')
                    <div style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:10px;padding:10px 12px;margin-bottom:10px;display:flex;align-items:center;gap:8px;">
                        <span style="font-size:20px;">⏳</span>
                        <div>
                            <div style="font-size:12px;font-weight:800;color:#1e40af;">Sedang diproses admin</div>
                            <div style="font-size:11px;color:#3b82f6;margin-top:1px;">Mohon tunggu, hasil akan muncul setelah admin selesai</div>
                        </div>
                    </div>
                @elseif($trk==='ditinjau_ulang')
                    <div style="background:#f5f3ff;border:1.5px solid #ddd6fe;border-radius:10px;padding:10px 12px;margin-bottom:10px;display:flex;align-items:center;gap:8px;">
                        <span style="font-size:20px;">🔍</span>
                        <div>
                            <div style="font-size:12px;font-weight:800;color:#6d28d9;">Data sedang ditinjau ulang</div>
                            <div style="font-size:11px;color:#7c3aed;margin-top:1px;">Admin perangkat desa sedang meninjau kembali data ini</div>
                        </div>
                    </div>
                @elseif($trk==='draft')
                    <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:10px 12px;margin-bottom:10px;display:flex;align-items:center;gap:8px;">
                        <span style="font-size:20px;">👆</span>
                        <div>
                            <div style="font-size:12px;font-weight:800;color:#166534;">Cek dulu, lalu ajukan</div>
                            <div style="font-size:11px;color:#16a34a;margin-top:1px;">Tekan <b>Lihat Detail</b> untuk cek data, lalu tekan <b>Ajukan</b></div>
                        </div>
                    </div>
                @endif

                <div class="mob-actions">
                    <a href="{{ route('rt.calon-penerima.show', $item->id) }}"
                       class="btn-act {{ in_array($trk,['selesai','sedang_validasi']) ? 'btn-detail-selesai' : 'btn-detail' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Detail
                    </a>
                    @if($trk==='draft')
                        <a href="{{ route('rt.calon-penerima.edit', $item->id) }}" class="btn-act btn-edit">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('rt.calon-penerima.ajukan', $item->id) }}"
                              onsubmit="return confirm('Yakin ajukan data {{ addslashes($item->nama_lengkap) }}?')"
                              style="display:contents;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-act btn-ajukan">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Ajukan
                            </button>
                        </form>
                        <form method="POST" action="{{ route('rt.calon-penerima.destroy', $item->id) }}"
                              onsubmit="return confirm('Yakin hapus data {{ addslashes($item->nama_lengkap) }}?')"
                              style="display:contents;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-act btn-hapus">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p style="font-size:13px;font-weight:600;color:#6b7280;margin-bottom:6px;">Belum ada data calon penerima</p>
                <a href="{{ route('rt.calon-penerima.create') }}" style="font-size:12px;color:#2563eb;font-weight:600;text-decoration:none;">+ Tambah sekarang</a>
            </div>
        @endforelse
        </div>

        <div class="pagi">{{ $calonPenerimas->links() }}</div>

    </div>

</x-app-layout>