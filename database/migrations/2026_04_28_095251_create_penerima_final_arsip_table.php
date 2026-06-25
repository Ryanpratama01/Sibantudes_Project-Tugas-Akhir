<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerima_final_arsip', function (Blueprint $table) {
            $table->id();
            $table->string('periode_arsip');
            $table->unsignedBigInteger('penerima_final_id')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('nik')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('nomor_rt')->nullable();
            $table->string('nama_dusun')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->bigInteger('penghasilan')->default(0);
            $table->integer('jumlah_tanggungan')->default(0);
            $table->string('aset_kepemilikan')->nullable();
            $table->string('bantuan_lain')->nullable();
            $table->integer('usia')->nullable();
            $table->float('probability')->nullable();
            $table->string('status_verifikasi')->nullable();
            $table->date('tanggal_penetapan')->nullable();
            $table->string('periode_bantuan')->nullable();
            $table->bigInteger('jumlah_bantuan')->default(300000);
            $table->timestamp('diarsipkan_pada')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerima_final_arsip');
    }
};