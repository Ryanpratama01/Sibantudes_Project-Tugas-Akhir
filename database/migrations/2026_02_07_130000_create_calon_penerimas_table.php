<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calon_penerimas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade');

            $table->string('no_kk', 16);
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('desa');
            $table->string('pekerjaan');
            $table->decimal('penghasilan', 15, 2);
            $table->integer('jumlah_tanggungan');
            $table->string('aset_kepemilikan');
            $table->string('kondisi_rumah')->default('Tidak Diketahui');
            $table->string('meteran_listrik')->default('Tidak Diketahui');
            $table->string('sumber_air')->default('Tidak Diketahui');
            $table->enum('bantuan_lain', ['ya', 'tidak']);
            $table->integer('usia');
            $table->string('status_perkawinan');
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_admin')->nullable();

            $table->string('foto_rumah_depan')->nullable();
            $table->string('foto_rumah_belakang')->nullable();
            $table->string('foto_rumah_kanan')->nullable();
            $table->string('foto_rumah_kiri')->nullable();
            $table->string('foto_kk')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_rekening_listrik')->nullable();
            $table->string('foto_meteran_air')->nullable();
            $table->string('dokumen_pendukung')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calon_penerimas');
    }
};