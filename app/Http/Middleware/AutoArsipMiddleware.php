<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AutoArsipMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $this->jalankanAutoArsip();
        return $next($request);
    }

    private function jalankanAutoArsip(): void
    {
        $sekarang = Carbon::now('Asia/Jakarta');

        // ── 1. AUTO ARSIP ──────────────────────────────────────────────
        // Tiap tanggal 1, arsipkan semua data yang periode_bulan-nya
        // sudah bulan lalu (atau lebih lama) dan belum diarsip
        if ($sekarang->day === 1) {
            $batasBulan = $sekarang->copy()->startOfMonth(); // awal bulan ini

            DB::table('calon_penerimas')
                ->whereIn('tracking_status', ['terkirim', 'sedang_validasi', 'selesai'])
                ->whereNull('arsip_tahun')
                ->whereNotNull('periode_bulan')
                ->where('periode_bulan', '<', $batasBulan->toDateString())
                ->update([
                    'arsip_tahun' => $batasBulan->copy()->subMonth()->year,
                    'updated_at'  => now(),
                ]);
        }

        // ── 2. AUTO HAPUS ARSIP ────────────────────────────────────────
        // Tiap tanggal 1 Januari, hapus permanen arsip tahun lalu
        if ($sekarang->day === 1 && $sekarang->month === 1) {
            $tahunHapus = $sekarang->year - 1;

            // Hapus prediksi terkait dulu (foreign key)
            $idArsip = DB::table('calon_penerimas')
                ->where('arsip_tahun', $tahunHapus)
                ->pluck('id');

            if ($idArsip->isNotEmpty()) {
                DB::table('prediksi_kelayakans')
                    ->whereIn('calon_penerima_id', $idArsip)
                    ->delete();

                DB::table('calon_penerimas')
                    ->where('arsip_tahun', $tahunHapus)
                    ->delete();
            }
        }
    }
}