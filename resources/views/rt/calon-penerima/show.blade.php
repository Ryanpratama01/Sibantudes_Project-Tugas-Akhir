<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Calon Penerima</h2>
                <p class="text-sm text-gray-500 mt-0.5">Detail data pendataan warga dan hasil prediksi.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('rt.calon-penerima.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                @if(($calonPenerima->status_verifikasi ?? '') === 'pending')
                    <a href="{{ route('calon-penerima.edit', $calonPenerima->id) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <svg class="w-4 h-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- IDENTITY BANNER --}}
    <div class="bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl p-5 mb-4 flex items-center gap-4 text-white shadow-lg shadow-blue-200">
        <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-2xl font-bold shrink-0">
            {{ strtoupper(substr($calonPenerima->nama_lengkap ?? 'U', 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-lg font-bold truncate">{{ $calonPenerima->nama_lengkap ?? '-' }}</h3>
            <p class="text-blue-100 text-sm font-mono mt-0.5">NIK: {{ $calonPenerima->nik ?? '-' }}</p>
        </div>
        <div class="shrink-0">
            @php $st = $calonPenerima->status_verifikasi ?? 'pending'; @endphp
            @if ($st === 'pending')
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                </span>
            @elseif ($st === 'disetujui')
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- KOLOM KIRI: DATA UTAMA --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- IDENTITAS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                    <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-700">Data Identitas</h3>
                </div>
                <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                        $identitas = [
                            'No. KK'            => $calonPenerima->no_kk ?? '-',
                            'Jenis Kelamin'     => $calonPenerima->jenis_kelamin ?? '-',
                            'Tempat Lahir'      => $calonPenerima->tempat_lahir ?? '-',
                            'Tanggal Lahir'     => $calonPenerima->tanggal_lahir
                                ? \Carbon\Carbon::parse($calonPenerima->tanggal_lahir)->translatedFormat('d F Y')
                                : '-',
                            'Usia'              => ($calonPenerima->usia ?? '-') . ' tahun',
                            'Status Perkawinan' => $calonPenerima->status_perkawinan ?? '-',
                        ];
                    @endphp
                    @foreach($identitas as $label => $val)
                        <div>
                            <div class="text-xs text-gray-400 mb-0.5">{{ $label }}</div>
                            <div class="text-sm font-semibold text-gray-800">{{ $val }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TEMPAT TINGGAL --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                    <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-700">Data Tempat Tinggal</h3>
                </div>
                <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="col-span-2">
                        <div class="text-xs text-gray-400 mb-0.5">Alamat</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $calonPenerima->alamat ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-0.5">Dusun</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $calonPenerima->desa ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-0.5">RT</div>
                        <div class="text-sm font-semibold text-gray-800">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100">
                                RT {{ str_pad($calonPenerima->rt->nomor_rt ?? 0, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-xs text-gray-400 mb-0.5">Aset Kepemilikan</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $calonPenerima->aset_kepemilikan ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- EKONOMI --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                    <div class="w-1 h-4 bg-amber-500 rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-700">Data Ekonomi</h3>
                </div>
                <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <div class="text-xs text-gray-400 mb-0.5">Pekerjaan</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $calonPenerima->pekerjaan ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-0.5">Penghasilan</div>
                        <div class="text-sm font-semibold text-gray-800">
                            Rp {{ number_format((float)($calonPenerima->penghasilan ?? 0), 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-0.5">Jumlah Tanggungan</div>
                        <div class="text-sm font-semibold text-gray-800">{{ $calonPenerima->jumlah_tanggungan ?? '-' }} orang</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-0.5">Bantuan Lain</div>
                        @if(($calonPenerima->bantuan_lain ?? '') === 'ya')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Ya</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Tidak</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN --}}
        <div class="space-y-4">

            {{-- STATUS PENGAJUAN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                    <div class="w-1 h-4 bg-gray-700 rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-700">Status Pengajuan</h3>
                </div>
                <div class="p-5 space-y-3">
                    @if ($st === 'pending')
                        <div class="flex items-center gap-2 p-3 rounded-xl bg-amber-50 border border-amber-100">
                            <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                            <span class="text-sm font-semibold text-amber-700">Menunggu Verifikasi</span>
                        </div>
                    @elseif ($st === 'disetujui')
                        <div class="flex items-center gap-2 p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                            <span class="text-sm font-semibold text-emerald-700">Pengajuan Disetujui</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 p-3 rounded-xl bg-rose-50 border border-rose-100">
                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                            <span class="text-sm font-semibold text-rose-700">Pengajuan Ditolak</span>
                        </div>
                    @endif

                    <div class="text-xs text-gray-400 space-y-1">
                        <div class="flex justify-between">
                            <span>Dibuat</span>
                            <span class="text-gray-600 font-medium">
                                {{ $calonPenerima->created_at ? \Carbon\Carbon::parse($calonPenerima->created_at)->translatedFormat('d M Y, H:i') : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Diperbarui</span>
                            <span class="text-gray-600 font-medium">
                                {{ $calonPenerima->updated_at ? \Carbon\Carbon::parse($calonPenerima->updated_at)->translatedFormat('d M Y, H:i') : '-' }}
                            </span>
                        </div>
                    </div>

                    @if(!empty($calonPenerima->catatan_admin))
                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-3 text-xs text-gray-600">
                            <div class="font-semibold text-gray-700 mb-1">Catatan Admin</div>
                            {{ $calonPenerima->catatan_admin }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- PREDIKSI --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                    <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-700">Prediksi Kelayakan</h3>
                </div>
                <div class="p-5">
                    @php
                        $pred = $calonPenerima->prediksiKelayakan ?? null;
                        $prob = $pred ? (float)$pred->probability : null;
                        $rec  = $pred ? ($pred->recommendation ?? '-') : null;
                        $probPct = $prob !== null ? ($prob <= 1 ? $prob * 100 : $prob) : null;
                    @endphp

                    @if($pred)
                        <div class="text-center py-2">
                            <div class="text-4xl font-bold text-gray-900">{{ number_format($probPct, 1) }}<span class="text-xl text-gray-400">%</span></div>
                            <div class="text-xs text-gray-400 mt-0.5">Probabilitas Kelayakan</div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="mt-3 mb-4">
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500
                                    {{ $probPct >= 70 ? 'bg-emerald-500' : ($probPct >= 40 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                    style="width: {{ min($probPct, 100) }}%">
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-xl border text-sm font-semibold text-center
                            {{ $probPct >= 70 ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : ($probPct >= 40 ? 'bg-amber-50 border-amber-100 text-amber-700' : 'bg-rose-50 border-rose-100 text-rose-700') }}">
                            {{ $rec }}
                        </div>
                    @else
                        <div class="py-6 text-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-400">Belum ada data prediksi</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- AKSI HAPUS --}}
            @if(($calonPenerima->status_verifikasi ?? '') === 'pending')
                <div class="bg-white rounded-2xl shadow-sm border border-rose-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-rose-100 flex items-center gap-2 bg-rose-50">
                        <div class="w-1 h-4 bg-rose-500 rounded-full"></div>
                        <h3 class="text-sm font-semibold text-rose-700">Zona Bahaya</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-gray-500 mb-3">Hapus data ini secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                        <form action="{{ route('calon-penerima.destroy', $calonPenerima->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus data {{ $calonPenerima->nama_lengkap }}? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>