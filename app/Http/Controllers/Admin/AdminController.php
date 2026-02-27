<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Halaman Data Akun: list semua user + role
     */
    public function dataAkun(Request $request)
    {
        $this->guardAdmin($request);

        $q = trim((string) $request->query('q', ''));

        $usersQuery = User::query()->orderByDesc('id');

        if ($q !== '') {
            $usersQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $usersQuery->paginate(10)->withQueryString();

        return view('admin.data-akun', compact('users'));
    }

    /**
     * Ubah Role: rt <-> admin
     */
    public function ubahRole(Request $request, $id)
    {
        $this->guardAdmin($request);

        $actor = $request->user();
        $target = User::findOrFail($id);

        // Tidak boleh ubah diri sendiri
        if ($target->id === $actor->id) {
            return back()->with('error', 'Kamu tidak bisa mengubah role akun sendiri.');
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,rt',
        ]);

        // Kalau target adalah admin, lalu mau diturunkan jadi RT,
        // pastikan masih ada minimal 1 admin aktif setelah perubahan
        if ($target->role === 'admin' && $validated['role'] === 'rt') {
            if ($this->countActiveAdmins() <= 1) {
                return back()->with('error', 'Tidak bisa menurunkan role admin terakhir.');
            }
        }

        $target->update([
            'role' => $validated['role'],
        ]);

        return back()->with('success', "Role {$target->name} berhasil diubah menjadi {$validated['role']}.");
    }

    /**
     * Aktifkan / Nonaktifkan Akun
     * NOTE: butuh kolom users.is_active (boolean)
     */
    public function toggleAktif(Request $request, $id)
    {
        $this->guardAdmin($request);

        $actor = $request->user();
        $target = User::findOrFail($id);

        // Tidak boleh nonaktifkan diri sendiri
        if ($target->id === $actor->id) {
            return back()->with('error', 'Kamu tidak bisa menonaktifkan akun sendiri.');
        }

        // Kalau mau menonaktifkan admin, pastikan bukan admin terakhir yang masih aktif
        $willDeactivate = (bool) $target->is_active === true; // kalau sekarang aktif, maka toggle jadi nonaktif
        if ($willDeactivate && $target->role === 'admin') {
            if ($this->countActiveAdmins() <= 1) {
                return back()->with('error', 'Tidak bisa menonaktifkan admin terakhir.');
            }
        }

        $target->is_active = !$target->is_active;
        $target->save();

        $status = $target->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$target->name} berhasil {$status}.");
    }

    /**
     * Hapus akun
     */
    public function hapusUser(Request $request, $id)
    {
        $this->guardAdmin($request);

        $actor = $request->user();
        $target = User::findOrFail($id);

        // Tidak boleh hapus diri sendiri
        if ($target->id === $actor->id) {
            return back()->with('error', 'Kamu tidak bisa menghapus akun sendiri.');
        }

        // Kalau menghapus admin, pastikan bukan admin terakhir yang masih aktif
        if ($target->role === 'admin' && (bool) $target->is_active === true) {
            if ($this->countActiveAdmins() <= 1) {
                return back()->with('error', 'Tidak bisa menghapus admin terakhir.');
            }
        }

        $target->delete();

        return back()->with('success', "Akun {$target->name} berhasil dihapus.");
    }

    /**
     * =========================
     * Helper
     * =========================
     */

    private function guardAdmin(Request $request): void
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }
    }

    private function countActiveAdmins(): int
    {
        return User::where('role', 'admin')
            ->where('is_active', 1)
            ->count();
    }
}