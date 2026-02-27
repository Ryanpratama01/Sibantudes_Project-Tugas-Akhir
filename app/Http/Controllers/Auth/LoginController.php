<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // TAMBAHKAN INI

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function username()
    {
        return 'email';
    }

    protected function authenticated(Request $request, $user)
    {
        // Pastikan method isAdmin() ada di model User
        // Atau ganti dengan pengecekan role yang sesuai
        if ($user->role === 'admin') { // Sesuaikan dengan struktur database Anda
            return redirect()->route('dashboard');
        }
        
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout(); // Gunakan Auth facade
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}