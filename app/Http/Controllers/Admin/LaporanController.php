<?php

namespace App\Http\Controllers;

use App\Models\CalonPenerima;
use App\Models\LaporanPublik;
use App\Models\PenerimaFinal;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $nik = trim((string) $request->query('nik', ''));
        $statusData  = null;
        $penerimaFinal = null;
        $explanation = ['positive' => [], 'negative' => [], 'summary' => null];

        if ($nik !== '') {
            $statusData = CalonPenerima::with(['rt.dusun', 'prediksiKelayakan'])
                ->where('nik', $nik)
                ->first();

            if ($statusData) {
                // ── Cari PenerimaFinal secara manual ──
                // Coba berbagai kemungkinan foreign key / kolom pencarian
                try {
                    $penerimaFinal = PenerimaFinal::where('nik', $nik)->first();
                } catch (\Exception $e) {
                    // Kolom 'nik' tidak ada, coba cara lain
                }

                if (!$penerimaFinal) {
                    try {
                        $penerimaFinal = PenerimaFinal::where('calon_id', $statusData->id)->first();
                    } catch (\Exception $e) {}
                }

                if (!$penerimaFinal) {
                    try {
                        $penerimaFinal = PenerimaFinal::where('id_calon', $statusData->id)->first();
                    } catch (\Exception $e) {}
                }

                if (!$penerimaFinal) {
                    try {
                        $penerimaFinal = PenerimaFinal::where('user_id', $statusData->user_id)->first();
                    } catch (\Exception $e) {}
                }

                // ── Parse explanation dari prediksiKelayakan ──
                if ($statusData->prediksiKelayakan) {
                    $pred = $statusData->prediksiKelayakan;

                    $raw = $pred->explanation
                        ?? $pred->feature_explanation
                        ?? $pred->alasan
                        ?? null;

                    if (is_string($raw) && !empty($raw)) {
                        $decoded = json_decode($raw, true);
                        if (is_array($decoded)) {
                            $explanation = [
                                'positive' => $decoded['positive'] ?? $decoded['pendukung'] ?? [],
                                'negative' => $decoded['negative'] ?? $decoded['pengurang'] ?? [],
                                'summary'  => $decoded['summary']  ?? $decoded['ringkasan'] ?? null,
                            ];
                        }
                    } elseif (is_array($raw)) {
                        $explanation = [
                            'positive' => $raw['positive'] ?? $raw['pendukung'] ?? [],
                            'negative' => $raw['negative'] ?? $raw['pengurang'] ?? [],
                            'summary'  => $raw['summary']  ?? $raw['ringkasan'] ?? null,
                        ];
                    }

                    // Fallback field lain dari prediksi
                    if (empty($explanation['positive']) && empty($explanation['negative'])) {
                        foreach (['feature_importances', 'features', 'detail', 'data'] as $field) {
                            if (!empty($pred->$field)) {
                                $val = is_string($pred->$field) ? json_decode($pred->$field, true) : $pred->$field;
                                if (is_array($val) && isset($val['positive'])) {
                                    $explanation = [
                                        'positive' => $val['positive'] ?? [],
                                        'negative' => $val['negative'] ?? [],
                                        'summary'  => $val['summary']  ?? null,
                                    ];
                                    break;
                                }
                            }
                        }
                    }

                    // ── GENERATE OTOMATIS dari data CalonPenerima ──
                    // Dijalankan jika explanation masih kosong setelah semua fallback
                    if (empty($explanation['positive']) && empty($explanation['negative'])) {
                        $cp       = $statusData;
                        $positive = [];
                        $negative = [];

                        // Pekerjaan
                        $pekerjaan = strtolower($cp->pekerjaan ?? '');
                        if (in_array($pekerjaan, ['tidak bekerja', 'tidak ada', '-', ''])) {
                            $positive[] = 'Tidak bekerja / tidak memiliki penghasilan tetap';
                        } elseif (in_array($pekerjaan, ['petani', 'buruh', 'nelayan', 'pemulung', 'pedagang kecil'])) {
                            $positive[] = 'Pekerjaan termasuk kategori rentan ('. ucfirst($pekerjaan) .')';
                        } else {
                            $negative[] = 'Memiliki pekerjaan (' . ucfirst($pekerjaan) . ')';
                        }

                        // Penghasilan
                        $penghasilan = (float)($cp->penghasilan ?? 0);
                        if ($penghasilan == 0) {
                            $positive[] = 'Penghasilan Rp 0 (tidak ada penghasilan)';
                        } elseif ($penghasilan < 500000) {
                            $positive[] = 'Penghasilan sangat rendah (Rp ' . number_format($penghasilan, 0, ',', '.') . '/bulan)';
                        } elseif ($penghasilan < 1500000) {
                            $positive[] = 'Penghasilan di bawah UMR (Rp ' . number_format($penghasilan, 0, ',', '.') . '/bulan)';
                        } else {
                            $negative[] = 'Penghasilan cukup (Rp ' . number_format($penghasilan, 0, ',', '.') . '/bulan)';
                        }

                        // Tanggungan
                        $tanggungan = (int)($cp->jumlah_tanggungan ?? 0);
                        if ($tanggungan >= 4) {
                            $positive[] = 'Jumlah tanggungan banyak (' . $tanggungan . ' orang)';
                        } elseif ($tanggungan >= 2) {
                            $positive[] = 'Memiliki tanggungan ' . $tanggungan . ' orang';
                        } else {
                            $negative[] = 'Tanggungan sedikit (' . $tanggungan . ' orang)';
                        }

                        // Aset
                        $aset = strtolower($cp->aset_kepemilikan ?? '');
                        if (in_array($aset, ['tidak punya', 'tidak ada', 'tidak memiliki', '-', ''])) {
                            $positive[] = 'Tidak memiliki aset berarti';
                        } else {
                            $negative[] = 'Memiliki aset kepemilikan (' . ucfirst($aset) . ')';
                        }

                        // Bantuan lain
                        $bantuan = strtolower($cp->bantuan_lain ?? '');
                        if (in_array($bantuan, ['tidak', 'tidak ada', '-', ''])) {
                            $positive[] = 'Belum menerima bantuan sosial lain';
                        } else {
                            $negative[] = 'Sudah menerima bantuan lain (' . $cp->bantuan_lain . ')';
                        }

                        // Usia
                        $usia = $cp->usia ?? ($cp->tanggal_lahir
                            ? \Carbon\Carbon::parse($cp->tanggal_lahir)->age
                            : null);
                        if ($usia !== null) {
                            if ($usia >= 60) {
                                $positive[] = 'Usia lanjut (' . $usia . ' tahun) — prioritas penerima';
                            } elseif ($usia >= 50) {
                                $positive[] = 'Usia ' . $usia . ' tahun (termasuk kelompok rentan)';
                            } elseif ($usia < 18) {
                                $positive[] = 'Usia ' . $usia . ' tahun (anak/remaja dalam keluarga)';
                            }
                        }

                        // Kondisi rumah
                        $kondisi = strtolower($cp->kondisi_rumah ?? '');
                        if (in_array($kondisi, ['tidak layak', 'buruk', 'rusak', 'sangat buruk'])) {
                            $positive[] = 'Kondisi rumah tidak layak huni';
                        } elseif (in_array($kondisi, ['sedang', 'cukup'])) {
                            // netral, tidak tambah ke mana-mana
                        } elseif (!empty($kondisi) && !in_array($kondisi, ['-', ''])) {
                            $negative[] = 'Kondisi rumah ' . ucfirst($kondisi);
                        }

                        // Meteran listrik
                        $meteran = strtolower($cp->meteran_listrik ?? '');
                        if (in_array($meteran, ['450va', '450 va'])) {
                            $positive[] = 'Meteran listrik 450VA (golongan sangat miskin)';
                        } elseif (in_array($meteran, ['900va', '900 va'])) {
                            $positive[] = 'Meteran listrik 900VA (golongan miskin/subsidi)';
                        } elseif (!empty($meteran) && !in_array($meteran, ['-', ''])) {
                            $negative[] = 'Meteran listrik ' . strtoupper($cp->meteran_listrik);
                        }

                        // Sumber air
                        $air = strtolower($cp->sumber_air ?? '');
                        if (in_array($air, ['sumur', 'sungai', 'mata air', 'tadah hujan'])) {
                            $positive[] = 'Sumber air ' . ucfirst($air) . ' (bukan PDAM)';
                        } elseif (!empty($air) && !in_array($air, ['-', ''])) {
                            $negative[] = 'Sumber air ' . ucfirst($air);
                        }

                        // Rekomendasi prediksi
                        $rek = strtolower($pred->recommendation ?? $pred->status ?? '');
                        $isLayakPred = in_array($rek, ['layak', 'ya', 'yes', '1', 'true']);

                        $explanation = [
                            'positive' => $positive,
                            'negative' => $negative,
                            'summary'  => 'Nilai kelayakan dihitung berdasarkan pekerjaan, penghasilan, tanggungan, aset, bantuan lain, usia, kondisi rumah, meteran listrik, dan sumber air.'
                                . (count($positive) > 0 && count($negative) > 0
                                    ? ' Terdapat faktor pendukung dan pengurang.'
                                    : (count($positive) > 0 ? ' Terdapat beberapa faktor pendukung.' : ' Tidak ada faktor yang signifikan.')),
                        ];
                    }
                }
            }
        }

        $laporanPubliks = LaporanPublik::where('is_active', true)
            ->latest()
            ->get();

        return view('landing', compact(
            'nik',
            'statusData',
            'penerimaFinal',
            'laporanPubliks',
            'explanation'
        ));
    }
}