<?php

namespace Database\Seeders;

use App\Models\JenisKelas;
use Illuminate\Database\Seeder;

class JenisKelasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Reguler', 'slug' => 'reguler', 'is_aktif' => true],
            ['nama' => 'Privat',  'slug' => 'privat',  'is_aktif' => true],
        ];

        foreach ($data as $item) {
            JenisKelas::firstOrCreate(
                ['slug'  => $item['slug']],
                ['nama'  => $item['nama'], 'is_aktif' => $item['is_aktif']]
            );
        }
    }
}
