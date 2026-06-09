<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertifikat extends Model
{
    protected $table = 'sertifikat';

    protected $fillable = [
        'nomor_sertifikat',
        'peserta_id',
        'mentor_id',
        'nama_program',
        'tgl_terbit',
        'predikat',
    ];

    protected $casts = [
        'tgl_terbit' => 'date',
    ];

    // --- Relasi ---

    /** Peserta didik penerima sertifikat */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }

    /** Mentor yang menerbitkan sertifikat */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
}
