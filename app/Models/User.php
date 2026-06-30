<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    // --- Relasi ---

    /** Proyek yang dipegang oleh mentor ini */
    public function proyek(): HasMany
    {
        return $this->hasMany(Proyek::class, 'user_id');
    }

    /** Semua enrollment (pendaftaran program) peserta ini */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    /** Enrollment yang masih aktif */
    public function enrollmentsAktif(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id')->where('status', 'aktif');
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

    /**
     * Cek apakah nilai peserta pada enrollment tertentu sudah lengkap.
     * Jika enrollment tidak disediakan, cek salah satu enrollment aktif yang sudah lengkap.
     */
    public function isPenilaianLengkap(?Enrollment $enrollment = null): bool
    {
        if ($enrollment) {
            return $enrollment->isPenilaianLengkap();
        }

        // Fallback: cek apakah ada enrollment aktif yang sudah lengkap
        return $this->enrollmentsAktif()->get()->some(fn($e) => $e->isPenilaianLengkap());
    }
}

