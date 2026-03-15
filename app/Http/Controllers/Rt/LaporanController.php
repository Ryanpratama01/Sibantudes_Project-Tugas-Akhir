<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\PenerimaFinal;
use App\Models\Rt;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'rt') {
            abort(403, 'Akses ditolak: hanya RT.');
        }

        $rt = Rt::with('dusun')->find($user->rt_id);

        if (!$rt) {
            $laporans = collect();
            $stats = [
                'total' => 0,
                'diterima' => 0,
                'ditolak' => 0,
            ];

            return view('rt.laporan', compact('laporans', 'stats'));
        }

        $nomorRt = $rt->nomor_rt;
        $namaDusun = $rt->dusun->nama_dusun ?? null;

        $laporans = PenerimaFinal::query()
            ->where('nomor_rt', $nomorRt)
            ->where('nama_dusun', $namaDusun)
            ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
            ->orderByDesc('tanggal_penetapan')
            ->get();

        $stats = [
            'total' => $laporans->count(),
            'diterima' => $laporans->where('status_verifikasi', 'disetujui')->count(),
            'ditolak' => $laporans->where('status_verifikasi', 'ditolak')->count(),
        ];

        return view('rt.laporan', compact('laporans', 'stats'));
    }
}