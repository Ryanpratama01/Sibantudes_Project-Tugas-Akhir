<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrediksiKelayakan extends Model
{
    use HasFactory;
    protected $fillable = ['calon_penerima_id', 'probability', 'recommendation'];
    protected $casts = ['probability' => 'decimal:2'];

    public function calonPenerima()
    {
        return $this->belongsTo(CalonPenerima::class);
    }
}