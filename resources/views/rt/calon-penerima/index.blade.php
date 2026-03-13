<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
            <div>
                <h2 style="font-size:17px;font-weight:800;color:#111827;line-height:1.2;">Data Calon Penerima</h2>
                <p style="font-size:11px;color:#9ca3af;margin-top:2px;">Daftar seluruh warga yang telah didaftarkan</p>
            </div>
            <a href="{{ route('rt.calon-penerima.create') }}"
               style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;background:#2563eb;color:#fff;font-size:12px;font-weight:700;border-radius:11px;text-decoration:none;box-shadow:0 3px 10px rgba(37,99,235,.28);">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Data
            </a>
        </div>
    </x-slot>

    <style>
        /* Flash */
        .flash{display:flex;align-items:center;gap:10px;border-radius:12px;padding:9px 14px;margin-bottom:10px;font-size:12px;font-weight:500;}
        .flash svg{width:14px;height:14px;flex-shrink:0;}

        /* Card */
        .idx-card{background:#fff;border-radius:18px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04),0 4px 16px rgba(0,0,0,.03);overflow:hidden;}

        /* Table */
        table{width:100%;border-collapse:collapse;}
        thead tr{background:#f8fafc;border-bottom:1.5px solid #f1f5f9;}
        thead th{padding:10px 14px;text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap;}
        tbody tr{border-bottom:1px solid #f8fafc;transition:background .12s;}
        tbody tr:hover{background:#f0f7ff;}
        tbody tr:last-child{border-bottom:none;}
        tbody td{padding:11px 14px;vertical-align:middle;}

        /* Avatar */
        .av{width:32px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0;background:linear-gradient(135deg,#3b82f6,#2563eb);}

        /* Badges */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:10.5px;font-weight:700;white-space:nowrap;}
        .dot{width:5px;height:5px;border-radius:50%;flex-shrink:0;}
        .badge-rt{display:inline-flex;align-items:center;padding:2px 8px;border-radius:7px;background:#eff6ff;color:#1d4ed8;font-size:11px;font-weight:700;border:1px solid #bfdbfe;}

        /* Tracking badges */
        .trk-draft     {background:#f3f4f6;color:#374151;}  .trk-draft .dot{background:#9ca3af;}
        .trk-terkirim  {background:#eff6ff;color:#1d4ed8;}  .trk-terkirim .dot{background:#3b82f6;}
        .trk-validasi  {background:#fffbeb;color:#b45309;}  .trk-validasi .dot{background:#f59e0b;}
        .trk-selesai   {background:#f0fdf4;color:#166534;}  .trk-selesai .dot{background:#22c55e;}

        /* Status badges */
        .st-pending    {background:#fffbeb;color:#b45309;}  .st-pending .dot{background:#f59e0b;}
        .st-disetujui  {background:#f0fdf4;color:#166534;}  .st-disetujui .dot{background:#22c55e;}
        .st-ditolak    {background:#fff1f2;color:#9f1239;}  .st-ditolak .dot{background:#f43f5e;}

        /* Action buttons */
        .btn-act{display:inline-flex;align-items:center;gap:4px;padding:4px 9px;border-radius:8px;font-size:10.5px;font-weight:600;text-decoration:none;border:none;cursor:pointer;white-space:nowrap;line-height:1.4;}
        .btn-act svg{width:11px;height:11px;flex-shrink:0;}
        .btn-detail{background:#f3f4f6;color:#374151;}   .btn-detail:hover{background:#e5e7eb;}
        .btn-edit  {background:#eff6ff;color:#1d4ed8;}   .btn-edit:hover{background:#dbeafe;}
        .btn-ajukan{background:#f0fdf4;color:#166534;}   .btn-ajukan:hover{background:#dcfce7;}
        .btn-hapus {background:#fff1f2;color:#9f1239;}   .btn-hapus:hover{background:#ffe4e6;}

        /* Empty state */
        .empty-state{padding:48px 16px;text-align:center;}
        .empty-icon{width:44px;height:44px;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;}
        .empty-icon svg{width:20px;height:20px;stroke:#d1d5db;}

        /* Pagination wrapper */
        .pagi{padding:10px 16px;background:#fafafa;border-top:1px solid #f1f5f9;}
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
        <div style="padding:10px 16px;border-bottom:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;justify-content:space-between;gap:12px;">
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:3px;height:16px;background:#2563eb;border-radius:4px;"></div>
                <span style="font-size:12.5px;font-weight:700;color:#374151;">
                    Total <span style="color:#2563eb;">{{ $calonPenerimas->total() }}</span> data
                </span>
            </div>
            <span style="font-size:11px;color:#9ca3af;">
                Halaman {{ $calonPenerimas->currentPage() }} / {{ $calonPenerimas->lastPage() }}
            </span>
        </div>

        {{-- TABLE --}}
        <div style="overflow-x:auto;">
            <table>
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
                        <tr>
                            {{-- No --}}
                            <td style="font-size:11px;color:#9ca3af;font-weight:600;text-align:center;">
                                {{ $calonPenerimas->firstItem() + $index }}
                            </td>

                            {{-- Nama --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:9px;">
                                    <div class="av">{{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}</div>
                                    <span style="font-size:13px;font-weight:600;color:#111827;">{{ $item->nama_lengkap }}</span>
                                </div>
                            </td>

                            {{-- NIK --}}
                            <td style="font-family:monospace;font-size:11.5px;color:#6b7280;letter-spacing:.02em;">
                                {{ $item->nik }}
                            </td>

                            {{-- RT --}}
                            <td>
                                <span class="badge-rt">RT {{ str_pad($item->rt->nomor_rt ?? '-', 3, '0', STR_PAD_LEFT) }}</span>
                            </td>

                            {{-- Dusun --}}
                            <td style="font-size:12px;color:#6b7280;">
                                {{ $item->rt->dusun->nama_dusun ?? '-' }}
                            </td>

                            {{-- Tracking --}}
                            <td>
                                @php $trk = $item->tracking_status ?? 'draft'; @endphp
                                @if($trk === 'draft')
                                    <span class="badge trk-draft"><span class="dot"></span>Draft</span>
                                @elseif($trk === 'terkirim')
                                    <span class="badge trk-terkirim"><span class="dot"></span>Terkirim</span>
                                @elseif($trk === 'sedang_validasi')
                                    <span class="badge trk-validasi"><span class="dot"></span>Validasi</span>
                                @elseif($trk === 'selesai')
                                    <span class="badge trk-selesai"><span class="dot"></span>Selesai</span>
                                @else
                                    <span class="badge trk-draft"><span class="dot"></span>—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($item->status_verifikasi === 'pending')
                                    <span class="badge st-pending"><span class="dot"></span>Pending</span>
                                @elseif($item->status_verifikasi === 'disetujui')
                                    <span class="badge st-disetujui"><span class="dot"></span>Disetujui</span>
                                @else
                                    <span class="badge st-ditolak"><span class="dot"></span>Ditolak</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;">
                                    <a href="{{ route('rt.calon-penerima.show', $item->id) }}" class="btn-act btn-detail">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>

                                    @if($trk === 'draft')
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

        {{-- PAGINATION --}}
        <div class="pagi">
            {{ $calonPenerimas->links() }}
        </div>

    </div>

</x-app-layout>