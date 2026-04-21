<x-app-layout>

    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h2 style="font-size:16px;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Data Akun</h2>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;">Kelola akun Admin & RT yang menggunakan sistem</p>
            </div>
            @if(isset($users) && method_exists($users,'total'))
            <div style="display:inline-flex;align-items:center;gap:6px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:6px 12px;font-size:11px;color:#64748b;">
                <svg width="13" height="13" fill="none" stroke="#3b82f6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                {{ $users->total() }} akun terdaftar
            </div>
            @endif
        </div>
    </x-slot>

    <style>
        .da-card{background:#fff;border-radius:16px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);overflow:hidden;}
        .fl-box{display:flex;align-items:center;gap:9px;border-radius:12px;padding:10px 14px;font-size:12px;font-weight:500;margin-bottom:10px;}
        .fl-box svg{width:14px;height:14px;flex-shrink:0;}

        /* search */
        .sw{position:relative;}
        .sw svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;stroke:#9ca3af;pointer-events:none;}
        .sw input{width:100%;padding:9px 12px 9px 34px;font-size:13px;color:#111827;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:11px;outline:none;transition:border-color .15s,box-shadow .15s;font-family:inherit;}
        .sw input:focus{border-color:#3b82f6;background:#fff;box-shadow:0 0 0 3px rgba(59,130,246,.10);}
        .sw input::placeholder{color:#9ca3af;}

        /* table */
        .tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
        table{width:100%;border-collapse:collapse;min-width:580px;}
        thead tr{background:#f8fafc;border-bottom:1.5px solid #f1f5f9;}
        thead th{padding:9px 14px;text-align:left;font-size:9.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;}
        tbody tr{border-bottom:1px solid #f8fafc;transition:background .1s;}
        tbody tr:hover{background:#f0f7ff;}
        tbody tr:last-child{border-bottom:none;}
        tbody td{padding:10px 14px;vertical-align:middle;}

        /* badges */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:10.5px;font-weight:700;white-space:nowrap;}
        .dot{width:5px;height:5px;border-radius:50%;flex-shrink:0;}

        /* action buttons */
        .btn-a{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:8px;font-size:10.5px;font-weight:600;border:none;cursor:pointer;white-space:nowrap;font-family:inherit;text-decoration:none;line-height:1.4;transition:filter .1s;}
        .btn-a:hover{filter:brightness(.92);}
        .btn-a svg{width:11px;height:11px;flex-shrink:0;}
        .btn-role{background:#f1f5f9;color:#475569;}
        .btn-nonaktif{background:#fffbeb;color:#b45309;}
        .btn-aktif{background:#f0fdf4;color:#166534;}
        .btn-hapus{background:#fff1f2;color:#be123c;}
        .btn-locked{background:#f8fafc;color:#94a3b8;border:1.5px solid #e2e8f0;cursor:default;}

        /* avatar */
        .av-wrap{position:relative;flex-shrink:0;}
        .av{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff;}
        .av-dot{position:absolute;bottom:-1px;right:-1px;width:10px;height:10px;border-radius:50%;background:#10b981;border:2px solid #fff;}

        /* mobile card view */
        .mob-card{display:none;flex-direction:column;gap:10px;padding:14px 16px;}
        .mob-row{background:#f8fafc;border-radius:13px;padding:12px 14px;border:1.5px solid #f1f5f9;}
        .mob-name{font-size:13px;font-weight:700;color:#0f172a;}
        .mob-email{font-size:11px;color:#64748b;margin-top:2px;}
        .mob-meta{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-top:8px;}
        .mob-actions{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-top:10px;padding-top:10px;border-top:1px solid #f1f5f9;}

        @media(max-width:640px){
            .tbl-wrap table{display:none;}
            .mob-card{display:flex;}
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

    {{-- SEARCH --}}
    <div class="da-card" style="padding:14px 16px;margin-bottom:12px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:10px;">
            <div style="width:3px;height:14px;background:#2563eb;border-radius:4px;"></div>
            <h3 style="font-size:12px;font-weight:700;color:#374151;">Pencarian</h3>
        </div>
        <form method="GET" action="{{ route('admin.data-akun') }}"
              style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <div class="sw" style="flex:1;min-width:200px;">
                <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email...">
            </div>
            <button type="submit"
                    style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:#2563eb;color:#fff;font-size:12px;font-weight:700;border:none;border-radius:11px;cursor:pointer;white-space:nowrap;font-family:inherit;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>
            @if(request()->filled('q'))
                <a href="{{ route('admin.data-akun') }}"
                   style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#f3f4f6;border-radius:10px;color:#6b7280;text-decoration:none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>
    </div>

    {{-- TABLE CARD --}}
    <div class="da-card">
        <div style="padding:10px 16px;border-bottom:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;">
            <div style="display:flex;align-items:center;gap:7px;">
                <div style="width:3px;height:15px;background:#2563eb;border-radius:4px;"></div>
                <span style="font-size:12.5px;font-weight:700;color:#374151;">
                    Daftar Akun
                    @if(isset($users) && method_exists($users,'total'))
                        <span style="color:#9ca3af;font-weight:400;font-size:11px;"> ({{ $users->total() }} akun)</span>
                    @endif
                </span>
            </div>
            @if(isset($users) && method_exists($users,'currentPage') && $users->lastPage() > 1)
                <span style="font-size:11px;color:#9ca3af;">Hal. {{ $users->currentPage() }} / {{ $users->lastPage() }}</span>
            @endif
        </div>

        {{-- ── DESKTOP TABLE ── --}}
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $u)
                        @php
                            $isMe         = ($u->id === auth()->id());
                            $isActive     = isset($u->is_active) ? (bool)$u->is_active : true;
                            $isAdmin      = $u->role === 'admin';
                            $newRole      = $isAdmin ? 'rt' : 'admin';
                            $newRoleLabel = $isAdmin ? 'RT' : 'Admin';
                            $initial      = strtoupper(substr($u->name,0,1));
                            $avBg         = $isAdmin
                                ? 'background:linear-gradient(135deg,#10b981,#059669);'
                                : 'background:linear-gradient(135deg,#3b82f6,#2563eb);';
                        @endphp
                        <tr style="{{ !$isActive ? 'opacity:.5;' : '' }}">

                            {{-- NAMA --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="av-wrap">
                                        <div class="av" style="{{ $avBg }}">{{ $initial }}</div>
                                        @if($isActive)<div class="av-dot"></div>@endif
                                    </div>
                                    <div style="min-width:0;">
                                        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                            <span style="font-size:12.5px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:130px;">{{ $u->name }}</span>
                                            @if($isMe)
                                                <span style="font-size:9.5px;padding:1px 7px;border-radius:20px;background:#ede9fe;color:#6d28d9;border:1px solid #ddd6fe;font-weight:700;">Kamu</span>
                                            @endif
                                        </div>
                                        <div style="font-size:10.5px;color:#94a3b8;margin-top:1px;">ID #{{ $u->id }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- EMAIL --}}
                            <td style="font-size:12px;color:#64748b;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ $u->email }}
                            </td>

                            {{-- ROLE --}}
                            <td>
                                @if($isAdmin)
                                    <span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;">
                                        <span class="dot" style="background:#10b981;"></span>Admin
                                    </span>
                                @else
                                    <span class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;">
                                        <span class="dot" style="background:#3b82f6;"></span>RT
                                    </span>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($isActive)
                                    <span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;">
                                        <span class="dot" style="background:#10b981;animation:pulse 2s infinite;"></span>Aktif
                                    </span>
                                @else
                                    <span class="badge" style="background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;">
                                        <span class="dot" style="background:#9ca3af;"></span>Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:5px;flex-wrap:wrap;">
                                    @if($isMe)
                                        <span class="btn-a btn-locked">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            Terkunci
                                        </span>
                                    @else
                                        {{-- Ubah role --}}
                                        <form method="POST" action="{{ route('admin.data-akun.role',$u->id) }}">
                                            @csrf
                                            <input type="hidden" name="role" value="{{ $newRole }}">
                                            <button type="submit" class="btn-a btn-role"
                                                onclick="return confirm('Ubah role {{ addslashes($u->name) }} menjadi {{ $newRoleLabel }}?')">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                                → {{ $newRoleLabel }}
                                            </button>
                                        </form>

                                        {{-- Toggle aktif --}}
                                        <form method="POST" action="{{ route('admin.data-akun.toggle-aktif',$u->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="btn-a {{ $isActive ? 'btn-nonaktif' : 'btn-aktif' }}"
                                                onclick="return confirm('{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ addslashes($u->name) }}?')">
                                                @if($isActive)
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    Nonaktifkan
                                                @else
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Aktifkan
                                                @endif
                                            </button>
                                        </form>

                                        {{-- Hapus --}}
                                        <form method="POST" action="{{ route('admin.data-akun.hapus',$u->id) }}"
                                              onsubmit="return confirm('Yakin hapus akun {{ addslashes($u->name) }}? Tindakan ini tidak bisa dibatalkan.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-a btn-hapus">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">
                            <div style="padding:48px 16px;text-align:center;">
                                <div style="width:44px;height:44px;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                    <svg width="22" height="22" fill="none" stroke="#d1d5db" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p style="font-size:13px;font-weight:600;color:#6b7280;">Belum ada akun</p>
                                @if(request()->filled('q'))
                                    <p style="font-size:11.5px;color:#9ca3af;margin-top:4px;">Tidak ada hasil untuk "<strong>{{ request('q') }}</strong>"</p>
                                    <a href="{{ route('admin.data-akun') }}" style="display:inline-block;margin-top:8px;font-size:12px;color:#2563eb;font-weight:600;text-decoration:none;">← Hapus pencarian</a>
                                @endif
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── MOBILE CARD VIEW ── --}}
        <div class="mob-card">
            @forelse($users ?? [] as $u)
                @php
                    $isMe         = ($u->id === auth()->id());
                    $isActive     = isset($u->is_active) ? (bool)$u->is_active : true;
                    $isAdmin      = $u->role === 'admin';
                    $newRole      = $isAdmin ? 'rt' : 'admin';
                    $newRoleLabel = $isAdmin ? 'RT' : 'Admin';
                    $initial      = strtoupper(substr($u->name,0,1));
                    $avBg         = $isAdmin
                        ? 'background:linear-gradient(135deg,#10b981,#059669);'
                        : 'background:linear-gradient(135deg,#3b82f6,#2563eb);';
                @endphp
                <div class="mob-row" style="{{ !$isActive ? 'opacity:.5;' : '' }}">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="av-wrap">
                            <div class="av" style="{{ $avBg }}">{{ $initial }}</div>
                            @if($isActive)<div class="av-dot"></div>@endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                <span class="mob-name">{{ $u->name }}</span>
                                @if($isMe)
                                    <span style="font-size:9.5px;padding:1px 7px;border-radius:20px;background:#ede9fe;color:#6d28d9;border:1px solid #ddd6fe;font-weight:700;">Kamu</span>
                                @endif
                            </div>
                            <div class="mob-email">{{ $u->email }}</div>
                        </div>
                    </div>

                    <div class="mob-meta">
                        @if($isAdmin)
                            <span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;"><span class="dot" style="background:#10b981;"></span>Admin</span>
                        @else
                            <span class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;"><span class="dot" style="background:#3b82f6;"></span>RT</span>
                        @endif

                        @if($isActive)
                            <span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;"><span class="dot" style="background:#10b981;"></span>Aktif</span>
                        @else
                            <span class="badge" style="background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;"><span class="dot" style="background:#9ca3af;"></span>Nonaktif</span>
                        @endif

                        <span style="font-size:10.5px;color:#94a3b8;">ID #{{ $u->id }}</span>
                    </div>

                    <div class="mob-actions">
                        @if($isMe)
                            <span class="btn-a btn-locked">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Terkunci
                            </span>
                        @else
                            <form method="POST" action="{{ route('admin.data-akun.role',$u->id) }}">
                                @csrf
                                <input type="hidden" name="role" value="{{ $newRole }}">
                                <button type="submit" class="btn-a btn-role"
                                    onclick="return confirm('Ubah role {{ addslashes($u->name) }} menjadi {{ $newRoleLabel }}?')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    → {{ $newRoleLabel }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.data-akun.toggle-aktif',$u->id) }}">
                                @csrf
                                <button type="submit"
                                    class="btn-a {{ $isActive ? 'btn-nonaktif' : 'btn-aktif' }}"
                                    onclick="return confirm('{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ addslashes($u->name) }}?')">
                                    {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.data-akun.hapus',$u->id) }}"
                                  onsubmit="return confirm('Yakin hapus akun {{ addslashes($u->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-a btn-hapus">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div style="padding:40px 16px;text-align:center;">
                    <p style="font-size:13px;font-weight:600;color:#6b7280;">Belum ada akun</p>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if(isset($users) && method_exists($users,'links') && $users->hasPages())
            <div style="padding:10px 16px;border-top:1px solid #f1f5f9;background:#fafafa;">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif

        {{-- WATERMARK --}}
        <div style="padding:12px 16px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:center;gap:8px;">
            <svg width="13" height="13" fill="#d1d5db" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
            <span style="font-size:11px;color:#d1d5db;font-weight:500;">SiBantuDes · Desa Ngerong</span>
        </div>
    </div>

    <style>
        @keyframes pulse{0%,100%{opacity:1;}50%{opacity:.5;}}
    </style>

</x-app-layout>