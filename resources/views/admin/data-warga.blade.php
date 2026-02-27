<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Warga</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola dan pantau seluruh data warga dari semua dusun.</p>
            </div>
            <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs text-gray-500">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }} — {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                <svg class="w-4 h-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- SEARCH CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Pencarian</h3>
            </div>
            <form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="relative md:col-span-3">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Cari NIK / Nama / Email..."
                           class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                    @if(request()->filled('q'))
                        <a href="{{ url()->current() }}"
                           class="inline-flex items-center justify-center w-10 text-sm font-medium bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Table header info --}}
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                    <span class="text-sm font-semibold text-gray-700">
                        Daftar Warga
                        @if(isset($wargas) && method_exists($wargas, 'total'))
                            <span class="ml-1 text-gray-400 font-normal">({{ $wargas->total() }} data)</span>
                        @endif
                    </span>
                </div>
                @if(isset($wargas) && method_exists($wargas, 'currentPage') && $wargas->lastPage() > 1)
                    <span class="text-xs text-gray-400">
                        Halaman {{ $wargas->currentPage() }} dari {{ $wargas->lastPage() }}
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm table-fixed">
                    <colgroup>
                        <col style="width:13%">
                        <col style="width:21%">
                        <col style="width:14%">
                        <col style="width:12%">
                        <col style="width:16%">
                        <col style="width:10%">
                        <col style="width:14%">
                    </colgroup>
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">NIK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Dusun / RT</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Penghasilan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Probabilitas</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        @forelse($wargas ?? [] as $warga)
                            @php
                                $prob    = optional($warga->prediksiKelayakan)->probability ?? null;
                                $probPct = $prob !== null ? ($prob <= 1 ? $prob * 100 : $prob) : null;

                                $detailPayload = [
                                    'nik'               => $warga->nik,
                                    'no_kk'             => $warga->no_kk ?? '-',
                                    'nama_lengkap'      => $warga->nama_lengkap,
                                    'jenis_kelamin'     => $warga->jenis_kelamin ?? '-',
                                    'tempat_lahir'      => $warga->tempat_lahir ?? '-',
                                    'tanggal_lahir'     => $warga->tanggal_lahir
                                                            ? \Carbon\Carbon::parse($warga->tanggal_lahir)->translatedFormat('d F Y')
                                                            : '-',
                                    'usia'              => $warga->usia ?? '-',
                                    'status_perkawinan' => $warga->status_perkawinan ?? '-',
                                    'alamat'            => $warga->alamat ?? '-',
                                    'desa'              => $warga->desa ?? '-',
                                    'aset_kepemilikan'  => $warga->aset_kepemilikan ?? '-',
                                    'pekerjaan'         => $warga->pekerjaan ?? '-',
                                    'penghasilan'       => $warga->penghasilan ?? 0,
                                    'jumlah_tanggungan' => $warga->jumlah_tanggungan ?? '-',
                                    'bantuan_lain'      => $warga->bantuan_lain ?? '-',
                                    'status_verifikasi' => $warga->status_verifikasi ?? '-',
                                    'dusun'             => $warga->rt->dusun->nama_dusun ?? '-',
                                    'rt'                => $warga->rt->nomor_rt ?? '-',
                                    'probability'       => optional($warga->prediksiKelayakan)->probability,
                                    'recommendation'    => optional($warga->prediksiKelayakan)->recommendation,
                                    'input_oleh'        => optional($warga->user)->name ?? (optional($warga->user)->email ?? '-'),
                                    'created_at'        => optional($warga->created_at)->translatedFormat('d F Y, H:i') ?? '-',
                                ];
                            @endphp

                            <tr class="hover:bg-blue-50/20 transition-colors duration-150 group">

                                {{-- NIK --}}
                                <td class="px-4 py-3">
                                    <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-lg">{{ $warga->nik }}</span>
                                </td>

                                {{-- NAMA --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shrink-0 shadow-sm shadow-blue-200">
                                            <span class="text-xs font-bold text-white">{{ strtoupper(substr($warga->nama_lengkap, 0, 1)) }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-gray-800 truncate">{{ $warga->nama_lengkap }}</div>
                                            <div class="text-xs text-gray-400 truncate">{{ $warga->pekerjaan ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- DUSUN / RT --}}
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-700">{{ $warga->rt->dusun->nama_dusun ?? '-' }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 text-xs font-medium border border-blue-100 mt-0.5">
                                        RT {{ str_pad($warga->rt->nomor_rt ?? '-', 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                {{-- PENGHASILAN --}}
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    Rp {{ number_format($warga->penghasilan ?? 0, 0, ',', '.') }}
                                </td>

                                {{-- PROBABILITAS --}}
                                <td class="px-4 py-3">
                                    @if($probPct !== null)
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                                                <div class="h-1.5 rounded-full transition-all {{ $probPct >= 70 ? 'bg-emerald-500' : ($probPct >= 40 ? 'bg-amber-400' : 'bg-rose-400') }}"
                                                     style="width: {{ min($probPct, 100) }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold tabular-nums {{ $probPct >= 70 ? 'text-emerald-600' : ($probPct >= 40 ? 'text-amber-600' : 'text-rose-500') }}">
                                                {{ number_format($probPct, 1) }}%
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 py-3">
                                    @if($warga->status_verifikasi === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Pending
                                        </span>
                                    @elseif($warga->status_verifikasi === 'disetujui')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Ditolak
                                        </span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- DETAIL --}}
                                        <button type="button"
                                                onclick="openDetailModal({{ $warga->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </button>

                                        @if($warga->status_verifikasi === 'pending')
                                            <form action="{{ route('admin.data-warga.setujui', $warga->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.data-warga.tolak', $warga->id) }}" method="POST"
                                                  onsubmit="return confirm('Yakin menolak data {{ addslashes($warga->nama_lengkap) }}?')">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Tolak
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-300">—</span>
                                        @endif
                                    </div>

                                    {{-- Data JSON tersembunyi --}}
                                    <script type="application/json" id="warga-{{ $warga->id }}">{!! json_encode($detailPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center">
                                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-500">Belum ada data warga</p>
                                            @if(request()->filled('q'))
                                                <p class="text-xs text-gray-400 mt-0.5">Tidak ada hasil untuk "<span class="font-medium">{{ request('q') }}</span>"</p>
                                                <a href="{{ url()->current() }}" class="inline-block mt-2 text-xs text-blue-600 hover:underline">← Hapus pencarian</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if(isset($wargas) && method_exists($wargas, 'links') && $wargas->hasPages())
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
                    {{ $wargas->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- ═══════════════════════════════════════════
         MODAL DETAIL WARGA
    ════════════════════════════════════════════ --}}
    {{-- Backdrop --}}
    <div id="detailBackdrop"
         onclick="closeDetailModal()"
         class="fixed inset-0 z-40 hidden bg-black/40 backdrop-blur-sm">
    </div>

    {{-- Panel — z-50 di atas backdrop --}}
    <div id="detailModal"
         class="fixed inset-0 z-50 hidden overflow-y-auto"
         style="pointer-events:none;">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[90vh]"
                 style="pointer-events:auto;">

                {{-- HERO HEADER --}}
                <div class="relative bg-gradient-to-br from-blue-600 to-blue-500 px-6 py-5 shrink-0">
                    {{-- Decorative circles --}}
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="absolute right-8 -bottom-6 w-16 h-16 bg-white/10 rounded-full"></div>

                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div id="modal-avatar"
                                 class="w-12 h-12 rounded-2xl bg-white/20 border-2 border-white/30 flex items-center justify-center shrink-0">
                                <span id="modal-initial" class="text-xl font-bold text-white">?</span>
                            </div>
                            <div>
                                <h3 id="modal-title" class="text-base font-bold text-white">—</h3>
                                <p id="modal-subtitle" class="text-xs text-blue-100 mt-0.5">—</p>
                            </div>
                        </div>
                        <button onclick="closeDetailModal()"
                                class="w-8 h-8 flex items-center justify-center rounded-xl bg-white/20 hover:bg-white/30 transition text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Status badge in header --}}
                    <div class="relative mt-3">
                        <div id="modal-status-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/30">
                            <span id="modal-status-dot" class="w-1.5 h-1.5 rounded-full bg-white"></span>
                            <span id="modal-status-text">—</span>
                        </div>
                    </div>
                </div>

                {{-- BODY (scrollable) --}}
                <div class="overflow-y-auto flex-1 p-5 space-y-4 bg-gray-50/50">

                    {{-- IDENTITAS --}}
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                        <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                            <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                            <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Data Identitas</span>
                        </div>
                        <div class="p-4 grid grid-cols-2 gap-x-6 gap-y-3">
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">NIK</div>
                                <div id="d_nik" class="text-sm font-mono font-semibold text-gray-800 bg-gray-50 border border-gray-100 rounded-lg px-2.5 py-1.5">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">No. KK</div>
                                <div id="d_no_kk" class="text-sm font-mono font-semibold text-gray-800 bg-gray-50 border border-gray-100 rounded-lg px-2.5 py-1.5">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Nama Lengkap</div>
                                <div id="d_nama" class="text-sm font-semibold text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Jenis Kelamin</div>
                                <div id="d_jk" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Tempat, Tgl Lahir</div>
                                <div id="d_lahir" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Usia</div>
                                <div id="d_usia" class="text-sm font-semibold text-gray-800">-</div>
                            </div>
                            <div class="col-span-2">
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Status Perkawinan</div>
                                <div id="d_kawin" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                        </div>
                    </div>

                    {{-- TEMPAT TINGGAL --}}
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                        <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                            <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                            <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Tempat Tinggal</span>
                        </div>
                        <div class="p-4 grid grid-cols-2 gap-x-6 gap-y-3">
                            <div class="col-span-2">
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Alamat</div>
                                <div id="d_alamat" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Dusun</div>
                                <div id="d_dusun" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">RT</div>
                                <div id="d_rt" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                            <div class="col-span-2">
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Aset Kepemilikan</div>
                                <div id="d_aset" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                        </div>
                    </div>

                    {{-- EKONOMI --}}
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                        <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                            <div class="w-1 h-4 bg-amber-500 rounded-full"></div>
                            <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Data Ekonomi</span>
                        </div>
                        <div class="p-4 grid grid-cols-2 gap-x-6 gap-y-3">
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Pekerjaan</div>
                                <div id="d_pekerjaan" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Penghasilan</div>
                                <div id="d_penghasilan" class="text-sm font-bold text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Jumlah Tanggungan</div>
                                <div id="d_tanggungan" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Bantuan Lain</div>
                                <div id="d_bantuan" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                        </div>
                    </div>

                    {{-- PREDIKSI --}}
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                        <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                            <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                            <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Prediksi Kelayakan</span>
                        </div>
                        <div class="p-4">
                            <div id="d_pred_empty" class="hidden py-4 text-center text-xs text-gray-400">
                                Belum ada data prediksi dari sistem ML.
                            </div>
                            <div id="d_pred_wrap">
                                <div class="flex items-end gap-4 mb-3">
                                    <div>
                                        <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Probabilitas</div>
                                        <div id="d_prob" class="text-3xl font-bold text-gray-800 tabular-nums">—</div>
                                    </div>
                                    <div class="flex-1 pb-1.5">
                                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                                            <div id="d_prob_bar" class="h-2.5 rounded-full transition-all duration-500 bg-emerald-500" style="width:0%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Rekomendasi</div>
                                    <div id="d_reco_badge" class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-semibold bg-gray-100 text-gray-700">—</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- META --}}
                    <div class="flex items-center justify-between px-1 text-xs text-gray-400">
                        <span>Input oleh: <span id="d_input_oleh" class="text-gray-600 font-semibold">-</span></span>
                        <span>Didaftarkan: <span id="d_created" class="text-gray-600 font-semibold">-</span></span>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-5 py-4 border-t border-gray-100 bg-white shrink-0 flex justify-end">
                    <button onclick="closeDetailModal()"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        function set(id, val) {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        }

        function openDetailModal(id) {
            const el = document.getElementById('warga-' + id);
            if (!el) return;
            const d = JSON.parse(el.textContent);

            // ── Header hero ─────────────────────────────────
            set('modal-initial', (d.nama_lengkap || '?').charAt(0).toUpperCase());
            set('modal-title',   d.nama_lengkap || '-');
            set('modal-subtitle', d.pekerjaan ? d.pekerjaan + ' · ' + (d.dusun || '-') : (d.dusun || '-'));

            // ── Status badge di hero ─────────────────────────
            const dot  = document.getElementById('modal-status-dot');
            const txt  = document.getElementById('modal-status-text');
            const st   = d.status_verifikasi || '';
            if (st === 'pending') {
                if (dot) { dot.className = 'w-1.5 h-1.5 rounded-full bg-amber-300 animate-pulse'; }
                if (txt) txt.textContent = 'Menunggu Verifikasi';
            } else if (st === 'disetujui') {
                if (dot) { dot.className = 'w-1.5 h-1.5 rounded-full bg-emerald-300'; }
                if (txt) txt.textContent = 'Disetujui';
            } else {
                if (dot) { dot.className = 'w-1.5 h-1.5 rounded-full bg-rose-300'; }
                if (txt) txt.textContent = 'Ditolak';
            }

            // ── Identitas ────────────────────────────────────
            set('d_nik',   d.nik   || '-');
            set('d_no_kk', d.no_kk || '-');
            set('d_nama',  d.nama_lengkap  || '-');
            set('d_jk',    d.jenis_kelamin || '-');
            set('d_lahir', (d.tempat_lahir || '-') + ', ' + (d.tanggal_lahir || '-'));
            set('d_usia',  d.usia ? d.usia + ' tahun' : '-');
            set('d_kawin', d.status_perkawinan || '-');

            // ── Tempat tinggal ───────────────────────────────
            set('d_alamat', d.alamat || '-');
            set('d_dusun',  d.dusun  || '-');
            set('d_rt',     'RT ' + String(d.rt || '-').padStart(3, '0'));
            set('d_aset',   d.aset_kepemilikan || '-');

            // ── Ekonomi ──────────────────────────────────────
            set('d_pekerjaan',  d.pekerjaan   || '-');
            set('d_penghasilan','Rp ' + Number(d.penghasilan || 0).toLocaleString('id-ID'));
            set('d_tanggungan', d.jumlah_tanggungan != null ? d.jumlah_tanggungan + ' orang' : '-');
            set('d_bantuan',    d.bantuan_lain || '-');

            // ── Prediksi ─────────────────────────────────────
            const prob = d.probability;
            const emptyEl = document.getElementById('d_pred_empty');
            const wrapEl  = document.getElementById('d_pred_wrap');

            if (prob === null || prob === undefined) {
                emptyEl && emptyEl.classList.remove('hidden');
                wrapEl  && wrapEl.classList.add('hidden');
            } else {
                emptyEl && emptyEl.classList.add('hidden');
                wrapEl  && wrapEl.classList.remove('hidden');

                const pct = prob <= 1 ? prob * 100 : Number(prob);
                set('d_prob', pct.toFixed(1) + '%');

                const bar = document.getElementById('d_prob_bar');
                if (bar) {
                    bar.style.width = Math.min(pct, 100) + '%';
                    bar.className   = 'h-2.5 rounded-full transition-all duration-700 ' +
                        (pct >= 70 ? 'bg-emerald-500' : pct >= 40 ? 'bg-amber-400' : 'bg-rose-400');
                }

                const recoBadge = document.getElementById('d_reco_badge');
                if (recoBadge) {
                    const reco = d.recommendation || '—';
                    recoBadge.textContent = reco;
                    const isLayak = reco.toLowerCase().includes('layak');
                    recoBadge.className = 'inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-semibold ' +
                        (isLayak ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700');
                }
            }

            // ── Meta ─────────────────────────────────────────
            set('d_input_oleh', d.input_oleh || '-');
            set('d_created',    d.created_at || '-');

            // ── Tampilkan backdrop + modal ────────────────────
            document.getElementById('detailBackdrop').classList.remove('hidden');
            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailBackdrop').classList.add('hidden');
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetailModal(); });
    </script>

</x-app-layout>