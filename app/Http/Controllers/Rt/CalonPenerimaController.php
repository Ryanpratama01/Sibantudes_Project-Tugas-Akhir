<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\CalonPenerima;
use App\Models\PrediksiKelayakan;
use App\Models\RT;
use App\Services\MLPredictionService;
use Carbon\Carbon;
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
            'rt_id'                   => 'nullable|exists:rts,id',
            'no_kk'                   => 'required|digits:16|unique:calon_penerimas,no_kk',
            'nik'                     => 'required|digits:16|unique:calon_penerimas,nik',
            'nama_lengkap'            => 'required|string|max:255',
            'jenis_kelamin'           => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir'            => 'required|string|max:255',
            'tanggal_lahir'           => 'required|date',
            'alamat'                  => 'required|string',
            'desa'                    => 'nullable|string|max:255',
            'pekerjaan'               => 'required|string',
            'penghasilan'             => 'required|numeric|min:0',
            'jumlah_tanggungan'       => 'required|integer|min:0',
            'aset_pilihan'            => 'nullable|array',
            'aset_pilihan.*'          => 'nullable|string',
            'aset_manual'             => 'nullable|string|max:255',
            'bantuan_lain'            => 'required|in:ya,tidak',
            'usia'                    => 'required|integer|min:17|max:100',
            'status_perkawinan'       => 'required|string',
            'lantai_rumah'            => 'required|string',
            'dinding_rumah'           => 'required|string',
            'atap_rumah'              => 'required|string',
            'luas_rumah_m2'           => 'required|integer|min:1',
            'status_kepemilikan_rumah'=> 'required|string',
            'meteran_listrik'         => 'required|in:450VA,900VA,1300VA+',
            'sumber_air'              => 'required|in:PDAM,Sumur,Sungai',
            'foto_rumah_depan'        => 'nullable|image',
            'foto_rumah_belakang'     => 'nullable|image',
            'foto_rumah_kanan'        => 'nullable|image',
            'foto_rumah_kiri'         => 'nullable|image',
            'foto_kk'                 => 'nullable|image',
            'foto_ktp'                => 'nullable|image',
            'foto_rekening_listrik'   => 'nullable|mimes:jpg,jpeg,png,pdf',
            'foto_meteran_air'        => 'nullable|image',
            'dokumen_pendukung'       => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx',
        ], [
            'nik.unique'   => 'NIK sudah pernah diinput.',
            'no_kk.unique' => 'No. KK sudah pernah diinput.',
        ]);

        $validated['rt_id'] = $myRtId;

        $asetGabung = $this->buildAsetString(
            $validated['aset_pilihan'] ?? [],
            $validated['aset_manual']  ?? ''
        );
        $validated['aset_kepemilikan'] = $asetGabung;

        $validated['kondisi_rumah'] = $this->deriveKondisiRumah(
            $validated['lantai_rumah'],
            $validated['dinding_rumah']
        );

        $rt = RT::with('dusun')->find($myRtId);
        if ($rt && $rt->dusun) {
            $validated['desa'] = $rt->dusun->nama_dusun;
        } else {
            $validated['desa'] = $validated['desa'] ?? '-';
        }

        DB::beginTransaction();
        try {
            $fotoImages = [
                'foto_rumah_depan', 'foto_rumah_belakang', 'foto_rumah_kanan', 'foto_rumah_kiri',
                'foto_kk', 'foto_ktp', 'foto_meteran_air',
            ];
            $fotoFiles = [
                'foto_rekening_listrik', 'dokumen_pendukung',
            ];

            foreach ($fotoImages as $field) {
                if ($request->hasFile($field)) {
                    $validated[$field] = $this->simpanFotoKompres($request->file($field));
                }
            }
            foreach ($fotoFiles as $field) {
                if ($request->hasFile($field)) {
                    $validated[$field] = $request->file($field)->store('dokumen-warga', 'public');
                }
            }

            $calonPenerima = CalonPenerima::create([
                'user_id'                  => $user->id,
                'rt_id'                    => $validated['rt_id'],
                'no_kk'                    => $validated['no_kk'],
                'nik'                      => $validated['nik'],
                'nama_lengkap'             => $validated['nama_lengkap'],
                'jenis_kelamin'            => $validated['jenis_kelamin'],
                'tempat_lahir'             => $validated['tempat_lahir'],
                'tanggal_lahir'            => $validated['tanggal_lahir'],
                'alamat'                   => $validated['alamat'],
                'desa'                     => $validated['desa'],
                'pekerjaan'                => $validated['pekerjaan'],
                'penghasilan'              => $validated['penghasilan'],
                'jumlah_tanggungan'        => $validated['jumlah_tanggungan'],
                'aset_kepemilikan'         => $validated['aset_kepemilikan'],
                'kondisi_rumah'            => $validated['kondisi_rumah'],
                'lantai_rumah'             => $validated['lantai_rumah'],
                'dinding_rumah'            => $validated['dinding_rumah'],
                'atap_rumah'               => $validated['atap_rumah'],
                'luas_rumah_m2'            => $validated['luas_rumah_m2'],
                'status_kepemilikan_rumah' => $validated['status_kepemilikan_rumah'],
                'meteran_listrik'          => $validated['meteran_listrik'],
                'sumber_air'               => $validated['sumber_air'],
                'bantuan_lain'             => $validated['bantuan_lain'],
                'usia'                     => $validated['usia'],
                'status_perkawinan'        => $validated['status_perkawinan'],
                'status_verifikasi'        => 'pending',
                'tracking_status'          => 'draft',
                'periode_bulan'            => Carbon::now('Asia/Jakarta')->startOfMonth()->toDateString(),
                'foto_rumah_depan'         => $validated['foto_rumah_depan'] ?? null,
                'foto_rumah_belakang'      => $validated['foto_rumah_belakang'] ?? null,
                'foto_rumah_kanan'         => $validated['foto_rumah_kanan'] ?? null,
                'foto_rumah_kiri'          => $validated['foto_rumah_kiri'] ?? null,
                'foto_kk'                  => $validated['foto_kk'] ?? null,
                'foto_ktp'                 => $validated['foto_ktp'] ?? null,
                'foto_rekening_listrik'    => $validated['foto_rekening_listrik'] ?? null,
                'foto_meteran_air'         => $validated['foto_meteran_air'] ?? null,
                'dokumen_pendukung'        => $validated['dokumen_pendukung'] ?? null,
            ]);

            $predictionData = [
                'pekerjaan'                => $validated['pekerjaan'],
                'penghasilan'              => $validated['penghasilan'],
                'jumlah_tanggungan'        => $validated['jumlah_tanggungan'],
                'aset_pilihan'             => $validated['aset_pilihan'] ?? [],
                'aset_manual'              => $validated['aset_manual'] ?? '',
                'bantuan_lain'             => $validated['bantuan_lain'],
                'usia'                     => $validated['usia'],
                'status_perkawinan'        => $validated['status_perkawinan'],
                'lantai_rumah'             => $validated['lantai_rumah'],
                'dinding_rumah'            => $validated['dinding_rumah'],
                'atap_rumah'               => $validated['atap_rumah'],
                'luas_rumah_m2'            => $validated['luas_rumah_m2'],
                'status_kepemilikan_rumah' => $validated['status_kepemilikan_rumah'],
                'meteran_listrik'          => $validated['meteran_listrik'],
                'sumber_air'               => $validated['sumber_air'],
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

        if ((int) $calonPenerima->user_id !== (int) $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $calonPenerima->load(['rt.dusun', 'prediksiKelayakan']);

        $explanation = $this->getPredictionExplanation($calonPenerima);

        return view('rt.calon-penerima.show', compact('calonPenerima', 'explanation'));
    }

    public function edit(CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        if ((int) $calonPenerima->user_id !== (int) $user->id || ($calonPenerima->tracking_status ?? 'draft') !== 'draft') {
            abort(403, 'Data yang sudah diajukan tidak dapat diubah.');
        }

        return view('rt.calon-penerima.edit', compact('calonPenerima'));
    }

    public function update(Request $request, CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        if ((int) $calonPenerima->user_id !== (int) $user->id || ($calonPenerima->tracking_status ?? 'draft') !== 'draft') {
            abort(403, 'Data yang sudah diajukan tidak dapat diubah.');
        }

        $myRtId = $user->rt_id ?? null;
        if (!$myRtId) {
            return back()->withInput()->with('error', 'Akun RT belum terhubung ke data RT.');
        }

        $validated = $request->validate([
            'rt_id'                   => 'nullable|exists:rts,id',
            'no_kk'                   => 'required|digits:16|unique:calon_penerimas,no_kk,' . $calonPenerima->id,
            'nik'                     => 'required|digits:16|unique:calon_penerimas,nik,' . $calonPenerima->id,
            'nama_lengkap'            => 'required|string|max:255',
            'jenis_kelamin'           => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir'            => 'required|string|max:255',
            'tanggal_lahir'           => 'required|date',
            'alamat'                  => 'required|string',
            'desa'                    => 'nullable|string|max:255',
            'pekerjaan'               => 'required|string',
            'penghasilan'             => 'required|numeric|min:0',
            'jumlah_tanggungan'       => 'required|integer|min:0',
            'aset_pilihan'            => 'nullable|array',
            'aset_pilihan.*'          => 'nullable|string',
            'aset_manual'             => 'nullable|string|max:255',
            'bantuan_lain'            => 'required|in:ya,tidak',
            'usia'                    => 'required|integer|min:17|max:100',
            'status_perkawinan'       => 'required|string',
            'lantai_rumah'            => 'required|string',
            'dinding_rumah'           => 'required|string',
            'atap_rumah'              => 'required|string',
            'luas_rumah_m2'           => 'required|integer|min:1',
            'status_kepemilikan_rumah'=> 'required|string',
            'meteran_listrik'         => 'required|in:450VA,900VA,1300VA+',
            'sumber_air'              => 'required|in:PDAM,Sumur,Sungai',
            'foto_rumah_depan'        => 'nullable|image',
            'foto_rumah_belakang'     => 'nullable|image',
            'foto_rumah_kanan'        => 'nullable|image',
            'foto_rumah_kiri'         => 'nullable|image',
            'foto_kk'                 => 'nullable|image',
            'foto_ktp'                => 'nullable|image',
            'foto_rekening_listrik'   => 'nullable|mimes:jpg,jpeg,png,pdf',
            'foto_meteran_air'        => 'nullable|image',
            'dokumen_pendukung'       => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx',
        ], [
            'nik.unique'   => 'NIK sudah pernah diinput.',
            'no_kk.unique' => 'No. KK sudah pernah diinput.',
        ]);

        $validated['rt_id'] = $myRtId;

        $validated['aset_kepemilikan'] = $this->buildAsetString(
            $validated['aset_pilihan'] ?? [],
            $validated['aset_manual']  ?? ''
        );

        $validated['kondisi_rumah'] = $this->deriveKondisiRumah(
            $validated['lantai_rumah'],
            $validated['dinding_rumah']
        );

        $rt = RT::with('dusun')->find($myRtId);
        if ($rt && $rt->dusun) {
            $validated['desa'] = $rt->dusun->nama_dusun;
        } else {
            $validated['desa'] = $validated['desa'] ?? '-';
        }

        DB::beginTransaction();
        try {
            $fotoImages = [
                'foto_rumah_depan', 'foto_rumah_belakang', 'foto_rumah_kanan', 'foto_rumah_kiri',
                'foto_kk', 'foto_ktp', 'foto_meteran_air',
            ];
            $fotoFiles = [
                'foto_rekening_listrik', 'dokumen_pendukung',
            ];

            foreach ($fotoImages as $field) {
                if ($request->hasFile($field)) {
                    if ($calonPenerima->$field) {
                        Storage::disk('public')->delete($calonPenerima->$field);
                    }
                    $validated[$field] = $this->simpanFotoKompres($request->file($field));
                }
            }
            foreach ($fotoFiles as $field) {
                if ($request->hasFile($field)) {
                    if ($calonPenerima->$field) {
                        Storage::disk('public')->delete($calonPenerima->$field);
                    }
                    $validated[$field] = $request->file($field)->store('dokumen-warga', 'public');
                }
            }

            $calonPenerima->update($validated);

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

        if ((int) $calonPenerima->user_id !== (int) $user->id) {
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

        if ((int) $calonPenerima->user_id !== (int) $user->id || ($calonPenerima->tracking_status ?? 'draft') !== 'draft') {
            abort(403, 'Data yang sudah diajukan tidak dapat dihapus.');
        }

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

    // ── PRIVATE HELPERS ───────────────────────────────────────────────────────

    private function simpanFotoKompres(\Illuminate\Http\UploadedFile $file): string
    {
        return $file->store('dokumen-warga', 'public');
    }

    private function buildAsetString(array $pilihan, string $manual): string
    {
        $items = array_filter(array_map('trim', $pilihan));

        if ($manual !== '') {
            foreach (explode(',', $manual) as $item) {
                $item = trim($item);
                if ($item !== '') $items[] = $item;
            }
        }

        $items = array_unique($items);
        return count($items) > 0 ? implode(', ', $items) : 'Tidak Punya';
    }

    private function deriveKondisiRumah(string $lantai, string $dinding): string
    {
        $skorLantai = match($lantai) {
            'Tanah'         => 3,
            'Papan/Kayu'    => 2,
            'Semen Kasar'   => 2,
            'Keramik Murah' => 1,
            default         => 0,
        };

        $skorDinding = match($dinding) {
            'Bambu'                 => 3,
            'Anyaman Bambu/Gedek'   => 3,
            'Kayu/Papan'            => 2,
            'Tembok Tidak Plester'  => 1,
            default                 => 0,
        };

        $total = $skorLantai + $skorDinding;

        if ($total >= 4) return 'Tidak Layak';
        if ($total >= 1) return 'Sedang';
        return 'Layak';
    }

    private function getPredictionExplanation($calonPenerima): array
    {
        $positive = [];
        $negative = [];

        if (strtolower($calonPenerima->pekerjaan) === 'tidak bekerja') {
            $positive[] = 'Tidak bekerja';
        } elseif (in_array(strtolower($calonPenerima->pekerjaan), [
            'buruh harian lepas', 'buruh harian', 'buruh',
            'petani kecil', 'petani', 'mengurus rumah tangga',
        ])) {
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

        $aset = strtolower($calonPenerima->aset_kepemilikan ?? '');
        if (str_contains($aset, 'aset lengkap'))      $negative[] = 'Memiliki aset bernilai tinggi';
        elseif (str_contains($aset, 'motor + tanah')) $negative[] = 'Memiliki motor dan tanah';
        elseif (str_contains($aset, 'motor + emas'))  $negative[] = 'Memiliki motor dan emas';
        elseif (str_contains($aset, 'motor'))         $negative[] = 'Memiliki kendaraan bermotor';
        elseif (str_contains($aset, 'tidak punya'))   $positive[] = 'Tidak memiliki aset berarti';

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

        $lantai = $calonPenerima->lantai_rumah ?? '';
        match($lantai) {
            'Tanah'         => $positive[] = 'Lantai rumah masih tanah',
            'Papan/Kayu'    => $positive[] = 'Lantai rumah papan/kayu',
            'Semen Kasar'   => $positive[] = 'Lantai rumah semen kasar',
            'Keramik Murah' => $positive[] = 'Lantai rumah keramik murah',
            'Keramik'       => $negative[] = 'Lantai rumah sudah keramik',
            default         => null,
        };

        $dinding = $calonPenerima->dinding_rumah ?? '';
        match($dinding) {
            'Bambu'                => $positive[] = 'Dinding rumah bambu',
            'Anyaman Bambu/Gedek'  => $positive[] = 'Dinding rumah anyaman bambu/gedek',
            'Kayu/Papan'           => $positive[] = 'Dinding rumah kayu/papan',
            'Tembok Tidak Plester' => $positive[] = 'Dinding rumah tembok belum diplester',
            'Tembok Plester'       => $negative[] = 'Dinding rumah tembok plester',
            default                => null,
        };

        $atap = $calonPenerima->atap_rumah ?? '';
        match($atap) {
            'Bambu/Jerami'           => $positive[] = 'Atap rumah bambu/jerami',
            'Seng'                   => $positive[] = 'Atap rumah seng',
            'Campuran Seng+Genteng'  => $positive[] = 'Atap rumah campuran seng dan genteng',
            'Genteng'                => $negative[] = 'Atap rumah sudah genteng',
            'Genteng Beton'          => $negative[] = 'Atap rumah genteng beton',
            default                  => null,
        };

        $statusRumah = $calonPenerima->status_kepemilikan_rumah ?? '';
        match($statusRumah) {
            'Menumpang'    => $positive[] = 'Status rumah menumpang',
            'Sewa/Kontrak' => $positive[] = 'Status rumah sewa/kontrak',
            'Milik Sendiri'=> $negative[] = 'Memiliki rumah sendiri',
            default        => null,
        };

        match($calonPenerima->meteran_listrik ?? '') {
            '450VA'   => $positive[] = 'Meteran listrik 450 VA',
            '900VA'   => $positive[] = 'Meteran listrik 900 VA',
            '1300VA+' => $negative[] = 'Meteran listrik 1300 VA ke atas',
            default   => null,
        };

        match(strtolower($calonPenerima->sumber_air ?? '')) {
            'sungai' => $positive[] = 'Sumber air dari sungai',
            'sumur'  => $positive[] = 'Sumber air dari sumur',
            'pdam'   => $negative[] = 'Sumber air PDAM',
            default  => null,
        };

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