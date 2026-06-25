<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // 1. Migrasi data existing (string → FK) sebelum drop kolom lama
        // ---------------------------------------------------------------
        // Map nama program (string lama) → id baru
        $programMap = DB::table('program_pelatihans')->pluck('id', 'nama_program')->toArray();
        // Map slug jenis_kelas → id baru
        $kelasMap   = DB::table('jenis_kelas')->pluck('id', 'slug')->toArray();

        // Update users yang sudah punya program_pelatihan & jenis_kelas string
        if (!empty($programMap) && !empty($kelasMap)) {
            $users = DB::table('users')
                ->whereNotNull('program_pelatihan')
                ->get(['id', 'program_pelatihan', 'jenis_kelas']);

            foreach ($users as $user) {
                $programId = $programMap[$user->program_pelatihan] ?? null;
                $kelasId   = $kelasMap[$user->jenis_kelas]         ?? null;

                DB::table('users')->where('id', $user->id)->update([
                    'program_pelatihan_id' => $programId,
                    'jenis_kelas_id'       => $kelasId,
                ]);
            }
        }

        // ---------------------------------------------------------------
        // 2. Drop kolom lama & rename (jika FK cols belum ada)
        // ---------------------------------------------------------------
        Schema::table('users', function (Blueprint $table) {
            // Drop kolom string lama
            $table->dropColumn(['program_pelatihan', 'jenis_kelas']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('program_pelatihan', 100)->nullable()->after('status');
            $table->enum('jenis_kelas', ['reguler', 'privat'])->nullable()->after('program_pelatihan');
        });

        // Kembalikan data dari FK ke string
        $programs = DB::table('program_pelatihans')->pluck('nama_program', 'id')->toArray();
        $kelas    = DB::table('jenis_kelas')->pluck('slug', 'id')->toArray();

        $users = DB::table('users')
            ->whereNotNull('program_pelatihan_id')
            ->get(['id', 'program_pelatihan_id', 'jenis_kelas_id']);

        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'program_pelatihan' => $programs[$user->program_pelatihan_id] ?? null,
                'jenis_kelas'       => $kelas[$user->jenis_kelas_id] ?? null,
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['program_pelatihan_id']);
            $table->dropForeign(['jenis_kelas_id']);
            $table->dropColumn(['program_pelatihan_id', 'jenis_kelas_id']);
        });
    }
};
