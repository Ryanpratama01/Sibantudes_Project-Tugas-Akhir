<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPenerima extends Model
{
    protected $table = 'riwayat_penerima';

    protected $fillable = [
        'periode',
        'nama_lengkap',
        'nik',
        'no_kk',
        'rt_id',
        'nomor_rt',
        'nama_dusun',
        'pekerjaan',
        'penghasilan',
        'jumlah_tanggungan',
        'aset_kepemilikan',
        'kondisi_rumah',
        'meteran_listrik',
        'sumber_air',
        'bantuan_lain',
        'usia',
        'probability',
        'status_verifikasi',
        'tanggal_penetapan',
        'jumlah_bantuan',
        'di_arsipkan_pada',
    ];

    protected $casts = [
        'tanggal_penetapan' => 'date',
        'di_arsipkan_pada'  => 'datetime',
    ];
}