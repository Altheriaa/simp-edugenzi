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
        Schema::table('program_kelas_durasi', function (Blueprint $table) {
            $table->integer('durasi_bulan')->default(1)->after('durasi_pelatihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_kelas_durasi', function (Blueprint $table) {
            $table->dropColumn('durasi_bulan');
        });
    }
};
