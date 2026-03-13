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
        $pickedIds  = collect();

        if ($dusunId > 0) {
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

            $pickedIds = PenerimaFinal::whereIn('calon_penerima_id', $candidates->pluck('id')->all())
                ->pluck('calon_penerima_id');
        }

        return view('admin.filterisasi', compact('dusuns', 'dusunId', 'kuota', 'candidates', 'pickedIds'));
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
            // Ambil semua kandidat dalam dusun yang sedang divalidasi / sudah selesai
            $allCandidates = CalonPenerima::query()
                ->whereIn('tracking_status', ['sedang_validasi', 'selesai'])
                ->whereHas('rt', function ($r) use ($dusunId) {
                    $r->where('dusun_id', $dusunId);
                })
                ->leftJoin('prediksi_kelayakans', 'prediksi_kelayakans.calon_penerima_id', '=', 'calon_penerimas.id')
                ->select('calon_penerimas.id', DB::raw('COALESCE(prediksi_kelayakans.probability, 0) as prob'))
                ->orderByDesc('prob')
                ->orderByDesc('calon_penerimas.created_at')
                ->get();

            $topIds = $allCandidates->take($kuota)->pluck('id')->all();
            $allIds = $allCandidates->pluck('id')->all();

            // Hapus hasil final lama untuk kandidat pada dusun ini
            if (!empty($allIds)) {
                PenerimaFinal::whereIn('calon_penerima_id', $allIds)->delete();
            }

            // Simpan hasil final penerima yang masuk kuota
            foreach ($topIds as $id) {
                PenerimaFinal::updateOrCreate(
                    ['calon_penerima_id' => $id],
                    [
                        'tanggal_penetapan' => now()->toDateString(),
                        'periode_bantuan'   => date('Y') . ' Triwulan 1',
                        'jumlah_bantuan'    => 0,
                        'status_pencairan'  => 'belum_cair',
                        'tanggal_pencairan' => null,
                    ]
                );
            }

            // Yang masuk kuota = disetujui
            if (!empty($topIds)) {
                CalonPenerima::whereIn('id', $topIds)->update([
                    'status_verifikasi' => 'disetujui',
                ]);
            }

            // Yang tidak masuk kuota = ditolak
            $notPickedIds = array_diff($allIds, $topIds);
            if (!empty($notPickedIds)) {
                CalonPenerima::whereIn('id', $notPickedIds)->update([
                    'status_verifikasi' => 'ditolak',
                ]);
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

        $dusunId = (int) $data['dusun_id'];

        DB::transaction(function () use ($dusunId) {
            $candidateIds = CalonPenerima::query()
                ->whereHas('rt', function ($r) use ($dusunId) {
                    $r->where('dusun_id', $dusunId);
                })
                ->whereIn('tracking_status', ['sedang_validasi', 'selesai'])
                ->pluck('id')
                ->all();

            if (!empty($candidateIds)) {
                PenerimaFinal::whereIn('calon_penerima_id', $candidateIds)->delete();

                CalonPenerima::whereIn('id', $candidateIds)->update([
                    'status_verifikasi' => 'pending',
                ]);
            }
        });

        return back()->with('success', 'Hasil filterisasi dusun berhasil di-reset.');
    }
}