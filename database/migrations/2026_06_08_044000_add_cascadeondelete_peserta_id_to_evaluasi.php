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
        try {
            Schema::table('evaluasi', function (Blueprint $table) {
                $table->dropForeign(['peserta_id']);
            });
        } catch (\Exception $e) {
            // Ignore if already dropped
        }

        try {
            Schema::table('evaluasi', function (Blueprint $table) {
                $table->dropForeign(['mentor_id']);
            });
        } catch (\Exception $e) {
            // Ignore if already dropped
        }

        Schema::table('evaluasi', function (Blueprint $table) {
            // Re-create foreign keys with cascade on delete
            $table->foreign('peserta_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('mentor_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('evaluasi', function (Blueprint $table) {
            // Drop cascade foreign keys
            $table->dropForeign(['peserta_id']);
            $table->dropForeign(['mentor_id']);

            // Re-create original foreign keys
            $table->foreign('peserta_id')->references('id')->on('users');
            $table->foreign('mentor_id')->references('id')->on('users');
        });
    }
};
