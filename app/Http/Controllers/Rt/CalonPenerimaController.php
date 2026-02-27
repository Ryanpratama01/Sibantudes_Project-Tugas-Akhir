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
    protected $mlService;

    public function __construct(MLPredictionService $mlService)
    {
        $this->mlService = $mlService;
    }

    public function index()
    {
        $user = Auth::user();

        // KHUSUS RT: hanya data milik RT tersebut
        $calonPenerimas = CalonPenerima::with(['rt.dusun', 'prediksiKelayakan'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('rt.calon-penerima.index', compact('calonPenerimas'));
    }

    public function create()
    {
        $user = Auth::user();

        $rts = RT::where('id', $user->rt_id)->with('dusun')->get();
        if ($rts->isEmpty()) {
            $rts = RT::with('dusun')->get();
        }

        return view('rt.calon-penerima.create', compact('rts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rt_id' => 'required|exists:rts,id',
            'no_kk' => 'required|string|max:16',
            'nik' => 'required|digits:16|unique:calon_penerimas,nik',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'desa' => 'required|string|max:255',
            'pekerjaan' => 'required|string',
            'penghasilan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'aset_kepemilikan' => 'required|string',
            'bantuan_lain' => 'required|in:ya,tidak',
            'usia' => 'required|integer|min:17|max:100',
            'status_perkawinan' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $calonPenerima = CalonPenerima::create([
                'user_id' => Auth::id(),
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
            ]);

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
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        // pastikan RT hanya bisa lihat miliknya
        if ($calonPenerima->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $calonPenerima->load(['rt.dusun', 'prediksiKelayakan', 'penerimaFinal']);

        return view('rt.calon-penerima.show', compact('calonPenerima'));
    }

    public function edit(CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        if ($calonPenerima->user_id !== $user->id || $calonPenerima->status_verifikasi !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $rts = RT::where('id', $user->rt_id)->with('dusun')->get();
        if ($rts->isEmpty()) {
            $rts = RT::with('dusun')->get();
        }

        return view('rt.calon-penerima.edit', compact('calonPenerima', 'rts'));
    }

    public function update(Request $request, CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        if ($calonPenerima->user_id !== $user->id || $calonPenerima->status_verifikasi !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'rt_id' => 'required|exists:rts,id',
            'no_kk' => 'required|string|max:16',
            'nik' => 'required|digits:16|unique:calon_penerimas,nik,' . $calonPenerima->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'desa' => 'required|string|max:255',
            'pekerjaan' => 'required|string',
            'penghasilan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'aset_kepemilikan' => 'required|string',
            'bantuan_lain' => 'required|in:ya,tidak',
            'usia' => 'required|integer|min:17|max:100',
            'status_perkawinan' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $calonPenerima->update($validated);

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
                $calonPenerima->prediksiKelayakan()->updateOrCreate(
                    ['calon_penerima_id' => $calonPenerima->id],
                    [
                        'probability' => $prediction['probability'],
                        'recommendation' => $prediction['recommendation'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('rt.calon-penerima.index')
                ->with('success', 'Data calon penerima berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(CalonPenerima $calonPenerima)
    {
        $user = Auth::user();

        if ($calonPenerima->user_id !== $user->id || $calonPenerima->status_verifikasi !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $calonPenerima->delete();

        return redirect()->route('rt.calon-penerima.index')
            ->with('success', 'Data calon penerima berhasil dihapus!');
    }
}