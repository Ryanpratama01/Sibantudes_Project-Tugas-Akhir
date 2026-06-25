<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_penerima', function (Blueprint $table) {
            $table->id();
            $table->string('periode');             // contoh: "2026-04"
            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->string('no_kk', 16)->nullable();
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->string('nomor_rt')->nullable();
            $table->string('nama_dusun')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->decimal('penghasilan', 15, 2)->default(0);
            $table->integer('jumlah_tanggungan')->default(0);
            $table->string('aset_kepemilikan')->nullable();
            $table->string('kondisi_rumah')->nullable();
            $table->string('meteran_listrik')->nullable();
            $table->string('sumber_air')->nullable();
            $table->string('bantuan_lain')->default('tidak');
            $table->integer('usia')->default(0);
            $table->decimal('probability', 8, 4)->default(0);
            $table->string('status_verifikasi')->default('disetujui');
            $table->date('tanggal_penetapan')->nullable();
            $table->decimal('jumlah_bantuan', 15, 2)->default(0);
            $table->timestamp('di_arsipkan_pada')->nullable(); // kapan di-reset/arsip
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_penerima');
    }
};