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
        Schema::table('proyek', function (Blueprint $table) {
            $table->foreignId('program_pelatihan_id')->nullable()->constrained('program_pelatihans')->nullOnDelete();
            $table->foreignId('jenis_kelas_id')->nullable()->constrained('jenis_kelas')->nullOnDelete();
            $table->string('durasi_pelatihan', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            $table->dropForeign(['program_pelatihan_id']);
            $table->dropForeign(['jenis_kelas_id']);
            $table->dropColumn(['program_pelatihan_id', 'jenis_kelas_id', 'durasi_pelatihan']);
        });
    }
};
