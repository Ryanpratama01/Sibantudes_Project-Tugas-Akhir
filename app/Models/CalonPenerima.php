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
      'kondisi_rumah',
      'meteran_listrik',
      'sumber_air',
      'bantuan_lain',
      'usia',
      'status_perkawinan',
      'status_verifikasi',
      'catatan_admin',
      'foto_rumah_depan',
      'foto_rumah_belakang',
      'foto_rumah_kanan',
      'foto_rumah_kiri',
      'foto_kk',
      'foto_ktp',
      'foto_rekening_listrik',
      'foto_meteran_air',
      'dokumen_pendukung',
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