<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'peserta_id',
        'mentor_id',
        'bulan',
        'tahun',
        'm1_kls',
        'm1_pr',
        'm2_kls',
        'm2_pr',
        'm3_kls',
        'm3_pr',
        'm4_kls',
        'm4_pr',
        'catatan',
    ];

    protected $casts = [
        'tahun'  => 'integer',
        'm1_kls' => 'integer',
        'm1_pr'  => 'integer',
        'm2_kls' => 'integer',
        'm2_pr'  => 'integer',
        'm3_kls' => 'integer',
        'm3_pr'  => 'integer',
        'm4_kls' => 'integer',
        'm4_pr'  => 'integer',
    ];

    // --- Relasi ---

    /** Peserta didik yang dinilai */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }

    /** Mentor yang memberikan penilaian */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    // --- Accessor ---

    /** Rata-rata bintang keseluruhan */
    public function getRataRataAttribute(): float
    {
        $values = array_filter([
            $this->m1_kls, $this->m1_pr,
            $this->m2_kls, $this->m2_pr,
            $this->m3_kls, $this->m3_pr,
            $this->m4_kls, $this->m4_pr,
        ], fn($v) => $v > 0);

        return count($values) > 0 ? round(array_sum($values) / count($values), 1) : 0;
    }
}
