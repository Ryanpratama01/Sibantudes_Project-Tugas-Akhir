<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RT;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Ambil semua RT + dusun untuk dropdown di halaman register
        $rts = RT::with('dusun')
            ->orderBy('dusun_id')
            ->orderBy('nomor_rt')
            ->get();

        return view('auth.register', compact('rts'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // WAJIB pilih RT biar user punya rt_id
            'rt_id' => ['required', 'exists:rts,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

            // Set default akun hasil register sebagai RT
            'role' => 'rt',
            'rt_id' => $request->rt_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Kamu bisa arahkan ke dashboard RT kalau punya route khusus RT
        // return redirect()->route('rt.dashboard');

        return redirect(route('dashboard', absolute: false));
    }
}