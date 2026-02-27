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
        Schema::create('prediksi_kelayakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_penerima_id')->constrained('calon_penerimas')->onDelete('cascade');
            $table->decimal('probability', 5, 2); // Probabilitas kelayakan (0-100)
            $table->string('recommendation'); // Sangat Layak, Layak, Kurang Layak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediksi_kelayakans');
    }
};
