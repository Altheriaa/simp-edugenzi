<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramKelasDurasi extends Model
{
    protected $table = 'program_kelas_durasi';

    protected $fillable = [
        'program_pelatihan_id',
        'jenis_kelas_id',
        'durasi_pelatihan',
    ];

    public function programPelatihan(): BelongsTo
    {
        return $this->belongsTo(ProgramPelatihan::class, 'program_pelatihan_id');
    }

    public function jenisKelas(): BelongsTo
    {
        return $this->belongsTo(JenisKelas::class, 'jenis_kelas_id');
    }
}
