<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // kalau user login tapi nonaktif -> logout + balik ke login
        if ($user && (int)($user->is_active ?? 1) === 0) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akun kamu sudah dinonaktifkan oleh Admin.');
        }

        return $next($request);
    }
}