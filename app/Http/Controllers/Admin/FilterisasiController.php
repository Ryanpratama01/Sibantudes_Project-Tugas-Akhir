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
        if (!$user || $user->role !== 'admin') abort(403, 'Akses ditolak: hanya Admin.');

        $dusuns = Dusun::orderBy('nama_dusun')->get();

        $dusunId = (int) $request->query('dusun_id', 0);
        $kuota   = (int) $request->query('kuota', 7);
        if ($kuota <= 0) $kuota = 7;

        $candidates = collect();
        $pickedIds  = collect();

        if ($dusunId > 0) {
            $query = CalonPenerima::query()
                ->with(['rt.dusun', 'prediksiKelayakan'])
                ->where('status_verifikasi', 'disetujui') // ✅ ONLY DISETUJUI
                ->whereHas('rt', fn($q) => $q->where('dusun_id', $dusunId));

            // urut probability DESC (null jadi 0)
            $query->leftJoin('prediksi_kelayakans', 'prediksi_kelayakans.calon_penerima_id', '=', 'calon_penerimas.id')
                ->select('calon_penerimas.*', DB::raw('COALESCE(prediksi_kelayakans.probability, 0) as prob'))
                ->orderByDesc('prob')
                ->orderByDesc('calon_penerimas.created_at');

            $candidates = $query->paginate(15)->withQueryString();

            // tandai yang sudah masuk penerima_final
            $pickedIds = PenerimaFinal::whereIn('calon_penerima_id', $candidates->pluck('id')->all())
                ->pluck('calon_penerima_id');
        }

        return view('admin.filterisasi', compact('dusuns', 'dusunId', 'kuota', 'candidates', 'pickedIds'));
    }

    public function tetapkan(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') abort(403, 'Akses ditolak: hanya Admin.');

        $data = $request->validate([
            'dusun_id' => 'required|exists:dusuns,id',
            'kuota'    => 'nullable|integer|min:1|max:100',
        ]);

        $dusunId = (int) $data['dusun_id'];
        $kuota   = (int) ($data['kuota'] ?? 7);

        DB::transaction(function () use ($dusunId, $kuota) {

            $top = CalonPenerima::query()
                ->where('status_verifikasi', 'disetujui') // ✅ ONLY DISETUJUI
                ->whereHas('rt', fn($r) => $r->where('dusun_id', $dusunId))
                ->leftJoin('prediksi_kelayakans', 'prediksi_kelayakans.calon_penerima_id', '=', 'calon_penerimas.id')
                ->select('calon_penerimas.id', DB::raw('COALESCE(prediksi_kelayakans.probability, 0) as prob'))
                ->orderByDesc('prob')
                ->orderByDesc('calon_penerimas.created_at')
                ->limit($kuota)
                ->get();

            foreach ($top as $row) {
                PenerimaFinal::updateOrCreate(
                    ['calon_penerima_id' => $row->id],
                    ['dusun_id' => $dusunId] // kalau tabel kamu ga punya dusun_id, bilang ya nanti aku rapikan
                );
            }
        });

        return back()->with('success', "Berhasil menetapkan kuota {$kuota} penerima (status disetujui) untuk dusun terpilih.");
    }

    public function resetDusun(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') abort(403, 'Akses ditolak: hanya Admin.');

        $data = $request->validate([
            'dusun_id' => 'required|exists:dusuns,id',
        ]);

        $dusunId = (int) $data['dusun_id'];

        PenerimaFinal::where('dusun_id', $dusunId)->delete();

        return back()->with('success', 'Hasil filterisasi dusun berhasil di-reset.');
    }
}