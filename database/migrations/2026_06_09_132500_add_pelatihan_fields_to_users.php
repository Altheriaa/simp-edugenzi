<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('program_pelatihan', 100)->nullable()->after('status')
                ->comment('e.g. Desain Grafis & 3D Level 1');
            $table->enum('jenis_kelas', ['reguler', 'privat'])->nullable()->after('program_pelatihan');
            $table->string('durasi_pelatihan', 50)->nullable()->after('jenis_kelas')
                ->comment('e.g. 1 Bulan, 3 Bulan, 6 Bulan, 12 X Pertemuan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['program_pelatihan', 'jenis_kelas', 'durasi_pelatihan']);
        });
    }
};
