<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Form Pendataan Warga</h2>
            <p class="text-sm text-gray-500 mt-0.5">Isi semua data dengan benar sebelum menyimpan.</p>
        </div>
    </x-slot>

    {{-- TAMPILKAN ERROR VALIDASI --}}
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

    {{-- TAMPILKAN ERROR DARI TRY-CATCH CONTROLLER --}}
    @if (session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- TAMPILKAN SUCCESS --}}
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('rt.calon-penerima.store') }}" method="POST" id="formPendataan" novalidate>
        @csrf

        {{-- SECTION 1: Data Identitas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Identitas</h3>
            </div>

            <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $myRtId = auth()->user()->rt_id ?? (auth()->user()->rt->id ?? null);
                @endphp

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        RT <span class="text-red-500">*</span>
                    </label>

                    {{-- tampilkan tapi terkunci --}}
                    <select
                        id="rt_id_display"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-100"
                        disabled
                    >
                        @foreach(($rts ?? []) as $rt)
                            <option
                                value="{{ $rt->id }}"
                                {{ (string)$rt->id === (string)$myRtId ? 'selected' : '' }}
                            >
                                RT {{ str_pad($rt->nomor_rt, 3, '0', STR_PAD_LEFT) }} - {{ $rt->dusun->nama_dusun ?? '' }}
                            </option>
                        @endforeach
                    </select>

                    {{-- yang dikirim --}}
                    <input type="hidden" name="rt_id" id="rt_id" value="{{ $myRtId }}">

                    <p class="error-msg text-xs text-red-500 mt-1 hidden">RT wajib dipilih.</p>
                </div>

                {{-- NO KK --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        No. KK <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="no_kk"
                        id="no_kk"
                        maxlength="16"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="16 digit No. KK"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">No. KK harus 16 digit angka.</p>
                </div>

                {{-- NIK --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nik"
                        id="nik"
                        maxlength="16"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="16 digit NIK"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">NIK harus tepat 16 digit angka.</p>
                </div>

                {{-- NAMA --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_lengkap"
                        id="nama_lengkap"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Nama sesuai KTP"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Nama lengkap wajib diisi.</p>
                </div>

                {{-- JENIS KELAMIN --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="jenis_kelamin"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                    >
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                {{-- TEMPAT LAHIR --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Tempat Lahir <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="tempat_lahir"
                        id="tempat_lahir"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Kota/Kabupaten"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Tempat lahir wajib diisi.</p>
                </div>

                {{-- TANGGAL LAHIR --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        name="tanggal_lahir"
                        id="tanggal_lahir"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Tanggal lahir wajib diisi.</p>
                </div>

                {{-- USIA --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Usia <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="usia"
                        id="usia"
                        min="0"
                        max="150"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Otomatis dari tanggal lahir"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Usia harus antara 0–150 tahun.</p>
                </div>

                {{-- STATUS PERKAWINAN --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Status Perkawinan <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="status_perkawinan"
                        id="status_perkawinan"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                    >
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
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Tempat Tinggal</h3>
            </div>

            <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- ALAMAT --}}
                <div class="field-group lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="alamat"
                        id="alamat"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Jalan, nomor rumah, RT/RW"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Alamat wajib diisi.</p>
                </div>

                @php
                    $myDusunName = auth()->user()->rt->dusun->nama_dusun ?? '';
                @endphp

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Dusun <span class="text-red-500">*</span>
                    </label>

                    {{-- tampilkan tapi terkunci --}}
                    <input
                        type="text"
                        id="desa_display"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-100"
                        value="{{ $myDusunName }}"
                        readonly
                    >

                    {{-- yang dikirim --}}
                    <input type="hidden" name="desa" id="desa" value="{{ $myDusunName }}">

                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Dusun wajib diisi.</p>
                </div>

                {{-- ASET --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Aset Kepemilikan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="aset_kepemilikan"
                        id="aset_kepemilikan"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Misal: Rumah, Motor, dll"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Aset kepemilikan wajib diisi.</p>
                </div>
            </div>
        </div>

        {{-- SECTION 3: Data Ekonomi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-5 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-amber-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Ekonomi</h3>
            </div>

            <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- PEKERJAAN --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Pekerjaan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="pekerjaan"
                        id="pekerjaan"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Jenis pekerjaan"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Pekerjaan wajib diisi.</p>
                </div>

                {{-- PENGHASILAN --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Penghasilan (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        name="penghasilan"
                        id="penghasilan"
                        min="0"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="0"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Penghasilan tidak boleh negatif.</p>
                </div>

                {{-- JUMLAH TANGGUNGAN --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Jumlah Tanggungan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="jumlah_tanggungan"
                        id="jumlah_tanggungan"
                        min="0"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Jumlah orang"
                        required
                    >
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Jumlah tanggungan tidak boleh negatif.</p>
                </div>

                {{-- BANTUAN LAIN --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Menerima Bantuan Lain? <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="bantuan_lain"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                    >
                        <option value="tidak">Tidak</option>
                        <option value="ya">Ya</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="flex justify-end gap-3">
            <a
                href="{{ route('rt.calon-penerima.index') }}"
                class="px-5 py-2 text-sm font-medium bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition"
            >
                Batal
            </a>
            <button
                type="submit"
                class="px-6 py-2 text-sm font-semibold bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-200"
            >
                Simpan Data
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

        const rules = {
            rt_id:             v => v !== ''               ? null : 'RT wajib dipilih.',
            nik:               v => /^\d{16}$/.test(v)     ? null : 'NIK harus tepat 16 digit angka.',
            no_kk:             v => /^\d{16}$/.test(v)     ? null : 'No. KK harus tepat 16 digit angka.',
            nama_lengkap:      v => v.trim().length >= 3   ? null : 'Nama minimal 3 karakter.',
            tempat_lahir:      v => v.trim().length >= 2   ? null : 'Tempat lahir wajib diisi.',
            tanggal_lahir:     v => v !== ''               ? null : 'Tanggal lahir wajib diisi.',
            usia:              v => (parseInt(v) >= 0 && parseInt(v) <= 150) ? null : 'Usia harus antara 0–150.',
            status_perkawinan: v => v !== ''               ? null : 'Status perkawinan wajib dipilih.',
            alamat:            v => v.trim().length >= 5   ? null : 'Alamat minimal 5 karakter.',
            desa:              v => v.trim().length >= 2   ? null : 'Dusun wajib diisi.',
            aset_kepemilikan:  v => v.trim().length >= 2   ? null : 'Aset kepemilikan wajib diisi.',
            pekerjaan:         v => v.trim().length >= 2   ? null : 'Pekerjaan wajib diisi.',
            penghasilan:       v => parseFloat(v) >= 0     ? null : 'Penghasilan tidak boleh negatif.',
            jumlah_tanggungan: v => parseInt(v) >= 0       ? null : 'Jumlah tanggungan tidak boleh negatif.',
        };

        Object.keys(rules).forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('blur', () => {
                const err = rules[id](el.value);
                err ? showError(id, err) : clearError(id);
            });
            el.addEventListener('input', () => {
                if (!el.classList.contains('border-red-400')) return;
                const err = rules[id](el.value);
                err ? showError(id, err) : clearError(id);
            });
            el.addEventListener('change', () => {
                const err = rules[id](el.value);
                err ? showError(id, err) : clearError(id);
            });
        });

        // Auto-isi usia dari tanggal lahir
        document.getElementById('tanggal_lahir')?.addEventListener('change', function () {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
            if (age >= 0 && age <= 150) {
                document.getElementById('usia').value = age;
                clearError('usia');
            }
        });

        // Submit validation
        document.getElementById('formPendataan').addEventListener('submit', function (e) {
            let valid = true;
            Object.keys(rules).forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                const err = rules[id](el.value);
                if (err) { showError(id, err); valid = false; }
                else clearError(id);
            });
            if (!valid) {
                e.preventDefault();
                showToast('Mohon perbaiki field yang tidak valid sebelum menyimpan.');
                const firstErr = document.querySelector('.border-red-400');
                firstErr?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</x-app-layout>