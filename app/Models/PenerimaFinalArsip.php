<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaFinalArsip extends Model
{
    protected $table   = 'penerima_final_arsip';
    protected $guarded = [];
    protected $casts   = ['tanggal_penetapan' => 'date'];
}