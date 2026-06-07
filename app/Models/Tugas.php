<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $fillable = [
        'proyek_id',
        'user_id',
        'judul_task',
        'deskripsi_task',
        'prioritas',
        'deadline',
        'status_task',
        'tgl_update',
    ];

    protected $casts = [
        'deadline'   => 'date',
        'tgl_update' => 'datetime',
    ];

    // --- Relasi ---

    /** Proyek yang memiliki tugas ini */
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    /** Peserta didik yang ditugaskan */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Sub-tugas / checklist */
    public function subTugas(): HasMany
    {
        return $this->hasMany(SubTugas::class, 'task_id');
    }

    /** Lampiran file */
    public function lampiran(): HasMany
    {
        return $this->hasMany(Lampiran::class, 'task_id');
    }
}
