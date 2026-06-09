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
            Schema::table('lampiran', function (Blueprint $table) {
                $table->dropForeign(['uploaded_by']);
            });
        } catch (\Exception $e) {
            // Ignore if already dropped
        }

        Schema::table('lampiran', function (Blueprint $table) {
            $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lampiran', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
            $table->foreign('uploaded_by')->references('id')->on('users');
        });
    }
};
