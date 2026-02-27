<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RT extends Model
{
    use HasFactory;

    // ✅ Tambahkan ini supaya Laravel tidak mencari tabel r_t_s
    protected $table = 'rts';

    protected $fillable = ['dusun_id', 'nomor_rt', 'kode_rt'];

    public function dusun()
    {
        return $this->belongsTo(Dusun::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function calonPenerimas()
    {
        return $this->hasMany(CalonPenerima::class);
    }
}
