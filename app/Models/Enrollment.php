<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    protected $table = 'enrollments';

    protected $fillable = [
        'user_id',
        'program_pelatihan_id',
        'jenis_kelas_id',
        'durasi_pelatihan',
        'status',
        'tgl_daftar',
    ];

    protected $casts = [
        'tgl_daftar' => 'date',
    ];

    // --- Relasi ---

    /** Peserta yang terdaftar */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Program pelatihan yang diikuti */
    public function programPelatihan(): BelongsTo
    {
        return $this->belongsTo(ProgramPelatihan::class, 'program_pelatihan_id');
    }

    /** Jenis kelas yang diikuti */
    public function jenisKelas(): BelongsTo
    {
        return $this->belongsTo(JenisKelas::class, 'jenis_kelas_id');
    }

    /** Semua penilaian dalam enrollment ini */
    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'enrollment_id');
    }

    /** Sertifikat untuk enrollment ini */
    public function sertifikat(): HasMany
    {
        return $this->hasMany(Sertifikat::class, 'enrollment_id');
    }

    // --- Scopes ---

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    // --- Helpers ---

    /**
     * Cek apakah penilaian enrollment ini sudah lengkap
     * sesuai durasi pelatihan yang didaftarkan.
     */
    public function isPenilaianLengkap(): bool
    {
        $durasi = $this->getDurasiBulan();
        if ($durasi <= 0) return false;

        $penilaians = $this->penilaian()->get();
        if ($penilaians->count() < $durasi) return false;

        $validCount = 0;
        foreach ($penilaians as $p) {
            if (
                $p->m1_kls > 0 && $p->m1_pr > 0 &&
                $p->m2_kls > 0 && $p->m2_pr > 0 &&
                $p->m3_kls > 0 && $p->m3_pr > 0 &&
                $p->m4_kls > 0 && $p->m4_pr > 0
            ) {
                $validCount++;
            }
        }

        return $validCount >= $durasi;
    }

    /**
     * Ambil durasi dalam bulan dari master ProgramKelasDurasi.
     */
    public function getDurasiBulan(): int
    {
        if (!$this->program_pelatihan_id || !$this->jenis_kelas_id || !$this->durasi_pelatihan) {
            return 0;
        }

        $kombinasi = ProgramKelasDurasi::where([
            'program_pelatihan_id' => $this->program_pelatihan_id,
            'jenis_kelas_id'       => $this->jenis_kelas_id,
            'durasi_pelatihan'     => $this->durasi_pelatihan,
        ])->first();

        return $kombinasi ? $kombinasi->durasi_bulan : 0;
    }
}
