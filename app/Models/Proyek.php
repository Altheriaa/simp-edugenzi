<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    protected $table = 'proyek';

    protected $fillable = [
        'nama_proyek',
        'deskripsi',
        'tgl_mulai',
        'tgl_selesai',
        'user_id',
        'status_proyek',
        'program_pelatihan_id',
        'jenis_kelas_id',
        'durasi_pelatihan',
    ];

    protected $casts = [
        'tgl_mulai'   => 'date',
        'tgl_selesai' => 'date',
    ];

    // --- Relasi ---

    /** Mentor yang memegang proyek ini */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Program pelatihan yang terkait dengan proyek ini */
    public function programPelatihan(): BelongsTo
    {
        return $this->belongsTo(ProgramPelatihan::class, 'program_pelatihan_id');
    }

    /** Jenis kelas yang terkait dengan proyek ini */
    public function jenisKelas(): BelongsTo
    {
        return $this->belongsTo(JenisKelas::class, 'jenis_kelas_id');
    }

    /** Tugas-tugas dalam proyek ini */
    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'proyek_id');
    }

    /** Evaluasi dalam proyek ini */
    public function evaluasi(): HasMany
    {
        return $this->hasMany(Evaluasi::class, 'proyek_id');
    }
}
