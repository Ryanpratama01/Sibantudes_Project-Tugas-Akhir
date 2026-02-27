<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    
    protected $fillable = ['name', 'email', 'password', 'role', 'rt_id'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
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
