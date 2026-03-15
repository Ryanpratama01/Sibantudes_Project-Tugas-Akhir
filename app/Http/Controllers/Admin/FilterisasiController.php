<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalonPenerima;
use App\Models\Dusun;
use App\Models\PenerimaFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterisasiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $dusuns = Dusun::orderBy('nama_dusun')->get();

        $dusunId = (int) $request->query('dusun_id', 0);
        $kuota   = (int) $request->query('kuota', 7);

        if ($kuota <= 0) {
            $kuota = 7;
        }

        $candidates = collect();
        $pickedNiks = collect();

        if ($dusunId > 0) {
            $dusun = Dusun::find($dusunId);

            $query = CalonPenerima::query()
                ->with(['rt.dusun', 'prediksiKelayakan'])
                ->whereIn('tracking_status', ['sedang_validasi', 'selesai'])
                ->whereHas('rt', function ($q) use ($dusunId) {
                    $q->where('dusun_id', $dusunId);
                });

            $query->leftJoin('prediksi_kelayakans', 'prediksi_kelayakans.calon_penerima_id', '=', 'calon_penerimas.id')
                ->select('calon_penerimas.*', DB::raw('COALESCE(prediksi_kelayakans.probability, 0) as prob'))
                ->orderByDesc('prob')
                ->orderByDesc('calon_penerimas.created_at');

            $candidates = $query->paginate(15)->withQueryString();

            $pickedNiks = PenerimaFinal::query()
                ->where('status_verifikasi', 'disetujui')
                ->where('nama_dusun', $dusun?->nama_dusun)
                ->pluck('nik');
        }

        return view('admin.filterisasi', compact('dusuns', 'dusunId', 'kuota', 'candidates', 'pickedNiks'));
    }

    public function tetapkan(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $data = $request->validate([
            'dusun_id' => 'required|exists:dusuns,id',
            'kuota'    => 'nullable|integer|min:1|max:100',
        ]);

        $dusunId = (int) $data['dusun_id'];
        $kuota   = (int) ($data['kuota'] ?? 7);

        DB::transaction(function () use ($dusunId, $kuota) {
            $dusun = Dusun::findOrFail($dusunId);

            $allCandidates = CalonPenerima::query()
                ->with(['rt.dusun', 'prediksiKelayakan'])
                ->whereIn('tracking_status', ['sedang_validasi', 'selesai'])
                ->whereHas('rt', function ($r) use ($dusunId) {
                    $r->where('dusun_id', $dusunId);
                })
                ->leftJoin('prediksi_kelayakans', 'prediksi_kelayakans.calon_penerima_id', '=', 'calon_penerimas.id')
                ->select('calon_penerimas.*', DB::raw('COALESCE(prediksi_kelayakans.probability, 0) as prob'))
                ->orderByDesc('prob')
                ->orderByDesc('calon_penerimas.created_at')
                ->get();

            $topIds = $allCandidates->take($kuota)->pluck('id')->all();
            $allIds = $allCandidates->pluck('id')->all();

            // Top kuota = disetujui
            if (!empty($topIds)) {
                CalonPenerima::whereIn('id', $topIds)->update([
                    'status_verifikasi' => 'disetujui',
                ]);
            }

            // Sisanya = ditolak
            $notPickedIds = array_diff($allIds, $topIds);

            if (!empty($notPickedIds)) {
                CalonPenerima::whereIn('id', $notPickedIds)->update([
                    'status_verifikasi' => 'ditolak',
                ]);
            }

            // Ambil ulang data final setelah status diperbarui
            $finalCandidates = CalonPenerima::query()
                ->with(['rt.dusun', 'prediksiKelayakan'])
                ->whereIn('id', $allIds)
                ->get();

            foreach ($finalCandidates as $candidate) {
                $probability = $candidate->prediksiKelayakan->probability ?? 0;

                PenerimaFinal::updateOrCreate(
                    [
                        'nik' => $candidate->nik,
                        'periode_bantuan' => '2026 Triwulan 1',
                    ],
                    [
                        'nama_lengkap'      => $candidate->nama_lengkap ?? $candidate->nama ?? '-',
                        'no_kk'             => $candidate->no_kk ?? '-',
                        'rt_id'             => $candidate->rt_id,
                        'nomor_rt'          => $candidate->rt?->nomor_rt ?? null,
                        'nama_dusun'        => $candidate->rt?->dusun?->nama_dusun ?? $dusun->nama_dusun,
                        'pekerjaan'         => $candidate->pekerjaan ?? '-',
                        'penghasilan'       => $candidate->penghasilan ?? 0,
                        'jumlah_tanggungan' => $candidate->jumlah_tanggungan ?? 0,
                        'aset_kepemilikan'  => $candidate->aset_kepemilikan ?? '-',
                        'bantuan_lain'      => $candidate->bantuan_lain ?? 'tidak',
                        'usia'              => $candidate->usia ?? 0,
                        'probability'       => $probability,
                        'status_verifikasi' => $candidate->status_verifikasi ?? 'pending',
                        'tanggal_penetapan' => now()->toDateString(),
                        'periode_bantuan'   => '2026 Triwulan 1',
                        'jumlah_bantuan'    => 300000,
                    ]
                );
            }
        });

        return back()->with('success', "Berhasil menetapkan kuota {$kuota} penerima untuk dusun terpilih.");
    }

    public function resetDusun(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $data = $request->validate([
            'dusun_id' => 'required|exists:dusuns,id',
        ]);

        $dusun = Dusun::findOrFail($data['dusun_id']);

        DB::transaction(function () use ($dusun) {
            $candidateIds = CalonPenerima::query()
                ->whereHas('rt', function ($r) use ($dusun) {
                    $r->where('dusun_id', $dusun->id);
                })
                ->whereIn('tracking_status', ['sedang_validasi', 'selesai'])
                ->pluck('id')
                ->all();

            if (!empty($candidateIds)) {
                CalonPenerima::whereIn('id', $candidateIds)->update([
                    'status_verifikasi' => 'pending',
                ]);
            }

            PenerimaFinal::where('nama_dusun', $dusun->nama_dusun)->delete();
        });

        return back()->with('success', 'Hasil filterisasi dusun berhasil di-reset.');
    }
}