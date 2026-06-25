<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_kelas_durasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_pelatihan_id')
                  ->constrained('program_pelatihans')
                  ->cascadeOnDelete();
            $table->foreignId('jenis_kelas_id')
                  ->constrained('jenis_kelas')
                  ->cascadeOnDelete();
            $table->string('durasi_pelatihan', 50); // e.g. '1 Bulan', '3 Bulan', '6 Bulan', '12 X Pertemuan'
            $table->unique(['program_pelatihan_id', 'jenis_kelas_id', 'durasi_pelatihan'], 'unique_program_kelas_durasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kelas_durasi');
    }
};
