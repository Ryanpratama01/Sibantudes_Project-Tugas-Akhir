<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPublik extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'file_path',
        'uploaded_by',
        'is_active',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}