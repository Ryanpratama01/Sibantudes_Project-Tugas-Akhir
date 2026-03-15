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
    Schema::create('laporan_publiks', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->string('file_path');
        $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_publiks');
    }
};
