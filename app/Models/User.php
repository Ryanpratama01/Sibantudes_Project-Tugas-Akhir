<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'rt_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function rt()
    {
        return $this->belongsTo(RT::class);
    }

    public function calonPenerimas()
    {
        return $this->hasMany(CalonPenerima::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isRT()
    {
        return $this->role === 'rt';
    }
}