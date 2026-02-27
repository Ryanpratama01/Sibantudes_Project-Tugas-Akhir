<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rt_id' => [
                'required',
                'exists:rts,id',
                function ($attribute, $value, $fail) {
                    // Validasi: RT sudah terdaftar atau belum
                    $exists = User::where('rt_id', $value)->where('role', 'rt')->exists();
                    if ($exists) {
                        $fail('RT ini sudah terdaftar. Silakan pilih RT lain atau hubungi admin.');
                    }
                },
            ],
            'alamat' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'rt_id.required' => 'RT wajib dipilih.',
            'rt_id.exists' => 'RT tidak ditemukan.',
            'alamat.required' => 'Alamat wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'rt',
            'rt_id' => $data['rt_id'],
        ]);
    }
}