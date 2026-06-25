<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenerimaFinal;
use App\Models\LaporanPublik;
use App\Models\CalonPenerima;
use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;


class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $dusuns = Dusun::orderBy('nama_dusun')->get();

        $filterDusun  = $request->query('dusun', '');
        $filterStatus = $request->query('status', '');
        $filterPeriode = $request->query('periode', '');

        $query = PenerimaFinal::query();

        if ($filterDusun !== '') {
            $query->where('nama_dusun', $filterDusun);
        }

        if ($filterStatus !== '') {
            $query->where('status_verifikasi', $filterStatus);
        }

        if ($filterPeriode !== '') {
            $query->where('periode_bantuan', $filterPeriode);
        }

        $penerimas = $query->orderBy('nama_dusun')->orderByDesc('probability')->paginate(15)->withQueryString();

        $totalPenerima  = PenerimaFinal::where('status_verifikasi', 'ditetapkan')->count();
        $totalDitolak   = PenerimaFinal::where('status_verifikasi', 'ditolak')->count();
        $totalBantuan   = PenerimaFinal::where('status_verifikasi', 'ditetapkan')->sum('jumlah_bantuan');

        $periodes = PenerimaFinal::distinct()->orderByDesc('periode_bantuan')->pluck('periode_bantuan');

        $laporanPubliks = LaporanPublik::latest()->get();

        return view('admin.laporan', compact(
            'penerimas',
            'dusuns',
            'filterDusun',
            'filterStatus',
            'filterPeriode',
            'totalPenerima',
            'totalDitolak',
            'totalBantuan',
            'periodes',
            'laporanPubliks'
        ));
    }

    public function exportPdf(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $filterDusun  = $request->query('dusun', '');
        $filterStatus = $request->query('status', '');
        $filterPeriode = $request->query('periode', '');

        $query = PenerimaFinal::query();

        if ($filterDusun !== '') {
            $query->where('nama_dusun', $filterDusun);
        }

        if ($filterStatus !== '') {
            $query->where('status_verifikasi', $filterStatus);
        }

        if ($filterPeriode !== '') {
            $query->where('periode_bantuan', $filterPeriode);
        }

        $penerimas = $query->orderBy('nama_dusun')->orderByDesc('probability')->get();

        $pdf = Pdf::loadView('admin.laporan-pdf', compact('penerimas', 'filterDusun', 'filterStatus', 'filterPeriode'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penerima-blt-dd.pdf');
    }

    public function exportExcel(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $filterDusun   = $request->query('dusun', '');
        $filterStatus  = $request->query('status', '');
        $filterPeriode = $request->query('periode', '');

        return Excel::download(
            new LaporanExport($filterDusun, $filterStatus, $filterPeriode),
            'laporan-penerima-blt-dd.xlsx'
        );
    }

    public function kirimKeRt(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        // Tandai semua CalonPenerima yang sudah selesai agar RT bisa lihat hasilnya
        CalonPenerima::where('tracking_status', 'selesai')
            ->update(['tracking_status' => 'selesai']);

        return back()->with('success', 'Laporan berhasil dikirim ke RT.');
    }

    public function resetManual(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $request->validate([
            'periode_bantuan' => ['required', 'string'],
        ]);

        PenerimaFinal::where('periode_bantuan', $request->periode_bantuan)->delete();

        return back()->with('success', 'Data laporan periode ' . $request->periode_bantuan . ' berhasil direset.');
    }

    public function uploadPublikPdf(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $request->validate([
            'judul'  => ['required', 'string', 'max:255'],
            'file'   => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $path = $request->file('file')->store('laporan-publik', 'public');

        LaporanPublik::create([
            'judul'     => $request->judul,
            'file_path' => $path,
            'is_active' => true,
        ]);

        return back()->with('success', 'PDF publik berhasil diupload.');
    }

    public function hapusPublikPdf(Request $request, $id)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        $laporan = LaporanPublik::findOrFail($id);

        if (Storage::disk('public')->exists($laporan->file_path)) {
            Storage::disk('public')->delete($laporan->file_path);
        }

        $laporan->delete();

        return back()->with('success', 'PDF publik berhasil dihapus.');
    }

    public function bersihkanRiwayatSelesai(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak: hanya Admin.');
        }

        // Arsipkan semua CalonPenerima yang tracking_status = selesai
        CalonPenerima::where('tracking_status', 'selesai')
            ->whereNull('arsip_tahun')
            ->update(['arsip_tahun' => now()->year]);

        return back()->with('success', 'Riwayat data selesai berhasil dibersihkan dan diarsipkan.');
    }
}