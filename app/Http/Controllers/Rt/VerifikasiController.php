<?php

namespace App\Http\Controllers;

use App\Models\CalonPenerima;
use App\Models\PenerimaFinal;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index()
    {
        $pendingList = CalonPenerima::with(['rt.dusun', 'user', 'prediksiKelayakan'])
            ->where('status_verifikasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.verifikasi.index', compact('pendingList'));
    }

    public function review(CalonPenerima $calonPenerima)
    {
        $calonPenerima->load(['rt.dusun', 'user', 'prediksiKelayakan']);
        
        return view('admin.verifikasi.review', compact('calonPenerima'));
    }

    public function approve(Request $request, CalonPenerima $calonPenerima)
    {
        $validated = $request->validate([
            'catatan_admin' => 'nullable|string',
        ]);

        $calonPenerima->update([
            'status_verifikasi' => 'disetujui',
            'catatan_admin' => $validated['catatan_admin'] ?? 'Disetujui oleh admin',
        ]);

        return redirect()->route('verifikasi.index')
            ->with('success', 'Calon penerima telah disetujui!');
    }

    public function reject(Request $request, CalonPenerima $calonPenerima)
    {
        $validated = $request->validate([
            'catatan_admin' => 'required|string',
        ]);

        $calonPenerima->update([
            'status_verifikasi' => 'ditolak',
            'catatan_admin' => $validated['catatan_admin'],
        ]);

        return redirect()->route('verifikasi.index')
            ->with('success', 'Calon penerima telah ditolak!');
    }

    public function disetujui()
    {
        $disetujuiList = CalonPenerima::with(['rt.dusun', 'user', 'prediksiKelayakan'])
            ->where('status_verifikasi', 'disetujui')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('admin.verifikasi.disetujui', compact('disetujuiList'));
    }

    public function ditolak()
    {
        $ditolakList = CalonPenerima::with(['rt.dusun', 'user', 'prediksiKelayakan'])
            ->where('status_verifikasi', 'ditolak')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('admin.verifikasi.ditolak', compact('ditolakList'));
    }

    public function tetapkanPenerima(Request $request, CalonPenerima $calonPenerima)
    {
        if ($calonPenerima->status_verifikasi !== 'disetujui') {
            return redirect()->back()
                ->with('error', 'Hanya calon penerima yang disetujui yang bisa ditetapkan!');
        }

        $validated = $request->validate([
            'periode_bantuan' => 'required|string',
            'jumlah_bantuan' => 'required|numeric|min:0',
        ]);

        PenerimaFinal::create([
            'calon_penerima_id' => $calonPenerima->id,
            'tanggal_penetapan' => now(),
            'periode_bantuan' => $validated['periode_bantuan'],
            'jumlah_bantuan' => $validated['jumlah_bantuan'],
            'status_pencairan' => 'belum_cair',
        ]);

        return redirect()->route('penerima-final.index')
            ->with('success', 'Penerima final berhasil ditetapkan!');
    }
}