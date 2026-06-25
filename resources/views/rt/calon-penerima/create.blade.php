<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Form Pendataan Warga</h2>
            <p class="text-sm text-gray-500 mt-0.5">Isi semua data dengan benar sebelum menyimpan.</p>
        </div>
    </x-slot>

    <style>
        .input-field { font-size: 16px !important; }
        @media (min-width: 640px) { .input-field { font-size: 14px !important; } }
        @media (max-width: 480px) {
            .action-row { flex-direction: column-reverse !important; }
            .action-row a, .action-row button { width: 100%; justify-content: center; text-align: center; }
        }
        @media (max-width: 640px) {
            .section-body { padding: 14px 14px !important; }
            .section-head { padding: 10px 14px !important; }
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Checkbox aset */
        .aset-checkbox-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 8px; }
        .aset-checkbox-item { display: flex; align-items: center; gap: 8px; padding: 8px 12px;
            border: 1px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: background .15s, border-color .15s; }
        .aset-checkbox-item:has(input:checked) { background: #eff6ff; border-color: #93c5fd; }
        .aset-checkbox-item input[type=checkbox] { accent-color: #2563eb; width: 15px; height: 15px; flex-shrink: 0; }
        .aset-checkbox-item span { font-size: 13px; color: #374151; }
    </style>

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <div class="font-semibold mb-2">Gagal menyimpan:</div>
            <ul class="list-disc ps-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('rt.calon-penerima.store') }}" method="POST" id="formPendataan" novalidate enctype="multipart/form-data">
        @csrf

        {{-- SECTION 1: Data Identitas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
            <div class="section-head px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Identitas</h3>
            </div>
            <div class="section-body p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @php $myRtId = auth()->user()->rt_id ?? (auth()->user()->rt->id ?? null); @endphp

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">RT <span class="text-red-500">*</span></label>
                    <select id="rt_id_display" class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-100" disabled>
                        @foreach(($rts ?? []) as $rt)
                            <option value="{{ $rt->id }}" {{ (string)$rt->id === (string)$myRtId ? 'selected' : '' }}>
                                RT {{ str_pad($rt->nomor_rt, 3, '0', STR_PAD_LEFT) }} - {{ $rt->dusun->nama_dusun ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="rt_id" id="rt_id" value="{{ $myRtId }}">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">RT wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">No. KK <span class="text-red-500">*</span></label>
                    <input type="text" name="no_kk" id="no_kk" maxlength="16"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="16 digit No. KK" required inputmode="numeric">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">No. KK harus 16 digit angka.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" id="nik" maxlength="16"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="16 digit NIK" required inputmode="numeric">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">NIK harus tepat 16 digit angka.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Nama sesuai KTP" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Nama lengkap wajib diisi.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Jenis kelamin wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Kota/Kabupaten" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Tempat lahir wajib diisi.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Tanggal lahir wajib diisi.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Usia <span class="text-red-500">*</span></label>
                    <input type="number" name="usia" id="usia" min="0" max="150"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Otomatis dari tanggal lahir" required inputmode="numeric">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Usia harus antara 0–150 tahun.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status Perkawinan <span class="text-red-500">*</span></label>
                    <select name="status_perkawinan" id="status_perkawinan"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Status perkawinan wajib dipilih.</p>
                </div>
            </div>
        </div>

        {{-- SECTION 2: Data Tempat Tinggal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
            <div class="section-head px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Tempat Tinggal</h3>
            </div>
            <div class="section-body p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="field-group md:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat <span class="text-red-500">*</span></label>
                    <input type="text" name="alamat" id="alamat"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Jalan, nomor rumah, RT/RW" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Alamat wajib diisi.</p>
                </div>

                @php $myDusunName = auth()->user()->rt->dusun->nama_dusun ?? ''; @endphp
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dusun <span class="text-red-500">*</span></label>
                    <input type="text" id="desa_display"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-100"
                        value="{{ $myDusunName }}" readonly>
                    <input type="hidden" name="desa" id="desa" value="{{ $myDusunName }}">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Dusun wajib diisi.</p>
                </div>
            </div>
        </div>

        {{-- SECTION 3: Data Ekonomi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
            <div class="section-head px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-amber-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Ekonomi</h3>
            </div>
            <div class="section-body p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Pekerjaan <span class="text-red-500">*</span></label>
                    <select name="pekerjaan" id="pekerjaan"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        <option value="Tidak Bekerja">Tidak Bekerja</option>
                        <option value="Buruh Harian Lepas">Buruh Harian Lepas</option>
                        <option value="Petani Kecil">Petani Kecil</option>
                        <option value="Mengurus Rumah Tangga">Mengurus Rumah Tangga</option>
                        <option value="Pedagang">Pedagang</option>
                        <option value="Wiraswasta">Wiraswasta</option>
                        <option value="Karyawan Swasta">Karyawan Swasta</option>
                        <option value="Guru Honorer">Guru Honorer</option>
                        <option value="PNS">PNS</option>
                        <option value="TNI/Polri">TNI/Polri</option>
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Pekerjaan wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Penghasilan (Rp) <span class="text-red-500">*</span></label>
                    <input type="text" id="penghasilan_display"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="0" inputmode="numeric" autocomplete="off">
                    <input type="hidden" name="penghasilan" id="penghasilan">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Penghasilan tidak boleh negatif.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah Tanggungan <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_tanggungan" id="jumlah_tanggungan" min="0"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Jumlah orang" required inputmode="numeric">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Jumlah tanggungan tidak boleh negatif.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Menerima Bantuan Lain? <span class="text-red-500">*</span></label>
                    <select name="bantuan_lain" id="bantuan_lain"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="tidak">Tidak</option>
                        <option value="ya">Ya</option>
                    </select>
                </div>
            </div>

            {{-- Aset Kepemilikan — checkbox + manual --}}
            <div class="section-body px-5 pb-5">
                <label class="block text-xs font-semibold text-gray-600 mb-2">
                    Aset Kepemilikan <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal ml-1">(centang semua yang dimiliki)</span>
                </label>

                <div class="aset-checkbox-grid mb-3" id="aset-checkbox-grid">
                    @php
                    $asetOptions = [
                        'motor'     => 'Motor',
                        'motor tua' => 'Motor Tua',
                        'tanah'     => 'Tanah',
                        'emas'      => 'Emas',
                        'mobil'     => 'Mobil',
                        'rumah'     => 'Rumah',
                        'sepeda'    => 'Sepeda',
                        'ternak'    => 'Ternak',
                    ];
                    @endphp

                    @foreach($asetOptions as $val => $label)
                    <label class="aset-checkbox-item">
                        <input type="checkbox" name="aset_pilihan[]" value="{{ $val }}">
                        <span>{{ $label }}</span>
                    </label>
                    @endforeach
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs text-gray-500">Aset lain (tulis manual, pisahkan dengan koma):</label>
                    <input type="text" name="aset_manual" id="aset_manual"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Contoh: sawah, kebun, mesin jahit">
                </div>

                <p class="error-msg-aset text-xs text-red-500 mt-1 hidden">Aset kepemilikan wajib diisi (centang minimal satu atau isi kolom manual).</p>
            </div>
        </div>

        {{-- SECTION 4: Kondisi Tempat Tinggal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
            <div class="section-head px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-purple-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Kondisi Tempat Tinggal</h3>
            </div>
            <div class="section-body p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                {{-- Lantai Rumah --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Lantai Rumah <span class="text-red-500">*</span></label>
                    <select name="lantai_rumah" id="lantai_rumah"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        <option value="Tanah">Tanah</option>
                        <option value="Papan/Kayu">Papan / Kayu</option>
                        <option value="Semen Kasar">Semen Kasar</option>
                        <option value="Keramik Murah">Keramik Murah</option>
                        <option value="Keramik">Keramik</option>
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Lantai rumah wajib dipilih.</p>
                </div>

                {{-- Dinding Rumah --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dinding Rumah <span class="text-red-500">*</span></label>
                    <select name="dinding_rumah" id="dinding_rumah"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        <option value="Bambu">Bambu</option>
                        <option value="Anyaman Bambu/Gedek">Anyaman Bambu / Gedek</option>
                        <option value="Kayu/Papan">Kayu / Papan</option>
                        <option value="Tembok Tidak Plester">Tembok Tidak Plester</option>
                        <option value="Tembok Plester">Tembok Plester</option>
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Dinding rumah wajib dipilih.</p>
                </div>

                {{-- Atap Rumah --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Atap Rumah <span class="text-red-500">*</span></label>
                    <select name="atap_rumah" id="atap_rumah"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        <option value="Bambu/Jerami">Bambu / Jerami</option>
                        <option value="Seng">Seng</option>
                        <option value="Campuran Seng+Genteng">Campuran Seng + Genteng</option>
                        <option value="Genteng">Genteng</option>
                        <option value="Genteng Beton">Genteng Beton</option>
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Atap rumah wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Luas Rumah (m²) <span class="text-red-500">*</span></label>
                    <input type="number" name="luas_rumah_m2" id="luas_rumah_m2" min="1"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Contoh: 36" required inputmode="numeric">
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Luas rumah wajib diisi.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status Kepemilikan Rumah <span class="text-red-500">*</span></label>
                    <select name="status_kepemilikan_rumah" id="status_kepemilikan_rumah"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        <option value="Menumpang">Menumpang</option>
                        <option value="Sewa/Kontrak">Sewa / Kontrak</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Status kepemilikan rumah wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Meteran Listrik <span class="text-red-500">*</span></label>
                    <select name="meteran_listrik" id="meteran_listrik"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        <option value="450VA">450 VA</option>
                        <option value="900VA">900 VA</option>
                        <option value="1300VA+">1300 VA ke atas</option>
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Meteran listrik wajib dipilih.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Sumber Air <span class="text-red-500">*</span></label>
                    <select name="sumber_air" id="sumber_air"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="">-- Pilih --</option>
                        <option value="Sungai">Sungai</option>
                        <option value="Sumur">Sumur</option>
                        <option value="PDAM">PDAM</option>
                    </select>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Sumber air wajib dipilih.</p>
                </div>
            </div>
        </div>

        {{-- SECTION 5: Upload Dokumen & Foto --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-5 overflow-hidden">
            <div class="section-head px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-rose-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Upload Dokumen & Foto</h3>
            </div>
            <div class="section-body p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Foto Rumah Depan</label>
                    <input type="file" name="foto_rumah_depan" accept="image/*" class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Foto Rumah Belakang</label>
                    <input type="file" name="foto_rumah_belakang" accept="image/*" class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Foto Rumah Kanan</label>
                    <input type="file" name="foto_rumah_kanan" accept="image/*" class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Foto Rumah Kiri</label>
                    <input type="file" name="foto_rumah_kiri" accept="image/*" class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Foto KK</label>
                    <input type="file" name="foto_kk" accept="image/*" class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Foto KTP</label>
                    <input type="file" name="foto_ktp" accept="image/*" class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Foto Rekening Listrik</label>
                    <input type="file" name="foto_rekening_listrik" accept="image/*,.pdf" class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Foto Meteran Air</label>
                    <input type="file" name="foto_meteran_air" accept="image/*" class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
                <div class="field-group lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dokumen Pendukung Lainnya</label>
                    <input type="file" name="dokumen_pendukung" accept="image/*,.pdf,.doc,.docx" class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="action-row flex justify-end gap-3">
            <a href="{{ route('rt.calon-penerima.index') }}"
                class="px-5 py-2 text-sm font-medium bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" id="btnSimpan"
                style="display:inline-flex;align-items:center;gap:8px;"
                class="px-6 py-2 text-sm font-semibold bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-200">
                <svg id="btnSpinner" style="display:none;width:16px;height:16px;animation:spin .7s linear infinite;flex-shrink:0;" fill="none" viewBox="0 0 24 24">
                    <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                <span id="btnText">Simpan Data</span>
            </button>
        </div>
    </form>

    {{-- TOAST NOTIFIKASI --}}
    <div id="toast" class="fixed bottom-5 right-5 z-50 hidden">
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

        // Validasi aset: minimal satu checkbox atau ada teks manual
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

        document.querySelectorAll('input[name="aset_pilihan[]"]').forEach(cb => {
            cb.addEventListener('change', validateAset);
        });
        document.getElementById('aset_manual')?.addEventListener('input', validateAset);

        const rules = {
            rt_id:                    v => v !== ''                                   ? null : 'RT wajib dipilih.',
            nik:                      v => /^\d{16}$/.test(v)                         ? null : 'NIK harus tepat 16 digit angka.',
            no_kk:                    v => /^\d{16}$/.test(v)                         ? null : 'No. KK harus tepat 16 digit angka.',
            nama_lengkap:             v => v.trim().length >= 3                       ? null : 'Nama minimal 3 karakter.',
            jenis_kelamin:            v => v !== ''                                   ? null : 'Jenis kelamin wajib dipilih.',
            tempat_lahir:             v => v.trim().length >= 2                       ? null : 'Tempat lahir wajib diisi.',
            tanggal_lahir:            v => v !== ''                                   ? null : 'Tanggal lahir wajib diisi.',
            usia:                     v => (parseInt(v) >= 0 && parseInt(v) <= 150)   ? null : 'Usia harus antara 0–150.',
            status_perkawinan:        v => v !== ''                                   ? null : 'Status perkawinan wajib dipilih.',
            alamat:                   v => v.trim().length >= 5                       ? null : 'Alamat minimal 5 karakter.',
            desa:                     v => v.trim().length >= 2                       ? null : 'Dusun wajib diisi.',
            pekerjaan:                v => v !== ''                                   ? null : 'Pekerjaan wajib dipilih.',
            penghasilan:              v => (v !== '' && parseFloat(v) >= 0)           ? null : 'Penghasilan tidak boleh negatif.',
            jumlah_tanggungan:        v => parseInt(v) >= 0                           ? null : 'Jumlah tanggungan tidak boleh negatif.',
            lantai_rumah:             v => v !== ''                                   ? null : 'Lantai rumah wajib dipilih.',
            dinding_rumah:            v => v !== ''                                   ? null : 'Dinding rumah wajib dipilih.',
            atap_rumah:               v => v !== ''                                   ? null : 'Atap rumah wajib dipilih.',
            luas_rumah_m2:            v => (parseInt(v) >= 1)                         ? null : 'Luas rumah wajib diisi.',
            status_kepemilikan_rumah: v => v !== ''                                   ? null : 'Status kepemilikan rumah wajib dipilih.',
            meteran_listrik:          v => v !== ''                                   ? null : 'Meteran listrik wajib dipilih.',
            sumber_air:               v => v !== ''                                   ? null : 'Sumber air wajib dipilih.',
        };

        Object.keys(rules).forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('blur',   () => { const e = rules[id](el.value); e ? showError(id, e) : clearError(id); });
            el.addEventListener('input',  () => { if (!el.classList.contains('border-red-400')) return; const e = rules[id](el.value); e ? showError(id, e) : clearError(id); });
            el.addEventListener('change', () => { const e = rules[id](el.value); e ? showError(id, e) : clearError(id); });
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
                const e = rules['penghasilan'](hiddenInput.value);
                e ? showError('penghasilan', e) : clearError('penghasilan');
            });
            displayInput.addEventListener('blur', function () {
                const e = rules['penghasilan'](hiddenInput.value);
                e ? showError('penghasilan', e) : clearError('penghasilan');
            });
        }

        document.getElementById('formPendataan').addEventListener('submit', function (e) {
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

            // Validasi aset
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
            document.getElementById('btnText').textContent = 'Memproses & Memprediksi...';
        });
    </script>
</x-app-layout>