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
        Schema::create('rts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dusun_id')->constrained('dusuns')->onDelete('cascade');
            $table->string('nomor_rt', 3); // 001, 002, 003, dst
            $table->string('kode_rt')->unique(); // MJR-RT01, KRJ-RT02, dst
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rts');
    }
};
