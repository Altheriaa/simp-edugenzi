<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('program_pelatihan_id')
                  ->nullable()
                  ->after('status')
                  ->constrained('program_pelatihans')
                  ->nullOnDelete();

            $table->foreignId('jenis_kelas_id')
                  ->nullable()
                  ->after('program_pelatihan_id')
                  ->constrained('jenis_kelas')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['program_pelatihan_id']);
            $table->dropForeign(['jenis_kelas_id']);
            $table->dropColumn(['program_pelatihan_id', 'jenis_kelas_id']);
        });
    }
};
