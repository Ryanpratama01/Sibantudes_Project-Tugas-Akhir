<?php

namespace App\Console\Commands;

use App\Models\PenerimaFinal;
use App\Models\RiwayatPenerima;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetPenerimaFinalBulanan extends Command
{
    protected $signature   = 'penerima:reset-bulanan';
    protected $description = 'Arsipkan semua data penerima_finals ke riwayat_penerima lalu kosongkan tabelnya (dijalankan tiap tanggal 1)';

    public function handle(): int
    {
        $periode = now()->subMonth()->format('Y-m'); // arsip bulan sebelumnya

        $semua = PenerimaFinal::all();

        if ($semua->isEmpty()) {
            $this->info('Tidak ada data penerima_finals untuk diarsipkan.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($semua, $periode) {
            foreach ($semua as $p) {
                RiwayatPenerima::create([
                    'periode'           => $periode,
                    'nama_lengkap'      => $p->nama_lengkap,
                    'nik'               => $p->nik,
                    'no_kk'             => $p->no_kk,
                    'rt_id'             => $p->rt_id,
                    'nomor_rt'          => $p->nomor_rt,
                    'nama_dusun'        => $p->nama_dusun,
                    'pekerjaan'         => $p->pekerjaan,
                    'penghasilan'       => $p->penghasilan,
                    'jumlah_tanggungan' => $p->jumlah_tanggungan,
                    'aset_kepemilikan'  => $p->aset_kepemilikan,
                    'kondisi_rumah'     => $p->kondisi_rumah,
                    'meteran_listrik'   => $p->meteran_listrik,
                    'sumber_air'        => $p->sumber_air,
                    'bantuan_lain'      => $p->bantuan_lain,
                    'usia'              => $p->usia,
                    'probability'       => $p->probability,
                    'status_verifikasi' => $p->status_verifikasi,
                    'tanggal_penetapan' => $p->tanggal_penetapan,
                    'jumlah_bantuan'    => $p->jumlah_bantuan,
                    'di_arsipkan_pada'  => now(),
                ]);
            }

            // Kosongkan tabel penerima_finals
            PenerimaFinal::truncate();
        });

        $this->info("Berhasil mengarsipkan {$semua->count()} data penerima ke periode {$periode}.");
        return self::SUCCESS;
    }
}