<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('program_pelatihan_id')->constrained('program_pelatihans')->cascadeOnDelete();
            $table->foreignId('jenis_kelas_id')->nullable()->constrained('jenis_kelas')->nullOnDelete();
            $table->string('durasi_pelatihan', 50)->nullable(); // e.g. "3 Bulan"
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->date('tgl_daftar')->default(now());
            $table->timestamps();

            // Satu peserta hanya boleh aktif 1x per program (boleh re-enroll setelah selesai)
            $table->unique(['user_id', 'program_pelatihan_id', 'status'], 'unique_active_enrollment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
