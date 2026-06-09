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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->string('nik', 16)->unique()->nullable()->after('id');
            $table->string('no_hp')->nullable()->after('email');
            $table->string('alamat')->nullable()->after('no_hp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('nama_lengkap');
            $table->dropColumn('nik');
            $table->dropColumn('no_hp');
            $table->dropColumn('alamat');
        });
    }
};
