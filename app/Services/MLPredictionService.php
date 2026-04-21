<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MLPredictionService
{
    public function getPrediction(array $data)
    {
        try {
            $payload = [
                'pekerjaan'          => $data['pekerjaan'] ?? '',
                'penghasilan'        => (float) ($data['penghasilan'] ?? 0),
                'jumlah_tanggungan'  => (int) ($data['jumlah_tanggungan'] ?? 0),
                'aset_kepemilikan'   => $data['aset_kepemilikan'] ?? '',
                'bantuan_lain'       => $data['bantuan_lain'] ?? '',
                'usia'               => (int) ($data['usia'] ?? 0),
                'kondisi_rumah'      => $data['kondisi_rumah'] ?? 'Tidak Diketahui',
                'meteran_listrik'    => $data['meteran_listrik'] ?? 'Tidak Diketahui',
                'sumber_air'         => $data['sumber_air'] ?? 'Tidak Diketahui',
            ];

            $apiUrl = env('ML_API_URL', 'https://ianvv.pythonanywhere.com') . '/predict';

            $response = Http::timeout(30)->post($apiUrl, $payload);

            if (!$response->successful()) {
                Log::error('ML API gagal.', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            $result = $response->json();

            if (!$result || isset($result['error'])) {
                Log::error('Hasil prediksi ML tidak valid.', [
                    'output'  => $response->body(),
                    'decoded' => $result,
                ]);
                return null;
            }

            return [
                'probability'    => $result['probability'] ?? 0,
                'recommendation' => $result['recommendation'] ?? 'Tidak Layak',
            ];
        } catch (\Throwable $e) {
            Log::error('ML Prediction Exception: ' . $e->getMessage());
            return null;
        }
    }

    public function healthCheck()
    {
        try {
            $apiUrl = env('ML_API_URL', 'https://ianvv.pythonanywhere.com') . '/predict';

            $testPayload = [
                'pekerjaan'          => 'Buruh Harian',
                'penghasilan'        => 500000,
                'jumlah_tanggungan'  => 4,
                'aset_kepemilikan'   => 'Rumah Sederhana',
                'bantuan_lain'       => 'tidak',
                'usia'               => 50,
                'kondisi_rumah'      => 'Tidak Layak',
                'meteran_listrik'    => '450VA',
                'sumber_air'         => 'Sumur',
            ];

            $response = Http::timeout(10)->post($apiUrl, $testPayload);

            if (!$response->successful()) {
                return false;
            }

            $result = $response->json();

            return is_array($result) && isset($result['probability']) && isset($result['recommendation']);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getBatchPredictions(array $dataArray)
    {
        $results = [];
        foreach ($dataArray as $item) {
            $results[] = $this->getPrediction($item);
        }
        return $results;
    }
}