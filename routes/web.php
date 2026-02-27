<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\CalonPenerima;
use App\Models\Dusun;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FilterisasiController;

// RT Controllers (folder Rt)
use App\Http\Controllers\Rt\DashboardController as RtDashboardController;
use App\Http\Controllers\Rt\CalonPenerimaController as RtCalonPenerimaController;

// Profile default (bawaan Breeze/Jetstream)
use App\Http\Controllers\ProfileController as UserProfileController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD UTAMA (redirect sesuai role)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = request()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'rt') {
        return redirect()->route('rt.dashboard');
    }

    abort(403, 'Role tidak dikenali.');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (ADMIN ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'is_active'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // DASHBOARD ADMIN
        Route::get('/dashboard', function (Request $request) {
            $user = $request->user();
            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak: hanya Admin.');
            }

            $totalWarga    = CalonPenerima::count();
            $totalDusun    = Dusun::count();
            $totalPenerima = CalonPenerima::where('status_verifikasi', 'disetujui')->count();
            $totalPending  = CalonPenerima::where('status_verifikasi', 'pending')->count();

            $statusDistribution = CalonPenerima::select('status_verifikasi as status', DB::raw('COUNT(*) as total'))
                ->groupBy('status_verifikasi')
                ->get();

            $wargaPerDusun = DB::table('dusuns')
                ->leftJoin('rts', 'rts.dusun_id', '=', 'dusuns.id')
                ->leftJoin('calon_penerimas', 'calon_penerimas.rt_id', '=', 'rts.id')
                ->select('dusuns.nama_dusun as dusun', DB::raw('COUNT(calon_penerimas.id) as total'))
                ->groupBy('dusuns.id', 'dusuns.nama_dusun')
                ->orderBy('dusuns.nama_dusun')
                ->get();

            $topWarga = DB::table('calon_penerimas')
                ->join('prediksi_kelayakans', 'prediksi_kelayakans.calon_penerima_id', '=', 'calon_penerimas.id')
                ->leftJoin('rts', 'rts.id', '=', 'calon_penerimas.rt_id')
                ->leftJoin('dusuns', 'dusuns.id', '=', 'rts.dusun_id')
                ->select(
                    'calon_penerimas.nik',
                    'calon_penerimas.nama_lengkap as nama',
                    'calon_penerimas.pekerjaan',
                    'dusuns.nama_dusun as dusun',
                    'calon_penerimas.penghasilan',
                    'prediksi_kelayakans.probability as probabilitas',
                    'calon_penerimas.status_verifikasi as status'
                )
                ->orderBy('prediksi_kelayakans.probability', 'desc')
                ->limit(10)
                ->get();

            return view('admin.dashboard', compact(
                'totalWarga',
                'totalDusun',
                'totalPenerima',
                'totalPending',
                'statusDistribution',
                'wargaPerDusun',
                'topWarga'
            ));
        })->name('dashboard');


        // DATA WARGA + SEARCH
        Route::get('/data-warga', function (Request $request) {
            $user = $request->user();
            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak: hanya Admin.');
            }

            $dusuns = Dusun::orderBy('nama_dusun')->get();
            $q = trim((string) $request->query('q', ''));

            $wargasQuery = CalonPenerima::with(['rt.dusun', 'prediksiKelayakan', 'user'])
                ->latest();

            if ($q !== '') {
                $wargasQuery->where(function ($sub) use ($q) {
                    $sub->where('nik', 'like', "%{$q}%")
                        ->orWhere('nama_lengkap', 'like', "%{$q}%");

                    $sub->orWhereHas('user', function ($uq) use ($q) {
                        $uq->where('name', 'like', "%{$q}%")
                           ->orWhere('email', 'like', "%{$q}%");
                    });
                });
            }

            $wargas = $wargasQuery->paginate(10)->withQueryString();
            return view('admin.data-warga', compact('dusuns', 'wargas'));
        })->name('data-warga');


        // SETUJUI / TOLAK
        Route::post('/data-warga/{id}/setujui', function (Request $request, $id) {
            $user = $request->user();
            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak: hanya Admin.');
            }

            $warga = CalonPenerima::findOrFail($id);
            $warga->update(['status_verifikasi' => 'disetujui']);
            return back()->with('success', 'Data warga berhasil disetujui.');
        })->name('data-warga.setujui');

        Route::post('/data-warga/{id}/tolak', function (Request $request, $id) {
            $user = $request->user();
            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak: hanya Admin.');
            }

            $warga = CalonPenerima::findOrFail($id);
            $warga->update(['status_verifikasi' => 'ditolak']);
            return back()->with('success', 'Data warga berhasil ditolak.');
        })->name('data-warga.tolak');


        // DATA AKUN (AdminController)
        Route::get('/data-akun', [AdminController::class, 'dataAkun'])->name('data-akun');
        Route::post('/data-akun/{id}/role', [AdminController::class, 'ubahRole'])->name('data-akun.role');
        Route::post('/data-akun/{id}/toggle-aktif', [AdminController::class, 'toggleAktif'])->name('data-akun.toggle-aktif');
        Route::post('/data-akun/{id}/hapus', [AdminController::class, 'hapusUser'])->name('data-akun.hapus');

        // Placeholder
        Route::get('/filterisasi', [FilterisasiController::class, 'index'])->name('filterisasi');
        Route::post('/filterisasi/tetapkan', [FilterisasiController::class, 'tetapkan'])->name('filterisasi.tetapkan');
        Route::post('/filterisasi/reset', [FilterisasiController::class, 'resetDusun'])->name('filterisasi.reset');
        Route::get('/laporan', fn () => view('admin.laporan'))->name('laporan');
    });


/*
|--------------------------------------------------------------------------
| RT ROUTES (RT ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->prefix('rt')
    ->name('rt.')
    ->group(function () {

        // DASHBOARD RT
        Route::get('/dashboard', [RtDashboardController::class, 'index'])
            ->name('dashboard');

        // Resource RT
        Route::resource('calon-penerima', RtCalonPenerimaController::class);
    });


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';