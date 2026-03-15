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

            // Snapshot data warga
            $table->string('nama_lengkap');
            $table->string('nik', 20);
            $table->string('no_kk', 20);

            // Snapshot wilayah
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->string('nomor_rt')->nullable();
            $table->string('nama_dusun')->nullable();

            // Snapshot data penilaian
            $table->string('pekerjaan')->nullable();
            $table->bigInteger('penghasilan')->default(0);
            $table->integer('jumlah_tanggungan')->default(0);
            $table->string('aset_kepemilikan')->nullable();
            $table->string('bantuan_lain')->nullable();
            $table->integer('usia')->default(0);

            // Snapshot hasil prediksi/final
            $table->decimal('probability', 8, 4)->default(0);
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');

            // Data arsip bantuan
            $table->date('tanggal_penetapan');
            $table->string('periode_bantuan');
            $table->decimal('jumlah_bantuan', 15, 2)->default(300000);

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