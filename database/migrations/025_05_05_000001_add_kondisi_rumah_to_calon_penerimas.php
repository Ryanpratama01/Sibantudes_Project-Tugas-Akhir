<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_penerimas', function (Blueprint $table) {
            $table->string('lantai_rumah')->nullable()->after('kondisi_rumah');
            $table->string('dinding_rumah')->nullable()->after('lantai_rumah');
            $table->string('atap_rumah')->nullable()->after('dinding_rumah');
            $table->integer('luas_rumah_m2')->nullable()->after('atap_rumah');
            $table->string('status_kepemilikan_rumah')->nullable()->after('luas_rumah_m2');
        });
    }

    public function down(): void
    {
        Schema::table('calon_penerimas', function (Blueprint $table) {
            $table->dropColumn([
                'lantai_rumah',
                'dinding_rumah',
                'atap_rumah',
                'luas_rumah_m2',
                'status_kepemilikan_rumah',
            ]);
        });
    }
};