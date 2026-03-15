<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\CalonPenerima;
use App\Models\Dusun;

use App\Http\Controllers\LandingPageController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FilterisasiController;
use App\Http\Controllers\Admin\LaporanController;

// RT Controllers
use App\Http\Controllers\Rt\DashboardController as RtDashboardController;
use App\Http\Controllers\Rt\CalonPenerimaController as RtCalonPenerimaController;
use App\Http\Controllers\Rt\LaporanController as RtLaporanController;

/*
|--------------------------------------------------------------------------
| ROOT / LANDING PAGE PUBLIK
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

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

            $visibleStatuses = ['terkirim', 'sedang_validasi', 'selesai'];

            $totalWarga = CalonPenerima::whereIn('tracking_status', $visibleStatuses)->count();

            $totalDusun = Dusun::count();

            $totalPenerima = CalonPenerima::whereIn('tracking_status', $visibleStatuses)
                ->where('status_verifikasi', 'disetujui')
                ->count();

            $totalPending = CalonPenerima::whereIn('tracking_status', $visibleStatuses)
                ->where('status_verifikasi', 'pending')
                ->count();

            $statusDistribution = CalonPenerima::select('status_verifikasi as status', DB::raw('COUNT(*) as total'))
                ->whereIn('tracking_status', $visibleStatuses)
                ->groupBy('status_verifikasi')
                ->get();

            $wargaPerDusun = DB::table('dusuns')
                ->leftJoin('rts', 'rts.dusun_id', '=', 'dusuns.id')
                ->leftJoin('calon_penerimas', function ($join) use ($visibleStatuses) {
                    $join->on('calon_penerimas.rt_id', '=', 'rts.id')
                        ->whereIn('calon_penerimas.tracking_status', $visibleStatuses);
                })
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
                ->whereIn('calon_penerimas.tracking_status', $visibleStatuses)
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
                ->whereIn('tracking_status', ['terkirim', 'sedang_validasi', 'selesai'])
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

        // TAMBAHAN: BERSIHKAN DATA WARGA AKTIF
        Route::delete('/data-warga/bersihkan', function (Request $request) {
            $user = $request->user();

            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak: hanya Admin.');
            }

            DB::transaction(function () {
                // hapus hasil prediksi yang terkait data aktif
                DB::table('prediksi_kelayakans')->delete();

                // hapus data pengajuan aktif
                DB::table('calon_penerimas')->delete();
            });

            return redirect()->route('admin.data-warga')
                ->with('success', 'Semua data warga aktif berhasil dibersihkan. Arsip laporan tetap aman.');
        })->name('data-warga.bersihkan');

        // MULAI VALIDASI
        Route::post('/data-warga/{id}/mulai-validasi', function (Request $request, $id) {
            $user = $request->user();

            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak: hanya Admin.');
            }

            $warga = CalonPenerima::findOrFail($id);

            if ($warga->tracking_status !== 'terkirim') {
                return back()->with('error', 'Data ini belum bisa masuk tahap validasi.');
            }

            $warga->update([
                'tracking_status' => 'sedang_validasi',
                'status_verifikasi' => 'pending',
            ]);

            return back()->with('success', 'Proses validasi data telah dimulai.');
        })->name('data-warga.mulai-validasi');

        // KIRIM HASIL VALIDASI KE RT
        Route::post('/data-warga/{id}/selesai-validasi', function (Request $request, $id) {
            $user = $request->user();

            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak: hanya Admin.');
            }

            $warga = CalonPenerima::findOrFail($id);

            if ($warga->tracking_status !== 'sedang_validasi') {
                return back()->with('error', 'Data ini belum berada pada tahap sedang divalidasi.');
            }

            if (!in_array($warga->status_verifikasi, ['disetujui', 'ditolak'])) {
                return back()->with('error', 'Hasil akhir belum ditetapkan. Silakan proses filterisasi terlebih dahulu.');
            }

            $warga->update([
                'tracking_status' => 'selesai',
            ]);

            return back()->with('success', 'Hasil validasi berhasil dikirim ke RT.');
        })->name('data-warga.selesai-validasi');

        // DATA AKUN
        Route::get('/data-akun', [AdminController::class, 'dataAkun'])->name('data-akun');
        Route::post('/data-akun/{id}/role', [AdminController::class, 'ubahRole'])->name('data-akun.role');
        Route::post('/data-akun/{id}/toggle-aktif', [AdminController::class, 'toggleAktif'])->name('data-akun.toggle-aktif');
        Route::delete('/data-akun/{id}/hapus', [AdminController::class, 'hapusUser'])->name('data-akun.hapus');

        // FILTERISASI
        Route::get('/filterisasi', [FilterisasiController::class, 'index'])->name('filterisasi');
        Route::post('/filterisasi/tetapkan', [FilterisasiController::class, 'tetapkan'])->name('filterisasi.tetapkan');
        Route::post('/filterisasi/reset', [FilterisasiController::class, 'resetDusun'])->name('filterisasi.reset');

        // LAPORAN ADMIN
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
        Route::post('/laporan/kirim-ke-rt', [LaporanController::class, 'kirimKeRt'])->name('laporan.kirim-ke-rt');
        Route::post('/laporan/upload-publik-pdf', [LaporanController::class, 'uploadPublikPdf'])->name('laporan.upload-publik-pdf');
        Route::delete('/laporan/publik-pdf/{id}', [LaporanController::class, 'hapusPublikPdf'])->name('laporan.hapus-publik-pdf');
        Route::delete('/laporan/bersihkan-riwayat-selesai', [LaporanController::class, 'bersihkanRiwayatSelesai'])
              ->name('laporan.bersihkan-riwayat-selesai');
    });

/*
|--------------------------------------------------------------------------
| RT ROUTES (RT ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'is_active'])
    ->prefix('rt')
    ->name('rt.')
    ->group(function () {

        // DASHBOARD RT
        Route::get('/dashboard', [RtDashboardController::class, 'index'])->name('dashboard');

        // LAPORAN RT
        Route::get('/laporan', [RtLaporanController::class, 'index'])->name('laporan.index');

        // AJUKAN DATA KE ADMIN
        Route::patch('/calon-penerima/{calonPenerima}/ajukan', [RtCalonPenerimaController::class, 'ajukan'])
            ->name('calon-penerima.ajukan');

        // RESOURCE RT
        Route::resource('calon-penerima', RtCalonPenerimaController::class);
    });

require __DIR__ . '/auth.php';