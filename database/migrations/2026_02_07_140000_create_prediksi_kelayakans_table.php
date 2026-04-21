<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediksi_kelayakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_penerima_id')->constrained('calon_penerimas')->onDelete('cascade');
            $table->decimal('probability', 5, 2);
            $table->string('recommendation');
            $table->string('kondisi_rumah')->default('Tidak Diketahui');
            $table->string('meteran_listrik')->default('Tidak Diketahui');
            $table->string('sumber_air')->default('Tidak Diketahui');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediksi_kelayakans');
    }
};