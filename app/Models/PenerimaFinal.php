<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaFinal extends Model
{
    use HasFactory;

    protected $fillable = [
        'calon_penerima_id', 'tanggal_penetapan', 'periode_bantuan',
        'jumlah_bantuan', 'status_pencairan', 'tanggal_pencairan',
    ];

    protected $casts = [
        'tanggal_penetapan' => 'date',
        'tanggal_pencairan' => 'date',
        'jumlah_bantuan' => 'decimal:2',
    ];

    public function calonPenerima()
    {
        return $this->belongsTo(CalonPenerima::class);
    }
}