<x-app-layout>

    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h2 style="font-size:16px;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Arsip Data Warga</h2>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;">Data warga yang telah diarsipkan berdasarkan tahun periode</p>
            </div>
            <a href="{{ route('admin.data-warga') }}" style="display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;padding:7px 14px;font-size:12px;font-weight:600;color:#374151;text-decoration:none;white-space:nowrap;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Data Warga
            </a>
        </div>
    </x-slot>

    <style>
        .dw-card{background:#fff;border-radius:16px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04),0 4px 16px rgba(0,0,0,.03);overflow:hidden;}
        .fl-box{display:flex;align-items:center;gap:9px;border-radius:12px;padding:10px 14px;font-size:12px;font-weight:500;}
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
        .av{width:30px;height:30px;border-radius:9px;background:linear-gradient(135deg,#64748b,#475569);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0;}
        .nik-pill{font-family:monospace;font-size:10px;color:#6b7280;background:#f3f4f6;padding:2px 6px;border-radius:6px;white-space:nowrap;}
        .rt-badge{display:inline-flex;align-items:center;padding:2px 7px;border-radius:6px;background:#eff6ff;color:#1d4ed8;font-size:10px;font-weight:700;border:1px solid #bfdbfe;margin-top:2px;}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:10.5px;font-weight:700;white-space:nowrap;}
        .dot{width:5px;height:5px;border-radius:50%;flex-shrink:0;}
        .stat-yes{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
        .stat-no{background:#fff1f2;color:#9f1239;border:1px solid #fecdd3;}
        .stat-pending{background:#fffbeb;color:#b45309;border:1px solid #fde68a;}
        .prob-wrap{display:flex;align-items:center;gap:6px;}
        .prob-bg{flex:1;height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;min-width:50px;}
        .prob-bar{height:100%;border-radius:99px;}
        .tab-tahun{display:inline-flex;align-items:center;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;text-decoration:none;border:1.5px solid #e2e8f0;color:#64748b;background:#fff;transition:all .15s;white-space:nowrap;}
        .tab-tahun:hover{background:#f1f5f9;}
        .tab-tahun.active{background:#1e40af;color:#fff;border-color:#1e40af;}
        .btn-act{display:inline-flex;align-items:center;gap:4px;padding:4px 9px;border-radius:8px;font-size:10.5px;font-weight:600;text-decoration:none;border:none;cursor:pointer;white-space:nowrap;line-height:1.4;transition:filter .1s;font-family:inherit;}
        .btn-act:hover{filter:brightness(.92);}
        .btn-act svg{width:11px;height:11px;flex-shrink:0;}
    </style>

    @if(session('success'))
        <div class="fl-box" style="background:#f0fdf4;border:1.5px solid #bbf7d0;color:#166534;margin-bottom:10px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="fl-box" style="background:#fff1f2;border:1.5px solid #fecdd3;color:#9f1239;margin-bottom:10px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTER TAHUN --}}
    <div class="dw-card" style="padding:14px 16px;margin-bottom:12px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:12px;">
            <div style="width:3px;height:14px;background:#475569;border-radius:4px;"></div>
            <h3 style="font-size:12px;font-weight:700;color:#374151;">Filter Tahun</h3>
        </div>

        @if($daftarTahun->isEmpty())
            <p style="font-size:13px;color:#9ca3af;">Belum ada data yang diarsipkan.</p>
        @else
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @foreach($daftarTahun as $th)
                    <a href="{{ route('admin.data-warga.arsip', ['tahun' => $th, 'q' => request('q')]) }}"
                       class="tab-tahun {{ $tahun == $th ? 'active' : '' }}">
                        {{ $th }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.data-warga.arsip') }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-top:12px;padding-top:12px;border-top:1px solid #f1f5f9;">
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <div class="sw" style="flex:1;min-width:200px;">
                <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari NIK atau Nama...">
            </div>
            <button type="submit" style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:#475569;color:#fff;font-size:12px;font-weight:700;border:none;border-radius:11px;cursor:pointer;white-space:nowrap;font-family:inherit;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>
            @if(request()->filled('q'))
                <a href="{{ route('admin.data-warga.arsip', ['tahun' => $tahun]) }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#f3f4f6;border-radius:10px;color:#6b7280;text-decoration:none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>
    </div>

    {{-- TABLE ARSIP --}}
    <div class="dw-card">
        <div style="padding:10px 16px;border-bottom:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;">
            <div style="display:flex;align-items:center;gap:7px;">
                <div style="width:3px;height:15px;background:#475569;border-radius:4px;"></div>
                <span style="font-size:12.5px;font-weight:700;color:#374151;">
                    Arsip Tahun <span style="color:#1e40af;">{{ $tahun }}</span>
                    @if(isset($arsips) && method_exists($arsips,'total'))
                        — <span style="color:#475569;">{{ $arsips->total() }}</span><span style="color:#9ca3af;font-weight:400;"> data</span>
                    @endif
                </span>
            </div>
            @if(isset($arsips) && method_exists($arsips,'currentPage') && $arsips->lastPage() > 1)
                <span style="font-size:11px;color:#9ca3af;">Hal. {{ $arsips->currentPage() }} / {{ $arsips->lastPage() }}</span>
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
                        <th>Hasil</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arsips ?? [] as $warga)
                        @php
                            $prob    = optional($warga->prediksiKelayakan)->probability ?? null;
                            $probPct = $prob !== null ? ($prob <= 1 ? $prob * 100 : $prob) : null;
                            $sc      = $probPct !== null ? ($probPct >= 70 ? '#10b981' : ($probPct >= 40 ? '#f59e0b' : '#f43f5e')) : '#e5e7eb';
                        @endphp
                        <tr>
                            <td><span class="nik-pill">{{ $warga->nik }}</span></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div class="av">{{ strtoupper(substr($warga->nama_lengkap,0,1)) }}</div>
                                    <div style="min-width:0;">
                                        <div style="font-size:12px;font-weight:600;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px;">{{ $warga->nama_lengkap }}</div>
                                        <div style="font-size:10.5px;color:#9ca3af;margin-top:1px;">{{ $warga->pekerjaan ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:11.5px;font-weight:600;color:#374151;white-space:nowrap;">{{ $warga->rt->dusun->nama_dusun ?? '-' }}</div>
                                <span class="rt-badge">RT {{ str_pad($warga->rt->nomor_rt ?? '-', 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td style="font-size:12px;font-weight:600;color:#374151;white-space:nowrap;">
                                Rp {{ number_format($warga->penghasilan ?? 0, 0, ',', '.') }}
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
                                @if(($warga->status_verifikasi ?? '') === 'disetujui')
                                    <span class="badge stat-yes"><span class="dot" style="background:#22c55e;"></span>Diterima</span>
                                @elseif(($warga->status_verifikasi ?? '') === 'ditolak')
                                    <span class="badge stat-no"><span class="dot" style="background:#f43f5e;"></span>Ditolak</span>
                                @else
                                    <span class="badge stat-pending"><span class="dot" style="background:#f59e0b;"></span>Pending</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;">
                                    {{-- Aktifkan Kembali dari Arsip --}}
                                    <form method="POST" action="{{ route('admin.data-warga.aktifkan-kembali', $warga->id) }}" onsubmit="return confirm('Keluarkan {{ addslashes($warga->nama_lengkap) }} dari arsip dan aktifkan kembali?')">
                                        @csrf
                                        <button type="submit" class="btn-act" style="background:#fffbeb;color:#b45309;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            Aktifkan Kembali
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">
                            <div style="padding:48px 16px;text-align:center;">
                                <div style="width:44px;height:44px;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                    <svg width="20" height="20" fill="none" stroke="#d1d5db" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                </div>
                                <p style="font-size:13px;font-weight:600;color:#6b7280;margin-bottom:4px;">Tidak ada data arsip untuk tahun {{ $tahun }}</p>
                                @if(request()->filled('q'))
                                    <p style="font-size:11.5px;color:#9ca3af;">Tidak ada hasil untuk "<strong>{{ request('q') }}</strong>"</p>
                                    <a href="{{ route('admin.data-warga.arsip', ['tahun' => $tahun]) }}" style="display:inline-block;margin-top:8px;font-size:12px;color:#2563eb;font-weight:600;text-decoration:none;">← Hapus pencarian</a>
                                @endif
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($arsips) && method_exists($arsips,'links') && $arsips->hasPages())
            <div style="padding:10px 16px;border-top:1px solid #f1f5f9;background:#fafafa;">
                {{ $arsips->links() }}
            </div>
        @endif
    </div>

</x-app-layout>