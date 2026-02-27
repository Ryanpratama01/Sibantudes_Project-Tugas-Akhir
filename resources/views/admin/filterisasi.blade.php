<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Filterisasi</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                Penetapan penerima BLT-DD berdasarkan probabilitas (hanya data yang sudah <b>disetujui</b>).
            </p>
        </div>
    </x-slot>

    <div class="space-y-4">

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Pilih Dusun & Kuota (Disetujui saja)</h3>
            </div>

            <form method="GET" action="{{ route('admin.filterisasi') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="text-xs text-gray-500">Dusun</label>
                    <select name="dusun_id" class="mt-1 w-full border border-gray-200 rounded-xl bg-gray-50 px-3 py-2 text-sm">
                        <option value="0">-- pilih dusun --</option>
                        @foreach($dusuns as $d)
                            <option value="{{ $d->id }}" {{ (int)($dusunId ?? 0) === $d->id ? 'selected' : '' }}>
                                {{ $d->nama_dusun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs text-gray-500">Kuota per dusun</label>
                    <input type="number" name="kuota" min="1" max="100" value="{{ (int)($kuota ?? 7) }}"
                        class="mt-1 w-full border border-gray-200 rounded-xl bg-gray-50 px-3 py-2 text-sm" />
                </div>

                <div class="flex items-end gap-2 md:col-span-2">
                    <button type="submit"
                        class="px-5 py-2 text-sm font-semibold bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                        Tampilkan
                    </button>

                    @if(!empty($dusunId))
                        <form method="POST" action="{{ route('admin.filterisasi.reset') }}">
                            @csrf
                            <input type="hidden" name="dusun_id" value="{{ $dusunId }}">
                            <button type="submit"
                                onclick="return confirm('Reset hasil filterisasi dusun ini?')"
                                class="px-4 py-2 text-sm font-semibold bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                                Reset
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.filterisasi.tetapkan') }}" class="ml-auto">
                            @csrf
                            <input type="hidden" name="dusun_id" value="{{ $dusunId }}">
                            <input type="hidden" name="kuota" value="{{ (int)($kuota ?? 7) }}">
                            <button type="submit"
                                onclick="return confirm('Tetapkan {{ (int)($kuota ?? 7) }} orang teratas untuk dusun ini?')"
                                class="px-5 py-2 text-sm font-semibold bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                                Tetapkan {{ (int)($kuota ?? 7) }} Teratas
                            </button>
                        </form>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                    <span class="text-sm font-semibold text-gray-700">Kandidat Disetujui (urut probabilitas)</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">NIK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Dusun</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Prob</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Final</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        @forelse(($candidates ?? []) as $w)
                            @php
                                $prob = optional($w->prediksiKelayakan)->probability;
                                $picked = isset($pickedIds) ? $pickedIds->contains($w->id) : false;
                            @endphp
                            <tr class="hover:bg-blue-50/20 {{ $picked ? 'bg-emerald-50/40' : '' }}">
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $w->nama_lengkap }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $w->nik }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ optional(optional($w->rt)->dusun)->nama_dusun ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-700">
                                    {{ $prob !== null ? number_format((float)$prob, 4) : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($picked)
                                        <span class="px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">Terpilih</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">Belum</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-400">
                                    Pilih dusun dulu untuk menampilkan kandidat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($candidates) && method_exists($candidates, 'links') && $candidates->hasPages())
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
                    {{ $candidates->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-4 text-xs text-amber-700">
            <b>Catatan:</b> Kandidat yang ditampilkan hanya yang <b>status_verifikasi = disetujui</b>. Kuota default 7 per dusun.
        </div>

    </div>

</x-app-layout>