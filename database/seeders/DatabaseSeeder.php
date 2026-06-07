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
        // Admin
        User::create([
            'nama_lengkap' => 'Admin Edugenzi',
            'username'     => 'admin',
            'email'        => 'admin@edugenzi.id',
            'password'     => Hash::make('password'),
            'role'         => 'admin',
            'status'       => 'aktif',
        ]);

        // Mentor
        User::create([
            'nama_lengkap' => 'Mentor Satu',
            'username'     => 'mentor1',
            'email'        => 'mentor1@edugenzi.id',
            'password'     => Hash::make('password'),
            'role'         => 'mentor',
            'status'       => 'aktif',
        ]);

        // Peserta Didik
        User::create([
            'nama_lengkap' => 'Peserta Satu',
            'username'     => 'peserta1',
            'email'        => 'peserta1@edugenzi.id',
            'password'     => Hash::make('password'),
            'role'         => 'peserta_didik',
            'status'       => 'aktif',
        ]);
    }
}
