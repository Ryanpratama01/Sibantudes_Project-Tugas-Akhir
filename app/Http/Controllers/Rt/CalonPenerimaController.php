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
use Illuminate\Support\Facades\Storage;

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
        $user = Auth::user();

        $rts = RT::where('id', $user->rt_id)
            ->with('dusun')
            ->get();

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
            'rt_id'              => 'nullable|exists:rts,id',
            'no_kk'              => 'required|digits:16|unique:calon_penerimas,no_kk',
            'nik'                => 'required|digits:16|unique:calon_penerimas,nik',
            'nama_lengkap'       => 'required|string|max:255',
            'jenis_kelamin'      => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir'       => 'required|string|max:255',
            'tanggal_lahir'      => 'required|date',
            'alamat'             => 'required|string',
            'desa'               => 'nullable|string|max:255',
            'pekerjaan'          => 'required|string',
            'penghasilan'        => 'required|numeric|min:0',
            'jumlah_tanggungan'  => 'required|integer|min:0',
            'aset_kepemilikan'   => 'required|string',
            'bantuan_lain'       => 'required|in:ya,tidak',
            'usia'               => 'required|integer|min:17|max:100',
            'status_perkawinan'  => 'required|string',
            'kondisi_rumah'      => 'required|in:Layak,Sedang,Tidak Layak',
            'meteran_listrik'    => 'required|in:450VA,900VA,1300VA+',
            'sumber_air'         => 'required|in:PDAM,Sumur,Sungai',
            // Upload foto (opsional)
            'foto_rumah_depan'      => 'nullable|image|max:2048',
            'foto_rumah_belakang'   => 'nullable|image|max:2048',
            'foto_rumah_kanan'      => 'nullable|image|max:2048',
            'foto_rumah_kiri'       => 'nullable|image|max:2048',
            'foto_kk'               => 'nullable|image|max:2048',
            'foto_ktp'              => 'nullable|image|max:2048',
            'foto_rekening_listrik' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_meteran_air'      => 'nullable|image|max:2048',
            'dokumen_pendukung'     => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ], [
            'nik.unique'   => 'NIK sudah pernah diinput.',
            'no_kk.unique' => 'No. KK sudah pernah diinput.',
        ]);

        $validated['rt_id'] = $myRtId;

        $rt = RT::with('dusun')->find($myRtId);
        if ($rt && $rt->dusun) {
            $validated['desa'] = $rt->dusun->nama_dusun;
        } else {
            $validated['desa'] = $validated['desa'] ?? '-';
        }

        DB::beginTransaction();
        try {
            // Handle upload foto
            $fotoFields = [
                'foto_rumah_depan', 'foto_rumah_belakang', 'foto_rumah_kanan', 'foto_rumah_kiri',
                'foto_kk', 'foto_ktp', 'foto_rekening_listrik', 'foto_meteran_air', 'dokumen_pendukung',
            ];
            foreach ($fotoFields as $field) {
                if ($request->hasFile($field)) {
                    $validated[$field] = $request->file($field)->store('dokumen-warga', 'public');
                }
            }

            $calonPenerima = CalonPenerima::create([
                'user_id'            => $user->id,
                'rt_id'              => $validated['rt_id'],
                'no_kk'              => $validated['no_kk'],
                'nik'                => $validated['nik'],
                'nama_lengkap'       => $validated['nama_lengkap'],
                'jenis_kelamin'      => $validated['jenis_kelamin'],
                'tempat_lahir'       => $validated['tempat_lahir'],
                'tanggal_lahir'      => $validated['tanggal_lahir'],
                'alamat'             => $validated['alamat'],
                'desa'               => $validated['desa'],
                'pekerjaan'          => $validated['pekerjaan'],
                'penghasilan'        => $validated['penghasilan'],
                'jumlah_tanggungan'  => $validated['jumlah_tanggungan'],
                'aset_kepemilikan'   => $validated['aset_kepemilikan'],
                'kondisi_rumah'      => $validated['kondisi_rumah'],
                'meteran_listrik'    => $validated['meteran_listrik'],
                'sumber_air'         => $validated['sumber_air'],
                'bantuan_lain'       => $validated['bantuan_lain'],
                'usia'               => $validated['usia'],
                'status_perkawinan'  => $validated['status_perkawinan'],
                'status_verifikasi'  => 'pending',
                'tracking_status'    => 'draft',
                'foto_rumah_depan'      => $validated['foto_rumah_depan'] ?? null,
                'foto_rumah_belakang'   => $validated['foto_rumah_belakang'] ?? null,
                'foto_rumah_kanan'      => $validated['foto_rumah_kanan'] ?? null,
                'foto_rumah_kiri'       => $validated['foto_rumah_kiri'] ?? null,
                'foto_kk'               => $validated['foto_kk'] ?? null,
                'foto_ktp'              => $validated['foto_ktp'] ?? null,
                'foto_rekening_listrik' => $validated['foto_rekening_listrik'] ?? null,
                'foto_meteran_air'      => $validated['foto_meteran_air'] ?? null,
                'dokumen_pendukung'     => $validated['dokumen_pendukung'] ?? null,
            ]);

            // ML Prediction
            $predictionData = [
                'pekerjaan'         => $validated['pekerjaan'],
                'penghasilan'       => $validated['penghasilan'],
                'jumlah_tanggungan' => $validated['jumlah_tanggungan'],
                'aset_kepemilikan'  => $validated['aset_kepemilikan'],
                'bantuan_lain'      => $validated['bantuan_lain'],
                'usia'              => $validated['usia'],
                'kondisi_rumah'     => $validated['kondisi_rumah'],
                'meteran_listrik'   => $validated['meteran_listrik'],
                'sumber_air'        => $validated['sumber_air'],
            ];

            $prediction = $this->mlService->getPrediction($predictionData);

            if ($prediction) {
                PrediksiKelayakan::create([
                    'calon_penerima_id' => $calonPenerima->id,
                    'probability'       => $prediction['probability'],
                    'recommendation'    => $prediction['recommendation'],
                    'kondisi_rumah'     => $validated['kondisi_rumah'],
                    'meteran_listrik'   => $validated['meteran_listrik'],
                    'sumber_air'        => $validated['sumber_air'],
                ]);
            }

            DB::commit();

            return redirect()->route('rt.calon-penerima.index')
                ->with('success', 'Data calon penerima berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

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

        if ($calonPenerima->user_id !== $user->id || ($calonPenerima->tracking_status ?? 'draft') !== 'draft') {
            abort(403, 'Data yang sudah diajukan tidak dapat diubah.');
        }

        return view('rt.calon-penerima.edit', compact('calonPenerima'));
    }

    public function update(Request $request, CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        if ($calonPenerima->user_id !== $user->id || ($calonPenerima->tracking_status ?? 'draft') !== 'draft') {
            abort(403, 'Data yang sudah diajukan tidak dapat diubah.');
        }

        $myRtId = $user->rt_id ?? null;
        if (!$myRtId) {
            return back()->withInput()->with('error', 'Akun RT belum terhubung ke data RT.');
        }

        $validated = $request->validate([
            'rt_id'              => 'nullable|exists:rts,id',
            'no_kk'              => 'required|digits:16|unique:calon_penerimas,no_kk,' . $calonPenerima->id,
            'nik'                => 'required|digits:16|unique:calon_penerimas,nik,' . $calonPenerima->id,
            'nama_lengkap'       => 'required|string|max:255',
            'jenis_kelamin'      => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir'       => 'required|string|max:255',
            'tanggal_lahir'      => 'required|date',
            'alamat'             => 'required|string',
            'desa'               => 'nullable|string|max:255',
            'pekerjaan'          => 'required|string',
            'penghasilan'        => 'required|numeric|min:0',
            'jumlah_tanggungan'  => 'required|integer|min:0',
            'aset_kepemilikan'   => 'required|string',
            'bantuan_lain'       => 'required|in:ya,tidak',
            'usia'               => 'required|integer|min:17|max:100',
            'status_perkawinan'  => 'required|string',
            'kondisi_rumah'      => 'required|in:Layak,Sedang,Tidak Layak',
            'meteran_listrik'    => 'required|in:450VA,900VA,1300VA+',
            'sumber_air'         => 'required|in:PDAM,Sumur,Sungai',
            'foto_rumah_depan'      => 'nullable|image|max:2048',
            'foto_rumah_belakang'   => 'nullable|image|max:2048',
            'foto_rumah_kanan'      => 'nullable|image|max:2048',
            'foto_rumah_kiri'       => 'nullable|image|max:2048',
            'foto_kk'               => 'nullable|image|max:2048',
            'foto_ktp'              => 'nullable|image|max:2048',
            'foto_rekening_listrik' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_meteran_air'      => 'nullable|image|max:2048',
            'dokumen_pendukung'     => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ], [
            'nik.unique'   => 'NIK sudah pernah diinput.',
            'no_kk.unique' => 'No. KK sudah pernah diinput.',
        ]);

        $validated['rt_id'] = $myRtId;

        $rt = RT::with('dusun')->find($myRtId);
        if ($rt && $rt->dusun) {
            $validated['desa'] = $rt->dusun->nama_dusun;
        } else {
            $validated['desa'] = $validated['desa'] ?? '-';
        }

        DB::beginTransaction();
        try {
            // Handle upload foto — hapus lama kalau ada yang baru
            $fotoFields = [
                'foto_rumah_depan', 'foto_rumah_belakang', 'foto_rumah_kanan', 'foto_rumah_kiri',
                'foto_kk', 'foto_ktp', 'foto_rekening_listrik', 'foto_meteran_air', 'dokumen_pendukung',
            ];
            foreach ($fotoFields as $field) {
                if ($request->hasFile($field)) {
                    if ($calonPenerima->$field) {
                        Storage::disk('public')->delete($calonPenerima->$field);
                    }
                    $validated[$field] = $request->file($field)->store('dokumen-warga', 'public');
                }
            }

            $calonPenerima->update($validated);

            /*
            // Uncomment kalau mau update ulang prediksi ML saat edit
            $predictionData = [
                'pekerjaan'         => $validated['pekerjaan'],
                'penghasilan'       => $validated['penghasilan'],
                'jumlah_tanggungan' => $validated['jumlah_tanggungan'],
                'aset_kepemilikan'  => $validated['aset_kepemilikan'],
                'bantuan_lain'      => $validated['bantuan_lain'],
                'usia'              => $validated['usia'],
                'kondisi_rumah'     => $validated['kondisi_rumah'],
                'meteran_listrik'   => $validated['meteran_listrik'],
                'sumber_air'        => $validated['sumber_air'],
            ];
            $prediction = $this->mlService->getPrediction($predictionData);
            if ($prediction) {
                PrediksiKelayakan::updateOrCreate(
                    ['calon_penerima_id' => $calonPenerima->id],
                    [
                        'probability'    => $prediction['probability'],
                        'recommendation' => $prediction['recommendation'],
                        'kondisi_rumah'  => $validated['kondisi_rumah'],
                        'meteran_listrik'=> $validated['meteran_listrik'],
                        'sumber_air'     => $validated['sumber_air'],
                    ]
                );
            }
            */

            DB::commit();

            return redirect()->route('rt.calon-penerima.index')
                ->with('success', 'Data calon penerima berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

        $calonPenerima->update(['tracking_status' => 'terkirim']);

        return redirect()->route('rt.calon-penerima.index')
            ->with('success', 'Data berhasil diajukan ke admin kelurahan.');
    }

    public function destroy(CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        if ($calonPenerima->user_id !== $user->id || ($calonPenerima->tracking_status ?? 'draft') !== 'draft') {
            abort(403, 'Data yang sudah diajukan tidak dapat dihapus.');
        }

        // Hapus foto dari storage
        $fotoFields = [
            'foto_rumah_depan', 'foto_rumah_belakang', 'foto_rumah_kanan', 'foto_rumah_kiri',
            'foto_kk', 'foto_ktp', 'foto_rekening_listrik', 'foto_meteran_air', 'dokumen_pendukung',
        ];
        foreach ($fotoFields as $field) {
            if ($calonPenerima->$field) {
                Storage::disk('public')->delete($calonPenerima->$field);
            }
        }

        $calonPenerima->delete();

        return redirect()->route('rt.calon-penerima.index')
            ->with('success', 'Data calon penerima berhasil dihapus!');
    }

    private function getPredictionExplanation($calonPenerima): array
    {
        $positive = [];
        $negative = [];

        if (strtolower($calonPenerima->pekerjaan) === 'tidak bekerja') {
            $positive[] = 'Tidak bekerja';
        } elseif (in_array(strtolower($calonPenerima->pekerjaan), ['buruh harian', 'buruh', 'petani', 'nelayan'])) {
            $positive[] = 'Pekerjaan tergolong rentan secara ekonomi';
        } else {
            $negative[] = 'Memiliki pekerjaan yang relatif lebih stabil';
        }

        if ($calonPenerima->penghasilan <= 1000000) {
            $positive[] = 'Penghasilan rendah';
        } elseif ($calonPenerima->penghasilan <= 2000000) {
            $positive[] = 'Penghasilan masih tergolong rendah';
        } else {
            $negative[] = 'Penghasilan relatif lebih tinggi';
        }

        if ($calonPenerima->jumlah_tanggungan >= 4) {
            $positive[] = 'Jumlah tanggungan banyak';
        } elseif ($calonPenerima->jumlah_tanggungan >= 2) {
            $positive[] = 'Jumlah tanggungan cukup banyak';
        } else {
            $negative[] = 'Jumlah tanggungan sedikit';
        }

        $aset = strtolower($calonPenerima->aset_kepemilikan);
        if (str_contains($aset, 'mobil')) $negative[] = 'Memiliki aset bernilai tinggi (mobil)';
        if (str_contains($aset, 'motor')) $negative[] = 'Memiliki aset kendaraan bermotor';
        if (str_contains($aset, 'rumah')) $negative[] = 'Memiliki aset rumah';
        if (in_array($aset, ['tidak ada', '-', 'tidak punya'])) $positive[] = 'Tidak memiliki aset berarti';

        if (strtolower($calonPenerima->bantuan_lain) === 'ya') {
            $negative[] = 'Sudah menerima bantuan lain';
        } else {
            $positive[] = 'Belum menerima bantuan lain';
        }

        if ($calonPenerima->usia >= 60) {
            $positive[] = 'Usia lanjut';
        } elseif ($calonPenerima->usia >= 45) {
            $positive[] = 'Usia cukup rentan';
        }

        // Parameter baru
        if (strtolower($calonPenerima->kondisi_rumah ?? '') === 'tidak layak') {
            $positive[] = 'Kondisi rumah tidak layak huni';
        } elseif (strtolower($calonPenerima->kondisi_rumah ?? '') === 'sedang') {
            $positive[] = 'Kondisi rumah sedang';
        } else {
            $negative[] = 'Kondisi rumah layak';
        }

        if (($calonPenerima->meteran_listrik ?? '') === '450VA') {
            $positive[] = 'Meteran listrik 450VA (daya rendah)';
        } elseif (($calonPenerima->meteran_listrik ?? '') === '900VA') {
            $positive[] = 'Meteran listrik 900VA';
        } else {
            $negative[] = 'Meteran listrik 1300VA ke atas';
        }

        if (strtolower($calonPenerima->sumber_air ?? '') === 'sungai') {
            $positive[] = 'Sumber air dari sungai (kurang layak)';
        } elseif (strtolower($calonPenerima->sumber_air ?? '') === 'sumur') {
            $positive[] = 'Sumber air dari sumur';
        } else {
            $negative[] = 'Sumber air PDAM (layak)';
        }

        $probability = $calonPenerima->prediksiKelayakan->probability ?? 0;
        $probability = $probability <= 1 ? $probability * 100 : $probability;
        $probability = number_format($probability, 1);

        $summary = "Nilai kelayakan {$probability}% diperoleh berdasarkan data pekerjaan, penghasilan, jumlah tanggungan, aset kepemilikan, bantuan lain, usia, kondisi rumah, meteran listrik, dan sumber air.";

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
            'summary'  => $summary,
        ];
    }
}