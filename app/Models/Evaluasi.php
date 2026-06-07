<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluasi extends Model
{
    protected $table = 'evaluasi';

    protected $fillable = [
        'proyek_id',
        'mentor_id',
        'peserta_id',
        'catatan',
    ];

    // --- Relasi ---

    /** Proyek yang dievaluasi */
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    /** Mentor yang menulis evaluasi */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /** Peserta yang dievaluasi */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }
}
