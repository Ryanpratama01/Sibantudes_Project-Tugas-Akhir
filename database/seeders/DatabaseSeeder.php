<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;  // ← TAMBAHKAN INI!
use App\Models\Dusun;
use App\Models\RT;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
   {
        // Buat Dusun
        $mojorejo = Dusun::create(['nama_dusun' => 'Mojorejo']);
        $karangploso = Dusun::create(['nama_dusun' => 'KarangPloso']);
        $payaman = Dusun::create(['nama_dusun' => 'Payaman']);

        // Buat RT untuk Mojoreja (5 RT)
        for ($i = 1; $i <= 5; $i++) {
            RT::create([
                'dusun_id' => $mojorejo->id,
                'nomor_rt' => str_pad($i, 3, '0', STR_PAD_LEFT),
                'kode_rt' => 'MJR-RT' . str_pad($i, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // Buat RT untuk Dummy Utara (4 RT)
        for ($i = 1; $i <= 4; $i++) {
            RT::create([
                'dusun_id' => $karangploso->id,
                'nomor_rt' => str_pad($i, 3, '0', STR_PAD_LEFT),
                'kode_rt' => 'UTR-RT' . str_pad($i, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // Buat RT untuk Dummy Selatan (3 RT)
        for ($i = 1; $i <= 3; $i++) {
            RT::create([
                'dusun_id' => $payaman->id,
                'nomor_rt' => str_pad($i, 3, '0', STR_PAD_LEFT),
                'kode_rt' => 'SEL-RT' . str_pad($i, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // Buat Admin
        User::create([
            'name' => 'Admin Kelurahan',
            'email' => 'admin@desangerong.go.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'rt_id' => null,
        ]);
    }
}
