<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Akun</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola akun Admin & RT yang menggunakan sistem.</p>
            </div>
            <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs text-gray-500">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                @if(isset($users) && method_exists($users, 'total'))
                    {{ $users->total() }} akun terdaftar
                @endif
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

        {{-- SEARCH --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Pencarian</h3>
            </div>
            <form method="GET" action="{{ route('admin.data-akun') }}" class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari nama / email..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2 text-sm font-semibold bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                    @if(request()->filled('q'))
                        <a href="{{ route('admin.data-akun') }}"
                            class="inline-flex items-center justify-center w-10 text-sm bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition">
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

            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                    <span class="text-sm font-semibold text-gray-700">
                        Daftar Akun
                        @if(isset($users) && method_exists($users, 'total'))
                            <span class="ml-1 text-gray-400 font-normal">({{ $users->total() }} akun)</span>
                        @endif
                    </span>
                </div>
                @if(isset($users) && method_exists($users, 'currentPage') && $users->lastPage() > 1)
                    <span class="text-xs text-gray-400">Hal. {{ $users->currentPage() }} / {{ $users->lastPage() }}</span>
                @endif
            </div>

            <div class="overflow-x-auto w-full">

                <table class="w-full text-sm table-fixed">
                    <colgroup>
                        <col style="width:28%">
                        <col style="width:23%">
                        <col style="width:11%">
                        <col style="width:11%">
                        <col style="width:27%">
                    </colgroup>
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($users ?? [] as $u)
                            @php
                                $isMe         = ($u->id === auth()->id());
                                $isActive     = isset($u->is_active) ? (bool)$u->is_active : true;
                                $isAdmin      = $u->role === 'admin';
                                $newRole      = $isAdmin ? 'rt' : 'admin';
                                $newRoleLabel = $isAdmin ? 'RT' : 'Admin';
                                $initial      = strtoupper(substr($u->name, 0, 1));
                                $avatarGrad   = $isAdmin
                                    ? 'from-emerald-500 to-emerald-600 shadow-emerald-200'
                                    : 'from-blue-500 to-blue-600 shadow-blue-200';
                            @endphp

                            <tr class="hover:bg-blue-50/20 transition-colors duration-150 {{ !$isActive ? 'opacity-50' : '' }}">

                                {{-- NAMA --}}
                                <td class="px-4 py-6">
                                    <div class="flex items-center gap-2.5">
                                        <div class="relative shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $avatarGrad }} flex items-center justify-center shadow-sm">
                                                <span class="text-sm font-bold text-white">{{ $initial }}</span>
                                            </div>
                                            @if($isActive)
                                                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-gray-800 truncate flex items-center gap-1.5 flex-wrap">
                                                {{ $u->name }}
                                                @if($isMe)
                                                    <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-indigo-100 text-indigo-600 border border-indigo-200 font-semibold leading-none">Kamu</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 mt-0.5">ID #{{ $u->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- EMAIL --}}
                                <td class="px-4 py-6">
                                    <span class="text-sm text-gray-500 truncate block">{{ $u->email }}</span>
                                </td>

                                {{-- ROLE --}}
                                <td class="px-4 py-6">
                                    @if($isAdmin)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                            </svg>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-lg bg-blue-100 text-blue-700 border border-blue-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                            </svg>
                                            RT
                                        </span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 py-6">
                                    @if($isActive)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-400 border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="px-4 py-6">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($isMe)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-400 rounded-lg border border-gray-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                Terkunci
                                            </span>
                                        @else
                                            {{-- UBAH ROLE --}}
                                            <form action="{{ route('admin.data-akun.role', $u->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="role" value="{{ $newRole }}">
                                                <button type="submit"
                                                    onclick="return confirm('Ubah role {{ addslashes($u->name) }} menjadi {{ $newRoleLabel }}?')"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                    </svg>
                                                    → {{ $newRoleLabel }}
                                                </button>
                                            </form>

                                            {{-- AKTIF / NONAKTIF --}}
                                            <form action="{{ route('admin.data-akun.toggle-aktif', $u->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    onclick="return confirm('{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ addslashes($u->name) }}?')"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-lg transition
                                                        {{ $isActive ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}">
                                                    @if($isActive)
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                        </svg>
                                                        Nonaktifkan
                                                    @else
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Aktifkan
                                                    @endif
                                                </button>
                                            </form>

                                            {{-- HAPUS --}}
                                            <form action="{{ route('admin.data-akun.hapus', $u->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus akun {{ addslashes($u->name) }}? Tindakan ini tidak bisa dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center">
                                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-500">Belum ada akun</p>
                                            @if(request()->filled('q'))
                                                <p class="text-xs text-gray-400 mt-0.5">Tidak ada hasil untuk "<span class="font-medium">{{ request('q') }}</span>"</p>
                                                <a href="{{ route('admin.data-akun') }}" class="inline-block mt-2 text-xs text-blue-600 hover:underline">← Hapus pencarian</a>
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
            @if(isset($users) && method_exists($users, 'links') && $users->hasPages())
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif

            {{-- CREDIT WATERMARK --}}
            <div class="px-5 py-5 border-t border-gray-100 flex items-center justify-center gap-2 select-none mt-1">
                <svg class="w-3.5 h-3.5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs text-gray-300 font-medium tracking-wide">Aplikasi SiBantuDes</span>
                <span class="text-gray-200">·</span>
                <span class="text-xs text-gray-300 tracking-wide">Desa Ngerong</span>
            </div>
        </div>

    </div>

</x-app-layout>