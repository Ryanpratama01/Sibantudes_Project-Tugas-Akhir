<?php

namespace App\Http\Controllers;

use App\Models\CalonPenerima;
use App\Models\LaporanPublik;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $nik = trim((string) $request->query('nik', ''));

        $statusData = null;
        $penerimaFinal = null;

        if ($nik !== '') {
            $statusData = CalonPenerima::with(['rt.dusun'])
                ->where('nik', $nik)
                ->first();

            // LOGIKA PENETAPAN: pakai kolom status_verifikasi yang benar-benar ada di tabel
            if ($statusData && isset($statusData->status_verifikasi)) {
                $currentStatus = strtolower($statusData->status_verifikasi);

                if (in_array($currentStatus, ['ditolak', 'disetujui'])) {
                    // Buat object penampung agar dibaca sebagai '$penerimaFinal' oleh file Blade Anda
                    $penerimaFinal = new \stdClass();
                    $penerimaFinal->status = $statusData->status_verifikasi;
                    $penerimaFinal->created_at = $statusData->updated_at ?? now();
                }
            }
        }

        $laporanPubliks = LaporanPublik::where('is_active', true)
            ->latest()
            ->get();

        return view('landing', compact(
            'nik',
            'statusData',
            'laporanPubliks',
            'penerimaFinal'
        ));
    }
}