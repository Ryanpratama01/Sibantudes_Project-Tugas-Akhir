<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Pendataan Warga</h2>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui data dengan benar sebelum menyimpan.</p>
            </div>
            <a href="{{ route('rt.calon-penerima.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <style>
        .input-field { font-size: 16px !important; }
        @media (min-width: 640px) { .input-field { font-size: 14px !important; } }

        .section-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            padding: 16px;
        }
        @media (min-width: 640px) {
            .section-grid { grid-template-columns: 1fr 1fr; padding: 18px; gap: 16px; }
        }
        @media (min-width: 1024px) {
            .section-grid { grid-template-columns: repeat(4, 1fr); padding: 20px; }
        }

        .col-span-2-up { grid-column: span 1; }
        @media (min-width: 640px) { .col-span-2-up { grid-column: span 2; } }

        .section-head { padding: 10px 16px; }
        @media (min-width: 640px) { .section-head { padding: 10px 20px; } }

        .action-row {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }
        @media (max-width: 480px) {
            .action-row { flex-direction: column-reverse; }
            .action-row a,
            .action-row button { width: 100%; justify-content: center; text-align: center; }
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .aset-checkbox-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 8px; }
        .aset-checkbox-item { display: flex; align-items: center; gap: 8px; padding: 8px 12px;
            border: 1px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: background .15s, border-color .15s; }
        .aset-checkbox-item:has(input:checked) { background: #eff6ff; border-color: #93c5fd; }
        .aset-checkbox-item input[type=checkbox] { accent-color: #2563eb; width: 15px; height: 15px; flex-shrink: 0; }
        .aset-checkbox-item span { font-size: 13px; color: #374151; }
    </style>

    @if ($errors->any())
        <div class="mb-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <svg class="w-4 h-4 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
            </svg>
            <div>
                <div class="font-semibold mb-1">Gagal menyimpan, mohon perbaiki:</div>
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('rt.calon-penerima.update', $calonPenerima->id) }}" method="POST" id="formEditPendataan" novalidate enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="rt_id" value="{{ $calonPenerima->rt_id }}">

        {{-- SECTION 1: Data Identitas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
            <div class="section-head border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Identitas</h3>
            </div>
            <div class="section-grid">

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">RT</label>
                    <input type="text"
                        value="RT {{ str_pad($calonPenerima->rt->nomor_rt ?? 0, 3, '0', STR_PAD_LEFT) }}"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 bg-gray-100 text-gray-700 cursor-not-allowed"
                        style="font-size:16px;" readonly>
                    <p class="text-[11px] text-gray-400 mt-1">RT mengikuti akun RT yang sedang login.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">No. KK <span class="text-red-500">*</span></label>
                    <input type="text" name="no_kk" id="no_kk" maxlength="16"
                        value="{{ old('no_kk', $calonPenerima->no_kk) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="16 digit No. KK" required inputmode="numeric">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">No. KK harus 16 digit angka.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" id="nik" maxlength="16"
                        value="{{ old('nik', $calonPenerima->nik) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="16 digit NIK" required inputmode="numeric">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">NIK harus tepat 16 digit angka.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                        value="{{ old('nama_lengkap', $calonPenerima->nama_lengkap) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Nama sesuai KTP" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Nama minimal 3 karakter.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="Laki-laki" {{ old('jenis_kelamin', $calonPenerima->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $calonPenerima->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                        value="{{ old('tempat_lahir', $calonPenerima->tempat_lahir) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Kota/Kabupaten" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Tempat lahir wajib diisi.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $calonPenerima->tanggal_lahir ? \Carbon\Carbon::parse($calonPenerima->tanggal_lahir)->format('Y-m-d') : '') }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Tanggal lahir wajib diisi.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Usia <span class="text-red-500">*</span></label>
                    <input type="number" name="usia" id="usia" min="17" max="100"
                        value="{{ old('usia', $calonPenerima->usia) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Tahun" required inputmode="numeric">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Usia harus antara 17–100 tahun.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status Perkawinan <span class="text-red-500">*</span></label>
                    <select name="status_perkawinan" id="status_perkawinan"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        @foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $sp)
                            <option value="{{ $sp }}" {{ old('status_perkawinan', $calonPenerima->status_perkawinan) === $sp ? 'selected' : '' }}>{{ $sp }}</option>
                        @endforeach
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Status perkawinan wajib dipilih.</p>
                </div>

            </div>
        </div>

        {{-- SECTION 2: Data Tempat Tinggal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
            <div class="section-head border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Tempat Tinggal</h3>
            </div>
            <div class="section-grid">

                <div class="field-group col-span-2-up">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat <span class="text-red-500">*</span></label>
                    <input type="text" name="alamat" id="alamat"
                        value="{{ old('alamat', $calonPenerima->alamat) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Jalan, nomor rumah, RT/RW" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Alamat minimal 5 karakter.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dusun</label>
                    <input type="text" name="desa" id="desa"
                        value="{{ old('desa', $calonPenerima->desa) }}"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 bg-gray-100 text-gray-700 cursor-not-allowed"
                        style="font-size:16px;" readonly>
                    <p class="text-[11px] text-gray-400 mt-1">Dusun mengikuti RT dan tidak dapat diubah.</p>
                </div>

            </div>
        </div>

        {{-- SECTION 3: Data Ekonomi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
            <div class="section-head border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-amber-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Ekonomi</h3>
            </div>
            <div class="section-grid">

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Pekerjaan <span class="text-red-500">*</span></label>
                    <select name="pekerjaan" id="pekerjaan"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        @foreach(['Tidak Bekerja','Buruh Harian Lepas','Petani Kecil','Mengurus Rumah Tangga','Pedagang','Wiraswasta','Karyawan Swasta','Guru Honorer','PNS','TNI/Polri'] as $pkj)
                            <option value="{{ $pkj }}" {{ old('pekerjaan', $calonPenerima->pekerjaan) === $pkj ? 'selected' : '' }}>{{ $pkj }}</option>
                        @endforeach
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Pekerjaan wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Penghasilan (Rp) <span class="text-red-500">*</span></label>
                    <input type="text" id="penghasilan_display"
                        value="{{ old('penghasilan', $calonPenerima->penghasilan) ? number_format((float) old('penghasilan', $calonPenerima->penghasilan), 0, ',', '.') : '' }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="0" inputmode="numeric" autocomplete="off">
                    <input type="hidden" name="penghasilan" id="penghasilan"
                        value="{{ old('penghasilan', $calonPenerima->penghasilan) }}">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Penghasilan tidak boleh negatif.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah Tanggungan <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_tanggungan" id="jumlah_tanggungan" min="0"
                        value="{{ old('jumlah_tanggungan', $calonPenerima->jumlah_tanggungan) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Jumlah orang" required inputmode="numeric">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Jumlah tanggungan tidak boleh negatif.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Menerima Bantuan Lain? <span class="text-red-500">*</span></label>
                    <select name="bantuan_lain"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="tidak" {{ old('bantuan_lain', $calonPenerima->bantuan_lain) === 'tidak' ? 'selected' : '' }}>Tidak</option>
                        <option value="ya" {{ old('bantuan_lain', $calonPenerima->bantuan_lain) === 'ya' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>

            </div>

            {{-- Aset Kepemilikan --}}
            <div style="padding: 0 16px 16px;">
                @php
                    $asetOptions = ['motor'=>'Motor','motor tua'=>'Motor Tua','tanah'=>'Tanah','emas'=>'Emas','mobil'=>'Mobil','rumah'=>'Rumah','sepeda'=>'Sepeda','ternak'=>'Ternak'];
                    $asetLama = strtolower(old('aset_kepemilikan', $calonPenerima->aset_kepemilikan ?? ''));
                    $asetLamaArr = array_map('trim', explode(',', $asetLama));
                    $asetManualVal = '';
                    $asetChecked = [];
                    foreach ($asetLamaArr as $item) {
                        if (array_key_exists($item, $asetOptions)) {
                            $asetChecked[] = $item;
                        } else if ($item !== '' && $item !== 'tidak punya') {
                            $asetManualVal .= ($asetManualVal ? ', ' : '') . $item;
                        }
                    }
                @endphp
                <label class="block text-xs font-semibold text-gray-600 mb-2">
                    Aset Kepemilikan <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal ml-1">(centang semua yang dimiliki)</span>
                </label>
                <div class="aset-checkbox-grid mb-3" id="aset-checkbox-grid">
                    @foreach($asetOptions as $val => $label)
                    <label class="aset-checkbox-item">
                        <input type="checkbox" name="aset_pilihan[]" value="{{ $val }}"
                            {{ in_array($val, $asetChecked) ? 'checked' : '' }}>
                        <span>{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs text-gray-500">Aset lain (tulis manual, pisahkan dengan koma):</label>
                    <input type="text" name="aset_manual" id="aset_manual"
                        value="{{ $asetManualVal }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Contoh: sawah, kebun, mesin jahit">
                </div>
                <p class="error-msg-aset text-xs text-red-500 mt-1 hidden">Aset kepemilikan wajib diisi.</p>
            </div>
        </div>

        {{-- SECTION 4: Kondisi Tempat Tinggal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
            <div class="section-head border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-purple-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Kondisi Tempat Tinggal</h3>
            </div>
            <div class="section-grid" style="grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));">

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Lantai Rumah <span class="text-red-500">*</span></label>
                    <select name="lantai_rumah" id="lantai_rumah"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        @foreach(['Tanah','Papan/Kayu','Semen Kasar','Keramik Murah','Keramik'] as $opt)
                            <option value="{{ $opt }}" {{ old('lantai_rumah', $calonPenerima->lantai_rumah) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Lantai rumah wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dinding Rumah <span class="text-red-500">*</span></label>
                    <select name="dinding_rumah" id="dinding_rumah"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        @foreach(['Bambu','Anyaman Bambu/Gedek','Kayu/Papan','Tembok Tidak Plester','Tembok Plester'] as $opt)
                            <option value="{{ $opt }}" {{ old('dinding_rumah', $calonPenerima->dinding_rumah) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Dinding rumah wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Atap Rumah <span class="text-red-500">*</span></label>
                    <select name="atap_rumah" id="atap_rumah"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        @foreach(['Bambu/Jerami','Seng','Campuran Seng+Genteng','Genteng','Genteng Beton'] as $opt)
                            <option value="{{ $opt }}" {{ old('atap_rumah', $calonPenerima->atap_rumah) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Atap rumah wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Luas Rumah (m²) <span class="text-red-500">*</span></label>
                    <input type="number" name="luas_rumah_m2" id="luas_rumah_m2" min="1"
                        value="{{ old('luas_rumah_m2', $calonPenerima->luas_rumah_m2) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Contoh: 36" required inputmode="numeric">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Luas rumah wajib diisi.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status Kepemilikan Rumah <span class="text-red-500">*</span></label>
                    <select name="status_kepemilikan_rumah" id="status_kepemilikan_rumah"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        @foreach(['Menumpang','Sewa/Kontrak','Milik Sendiri'] as $opt)
                            <option value="{{ $opt }}" {{ old('status_kepemilikan_rumah', $calonPenerima->status_kepemilikan_rumah) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Status kepemilikan rumah wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Meteran Listrik <span class="text-red-500">*</span></label>
                    <select name="meteran_listrik" id="meteran_listrik"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        @foreach(['450VA'=>'450 VA','900VA'=>'900 VA','1300VA+'=>'1300 VA ke atas'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('meteran_listrik', $calonPenerima->meteran_listrik) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Meteran listrik wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Sumber Air <span class="text-red-500">*</span></label>
                    <select name="sumber_air" id="sumber_air"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        @foreach(['Sungai','Sumur','PDAM'] as $opt)
                            <option value="{{ $opt }}" {{ old('sumber_air', $calonPenerima->sumber_air) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Sumber air wajib dipilih.</p>
                </div>

            </div>
        </div>

        {{-- SECTION 5: Upload Foto --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-5 overflow-hidden">
            <div class="section-head border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-rose-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Upload Dokumen & Foto</h3>
                <span class="text-xs text-gray-400 font-normal">(kosongkan jika tidak ingin mengganti)</span>
            </div>
            <div class="section-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">

                @php
                    $fotoFields = [
                        'foto_rumah_depan'      => 'Foto Rumah Depan',
                        'foto_rumah_belakang'   => 'Foto Rumah Belakang',
                        'foto_rumah_kanan'      => 'Foto Rumah Kanan',
                        'foto_rumah_kiri'       => 'Foto Rumah Kiri',
                        'foto_kk'               => 'Foto KK',
                        'foto_ktp'              => 'Foto KTP',
                        'foto_meteran_air'      => 'Foto Meteran Air',
                    ];
                @endphp

                @foreach($fotoFields as $field => $label)
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $label }}</label>
                    @if($calonPenerima->$field)
                        <div class="mb-1">
                            <img src="{{ asset('storage/' . $calonPenerima->$field) }}"
                                class="w-full h-24 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                    <input type="file" name="{{ $field }}" accept="image/*"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
                @endforeach

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Foto Rekening Listrik</label>
                    @if($calonPenerima->foto_rekening_listrik)
                        <div class="mb-1 text-xs text-blue-600">
                            <a href="{{ asset('storage/' . $calonPenerima->foto_rekening_listrik) }}" target="_blank">Lihat file saat ini</a>
                        </div>
                    @endif
                    <input type="file" name="foto_rekening_listrik" accept="image/*,.pdf"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dokumen Pendukung Lainnya</label>
                    @if($calonPenerima->dokumen_pendukung)
                        <div class="mb-1 text-xs text-blue-600">
                            <a href="{{ asset('storage/' . $calonPenerima->dokumen_pendukung) }}" target="_blank">Lihat file saat ini</a>
                        </div>
                    @endif
                    <input type="file" name="dokumen_pendukung" accept="image/*,.pdf,.doc,.docx"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>

            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="action-row">
            <a href="{{ route('rt.calon-penerima.index') }}"
               class="inline-flex items-center gap-1.5 px-5 py-2 text-sm font-medium bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" id="btnSimpan"
                style="display:inline-flex;align-items:center;gap:8px;"
                class="px-6 py-2 text-sm font-semibold bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-200">
                <svg id="btnSpinner"
                    style="display:none;width:16px;height:16px;animation:spin .7s linear infinite;flex-shrink:0;"
                    fill="none" viewBox="0 0 24 24">
                    <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                <span id="btnText">Simpan Perubahan</span>
            </button>
        </div>

    </form>

    {{-- TOAST --}}
    <div id="toast" class="fixed bottom-5 right-5 z-50 hidden" style="max-width:calc(100vw - 40px);">
        <div class="flex items-center gap-3 bg-red-500 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-lg">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
            </svg>
            <span id="toast-msg">Ada field yang tidak valid.</span>
        </div>
    </div>

    <script>
        function showError(id, msg) {
            const input = document.getElementById(id);
            const err = input?.parentElement?.querySelector('.error-msg');
            if (input) input.classList.add('border-red-400', 'bg-red-50');
            if (err) { err.textContent = msg; err.classList.remove('hidden'); }
        }
        function clearError(id) {
            const input = document.getElementById(id);
            const err = input?.parentElement?.querySelector('.error-msg');
            if (input) input.classList.remove('border-red-400', 'bg-red-50');
            if (err) err.classList.add('hidden');
        }
        function showToast(msg) {
            const t = document.getElementById('toast');
            document.getElementById('toast-msg').textContent = msg;
            t.classList.remove('hidden');
            setTimeout(() => t.classList.add('hidden'), 3500);
        }

        function validateAset() {
            const checked = document.querySelectorAll('input[name="aset_pilihan[]"]:checked').length > 0;
            const manual  = (document.getElementById('aset_manual')?.value ?? '').trim() !== '';
            const errEl   = document.querySelector('.error-msg-aset');
            if (!checked && !manual) {
                if (errEl) errEl.classList.remove('hidden');
                document.getElementById('aset-checkbox-grid')?.classList.add('ring-1', 'ring-red-400', 'rounded-xl');
                return false;
            }
            if (errEl) errEl.classList.add('hidden');
            document.getElementById('aset-checkbox-grid')?.classList.remove('ring-1', 'ring-red-400', 'rounded-xl');
            return true;
        }

        document.querySelectorAll('input[name="aset_pilihan[]"]').forEach(cb => cb.addEventListener('change', validateAset));
        document.getElementById('aset_manual')?.addEventListener('input', validateAset);

        const rules = {
            nik:                      v => /^\d{16}$/.test(v) ? null : 'NIK harus tepat 16 digit angka.',
            no_kk:                    v => /^\d{16}$/.test(v) ? null : 'No. KK harus tepat 16 digit angka.',
            nama_lengkap:             v => v.trim().length >= 3 ? null : 'Nama minimal 3 karakter.',
            tempat_lahir:             v => v.trim().length >= 2 ? null : 'Tempat lahir wajib diisi.',
            tanggal_lahir:            v => v !== '' ? null : 'Tanggal lahir wajib diisi.',
            usia:                     v => (parseInt(v) >= 17 && parseInt(v) <= 100) ? null : 'Usia harus antara 17–100.',
            status_perkawinan:        v => v !== '' ? null : 'Status perkawinan wajib dipilih.',
            alamat:                   v => v.trim().length >= 5 ? null : 'Alamat minimal 5 karakter.',
            desa:                     v => v.trim().length >= 2 ? null : 'Dusun wajib diisi.',
            pekerjaan:                v => v !== '' ? null : 'Pekerjaan wajib dipilih.',
            penghasilan:              v => (v !== '' && parseFloat(v) >= 0) ? null : 'Penghasilan tidak boleh negatif.',
            jumlah_tanggungan:        v => parseInt(v) >= 0 ? null : 'Jumlah tanggungan tidak boleh negatif.',
            lantai_rumah:             v => v !== '' ? null : 'Lantai rumah wajib dipilih.',
            dinding_rumah:            v => v !== '' ? null : 'Dinding rumah wajib dipilih.',
            atap_rumah:               v => v !== '' ? null : 'Atap rumah wajib dipilih.',
            luas_rumah_m2:            v => parseInt(v) >= 1 ? null : 'Luas rumah wajib diisi.',
            status_kepemilikan_rumah: v => v !== '' ? null : 'Status kepemilikan rumah wajib dipilih.',
            meteran_listrik:          v => v !== '' ? null : 'Meteran listrik wajib dipilih.',
            sumber_air:               v => v !== '' ? null : 'Sumber air wajib dipilih.',
        };

        Object.keys(rules).forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            ['blur','input','change'].forEach(ev => {
                el.addEventListener(ev, () => {
                    const err = rules[id](el.value);
                    err ? showError(id, err) : clearError(id);
                });
            });
        });

        document.getElementById('tanggal_lahir')?.addEventListener('change', function () {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
            if (age >= 0 && age <= 150) { document.getElementById('usia').value = age; clearError('usia'); }
        });

        const displayInput = document.getElementById('penghasilan_display');
        const hiddenInput  = document.getElementById('penghasilan');
        if (displayInput && hiddenInput) {
            displayInput.addEventListener('input', function () {
                let raw = this.value.replace(/\D/g, '');
                this.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
                hiddenInput.value = raw;
                const err = rules['penghasilan'](hiddenInput.value);
                err ? showError('penghasilan', err) : clearError('penghasilan');
            });
            displayInput.addEventListener('blur', function () {
                const err = rules['penghasilan'](hiddenInput.value);
                err ? showError('penghasilan', err) : clearError('penghasilan');
            });
        }

        document.getElementById('formEditPendataan').addEventListener('submit', function (e) {
            if (displayInput && hiddenInput && hiddenInput.value === '') {
                hiddenInput.value = displayInput.value.replace(/\D/g, '');
            }
            let valid = true;
            Object.keys(rules).forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                const err = rules[id](el.value);
                if (err) { showError(id, err); valid = false; }
                else clearError(id);
            });
            if (!validateAset()) valid = false;
            if (!valid) {
                e.preventDefault();
                showToast('Mohon perbaiki field yang tidak valid sebelum menyimpan.');
                document.querySelector('.border-red-400, .ring-red-400')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            const btn = document.getElementById('btnSimpan');
            btn.disabled = true;
            btn.style.opacity = '0.75';
            btn.style.cursor = 'not-allowed';
            document.getElementById('btnSpinner').style.display = 'block';
            document.getElementById('btnText').textContent = 'Menyimpan...';
        });
    </script>
</x-app-layout>