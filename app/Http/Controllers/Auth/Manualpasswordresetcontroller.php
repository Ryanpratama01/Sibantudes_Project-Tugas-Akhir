<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ManualPasswordResetController extends Controller
{
    /**
     * Tampilkan halaman reset password manual.
     */
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * AJAX: cek apakah email terdaftar di sistem.
     * POST /password/manual/check
     */
    public function checkEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email'],
            ]);

            $found = User::where('email', $validated['email'])->exists();

            return response()->json(['found' => $found]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'found'   => false,
                'message' => collect($e->errors())->flatten()->first(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'found'   => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * AJAX: update password berdasarkan email yang sudah diverifikasi.
     * POST /password/manual/update
     */
    public function updatePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email'                 => ['required', 'email', 'exists:users,email'],
                'password'              => ['required', 'confirmed', Password::min(8)],
                'password_confirmation' => ['required'],
            ]);

            $user = User::where('email', $validated['email'])->firstOrFail();
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json(['success' => true]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }
}