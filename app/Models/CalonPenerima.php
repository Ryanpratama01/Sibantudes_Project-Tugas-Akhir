<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonPenerima extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rt_id',
        'no_kk',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'desa',
        'pekerjaan',
        'penghasilan',
        'jumlah_tanggungan',
        'aset_kepemilikan',
        'bantuan_lain',
        'usia',
        'status_perkawinan',
        'status_verifikasi',
        'tracking_status', // status tracking proses
        'catatan_admin',
    ];

    protected $casts = [
        'penghasilan' => 'decimal:2',
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rt()
    {
        return $this->belongsTo(RT::class);
    }

    public function prediksiKelayakan()
    {
        return $this->hasOne(PrediksiKelayakan::class);
    }

    public function penerimaFinal()
    {
        return $this->hasOne(PenerimaFinal::class);
    }
}