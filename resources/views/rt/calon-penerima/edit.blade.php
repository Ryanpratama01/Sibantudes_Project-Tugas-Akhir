<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
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

    {{-- ERROR VALIDASI --}}
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

    <form action="{{ route('rt.calon-penerima.update', $calonPenerima->id) }}" method="POST" id="formEditPendataan" novalidate>
        @csrf
        @method('PUT')

        {{-- hidden aman, walau nanti tetap di-override controller --}}
        <input type="hidden" name="rt_id" value="{{ $calonPenerima->rt_id }}">

        {{-- SECTION 1: Data Identitas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-blue-600 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Identitas</h3>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- RT (readonly) --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">RT</label>
                    <input type="text"
                        value="RT {{ str_pad($calonPenerima->rt->nomor_rt ?? 0, 3, '0', STR_PAD_LEFT) }}"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-100 text-gray-700 cursor-not-allowed"
                        readonly>
                    <p class="text-[11px] text-gray-400 mt-1">RT mengikuti akun RT yang sedang login.</p>
                </div>

                {{-- NO KK --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">No. KK <span class="text-red-500">*</span></label>
                    <input type="text" name="no_kk" id="no_kk" maxlength="16"
                        value="{{ old('no_kk', $calonPenerima->no_kk) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="16 digit No. KK" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">No. KK harus 16 digit angka.</p>
                </div>

                {{-- NIK --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" id="nik" maxlength="16"
                        value="{{ old('nik', $calonPenerima->nik) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="16 digit NIK" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">NIK harus tepat 16 digit angka.</p>
                </div>

                {{-- NAMA --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                        value="{{ old('nama_lengkap', $calonPenerima->nama_lengkap) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Nama sesuai KTP" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Nama minimal 3 karakter.</p>
                </div>

                {{-- JENIS KELAMIN --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="Laki-laki" {{ old('jenis_kelamin', $calonPenerima->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $calonPenerima->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                {{-- TEMPAT LAHIR --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                        value="{{ old('tempat_lahir', $calonPenerima->tempat_lahir) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Kota/Kabupaten" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Tempat lahir wajib diisi.</p>
                </div>

                {{-- TANGGAL LAHIR --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $calonPenerima->tanggal_lahir ? \Carbon\Carbon::parse($calonPenerima->tanggal_lahir)->format('Y-m-d') : '') }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Tanggal lahir wajib diisi.</p>
                </div>

                {{-- USIA --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Usia <span class="text-red-500">*</span></label>
                    <input type="number" name="usia" id="usia" min="17" max="100"
                        value="{{ old('usia', $calonPenerima->usia) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Tahun" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Usia harus antara 17–100 tahun.</p>
                </div>

                {{-- STATUS PERKAWINAN --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status Perkawinan <span class="text-red-500">*</span></label>
                    <select name="status_perkawinan" id="status_perkawinan"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
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
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2 bg-gray-50">
                <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700">Data Tempat Tinggal</h3>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="field-group lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat <span class="text-red-500">*</span></label>
                    <input type="text" name="alamat" id="alamat"
                        value="{{ old('alamat', $calonPenerima->alamat) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Jalan, nomor rumah, RT/RW" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Alamat minimal 5 karakter.</p>
                </div>

                {{-- DUSUN (readonly) --}}
                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dusun</label>
                    <input type="text" name="desa" id="desa"
                        value="{{ old('desa', $calonPenerima->desa) }}"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-100 text-gray-700 cursor-not-allowed"
                        readonly>
                    <p class="text-[11px] text-gray-400 mt-1">Dusun mengikuti RT dan tidak dapat diubah.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Aset Kepemilikan <span class="text-red-500">*</span></label>
                    <input type="text" name="aset_kepemilikan" id="aset_kepemilikan"
                        value="{{ old('aset_kepemilikan', $calonPenerima->aset_kepemilikan) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Misal: Rumah, Motor, dll" required>
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

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Pekerjaan <span class="text-red-500">*</span></label>
                    <input type="text" name="pekerjaan" id="pekerjaan"
                        value="{{ old('pekerjaan', $calonPenerima->pekerjaan) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Jenis pekerjaan" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Pekerjaan wajib diisi.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Penghasilan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="penghasilan" id="penghasilan" min="0"
                        value="{{ old('penghasilan', $calonPenerima->penghasilan) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="0" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Penghasilan tidak boleh negatif.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah Tanggungan <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_tanggungan" id="jumlah_tanggungan" min="0"
                        value="{{ old('jumlah_tanggungan', $calonPenerima->jumlah_tanggungan) }}"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                        placeholder="Jumlah orang" required>
                    <p class="error-msg text-xs text-red-500 mt-1 hidden">Jumlah tanggungan tidak boleh negatif.</p>
                </div>

                <div class="field-group">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Menerima Bantuan Lain? <span class="text-red-500">*</span></label>
                    <select name="bantuan_lain"
                        class="input-field w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                        <option value="tidak" {{ old('bantuan_lain', $calonPenerima->bantuan_lain) === 'tidak' ? 'selected' : '' }}>Tidak</option>
                        <option value="ya" {{ old('bantuan_lain', $calonPenerima->bantuan_lain) === 'ya' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('rt.calon-penerima.index') }}"
               class="px-5 py-2 text-sm font-medium bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2 text-sm font-semibold bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>

    </form>

    {{-- TOAST --}}
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
            nik:               v => /^\d{16}$/.test(v) ? null : 'NIK harus tepat 16 digit angka.',
            no_kk:             v => /^\d{16}$/.test(v) ? null : 'No. KK harus tepat 16 digit angka.',
            nama_lengkap:      v => v.trim().length >= 3 ? null : 'Nama minimal 3 karakter.',
            tempat_lahir:      v => v.trim().length >= 2 ? null : 'Tempat lahir wajib diisi.',
            tanggal_lahir:     v => v !== '' ? null : 'Tanggal lahir wajib diisi.',
            usia:              v => (parseInt(v) >= 17 && parseInt(v) <= 100) ? null : 'Usia harus antara 17–100.',
            status_perkawinan: v => v !== '' ? null : 'Status perkawinan wajib dipilih.',
            alamat:            v => v.trim().length >= 5 ? null : 'Alamat minimal 5 karakter.',
            desa:              v => v.trim().length >= 2 ? null : 'Dusun wajib diisi.',
            aset_kepemilikan:  v => v.trim().length >= 2 ? null : 'Aset kepemilikan wajib diisi.',
            pekerjaan:         v => v.trim().length >= 2 ? null : 'Pekerjaan wajib diisi.',
            penghasilan:       v => parseFloat(v) >= 0 ? null : 'Penghasilan tidak boleh negatif.',
            jumlah_tanggungan: v => parseInt(v) >= 0 ? null : 'Jumlah tanggungan tidak boleh negatif.',
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
            if (age >= 0 && age <= 150) {
                document.getElementById('usia').value = age;
                clearError('usia');
            }
        });

        document.getElementById('formEditPendataan').addEventListener('submit', function (e) {
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
                document.querySelector('.border-red-400')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</x-app-layout>