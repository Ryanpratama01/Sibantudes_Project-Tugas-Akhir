<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dusun extends Model
{
    use HasFactory;
    protected $fillable = ['nama_dusun'];

    public function rts()
    {
        return $this->hasMany(RT::class);
    }

    // TAMBAHAN INI SAJA UNTUK FIX ERROR ADMIN DASHBOARD
    public function calonPenerimas()
    {
        return $this->hasManyThrough(
            \App\Models\CalonPenerima::class,
            \App\Models\RT::class,
            'dusun_id', // foreign key di tabel rts
            'rt_id',    // foreign key di tabel calon_penerimas
            'id',       // primary key dusuns
            'id'        // primary key rts
        );
    }
}