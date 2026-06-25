<?php

namespace Database\Seeders;

use App\Models\JenisKelas;
use App\Models\ProgramKelasDurasi;
use App\Models\ProgramPelatihan;
use Illuminate\Database\Seeder;

class ProgramPelatihanSeeder extends Seeder
{
    public function run(): void
    {
        // Sesuai CLAUDE.md section 18.1
        $programs = [
            'Desain Grafis & 3D Level 1',
            'Desain Grafis & 3D Level 2',
            'Coding & Ai Level 1',
            'Coding & Ai Level 2',
            'Robotika Pondasi Energi & Gerak',
            'Public Speaking Berani Cerita & Perkenalan Diri',
            'FOS Dewasa',
            'Desain Grafis Dewasa',
        ];

        foreach ($programs as $nama) {
            ProgramPelatihan::firstOrCreate(
                ['nama_program' => $nama],
                ['is_aktif'     => true]
            );
        }
    }
}
