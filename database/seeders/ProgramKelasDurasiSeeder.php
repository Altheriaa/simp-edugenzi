<?php

namespace Database\Seeders;

use App\Models\JenisKelas;
use App\Models\ProgramKelasDurasi;
use App\Models\ProgramPelatihan;
use Illuminate\Database\Seeder;

class ProgramKelasDurasiSeeder extends Seeder
{
    public function run(): void
    {
        // Sesuai CLAUDE.md section 18.1
        $mapping = [
            'Desain Grafis & 3D Level 1' => [
                'reguler' => ['3 Bulan', '6 Bulan'],
                'privat'  => ['6 Bulan'],
            ],
            'Desain Grafis & 3D Level 2' => [
                'reguler' => ['6 Bulan'],
            ],
            'Coding & Ai Level 1' => [
                'reguler' => ['3 Bulan', '6 Bulan'],
                'privat'  => ['12 X Pertemuan'],
            ],
            'Coding & Ai Level 2' => [
                'reguler' => ['6 Bulan'],
                'privat'  => ['12 X Pertemuan'],
            ],
            'Robotika Pondasi Energi & Gerak' => [
                'reguler' => ['1 Bulan'],
            ],
            'Public Speaking Berani Cerita & Perkenalan Diri' => [
                'reguler' => ['3 Bulan'],
            ],
            'FOS Dewasa' => [
                'privat' => ['12 X Pertemuan'],
            ],
            'Desain Grafis Dewasa' => [
                'privat' => ['12 X Pertemuan'],
            ],
        ];

        foreach ($mapping as $namaProgram => $kelasMap) {
            $program = ProgramPelatihan::where('nama_program', $namaProgram)->first();
            if (!$program) continue;

            foreach ($kelasMap as $kelasSlug => $durasiList) {
                $kelas = JenisKelas::where('slug', $kelasSlug)->first();
                if (!$kelas) continue;

                foreach ($durasiList as $durasi) {
                    $durasiBulan = 1;
                    if (str_contains($durasi, 'Bulan')) {
                        $durasiBulan = (int) filter_var($durasi, FILTER_SANITIZE_NUMBER_INT);
                    }

                    ProgramKelasDurasi::firstOrCreate([
                        'program_pelatihan_id' => $program->id,
                        'jenis_kelas_id'       => $kelas->id,
                        'durasi_pelatihan'     => $durasi,
                    ], [
                        'durasi_bulan'         => $durasiBulan,
                    ]);
                }
            }
        }
    }
}
