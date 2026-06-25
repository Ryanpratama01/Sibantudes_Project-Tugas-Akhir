<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeDanaBlt extends Model
{
    protected $table = 'periode_dana_blt';

    protected $fillable = [
        'periode',
        'dana_awal',
        'jumlah_penerima',
        'dana_terpakai',
        'dana_sisa',
        'keterangan',
    ];

    protected $casts = [
        'dana_awal'       => 'integer',
        'jumlah_penerima' => 'integer',
        'dana_terpakai'   => 'integer',
        'dana_sisa'       => 'integer',
    ];
}