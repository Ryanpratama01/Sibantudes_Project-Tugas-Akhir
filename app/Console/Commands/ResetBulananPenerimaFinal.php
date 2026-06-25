<?php

namespace App\Console\Commands;

use App\Models\PenerimaFinal;
use App\Models\PenerimaFinalArsip;
use App\Models\CalonPenerima;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

class ResetBulananPenerimaFinal extends Command
{
    protected $signature   = 'blt:reset-bulanan';
    protected $description = 'Arsipkan data penerima final lalu reset halaman admin dan RT';

    public function handle(): int
    {
        $periode   = now()->subMonth()->format('Y-m');
        $penerimas = PenerimaFinal::where('status_verifikasi', 'disetujui')->get();

        if ($penerimas->isEmpty()) {
            $this->info('Tidak ada data untuk diarsipkan.');
            return 0;
        }

        // LANGKAH A — Arsipkan semua data dulu
        foreach ($penerimas as $p) {
            PenerimaFinalArsip::create([
                'periode_arsip'     => $periode,
                'penerima_final_id' => $p->id,
                'nama_lengkap'      => $p->nama_lengkap,
                'nik'               => $p->nik,
                'no_kk'             => $p->no_kk,
                'nomor_rt'          => $p->nomor_rt,
                'nama_dusun'        => $p->nama_dusun,
                'pekerjaan'         => $p->pekerjaan,
                'penghasilan'       => $p->penghasilan,
                'jumlah_tanggungan' => $p->jumlah_tanggungan,
                'aset_kepemilikan'  => $p->aset_kepemilikan,
                'bantuan_lain'      => $p->bantuan_lain,
                'usia'              => $p->usia,
                'probability'       => $p->probability,
                'status_verifikasi' => $p->status_verifikasi,
                'tanggal_penetapan' => $p->tanggal_penetapan,
                'periode_bantuan'   => $p->periode_bantuan,
                'jumlah_bantuan'    => $p->jumlah_bantuan,
            ]);
        }

        // LANGKAH B — Kosongkan halaman admin
        PenerimaFinal::where('status_verifikasi', 'disetujui')->delete();

        // LANGKAH C — Kosongkan halaman RT
        CalonPenerima::where('tracking_status', 'selesai')
            ->update(['tracking_status' => 'belum_validasi']);

        Log::info("Reset bulanan selesai: {$penerimas->count()} data diarsipkan periode {$periode}.");
        $this->info("Selesai! {$penerimas->count()} data diarsipkan ({$periode}). Admin & RT sudah kosong.");

        return 0;
    }
}