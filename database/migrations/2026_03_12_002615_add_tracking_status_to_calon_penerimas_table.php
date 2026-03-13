<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_penerimas', function (Blueprint $table) {

            $table->enum('tracking_status', [
                'draft',
                'terkirim',
                'sedang_validasi',
                'selesai'
            ])->default('draft')->after('status_verifikasi');

        });
    }

    public function down(): void
    {
        Schema::table('calon_penerimas', function (Blueprint $table) {

            $table->dropColumn('tracking_status');

        });
    }
};