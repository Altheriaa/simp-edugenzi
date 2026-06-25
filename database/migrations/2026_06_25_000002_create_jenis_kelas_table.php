<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);       // e.g. 'Reguler', 'Privat'
            $table->string('slug', 20)->unique(); // e.g. 'reguler', 'privat'
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_kelas');
    }
};
