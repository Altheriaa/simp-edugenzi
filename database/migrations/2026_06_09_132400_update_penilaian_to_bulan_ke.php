<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop foreign keys dulu, baru drop unique constraint & kolom
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropForeign(['peserta_id']);
            $table->dropForeign(['mentor_id']);
        });

        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropUnique(['peserta_id', 'bulan', 'tahun']);
            $table->dropColumn(['bulan', 'tahun']);
        });

        Schema::table('penilaian', function (Blueprint $table) {
            // Tambah kolom baru setelah mentor_id
            $table->unsignedTinyInteger('bulan_ke')->after('mentor_id')
                ->comment('Urutan bulan pelatihan: 1 s.d maks durasi (3 atau 6)');

            // Re-attach foreign keys
            $table->foreign('peserta_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('mentor_id')->references('id')->on('users')->cascadeOnDelete();

            // Unique constraint baru
            $table->unique(['peserta_id', 'bulan_ke']);
        });
    }

    public function down(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropForeign(['peserta_id']);
            $table->dropForeign(['mentor_id']);
        });

        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropUnique(['peserta_id', 'bulan_ke']);
            $table->dropColumn('bulan_ke');
        });

        Schema::table('penilaian', function (Blueprint $table) {
            $table->string('bulan', 20)->after('mentor_id');
            $table->year('tahun')->after('bulan');

            $table->foreign('peserta_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('mentor_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unique(['peserta_id', 'bulan', 'tahun']);
        });
    }
};
