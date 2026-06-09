<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
            $table->string('bulan', 20);  // e.g. "Juni"
            $table->year('tahun');
            $table->unsignedTinyInteger('m1_kls')->default(0); // Minggu 1 - Kelas (2-5)
            $table->unsignedTinyInteger('m1_pr')->default(0);  // Minggu 1 - Proyek (2-5)
            $table->unsignedTinyInteger('m2_kls')->default(0); // Minggu 2 - Kelas
            $table->unsignedTinyInteger('m2_pr')->default(0);  // Minggu 2 - Proyek
            $table->unsignedTinyInteger('m3_kls')->default(0); // Minggu 3 - Kelas
            $table->unsignedTinyInteger('m3_pr')->default(0);  // Minggu 3 - Proyek
            $table->unsignedTinyInteger('m4_kls')->default(0); // Minggu 4 - Kelas
            $table->unsignedTinyInteger('m4_pr')->default(0);  // Minggu 4 - Proyek
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unique(['peserta_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
