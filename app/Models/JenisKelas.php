<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisKelas extends Model
{
    protected $table = 'jenis_kelas';

    protected $fillable = [
        'nama',
        'slug',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    /** Kombinasi durasi yang dimiliki jenis kelas ini */
    public function programKelasDurasi(): HasMany
    {
        return $this->hasMany(ProgramKelasDurasi::class, 'jenis_kelas_id');
    }

    /** Users yang terdaftar pada jenis kelas ini */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'jenis_kelas_id');
    }

    /** Scope: hanya jenis kelas aktif */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
