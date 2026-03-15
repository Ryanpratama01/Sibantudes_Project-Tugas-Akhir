<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaFinal extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'bantuan_lain',
        'usia',
        'probability',
        'status_verifikasi',
        'tanggal_penetapan',
        'periode_bantuan',
        'jumlah_bantuan',
    ];

    protected $casts = [
        'tanggal_penetapan' => 'date',
        'jumlah_bantuan' => 'decimal:2',
        'probability' => 'decimal:4',
        'penghasilan' => 'integer',
        'jumlah_tanggungan' => 'integer',
        'usia' => 'integer',
    ];
}