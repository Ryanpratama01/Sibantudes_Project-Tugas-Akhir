<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Calon Penerima</h2>
                <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap & hasil prediksi kelayakan.</p>
            </div>
            <a href="{{ route('rt.calon-penerima.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <style>
        .hero-banner { background: linear-gradient(135deg,#1e40af 0%,#2563eb 60%,#3b82f6 100%); border-radius:1rem; padding:1.5rem; position:relative; overflow:hidden; margin-bottom:1.25rem; }
        .hero-bubble  { position:absolute; border-radius:50%; pointer-events:none; }
        .detail-card       { background:#fff; border-radius:1rem; border:1px solid #f1f5f9; margin-bottom:1rem; overflow:hidden; }
        .detail-card-head  { padding:.65rem 1.1rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px; background:#f9fafb; }
        .detail-card-accent{ width:3px; height:16px; border-radius:2px; flex-shrink:0; }
        .detail-card-title { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#6b7280; }
        .detail-card-body  { padding:1rem 1.1rem; }
        .data-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:1rem; }
        .data-lbl  { font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#9ca3af; margin-bottom:3px; }
        .data-val  { font-size:14px; color:#111827; font-weight:500; }
        .badge-rt  { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:12px; background:#dbeafe; color:#1e40af; font-weight:600; }
        .donut-ring { transform:rotate(-90deg); transform-origin:36px 36px; }
        .progress-track{ height:6px; border-radius:3px; background:#e5e7eb; overflow:hidden; }
        .faktor-item { display:flex; align-items:flex-start; gap:8px; padding:7px 10px; border-radius:8px; font-size:13px; margin-bottom:5px; }
        .faktor-pos  { background:#f0fdf4; color:#166534; }
        .faktor-neg  { background:#fff1f2; color:#9f1239; }
        .penjelasan-row { display:flex; justify-content:space-between; align-items:center; padding:7px 0; font-size:13px; border-bottom:1px solid #f1f5f9; }
        .penjelasan-row:last-child { border-bottom:none; }
        .ringkasan-box { background:#f0f9ff; border:1px solid #bae6fd; border-radius:10px; padding:12px 14px; font-size:13px; color:#0c4a6e; line-height:1.6; }
        .track-step { display:flex; gap:.875rem; padding:.65rem 0; }
        .track-num  { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; flex-shrink:0; margin-top:2px; }
        .step-done   { background:#dcfce7; color:#15803d; }
        .step-active { background:#dbeafe; color:#1d4ed8; }
        .step-pending{ background:#f3f4f6; color:#9ca3af; }
        .track-line  { width:2px; height:16px; background:#e5e7eb; margin:0 auto; }
        .status-bar { background:#f9fafb; border:1px solid #f1f5f9; border-radius:10px; padding:14px 16px; display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
        .status-pill{ display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
        .foto-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:8px; }
        @media(max-width:480px){ .foto-grid{ grid-template-columns:repeat(3,1fr); } }
        .foto-thumb { aspect-ratio:1; border-radius:8px; overflow:hidden; border:1px solid #e5e7eb; position:relative; cursor:pointer; }
        .foto-thumb img  { width:100%; height:100%; object-fit:cover; display:block; }
        .foto-placeholder{ width:100%; height:100%; background:#f9fafb; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; }
        .foto-label{ position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,.5); color:#fff; font-size:10px; text-align:center; padding:3px 4px; }
        .zona-bahaya { border:1px solid #fecaca; border-radius:1rem; overflow:hidden; margin-bottom:1rem; }
        .zona-bahaya-head { padding:.65rem 1.1rem; border-bottom:1px solid #fecaca; display:flex; align-items:center; gap:8px; background:#fff5f5; }
        .detail-layout { display:grid; grid-template-columns:1fr 320px; gap:1rem; align-items:start; }
        @media(max-width:900px){ .detail-layout{ grid-template-columns:1fr; } }
        .badge-layak { background:#dcfce7; color:#15803d; padding:5px 14px; border-radius:20px; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:5px; }
        .badge-tl    { background:#fee2e2; color:#b91c1c; padding:5px 14px; border-radius:20px; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:5px; }
        .badge-draft { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:500; background:rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.25); }
    </style>

    @php
        $prediksi     = $calonPenerima->prediksiKelayakan;
        $prob         = 0;
        $label        = null;
        if ($prediksi) {
            $prob  = $prediksi->probability <= 1 ? round($prediksi->probability * 100) : round($prediksi->probability);
            $label = $prediksi->recommendation; // 'Layak' atau 'Tidak Layak'
        }
        $isLayak      = $label === 'Layak';
        $ringColor    = $isLayak ? '#22c55e' : ($label ? '#ef4444' : '#d1d5db');
        $r            = 28;
        $circ         = round(2 * M_PI * $r, 2);
        $fill         = round($circ * $prob / 100, 2);
        $gap          = $circ - $fill;
        $currentStatus = $calonPenerima->tracking_status ?? 'draft';
    @endphp

    {{-- HERO --}}
    <div class="hero-banner">
        <div class="hero-bubble" style="width:180px;height:180px;background:rgba(255,255,255,.06);top:-40px;right:-30px;"></div>
        <div class="hero-bubble" style="width:90px;height:90px;background:rgba(255,255,255,.04);top:50px;right:100px;"></div>
        <div class="flex items-start justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                    {{ strtoupper(substr($calonPenerima->nama_lengkap, 0, 1)) }}
                </div>
                <div>
                    <div class="text-white font-semibold text-xl leading-tight">{{ $calonPenerima->nama_lengkap }}</div>
                    <div class="text-white/70 text-sm">NIK: {{ $calonPenerima->nik }}</div>
                    <div class="mt-1.5">
                        <span class="badge-draft">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 inline-block"></span>
                            {{ ucfirst($currentStatus) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                @if($prediksi)
                    <div class="text-white font-semibold leading-none" style="font-size:42px;">
                        {{ $prob }}<span style="font-size:20px;">%</span>
                    </div>
                    <div class="text-white/65 text-xs mt-1">probabilitas</div>
                    <div class="mt-1.5">
                        @if($isLayak)
                            <span class="badge-layak">● Layak</span>
                        @else
                            <span class="badge-tl">● Tidak Layak</span>
                        @endif
                    </div>
                @else
                    <div class="text-white/50 text-sm mt-2">Belum diprediksi</div>
                @endif
            </div>
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="flex items-center gap-2 flex-wrap mb-4">
        @if($currentStatus === 'draft')
        <a href="{{ route('rt.calon-penerima.edit', $calonPenerima->id) }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 16H9v-2.828z"/></svg>
            Edit Data
        </a>
        <form action="{{ route('rt.calon-penerima.ajukan', $calonPenerima->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition shadow-md shadow-blue-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Ajukan ke perangkat desa
            </button>
        </form>
        @endif
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="detail-layout">

        {{-- ═══ KOLOM KIRI ═══ --}}
        <div>

            {{-- IDENTITAS --}}
            <div class="detail-card">
                <div class="detail-card-head">
                    <div class="detail-card-accent" style="background:#2563eb;"></div>
                    <span class="detail-card-title">Identitas</span>
                </div>
                <div class="detail-card-body">
                    <div class="data-grid">
                        <div><div class="data-lbl">No. KK</div><div class="data-val">{{ $calonPenerima->no_kk }}</div></div>
                        <div><div class="data-lbl">Jenis Kelamin</div><div class="data-val">{{ $calonPenerima->jenis_kelamin }}</div></div>
                        <div><div class="data-lbl">Tempat Lahir</div><div class="data-val">{{ $calonPenerima->tempat_lahir }}</div></div>
                        <div><div class="data-lbl">Tanggal Lahir</div><div class="data-val">{{ \Carbon\Carbon::parse($calonPenerima->tanggal_lahir)->translatedFormat('d F Y') }}</div></div>
                        <div><div class="data-lbl">Usia</div><div class="data-val">{{ $calonPenerima->usia }} tahun</div></div>
                        <div><div class="data-lbl">Status Kawin</div><div class="data-val">{{ $calonPenerima->status_perkawinan }}</div></div>
                    </div>
                </div>
            </div>

            {{-- TEMPAT TINGGAL --}}
            <div class="detail-card">
                <div class="detail-card-head">
                    <div class="detail-card-accent" style="background:#10b981;"></div>
                    <span class="detail-card-title">Tempat Tinggal</span>
                </div>
                <div class="detail-card-body">
                    <div class="data-grid">
                        <div style="grid-column:span 2;">
                            <div class="data-lbl">Alamat</div>
                            <div class="data-val">{{ $calonPenerima->alamat }}</div>
                        </div>
                        <div><div class="data-lbl">Dusun</div><div class="data-val">{{ $calonPenerima->desa }}</div></div>
                        <div>
                            <div class="data-lbl">RT</div>
                            <div class="data-val"><span class="badge-rt">RT {{ str_pad($calonPenerima->rt->nomor_rt ?? '?', 3, '0', STR_PAD_LEFT) }}</span></div>
                        </div>
                        <div><div class="data-lbl">Aset Kepemilikan</div><div class="data-val">{{ $calonPenerima->aset_kepemilikan ?? '-' }}</div></div>
                        <div><div class="data-lbl">Lantai</div><div class="data-val">{{ $calonPenerima->lantai_rumah }}</div></div>
                        <div><div class="data-lbl">Dinding</div><div class="data-val">{{ $calonPenerima->dinding_rumah }}</div></div>
                        <div><div class="data-lbl">Atap</div><div class="data-val">{{ $calonPenerima->atap_rumah }}</div></div>
                        <div><div class="data-lbl">Luas Rumah</div><div class="data-val">{{ $calonPenerima->luas_rumah_m2 }} m²</div></div>
                        <div><div class="data-lbl">Status Kepemilikan</div><div class="data-val">{{ $calonPenerima->status_kepemilikan_rumah }}</div></div>
                        <div><div class="data-lbl">Meteran Listrik</div><div class="data-val">{{ $calonPenerima->meteran_listrik }}</div></div>
                        <div><div class="data-lbl">Sumber Air</div><div class="data-val">{{ $calonPenerima->sumber_air }}</div></div>
                    </div>
                </div>
            </div>

            {{-- DATA EKONOMI --}}
            <div class="detail-card">
                <div class="detail-card-head">
                    <div class="detail-card-accent" style="background:#f59e0b;"></div>
                    <span class="detail-card-title">Data Ekonomi</span>
                </div>
                <div class="detail-card-body">
                    <div class="data-grid">
                        <div><div class="data-lbl">Pekerjaan</div><div class="data-val">{{ $calonPenerima->pekerjaan }}</div></div>
                        <div><div class="data-lbl">Penghasilan</div><div class="data-val">Rp {{ number_format($calonPenerima->penghasilan, 0, ',', '.') }}</div></div>
                        <div><div class="data-lbl">Tanggungan</div><div class="data-val">{{ $calonPenerima->jumlah_tanggungan }} orang</div></div>
                        <div>
                            <div class="data-lbl">Bantuan Lain</div>
                            <div class="data-val">
                                @if($calonPenerima->bantuan_lain === 'ya')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Ya</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Tidak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOTO & DOKUMEN --}}
            <div class="detail-card">
                <div class="detail-card-head">
                    <div class="detail-card-accent" style="background:#e11d48;"></div>
                    <span class="detail-card-title">Foto & Dokumen</span>
                </div>
                <div class="detail-card-body">
                    @php
                        $fotoFields = [
                            'foto_rumah_depan'      => 'Rumah Depan',
                            'foto_rumah_belakang'   => 'Rumah Belakang',
                            'foto_rumah_kanan'      => 'Rumah Kanan',
                            'foto_rumah_kiri'       => 'Rumah Kiri',
                            'foto_kk'               => 'Foto KK',
                            'foto_ktp'              => 'Foto KTP',
                            'foto_rekening_listrik' => 'Rekening Listrik',
                            'foto_meteran_air'      => 'Meteran Air',
                        ];
                    @endphp
                    <div class="foto-grid">
                        @foreach($fotoFields as $field => $label)
                            <div class="foto-thumb" @if($calonPenerima->$field) onclick="openFoto('{{ asset('storage/' . $calonPenerima->$field) }}')" @endif>
                                @if($calonPenerima->$field)
                                    <img src="{{ asset('storage/' . $calonPenerima->$field) }}" alt="{{ $label }}" loading="lazy">
                                @else
                                    <div class="foto-placeholder">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs text-gray-400">Belum ada</span>
                                    </div>
                                @endif
                                <div class="foto-label">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>
                    @if($calonPenerima->dokumen_pendukung)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <a href="{{ asset('storage/' . $calonPenerima->dokumen_pendukung) }}" target="_blank"
                            class="inline-flex items-center gap-2 text-sm text-blue-600 hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            Lihat Dokumen Pendukung
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- TRACKING PENGAJUAN --}}
            <div class="detail-card">
                <div class="detail-card-head">
                    <div class="detail-card-accent" style="background:#8b5cf6;"></div>
                    <span class="detail-card-title">Tracking Pengajuan</span>
                </div>
                <div class="detail-card-body" style="padding-top:.5rem;padding-bottom:.5rem;">
                    @php
                        $steps = [
                            'draft'    => ['label' => 'Data dibuat RT',               'desc' => 'Data masih bisa diubah dan dihapus sebelum diajukan.'],
                            'terkirim' => ['label' => 'Data diajukan ke kelurahan',   'desc' => 'Menunggu tindakan RT untuk mengajukan data.'],
                            'validasi' => ['label' => 'Proses validasi & filterisasi','desc' => 'Menunggu admin memulai proses validasi.'],
                            'final'    => ['label' => 'Hasil akhir ditetapkan',       'desc' => 'Menunggu hasil akhir dari admin perangkat desa.'],
                        ];
                        $statusOrder  = array_keys($steps);
                        $currentIndex = array_search($currentStatus, $statusOrder) ?? 0;
                    @endphp
                    @foreach($steps as $key => $step)
                        @php
                            $stepIndex = array_search($key, $statusOrder);
                            $isDone    = $stepIndex < $currentIndex;
                            $isActive  = $stepIndex === $currentIndex;
                        @endphp
                        <div class="track-step">
                            <div class="flex flex-col items-center">
                                <div class="track-num {{ $isDone ? 'step-done' : ($isActive ? 'step-active' : 'step-pending') }}">
                                    @if($isDone)
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        {{ $stepIndex + 1 }}
                                    @endif
                                </div>
                                @if(!$loop->last)<div class="track-line"></div>@endif
                            </div>
                            <div class="pb-1">
                                <div class="text-sm font-semibold {{ $isActive ? 'text-gray-800' : ($isDone ? 'text-gray-700' : 'text-gray-400') }} leading-snug">
                                    {{ $step['label'] }}
                                </div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $step['desc'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- STATUS PENGAJUAN --}}
            <div class="detail-card">
                <div class="detail-card-head">
                    <div class="detail-card-accent" style="background:#64748b;"></div>
                    <span class="detail-card-title">Status Pengajuan</span>
                </div>
                <div class="detail-card-body">
                    <div class="status-bar">
                        @php
                            $statusMap = [
                                'draft'    => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'dot' => 'bg-yellow-400', 'label' => 'Draft'],
                                'terkirim' => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'dot' => 'bg-blue-400',   'label' => 'Diajukan'],
                                'validasi' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'dot' => 'bg-purple-400', 'label' => 'Validasi'],
                                'final'    => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'dot' => 'bg-green-400',  'label' => 'Final'],
                            ];
                            $s = $statusMap[$currentStatus] ?? $statusMap['draft'];
                        @endphp
                        <span class="status-pill {{ $s['bg'] }} {{ $s['text'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }} inline-block"></span>
                            {{ $s['label'] }}
                        </span>
                        <div class="flex gap-6 text-sm text-gray-500 flex-wrap">
                            <div>
                                <span class="text-xs text-gray-400 block">Dibuat</span>
                                <span class="font-medium text-gray-700">{{ $calonPenerima->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Diperbarui</span>
                                <span class="font-medium text-gray-700">{{ $calonPenerima->updated_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end kolom kiri --}}

        {{-- ═══ KOLOM KANAN: PREDIKSI ═══ --}}
        <div>
            <div style="position:sticky;top:1rem;">

                <div class="detail-card">
                    <div class="detail-card-head">
                        <div class="detail-card-accent" style="background:#22c55e;"></div>
                        <span class="detail-card-title">Prediksi Kelayakan</span>
                    </div>
                    <div class="detail-card-body">

                        {{-- Donut --}}
                        <div class="flex items-center gap-4 mb-4">
                            <svg width="80" height="80" viewBox="0 0 72 72">
                                <circle cx="36" cy="36" r="{{ $r }}" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                                <circle cx="36" cy="36" r="{{ $r }}" fill="none"
                                    stroke="{{ $ringColor }}" stroke-width="8"
                                    stroke-dasharray="{{ $fill }} {{ $gap }}"
                                    stroke-linecap="round"
                                    class="donut-ring"/>
                                <text x="36" y="40" text-anchor="middle" font-size="13" font-weight="600" fill="#111827">{{ $prob }}%</text>
                            </svg>
                            <div>
                                <div class="text-xs text-gray-400 mb-1.5">Rekomendasi</div>
                                @if($prediksi)
                                    @if($isLayak)
                                        <span class="badge-layak">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Layak
                                        </span>
                                    @else
                                        <span class="badge-tl">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Tidak Layak
                                        </span>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400">Belum diprediksi</span>
                                @endif
                                <div class="progress-track mt-2" style="width:140px;">
                                    <div style="height:100%;border-radius:3px;background:{{ $ringColor }};width:{{ $prob }}%;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Faktor Pendukung --}}
                        @if(!empty($explanation['positive']))
                        <div class="mb-3">
                            <div class="text-xs font-semibold uppercase tracking-wider text-green-700 mb-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Faktor Pendukung
                            </div>
                            @foreach($explanation['positive'] as $f)
                                <div class="faktor-item faktor-pos">
                                    <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    {{ $f }}
                                </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Faktor Pengurang --}}
                        @if(!empty($explanation['negative']))
                        <div class="mb-3">
                            <div class="text-xs font-semibold uppercase tracking-wider text-red-700 mb-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Faktor Pengurang
                            </div>
                            @foreach($explanation['negative'] as $f)
                                <div class="faktor-item faktor-neg">
                                    <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    {{ $f }}
                                </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Penjelasan Lengkap — selalu terbuka --}}
                        <div class="mb-3">
                            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Penjelasan Lengkap</div>
                            <div class="border border-gray-100 rounded-xl overflow-hidden">
                                @php
                                    $penjelasanRows = [
                                        'Pekerjaan'       => $calonPenerima->pekerjaan,
                                        'Penghasilan'     => 'Rp ' . number_format($calonPenerima->penghasilan, 0, ',', '.'),
                                        'Tanggungan'      => $calonPenerima->jumlah_tanggungan . ' orang',
                                        'Aset'            => $calonPenerima->aset_kepemilikan ?? '-',
                                        'Bantuan Lain'    => ucfirst($calonPenerima->bantuan_lain),
                                        'Usia'            => $calonPenerima->usia . ' tahun',
                                        'Kondisi Rumah'   => ($calonPenerima->kondisi_rumah ?? '-'),
                                        'Meteran Listrik' => $calonPenerima->meteran_listrik,
                                        'Sumber Air'      => $calonPenerima->sumber_air,
                                    ];
                                @endphp
                                @foreach($penjelasanRows as $k => $v)
                                    <div class="penjelasan-row px-3">
                                        <span class="text-gray-400">{{ $k }}</span>
                                        <span class="text-gray-800 font-medium text-right ml-2">{{ $v }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Ringkasan --}}
                        @if(!empty($explanation['summary']))
                        <div class="ringkasan-box">
                            <div class="text-xs font-semibold uppercase tracking-wider text-sky-700 mb-1.5">Ringkasan</div>
                            <p class="text-sm text-sky-900 leading-relaxed">{{ $explanation['summary'] }}</p>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- ZONA BAHAYA --}}
                @if($currentStatus === 'draft')
                <div class="zona-bahaya">
                    <div class="zona-bahaya-head">
                        <div class="detail-card-accent" style="background:#ef4444;"></div>
                        <span class="detail-card-title" style="color:#b91c1c;">Zona Bahaya</span>
                    </div>
                    <div class="detail-card-body">
                        <p class="text-sm text-gray-500 mb-3">
                            Hapus data ini secara permanen. Tindakan ini <strong class="text-gray-700">tidak dapat dibatalkan</strong>.
                        </p>
                        <form action="{{ route('rt.calon-penerima.destroy', $calonPenerima->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus data {{ $calonPenerima->nama_lengkap }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl bg-red-600 text-white hover:bg-red-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>

    {{-- LIGHTBOX --}}
    <div id="lightbox" class="fixed inset-0 z-50 hidden" style="background:rgba(0,0,0,.8);" onclick="closeLightbox()">
        <div class="flex items-center justify-center h-full p-4">
            <img id="lightboxImg" src="" alt="Foto" class="max-w-full max-h-full rounded-xl object-contain shadow-2xl">
        </div>
    </div>

    <script>
        function openFoto(src) {
            document.getElementById('lightboxImg').src = src;
            document.getElementById('lightbox').classList.remove('hidden');
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
    </script>
</x-app-layout>

