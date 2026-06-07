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
        Schema::create('proyek', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proyek', 100);
            $table->text('deskripsi')->nullable();
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // mentor
            $table->enum('status_proyek', ['berjalan', 'selesai', 'tertunda'])->default('berjalan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek');
    }
};
