<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penerima_finals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('calon_penerima_id')
                ->constrained('calon_penerimas')
                ->onDelete('cascade');

            $table->date('tanggal_penetapan');

            $table->string('periode_bantuan'); // contoh: 2026 Triwulan 1

            $table->decimal('jumlah_bantuan', 15, 2);

            $table->enum('status_pencairan', [
                'belum_cair',
                'sudah_cair'
            ])->default('belum_cair');

            $table->date('tanggal_pencairan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima_finals');
    }
};