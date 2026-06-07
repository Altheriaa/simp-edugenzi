<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTugas extends Model
{
    protected $table = 'sub_tugas';

    protected $fillable = [
        'task_id',
        'judul_sub_task',
        'is_selesai',
    ];

    protected $casts = [
        'is_selesai' => 'boolean',
    ];

    // --- Relasi ---

    /** Tugas induk */
    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'task_id');
    }
}
