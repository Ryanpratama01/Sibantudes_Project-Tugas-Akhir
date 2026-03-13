<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MLPredictionService
{
    /**
     * Dapatkan prediksi dari script Python langsung
     */
    public function getPrediction(array $data)
    {
        try {
            $payload = [
                'pekerjaan' => $data['pekerjaan'] ?? '',
                'penghasilan' => (float) ($data['penghasilan'] ?? 0),
                'jumlah_tanggungan' => (int) ($data['jumlah_tanggungan'] ?? 0),
                'aset_kepemilikan' => $data['aset_kepemilikan'] ?? '',
                'bantuan_lain' => $data['bantuan_lain'] ?? '',
                'usia' => (int) ($data['usia'] ?? 0),
            ];

            $jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            $pythonPath = 'python';
            $scriptPath = base_path('ml/predict.py');

            $descriptorspec = [
                0 => ['pipe', 'r'], // stdin
                1 => ['pipe', 'w'], // stdout
                2 => ['pipe', 'w'], // stderr
            ];

            $process = proc_open(
                $pythonPath . ' ' . escapeshellarg($scriptPath),
                $descriptorspec,
                $pipes,
                base_path()
            );

            if (!is_resource($process)) {
                Log::error('Gagal menjalankan proses Python ML.');
                return null;
            }

            fwrite($pipes[0], $jsonPayload);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $errorOutput = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $exitCode = proc_close($process);

            if ($exitCode !== 0) {
                Log::error('Python ML exit code bukan 0.', [
                    'exit_code' => $exitCode,
                    'stderr' => $errorOutput,
                    'stdout' => $output,
                ]);
                return null;
            }

            $result = json_decode($output, true);

            if (!$result || isset($result['error'])) {
                Log::error('Hasil prediksi ML tidak valid.', [
                    'output' => $output,
                    'stderr' => $errorOutput,
                    'decoded' => $result,
                ]);
                return null;
            }

            return [
                'probability' => $result['probability'] ?? 0,
                'recommendation' => $result['recommendation'] ?? 'Tidak Layak',
            ];
        } catch (\Throwable $e) {
            Log::error('ML Prediction Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cek apakah script ML bisa dijalankan
     */
    public function healthCheck()
    {
        try {
            $pythonPath = 'python';
            $scriptPath = base_path('ml/predict.py');

            if (!file_exists($scriptPath)) {
                return false;
            }

            $testPayload = json_encode([
                'pekerjaan' => 'buruh',
                'penghasilan' => 500000,
                'jumlah_tanggungan' => 4,
                'aset_kepemilikan' => 'motor',
                'bantuan_lain' => 'tidak',
                'usia' => 50,
            ]);

            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open(
                $pythonPath . ' ' . escapeshellarg($scriptPath),
                $descriptorspec,
                $pipes,
                base_path()
            );

            if (!is_resource($process)) {
                return false;
            }

            fwrite($pipes[0], $testPayload);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            fclose($pipes[2]);

            $exitCode = proc_close($process);

            if ($exitCode !== 0) {
                return false;
            }

            $result = json_decode($output, true);

            return is_array($result) && isset($result['probability']) && isset($result['recommendation']);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Prediksi untuk banyak data sekaligus
     */
    public function getBatchPredictions(array $dataArray)
    {
        $results = [];

        foreach ($dataArray as $item) {
            $results[] = $this->getPrediction($item);
        }

        return $results;
    }
}