<?php

namespace App\Http\Controllers;

use App\Models\CalonPenerima;
use App\Models\LaporanPublik;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $nik = trim((string) $request->query('nik',''));

        $statusData = null;

        if($nik !== ''){
            $statusData = CalonPenerima::with(['rt.dusun'])
                ->where('nik',$nik)
                ->first();
        }

        $laporanPubliks = LaporanPublik::where('is_active',true)
            ->latest()
            ->get();

        return view('landing',compact(
            'nik',
            'statusData',
            'laporanPubliks'
        ));
    }
}