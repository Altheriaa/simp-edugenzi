<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramPelatihan extends Model
{
    protected $table = 'program_pelatihans';

    protected $fillable = [
        'nama_program',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    /** Semua kombinasi kelas & durasi yang valid untuk program ini */
    public function kelasDurasi(): HasMany
    {
        return $this->hasMany(ProgramKelasDurasi::class, 'program_pelatihan_id');
    }

    /** Users yang terdaftar pada program ini */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'program_pelatihan_id');
    }

    public function proyek(): HasMany
    {
        return $this->hasMany(Proyek::class, 'program_pelatihan_id');
    }

    /** Scope: hanya program yang aktif */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
