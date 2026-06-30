<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah enrollment_id ke penilaian
        Schema::table('penilaian', function (Blueprint $table) {
            $table->foreignId('enrollment_id')
                  ->nullable()
                  ->after('mentor_id')
                  ->constrained('enrollments')
                  ->nullOnDelete();
        });

        // Tambah enrollment_id ke sertifikat
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->foreignId('enrollment_id')
                  ->nullable()
                  ->after('mentor_id')
                  ->constrained('enrollments')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropColumn('enrollment_id');
        });

        Schema::table('sertifikat', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropColumn('enrollment_id');
        });
    }
};
