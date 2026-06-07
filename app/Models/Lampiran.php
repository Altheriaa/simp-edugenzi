<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lampiran extends Model
{
    protected $table = 'lampiran';

    protected $fillable = [
        'task_id',
        'nama_file',
        'path_file',
        'tipe_file',
        'ukuran_file',
        'uploaded_by',
    ];

    // --- Relasi ---

    /** Tugas yang memiliki lampiran ini */
    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'task_id');
    }

    /** User yang mengupload file ini */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
