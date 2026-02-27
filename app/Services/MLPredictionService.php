<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MLPredictionService
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.ml_api.url', 'http://127.0.0.1:5000');
    }

    /**
     * Dapatkan prediksi dari ML API
     */
    public function getPrediction(array $data)
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout(30)->post($this->apiUrl . '/predict', [
                'pekerjaan' => $data['pekerjaan'],
                'penghasilan' => (float) $data['penghasilan'],
                'jumlah_tanggungan' => (int) $data['jumlah_tanggungan'],
                'aset_kepemilikan' => $data['aset_kepemilikan'],
                'bantuan_lain' => $data['bantuan_lain'],
                'usia' => (int) $data['usia'],
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('ML API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('ML API Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cek apakah ML API sedang online
     */
    public function healthCheck()
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout(5)->get($this->apiUrl . '/health');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Prediksi untuk banyak data sekaligus
     */
    public function getBatchPredictions(array $dataArray)
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout(60)->post($this->apiUrl . '/batch-predict', [
                'data' => $dataArray
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (\Exception $e) {
            Log::error('ML Batch API Exception: ' . $e->getMessage());
            return null;
        }
    }
}