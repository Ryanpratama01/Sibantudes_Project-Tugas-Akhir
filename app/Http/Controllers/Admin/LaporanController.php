<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalonPenerima;
use App\Models\PenerimaFinal;
use App\Models\LaporanPublik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PenerimaFinalExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $laporans = PenerimaFinal::query()
            ->where('status_verifikasi', 'disetujui')
            ->orderByDesc('tanggal_penetapan')
            ->get();

        $laporanPubliks = LaporanPublik::latest()->get();

        return view('admin.laporan', compact('laporans', 'laporanPubliks'));
    }

    public function exportPdf(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $laporans = PenerimaFinal::query()
            ->where('status_verifikasi', 'disetujui')
            ->orderByDesc('tanggal_penetapan')
            ->get();

        $pdf = Pdf::loadView('admin.laporan-pdf', compact('laporans'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penerima-blt-dd-kelurahan-ngerong.pdf');
    }

    public function exportExcel(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        return Excel::download(new PenerimaFinalExport, 'laporan-penerima-blt-dd-kelurahan-ngerong.xlsx');
    }

    public function kirimKeRt(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $candidateIds = CalonPenerima::query()
            ->whereIn('tracking_status', ['sedang_validasi'])
            ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
            ->pluck('id')
            ->all();

        if (!empty($candidateIds)) {
            CalonPenerima::whereIn('id', $candidateIds)->update([
                'tracking_status' => 'selesai',
            ]);
        }

        return back()->with('success', 'Hasil final berhasil dikirim ke RT dusun. RT sekarang hanya dapat melihat status diterima atau ditolak.');
    }

    public function uploadPublikPdf(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'file_pdf' => 'required|mimes:pdf|max:5120',
        ]);

        $path = $request->file('file_pdf')->store('laporan-publik', 'public');

        LaporanPublik::create([
            'judul' => $data['judul'],
            'file_path' => $path,
            'uploaded_by' => $user->id,
            'is_active' => true,
        ]);

        return back()->with('success', 'PDF laporan publik berhasil diupload.');
    }

    public function hapusPublikPdf(Request $request, $id)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $laporan = LaporanPublik::findOrFail($id);

        if ($laporan->file_path && Storage::disk('public')->exists($laporan->file_path)) {
            Storage::disk('public')->delete($laporan->file_path);
        }

        $laporan->delete();

        return back()->with('success', 'PDF laporan publik berhasil dihapus.');
    }

    public function bersihkanRiwayatSelesai(Request $request)
    {
      $user = $request->user();

      if (!$user || $user->role !== 'admin') {
          abort(403, 'Akses ditolak: hanya Admin.');
      }

      $deleted = \App\Models\CalonPenerima::query()
          ->where('tracking_status', 'selesai')
          ->delete();

      return back()->with('success', "Berhasil membersihkan {$deleted} data riwayat selesai. Arsip laporan tetap tersimpan.");
    }
}