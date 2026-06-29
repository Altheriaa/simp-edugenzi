<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'nik',
        'no_registrasi',
        'email',
        'no_hp',
        'alamat',
        'password',
        'role',
        'status',
        'program_pelatihan_id',
        'jenis_kelas_id',
        'durasi_pelatihan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // --- Relasi Master Pelatihan ---

    /** Program pelatihan yang diikuti (khusus peserta_didik) */
    public function programPelatihan(): BelongsTo
    {
        return $this->belongsTo(ProgramPelatihan::class, 'program_pelatihan_id');
    }

    /** Jenis kelas yang diikuti (khusus peserta_didik) */
    public function jenisKelas(): BelongsTo
    {
        return $this->belongsTo(JenisKelas::class, 'jenis_kelas_id');
    }

    // --- Relasi ---

    /** Proyek yang dipegang oleh mentor ini */
    public function proyek(): HasMany
    {
        return $this->hasMany(Proyek::class, 'user_id');
    }

    /** Proyek yang diikuti oleh peserta ini */
    public function proyekDiikuti(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Proyek::class, 'proyek_user', 'user_id', 'proyek_id')->withTimestamps();
    }

    /** Tugas yang diberikan ke peserta ini */
    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'user_id');
    }

    /** Evaluasi yang ditulis oleh mentor ini */
    public function evaluasiSebagaiMentor(): HasMany
    {
        return $this->hasMany(Evaluasi::class, 'mentor_id');
    }

    /** Evaluasi yang ditujukan ke peserta ini */
    public function evaluasiSebagaiPeserta(): HasMany
    {
        return $this->hasMany(Evaluasi::class, 'peserta_id');
    }

    /** Penilaian yang diberikan oleh mentor ini */
    public function penilaianSebagaiMentor(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'mentor_id');
    }

    /** Penilaian yang diterima oleh peserta ini */
    public function penilaianSebagaiPeserta(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'peserta_id');
    }

    /** Sertifikat yang diterbitkan oleh mentor ini */
    public function sertifikatSebagaiMentor(): HasMany
    {
        return $this->hasMany(Sertifikat::class, 'mentor_id');
    }

    /** Sertifikat yang diterima oleh peserta ini */
    public function sertifikatSebagaiPeserta(): HasMany
    {
        return $this->hasMany(Sertifikat::class, 'peserta_id');
    }

    /** Cek apakah nilai peserta sudah diisi penuh sesuai durasi pelatihan */
    public function isPenilaianLengkap(): bool
    {
        if (!$this->durasi_pelatihan) {
            return false;
        }

        $durasi = intval($this->durasi_pelatihan);
        if ($durasi <= 0) {
            return false;
        }

        $penilaians = $this->penilaianSebagaiPeserta()->get();
        if ($penilaians->count() < $durasi) {
            return false;
        }

        $validCount = 0;
        foreach ($penilaians as $p) {
            // Semua 8 field nilai dalam bulan tersebut harus > 0
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
}

