<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Master Tables (harus di-seed lebih dulu) ---
        $this->call([
            JenisKelasSeeder::class,
            ProgramPelatihanSeeder::class,
            ProgramKelasDurasiSeeder::class,
        ]);

        // --- Users Default ---

        // Admin
        User::firstOrCreate(
            ['email' => 'admin@edugenzi.id'],
            [
                'nama_lengkap' => 'Admin Edugenzi',
                'email'        => 'admin@edugenzi.id',
                'password'     => Hash::make('password'),
                'role'         => 'admin',
                'status'       => 'aktif',
            ]
        );

        // Mentor
        User::firstOrCreate(
            ['email' => 'mentor1@edugenzi.id'],
            [
                'nama_lengkap' => 'Mentor Satu',
                'email'        => 'mentor1@edugenzi.id',
                'password'     => Hash::make('password'),
                'role'         => 'mentor',
                'status'       => 'aktif',
            ]
        );
    }
}
