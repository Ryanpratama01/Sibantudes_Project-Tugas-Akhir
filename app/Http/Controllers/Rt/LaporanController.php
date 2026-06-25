<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\PenerimaFinal;
use App\Models\RT;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'rt') {
            abort(403, 'Akses ditolak: hanya RT.');
        }

        $rt = RT::with('dusun')->find($user->rt_id);

        if (!$rt) {
            $laporans = collect();
            $stats = [
                'total'    => 0,
                'diterima' => 0,
                'ditolak'  => 0,
            ];

            return view('rt.laporan', compact('laporans', 'stats'));
        }

        $laporans = PenerimaFinal::query()
            ->where('rt_id', $rt->id)
            ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
            ->orderByDesc('tanggal_penetapan')
            ->get();

        $stats = [
            'total'    => $laporans->count(),
            'diterima' => $laporans->where('status_verifikasi', 'disetujui')->count(),
            'ditolak'  => $laporans->where('status_verifikasi', 'ditolak')->count(),
        ];

        return view('rt.laporan', compact('laporans', 'stats'));
    }
}