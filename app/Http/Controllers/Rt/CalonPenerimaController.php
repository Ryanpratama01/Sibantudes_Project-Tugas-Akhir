<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\CalonPenerima;
use App\Models\PrediksiKelayakan;
use App\Models\RT;
use App\Services\MLPredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalonPenerimaController extends Controller
{
    protected MLPredictionService $mlService;

    public function __construct(MLPredictionService $mlService)
    {
        $this->mlService = $mlService;
    }

    public function index()
    {
        $user = Auth::user();

        $calonPenerimas = CalonPenerima::with(['rt.dusun', 'prediksiKelayakan'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('rt.calon-penerima.index', compact('calonPenerimas'));
    }

    public function create()
    {
        // RT tidak boleh pilih RT lain
        // Tapi kita biarkan view create tetap bisa pakai data RT user (kalau mau ditampilkan)
        $user = Auth::user();

        $rts = RT::where('id', $user->rt_id)
            ->with('dusun')
            ->get();

        // fallback kalau data RT user belum ada (harusnya jarang terjadi)
        if ($rts->isEmpty()) {
            $rts = RT::with('dusun')->get();
        }

        return view('rt.calon-penerima.create', compact('rts'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $myRtId = $user->rt_id ?? null;
        if (!$myRtId) {
            return back()->withInput()->with('error', 'Akun RT belum terhubung ke data RT.');
        }

        $validated = $request->validate([
            // rt_id boleh ada di form, tapi akan kita override (anti inject)
            'rt_id' => 'nullable|exists:rts,id',

            // ✅ NIK & NO KK TIDAK BOLEH DUPLIKAT
            'no_kk' => 'required|digits:16|unique:calon_penerimas,no_kk',
            'nik'   => 'required|digits:16|unique:calon_penerimas,nik',

            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'desa' => 'nullable|string|max:255', // akan kita set otomatis
            'pekerjaan' => 'required|string',
            'penghasilan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'aset_kepemilikan' => 'required|string',
            'bantuan_lain' => 'required|in:ya,tidak',
            'usia' => 'required|integer|min:17|max:100',
            'status_perkawinan' => 'required|string',
        ], [
            'nik.unique' => 'NIK sudah pernah diinput.',
            'no_kk.unique' => 'No. KK sudah pernah diinput.',
        ]);

        // 🔒 Kunci RT dari user login (anti inject)
        $validated['rt_id'] = $myRtId;

        // 🔒 Kunci Dusun/Desa ikut RT
        $rt = RT::with('dusun')->find($myRtId);
        if ($rt && $rt->dusun) {
            $validated['desa'] = $rt->dusun->nama_dusun;
        } else {
            // fallback aman kalau relasi dusun kosong
            $validated['desa'] = $validated['desa'] ?? '-';
        }

        DB::beginTransaction();
        try {
            $calonPenerima = CalonPenerima::create([
                'user_id' => $user->id,
                'rt_id' => $validated['rt_id'],
                'no_kk' => $validated['no_kk'],
                'nik' => $validated['nik'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'alamat' => $validated['alamat'],
                'desa' => $validated['desa'],
                'pekerjaan' => $validated['pekerjaan'],
                'penghasilan' => $validated['penghasilan'],
                'jumlah_tanggungan' => $validated['jumlah_tanggungan'],
                'aset_kepemilikan' => $validated['aset_kepemilikan'],
                'bantuan_lain' => $validated['bantuan_lain'],
                'usia' => $validated['usia'],
                'status_perkawinan' => $validated['status_perkawinan'],
                'status_verifikasi' => 'pending',
                'tracking_status' => 'draft',
            ]);

            // 🔮 ML Prediction
            $predictionData = [
                'pekerjaan' => $validated['pekerjaan'],
                'penghasilan' => $validated['penghasilan'],
                'jumlah_tanggungan' => $validated['jumlah_tanggungan'],
                'aset_kepemilikan' => $validated['aset_kepemilikan'],
                'bantuan_lain' => $validated['bantuan_lain'],
                'usia' => $validated['usia'],
            ];

            $prediction = $this->mlService->getPrediction($predictionData);

            if ($prediction) {
                PrediksiKelayakan::create([
                    'calon_penerima_id' => $calonPenerima->id,
                    'probability' => $prediction['probability'],
                    'recommendation' => $prediction['recommendation'],
                ]);
            }

            DB::commit();

            return redirect()->route('rt.calon-penerima.index')
                ->with('success', 'Data calon penerima berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        // 🔒 RT hanya boleh lihat data miliknya
        if ($calonPenerima->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $calonPenerima->load(['rt.dusun', 'prediksiKelayakan']);

        $explanation = $this->getPredictionExplanation($calonPenerima);

        return view('rt.calon-penerima.show', compact('calonPenerima', 'explanation'));
    }

    public function edit(CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        // 🔒 RT hanya bisa edit kalau miliknya + masih draft
        if ($calonPenerima->user_id !== $user->id || ($calonPenerima->tracking_status ?? 'draft') !== 'draft') {
            abort(403, 'Data yang sudah diajukan tidak dapat diubah.');
        }

        return view('rt.calon-penerima.edit', compact('calonPenerima'));
    }

    public function update(Request $request, CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        // 🔒 RT hanya bisa update kalau miliknya + masih draft
        if ($calonPenerima->user_id !== $user->id || ($calonPenerima->tracking_status ?? 'draft') !== 'draft') {
            abort(403, 'Data yang sudah diajukan tidak dapat diubah.');
        }

        $myRtId = $user->rt_id ?? null;
        if (!$myRtId) {
            return back()->withInput()->with('error', 'Akun RT belum terhubung ke data RT.');
        }

        $validated = $request->validate([
            'rt_id' => 'nullable|exists:rts,id',

            // ✅ UNIQUE KECUALI DATA SENDIRI
            'no_kk' => 'required|digits:16|unique:calon_penerimas,no_kk,' . $calonPenerima->id,
            'nik'   => 'required|digits:16|unique:calon_penerimas,nik,' . $calonPenerima->id,

            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'desa' => 'nullable|string|max:255', // akan di-override
            'pekerjaan' => 'required|string',
            'penghasilan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'aset_kepemilikan' => 'required|string',
            'bantuan_lain' => 'required|in:ya,tidak',
            'usia' => 'required|integer|min:17|max:100',
            'status_perkawinan' => 'required|string',
        ], [
            'nik.unique' => 'NIK sudah pernah diinput.',
            'no_kk.unique' => 'No. KK sudah pernah diinput.',
        ]);

        // 🔒 Kunci RT dari user login (anti inject)
        $validated['rt_id'] = $myRtId;

        // 🔒 Kunci Dusun/Desa ikut RT
        $rt = RT::with('dusun')->find($myRtId);
        if ($rt && $rt->dusun) {
            $validated['desa'] = $rt->dusun->nama_dusun;
        } else {
            $validated['desa'] = $validated['desa'] ?? '-';
        }

        DB::beginTransaction();
        try {
            $calonPenerima->update($validated);

            // (Opsional) Kalau kamu mau: update ulang prediksi ML saat data diupdate
            // Kalau mau aktif, uncomment ini:

            /*
            $predictionData = [
                'pekerjaan' => $validated['pekerjaan'],
                'penghasilan' => $validated['penghasilan'],
                'jumlah_tanggungan' => $validated['jumlah_tanggungan'],
                'aset_kepemilikan' => $validated['aset_kepemilikan'],
                'bantuan_lain' => $validated['bantuan_lain'],
                'usia' => $validated['usia'],
            ];

            $prediction = $this->mlService->getPrediction($predictionData);

            if ($prediction) {
                PrediksiKelayakan::updateOrCreate(
                    ['calon_penerima_id' => $calonPenerima->id],
                    [
                        'probability' => $prediction['probability'],
                        'recommendation' => $prediction['recommendation'],
                    ]
                );
            }
            */

            DB::commit();

            return redirect()->route('rt.calon-penerima.index')
                ->with('success', 'Data calon penerima berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function ajukan(CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        if ($calonPenerima->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if (($calonPenerima->tracking_status ?? 'draft') !== 'draft') {
            return redirect()->route('rt.calon-penerima.index')
                ->with('error', 'Data ini sudah diajukan dan tidak dapat diajukan lagi.');
        }

        $calonPenerima->update([
            'tracking_status' => 'terkirim',
        ]);

        return redirect()->route('rt.calon-penerima.index')
            ->with('success', 'Data berhasil diajukan ke admin kelurahan.');
    }

    public function destroy(CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        // 🔒 RT hanya bisa hapus kalau miliknya + masih draft
        if ($calonPenerima->user_id !== $user->id || ($calonPenerima->tracking_status ?? 'draft') !== 'draft') {
            abort(403, 'Data yang sudah diajukan tidak dapat dihapus.');
        }

        $calonPenerima->delete();

        return redirect()->route('rt.calon-penerima.index')
            ->with('success', 'Data calon penerima berhasil dihapus!');
    }

    private function getPredictionExplanation($calonPenerima): array
    {
        $positive = [];
        $negative = [];

        // PEKERJAAN
        if (strtolower($calonPenerima->pekerjaan) === 'tidak bekerja') {
            $positive[] = 'Tidak bekerja';
        } elseif (in_array(strtolower($calonPenerima->pekerjaan), ['buruh harian', 'buruh', 'petani', 'nelayan'])) {
            $positive[] = 'Pekerjaan tergolong rentan secara ekonomi';
        } else {
            $negative[] = 'Memiliki pekerjaan yang relatif lebih stabil';
        }

        // PENGHASILAN
        if ($calonPenerima->penghasilan <= 1000000) {
            $positive[] = 'Penghasilan rendah';
        } elseif ($calonPenerima->penghasilan <= 2000000) {
            $positive[] = 'Penghasilan masih tergolong rendah';
        } else {
            $negative[] = 'Penghasilan relatif lebih tinggi';
        }

        // JUMLAH TANGGUNGAN
        if ($calonPenerima->jumlah_tanggungan >= 4) {
            $positive[] = 'Jumlah tanggungan banyak';
        } elseif ($calonPenerima->jumlah_tanggungan >= 2) {
            $positive[] = 'Jumlah tanggungan cukup banyak';
        } else {
            $negative[] = 'Jumlah tanggungan sedikit';
        }

        // ASET KEPEMILIKAN
        $aset = strtolower($calonPenerima->aset_kepemilikan);

        if (str_contains($aset, 'mobil')) {
            $negative[] = 'Memiliki aset bernilai tinggi (mobil)';
        }

        if (str_contains($aset, 'motor')) {
            $negative[] = 'Memiliki aset kendaraan bermotor';
        }

        if (str_contains($aset, 'rumah')) {
            $negative[] = 'Memiliki aset rumah';
        }

        if ($aset === 'tidak ada' || $aset === '-' || $aset === 'tidak punya') {
            $positive[] = 'Tidak memiliki aset berarti';
        }

        // BANTUAN LAIN
        if (strtolower($calonPenerima->bantuan_lain) === 'ya') {
            $negative[] = 'Sudah menerima bantuan lain';
        } else {
            $positive[] = 'Belum menerima bantuan lain';
        }

        // USIA
        if ($calonPenerima->usia >= 60) {
            $positive[] = 'Usia lanjut';
        } elseif ($calonPenerima->usia >= 45) {
            $positive[] = 'Usia cukup rentan';
        }

        // NARASI PENJELASAN
        $probability = $calonPenerima->prediksiKelayakan->probability ?? 0;
        $probability = $probability <= 1 ? $probability * 100 : $probability;
        $probability = number_format($probability, 1);

        $summary = "Nilai kelayakan {$probability}% diperoleh berdasarkan data pekerjaan, penghasilan, jumlah tanggungan, aset kepemilikan, bantuan lain, dan usia.";

        if (count($positive) > 0 && count($negative) > 0) {
            $summary .= " Sistem menilai terdapat beberapa faktor yang mendukung kelayakan, namun ada juga faktor yang mengurangi nilai kelayakan.";
        } elseif (count($positive) > 0) {
            $summary .= " Sebagian besar faktor yang dinilai cenderung mendukung kelayakan penerima bantuan.";
        } elseif (count($negative) > 0) {
            $summary .= " Sebagian besar faktor yang dinilai cenderung menurunkan tingkat kelayakan.";
        }

        return [
            'positive' => $positive,
            'negative' => $negative,
            'summary' => $summary,
        ];
    }
}