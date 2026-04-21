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

        /* form controls */
        .fi-label{display:block;font-size:10.5px;font-weight:600;color:#64748b;margin-bottom:5px;}
        .fi-select,.fi-input{width:100%;padding:9px 12px;font-size:13px;color:#111827;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:11px;outline:none;font-family:inherit;transition:border-color .15s;}
        .fi-select:focus,.fi-input:focus{border-color:#3b82f6;background:#fff;}

        /* buttons */
        .btn-fi{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;font-size:12.5px;font-weight:700;border:none;border-radius:11px;cursor:pointer;white-space:nowrap;font-family:inherit;transition:filter .1s;}
        .btn-fi:hover{filter:brightness(.93);}
        .btn-tampil{background:#2563eb;color:#fff;box-shadow:0 2px 8px rgba(37,99,235,.2);}
        .btn-reset-fi{background:#f1f5f9;color:#475569;}
        .btn-tetapkan{background:#059669;color:#fff;box-shadow:0 2px 8px rgba(5,150,105,.2);}

        /* table */
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

        /* ranking badge */
        .rank-badge{width:26px;height:26px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;}

        /* note box */
        .note-box{background:#fffbeb;border:1.5px solid #fde68a;border-radius:14px;padding:14px 16px;font-size:12px;color:#92400e;line-height:1.8;}
        .note-box b{font-weight:800;}

        /* filter form grid */
        .fi-grid{display:grid;grid-template-columns:1fr 1fr auto;gap:10px;align-items:end;}
        .fi-actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center;}

        /* mobile card */
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

    {{-- FILTER FORM --}}
    <div class="fi-card" style="padding:14px 16px;margin-bottom:12px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:12px;">
            <div style="width:3px;height:14px;background:#2563eb;border-radius:4px;"></div>
            <h3 style="font-size:12px;font-weight:700;color:#374151;">Pilih Dusun & Kuota</h3>
        </div>

        <form method="GET" action="{{ route('admin.filterisasi') }}">
            <div class="fi-grid">
                <div>
                    <label class="fi-label">Dusun</label>
                    <select name="dusun_id" class="fi-select">
                        <option value="0">-- pilih dusun --</option>
                        @foreach($dusuns as $d)
                            <option value="{{ $d->id }}" {{ (int)($dusunId??0)===$d->id?'selected':'' }}>
                                {{ $d->nama_dusun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="fi-label">Kuota per Dusun</label>
                    <input type="number" name="kuota" min="1" max="100"
                           value="{{ (int)($kuota??7) }}" class="fi-input">
                </div>

                <div class="fi-actions">
                    <button type="submit" class="btn-fi btn-tampil">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                        Tampilkan
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

                        <form method="POST" action="{{ route('admin.filterisasi.tetapkan') }}" style="display:contents;">
                            @csrf
                            <input type="hidden" name="dusun_id" value="{{ $dusunId }}">
                            <input type="hidden" name="kuota" value="{{ (int)($kuota??7) }}">
                            <button type="submit" class="btn-fi btn-tetapkan"
                                onclick="return confirm('Tetapkan {{ (int)($kuota??7) }} orang dengan probabilitas tertinggi untuk dusun ini?')">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Tetapkan {{ (int)($kuota??7) }} Teratas
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
        </div>

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
                    @forelse(($candidates??[]) as $index => $w)
                        @php
                            $prob    = $w->prob ?? optional($w->prediksiKelayakan)->probability;
                            $probPct = $prob !== null ? ($prob<=1 ? $prob*100 : $prob) : null;
                            $sc      = $probPct !== null ? ($probPct>=70?'#10b981':($probPct>=40?'#f59e0b':'#f43f5e')) : '#e5e7eb';
                            $picked  = isset($pickedIds) ? $pickedIds->contains($w->id) : false;
                            $rank    = (($candidates->currentPage()-1)*$candidates->perPage())+$index+1;
                        @endphp
                        <tr style="{{ $picked ? 'background:#f0fdf4;' : '' }}">

                            {{-- Rank --}}
                            <td>
                                @php
                                    $rk  = ['linear-gradient(135deg,#f59e0b,#d97706)','linear-gradient(135deg,#94a3b8,#64748b)','linear-gradient(135deg,#f97316,#ea580c)'];
                                    $rbg = $rank<=3 ? $rk[$rank-1] : '#f1f5f9';
                                    $rtc = $rank<=3 ? '#fff' : '#64748b';
                                @endphp
                                <div class="rank-badge" style="background:{{ $rbg }};color:{{ $rtc }};">{{ $rank }}</div>
                            </td>

                            {{-- Nama --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0;">
                                        {{ strtoupper(substr($w->nama_lengkap,0,1)) }}
                                    </div>
                                    <span style="font-size:12px;font-weight:600;color:#0f172a;">{{ $w->nama_lengkap }}</span>
                                </div>
                            </td>

                            {{-- NIK --}}
                            <td>
                                <span style="font-family:monospace;font-size:10.5px;color:#6b7280;background:#f3f4f6;padding:2px 7px;border-radius:6px;">{{ $w->nik }}</span>
                            </td>

                            {{-- Dusun --}}
                            <td style="font-size:12px;color:#374151;">
                                {{ optional(optional($w->rt)->dusun)->nama_dusun ?? '-' }}
                            </td>

                            {{-- Probabilitas --}}
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

                            {{-- Status verifikasi --}}
                            <td>
                                @php
                                    $sv=['pending'=>['#fffbeb','#b45309','#fde68a','Pending'],'disetujui'=>['#f0fdf4','#166534','#bbf7d0','Disetujui'],'ditolak'=>['#fff1f2','#9f1239','#fecdd3','Ditolak']];
                                    $sv=$sv[$w->status_verifikasi??'']??['#f3f4f6','#6b7280','#e5e7eb',ucfirst($w->status_verifikasi??'-')];
                                @endphp
                                <span class="badge" style="background:{{ $sv[0] }};color:{{ $sv[1] }};border:1px solid {{ $sv[2] }};">
                                    <span class="dot" style="background:{{ $sv[1] }};"></span>{{ $sv[3] }}
                                </span>
                            </td>

                            {{-- Final (terpilih) --}}
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
                    @empty
                        <tr><td colspan="7">
                            <div style="padding:48px 16px;text-align:center;">
                                <div style="width:44px;height:44px;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                    <svg width="22" height="22" fill="none" stroke="#d1d5db" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                                </div>
                                <p style="font-size:13px;font-weight:600;color:#6b7280;">Pilih dusun untuk menampilkan kandidat</p>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARD VIEW --}}
        <div class="mob-fi">
            @forelse(($candidates??[]) as $index => $w)
                @php
                    $prob    = $w->prob ?? optional($w->prediksiKelayakan)->probability;
                    $probPct = $prob !== null ? ($prob<=1 ? $prob*100 : $prob) : null;
                    $sc      = $probPct!==null ? ($probPct>=70?'#10b981':($probPct>=40?'#f59e0b':'#f43f5e')) : '#e5e7eb';
                    $picked  = isset($pickedIds) ? $pickedIds->contains($w->id) : false;
                    $rank    = (($candidates->currentPage()-1)*$candidates->perPage())+$index+1;
                    $sv=['pending'=>['#fffbeb','#b45309','Pending'],'disetujui'=>['#f0fdf4','#166534','Disetujui'],'ditolak'=>['#fff1f2','#9f1239','Ditolak']];
                    $sv=$sv[$w->status_verifikasi??'']??['#f3f4f6','#6b7280',ucfirst($w->status_verifikasi??'-')];
                @endphp
                <div class="mob-fi-row" style="{{ $picked ? 'background:#f0fdf4;border-color:#bbf7d0;' : '' }}">
                    <div style="display:flex;align-items:center;gap:10px;">
                        {{-- rank --}}
                        @php
                            $rk  = ['linear-gradient(135deg,#f59e0b,#d97706)','linear-gradient(135deg,#94a3b8,#64748b)','linear-gradient(135deg,#f97316,#ea580c)'];
                            $rbg = $rank<=3 ? $rk[$rank-1] : '#f1f5f9';
                            $rtc = $rank<=3 ? '#fff' : '#64748b';
                        @endphp
                        <div class="rank-badge" style="background:{{ $rbg }};color:{{ $rtc }};flex-shrink:0;">{{ $rank }}</div>

                        {{-- avatar + nama --}}
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $w->nama_lengkap }}</div>
                            <div style="font-size:10.5px;color:#64748b;margin-top:1px;font-family:monospace;">{{ $w->nik }}</div>
                        </div>

                        {{-- final status --}}
                        @if($picked)
                            <span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;flex-shrink:0;">✓ Terpilih</span>
                        @endif
                    </div>

                    <div style="display:flex;align-items:center;gap:8px;margin-top:10px;flex-wrap:wrap;">
                        {{-- dusun --}}
                        <span style="font-size:11px;color:#64748b;background:#f1f5f9;padding:2px 8px;border-radius:6px;">
                            {{ optional(optional($w->rt)->dusun)->nama_dusun ?? '-' }}
                        </span>

                        {{-- status --}}
                        <span class="badge" style="background:{{ $sv[0] }};color:{{ $sv[1] }};border:1px solid {{ $sv[0] }};">
                            <span class="dot" style="background:{{ $sv[1] }};"></span>{{ $sv[2] }}
                        </span>

                        {{-- probabilitas --}}
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
            @empty
                <div style="padding:32px 16px;text-align:center;">
                    <p style="font-size:13px;font-weight:600;color:#6b7280;">Pilih dusun untuk menampilkan kandidat</p>
                </div>
            @endforelse
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
        Saat tombol <b>Tetapkan Teratas</b> ditekan, sistem otomatis:<br>
        • memilih warga dengan probabilitas tertinggi sesuai kuota<br>
        • menetapkan yang lolos menjadi <b>disetujui</b><br>
        • menetapkan yang tidak lolos menjadi <b>ditolak</b>
    </div>

</x-app-layout>