<?php

namespace App\Http\Controllers\Rt;
use App\Http\Controllers\Controller;

use App\Models\CalonPenerima;
use App\Models\PenerimaFinal;
use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Dashboard Admin
            $stats = [
                'total_calon' => CalonPenerima::count(),
                'pending' => CalonPenerima::where('status_verifikasi', 'pending')->count(),
                'disetujui' => CalonPenerima::where('status_verifikasi', 'disetujui')->count(),
                'ditolak' => CalonPenerima::where('status_verifikasi', 'ditolak')->count(),
                'penerima_final' => PenerimaFinal::count(),
                'total_dusun' => Dusun::count(),
            ];

            $recentCalonPenerima = CalonPenerima::with(['rt.dusun', 'user', 'prediksiKelayakan'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return view('admin.dashboard', compact('stats', 'recentCalonPenerima'));
            
        } else {
            // Dashboard RT
            $stats = [
                'total_input' => CalonPenerima::where('user_id', $user->id)->count(),
                'pending' => CalonPenerima::where('user_id', $user->id)
                    ->where('status_verifikasi', 'pending')->count(),
                'disetujui' => CalonPenerima::where('user_id', $user->id)
                    ->where('status_verifikasi', 'disetujui')->count(),
                'ditolak' => CalonPenerima::where('user_id', $user->id)
                    ->where('status_verifikasi', 'ditolak')->count(),
            ];

            $recentCalonPenerima = CalonPenerima::with(['rt.dusun', 'prediksiKelayakan'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return view('rt.dashboard', compact('stats', 'recentCalonPenerima'));
        }
    }
}