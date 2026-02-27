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
        Schema::create('calon_penerimas', function (Blueprint $table) {
            $table->id(); // NO. URUT
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade');
            
            // Data Sesuai Kebutuhan
            $table->string('no_kk', 16); // NO. KK
            $table->string('nik', 16)->unique(); // NO. NIK KTP
            $table->string('nama_lengkap'); // NAMA LENGKAP
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']); // JENIS KELAMIN
            $table->string('tempat_lahir'); // TEMPAT LAHIR
            $table->date('tanggal_lahir'); // TANGGAL LAHIR
            $table->text('alamat'); // ALAMAT
            $table->string('desa'); // DESA
            $table->string('pekerjaan'); // PEKERJAAN
            $table->decimal('penghasilan', 15, 2); // PENGHASILAN
            $table->integer('jumlah_tanggungan'); // JUMLAH TANGGUNGAN
            $table->string('aset_kepemilikan'); // ASET KEPEMILIKAN
            $table->enum('bantuan_lain', ['ya', 'tidak']); // BANTUAN LAIN
            $table->integer('usia'); // USIA
            $table->string('status_perkawinan'); // STATUS PERKAWINAN
            
            // Untuk sistem (verifikasi admin)
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_admin')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calon_penerimas');
    }
};
