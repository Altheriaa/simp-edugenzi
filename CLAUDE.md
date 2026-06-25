# CLAUDE.md — Antigravity Agent
## Sistem Manajemen Proyek Berbasis Web | Edugenzi Banda Aceh

---

## 1. Identitas Proyek

| Key | Value |
|-----|-------|
| Nama Sistem | Sistem Manajemen Proyek Edugenzi |
| Framework | Laravel 12 (latest) |
| Template UI | TailAdmin Laravel (Blade) |
| Database | MySQL |
| Frontend | Blade + Alpine.js (bawaan TailAdmin) |
| Auth | Laravel Breeze / built-in Auth |
| Role System | Role-Based Access Control (RBAC) manual |

---

## 2. Perintah Penting

```bash
# Jalankan dev server
php artisan serve

# Jalankan queue (jika ada job)
php artisan queue:work

# Kompilasi asset
npm run dev       # development
npm run build     # production

# Jalankan semua test
php artisan test

# Jalankan satu test file
php artisan test --filter=NamaTest

# Fresh migration + seed
php artisan migrate:fresh --seed

# Generate key
php artisan key:generate

# Clear semua cache
php artisan optimize:clear
```

---

## 3. Arsitektur & Struktur Folder

Gunakan struktur **default Laravel**, tidak ada perubahan dari konvensi resmi kecuali yang disebutkan di bawah.

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Controller khusus Admin
│   │   ├── Mentor/         # Controller khusus Mentor
│   │   ├── Peserta/        # Controller khusus Peserta Didik
│   │   └── Auth/           # Controller autentikasi
│   ├── Middleware/
│   │   └── CheckRole.php   # Middleware pengecekan role
│   └── Requests/           # Form Request validation
├── Models/                 # Semua Eloquent model
├── Policies/               # Authorization policy per model
└── Services/               # Business logic kompleks (opsional)

database/
├── migrations/             # Semua file migrasi
├── seeders/                # Seeder data awal
└── factories/              # Factory untuk testing

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php   # Layout utama TailAdmin
│   ├── auth/               # Halaman login, signup, dll
│   ├── admin/              # View khusus Admin
│   ├── mentor/             # View khusus Mentor
│   ├── peserta/            # View khusus Peserta Didik
│   └── components/         # Blade components reusable
└── js/ & css/              # Asset (dikelola Vite)

routes/
├── web.php                 # Semua route web (dikelompokkan per role)
└── auth.php                # Route autentikasi
```

---

## 4. Database & Model

### 4.1 Daftar Tabel

| Tabel | Model | Keterangan |
|-------|-------|------------|
| `users` | `User` | Admin, Mentor, Peserta Didik (Dilengkapi NIK, No Registrasi, No HP, Alamat, serta Program Pelatihan, Jenis Kelas, dan Durasi) |
| `proyek` | `Proyek` | Data proyek (dibuat & dipegang oleh Mentor) |
| `tugas` | `Tugas` | Task dalam proyek (diberikan kepada Peserta Didik) |
| `sub_tugas` | `SubTugas` | Checklist item per task |
| `lampiran` | `Lampiran` | File upload per task (oleh Peserta Didik) |
| `evaluasi` | `Evaluasi` | Catatan evaluasi proyek oleh Mentor kepada Peserta Didik |
| `penilaian` | `Penilaian` | Nilai bintang pelatihan bulanan peserta didik (Bulan Ke-1 s.d Bulan Ke-6, Minggu 1-4) |
| `sertifikat` | `Sertifikat` | Sertifikat kelulusan/penghargaan untuk peserta didik |

### 4.2 Skema Migrasi (Kondisi Riil & Target)

**users**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('nik', 16)->unique()->nullable();
    $table->string('no_registrasi', 50)->unique()->nullable(); // Format: EDU-{timestamp}{counter} (khusus peserta)
    $table->string('nama_lengkap', 100);
    $table->string('email', 100)->unique();
    $table->string('no_hp')->nullable();
    $table->string('alamat')->nullable();
    $table->string('password');
    $table->enum('role', ['admin', 'mentor', 'peserta_didik']);
    $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
    
    // Bidang Program & Durasi Pelatihan (khusus Peserta Didik)
    $table->string('program_pelatihan', 100)->nullable(); // e.g. 'Desain Grafis & 3D Level 1'
    $table->enum('jenis_kelas', ['reguler', 'privat'])->nullable();
    $table->string('durasi_pelatihan', 50)->nullable(); // e.g. '1 Bulan', '3 Bulan', '6 Bulan', '12 X Pertemuan'
    
    $table->rememberToken();
    $table->timestamps();
});
```

**proyek**
```php
Schema::create('proyek', function (Blueprint $table) {
    $table->id();
    $table->string('nama_proyek', 100);
    $table->text('deskripsi')->nullable();
    $table->date('tgl_mulai');
    $table->date('tgl_selesai');
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Mentor
    $table->enum('status_proyek', ['berjalan', 'selesai', 'tertunda'])->default('berjalan');
    $table->timestamps();
});
```

**tugas**
```php
Schema::create('tugas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('proyek_id')->constrained('proyek')->cascadeOnDelete();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Peserta
    $table->string('judul_task', 150);
    $table->text('deskripsi_task')->nullable();
    $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
    $table->date('deadline')->nullable();
    $table->enum('status_task', ['to_do', 'in_progress', 'done'])->default('to_do');
    $table->timestamp('tgl_update')->nullable();
    $table->timestamps();
});
```

**sub_tugas**
```php
Schema::create('sub_tugas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('task_id')->constrained('tugas')->cascadeOnDelete();
    $table->string('judul_sub_task', 150);
    $table->tinyInteger('is_selesai')->default(0);
    $table->timestamps();
});
```

**lampiran**
```php
Schema::create('lampiran', function (Blueprint $table) {
    $table->id();
    $table->foreignId('task_id')->constrained('tugas')->cascadeOnDelete();
    $table->string('nama_file', 200);
    $table->string('path_file', 255);
    $table->string('tipe_file', 50);
    $table->integer('ukuran_file'); // dalam KB
    $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
    $table->timestamps();
});
```

**evaluasi**
```php
Schema::create('evaluasi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('proyek_id')->constrained('proyek')->cascadeOnDelete();
    $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('peserta_id')->constrained('users')->cascadeOnDelete();
    $table->text('catatan');
    $table->timestamps();
});
```

**penilaian**
```php
Schema::create('penilaian', function (Blueprint $table) {
    $table->id();
    $table->foreignId('peserta_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
    $table->unsignedTinyInteger('bulan_ke'); // Bulan ke-1 s.d Bulan ke-6 (mengikuti durasi pelatihan peserta)
    $table->unsignedTinyInteger('m1_kls')->default(0); // 2-5
    $table->unsignedTinyInteger('m1_pr')->default(0);  // 2-5
    $table->unsignedTinyInteger('m2_kls')->default(0); // 2-5
    $table->unsignedTinyInteger('m2_pr')->default(0);  // 2-5
    $table->unsignedTinyInteger('m3_kls')->default(0); // 2-5
    $table->unsignedTinyInteger('m3_pr')->default(0);  // 2-5
    $table->unsignedTinyInteger('m4_kls')->default(0); // 2-5
    $table->unsignedTinyInteger('m4_pr')->default(0);  // 2-5
    $table->text('catatan')->nullable();
    $table->timestamps();
    $table->unique(['peserta_id', 'bulan_ke']);
});
```

**sertifikat**
```php
Schema::create('sertifikat', function (Blueprint $table) {
    $table->id();
    $table->string('nomor_sertifikat', 100)->unique();
    $table->foreignId('peserta_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
    $table->string('nama_program', 150);
    $table->date('tgl_terbit');
    $table->string('predikat', 50);
    $table->timestamps();
});
```

### 4.3 Konvensi Model

- Nama model: **PascalCase singular** → `Proyek`, `SubTugas`, `Lampiran`
- Nama tabel: **snake_case plural** (Atau custom didefinisikan secara eksplisit via `$table`)
- Selalu definisikan `$fillable` — **jangan pakai `$guarded = []`**
- Selalu definisikan relasi Eloquent lengkap (hasMany, belongsTo, dll)

**Contoh Model Proyek:**
```php
class Proyek extends Model
{
    protected $table = 'proyek';

    protected $fillable = [
        'nama_proyek', 'deskripsi', 'tgl_mulai',
        'tgl_selesai', 'user_id', 'status_proyek',
    ];

    protected $casts = [
        'tgl_mulai'   => 'date',
        'tgl_selesai' => 'date',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'proyek_id');
    }

    public function evaluasi(): HasMany
    {
        return $this->hasMany(Evaluasi::class, 'proyek_id');
    }
}
```

---

## 5. Routing

Semua route dikelompokkan berdasarkan **role** menggunakan middleware `CheckRole`.

```php
// routes/web.php

// --- Public ---
Route::get('/', fn() => redirect()->route('login'));

// --- Auth (Guest) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/signup', [RegisterController::class, 'index'])->name('signup');
    Route::post('/signup', [RegisterController::class, 'store'])->name('signup.post');
});

// --- Auth (General) ---
Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// --- Admin ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pengguna', Admin\PenggunaController::class)->except(['show']);
    Route::get('penilaian', [Admin\PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('sertifikat', [Admin\SertifikatController::class, 'index'])->name('sertifikat.index');
});

// --- Mentor ---
Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/dashboard', [Mentor\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('proyek', Mentor\ProyekController::class);
    Route::resource('proyek.tugas', Mentor\TugasController::class)->parameters(['tugas' => 'tugas'])->shallow()->except(['index']);
    Route::post('tugas/{tugas}/sub-tugas', [Mentor\SubTugasController::class, 'store'])->name('sub-tugas.store');
    Route::patch('sub-tugas/{subTugas}', [Mentor\SubTugasController::class, 'update'])->name('sub-tugas.update');
    Route::delete('sub-tugas/{subTugas}', [Mentor\SubTugasController::class, 'destroy'])->name('sub-tugas.destroy');
    Route::resource('evaluasi', Mentor\EvaluasiController::class)->only(['index', 'store']);
    Route::resource('penilaian', Mentor\PenilaianController::class)->except(['show']);
    Route::get('penilaian/{peserta}/detail', [Mentor\PenilaianController::class, 'detail'])->name('penilaian.detail'); // Detail nilai per peserta
    Route::resource('sertifikat', Mentor\SertifikatController::class);
});

// --- Peserta Didik ---
Route::middleware(['auth', 'role:peserta_didik'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [Peserta\DashboardController::class, 'index'])->name('dashboard');
    Route::get('tugas', [Peserta\TugasController::class, 'index'])->name('tugas.index');
    Route::get('tugas/{tugas}', [Peserta\TugasController::class, 'show'])->name('tugas.show');
    Route::patch('tugas/{tugas}/status', [Peserta\TugasController::class, 'updateStatus'])->name('tugas.status');
    Route::patch('sub-tugas/{subTugas}/toggle', [Peserta\SubTugasController::class, 'toggle'])->name('sub-tugas.toggle');
    Route::post('tugas/{tugas}/lampiran', [Peserta\LampiranController::class, 'store'])->name('lampiran.store');
    Route::delete('lampiran/{lampiran}', [Peserta\LampiranController::class, 'destroy'])->name('lampiran.destroy');
    Route::get('penilaian', [Peserta\PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('sertifikat', [Peserta\SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('sertifikat/{sertifikat}/print', [Peserta\SertifikatController::class, 'print'])->name('sertifikat.print');
});
```

---

## 6. Middleware

### CheckRole Middleware

```php
// app/Http/Middleware/CheckRole.php

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
```

**Daftarkan di `bootstrap/app.php` (Laravel 12):**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

---

## 7. Controller

### Konvensi

- Satu controller hanya menangani **satu resource**
- Gunakan **Form Request** untuk semua validasi — jangan validasi di controller
- Gunakan `authorize()` dari Policy di setiap method yang butuh otorisasi (atau sesuaikan dengan permission logic)
- Redirect setelah operasi tulis (POST/PUT/DELETE) selalu dengan `->with('success', '...')` or `->with('error', '...')`
- Tidak ada logika bisnis kompleks di controller — pindahkan ke Service jika perlu

---

## 8. Form Request & Validasi

- Buat Form Request **terpisah** untuk store dan update jika aturannya berbeda
- Letakkan di `app/Http/Requests/`
- Selalu override `authorize()` — return `true` jika auth check ada di middleware, atau gunakan Policy

---

## 9. View & Blade

### Konvensi

- Semua view diletakkan di folder sesuai role: `resources/views/admin/`, `mentor/`, `peserta/`
- Setiap halaman **extend layout utama** TailAdmin: `@extends('layouts.app')`
- Gunakan `@section('title', '...')` untuk judul halaman
- Gunakan `@section('content')` untuk konten utama
- Komponen reusable (tabel, modal, alert) dibuat sebagai **Blade component** di `resources/views/components/`

---

## 10. Autentikasi & Redirect Setelah Login

Setelah login, redirect berdasarkan role:

```php
// app/Http/Controllers/Auth/AuthController.php

private function redirectBasedOnRole(): string
{
    return match(Auth::user()->role) {
        'admin'        => route('admin.dashboard'),
        'mentor'       => route('mentor.dashboard'),
        'peserta_didik'=> route('peserta.dashboard'),
        default        => route('login'),
    };
}
```

---

## 11. Upload File (Lampiran)

- Semua file disimpan di `storage/app/public/lampiran/`
- Jalankan `php artisan storage:link` sekali setelah setup
- Validasi tipe file: `pdf`, `jpg`, `jpeg`, `png`, `zip`, dll
- Batas ukuran: **10MB per file**
- Nama file disimpan dengan format: `{timestamp}_{nama_asli}`

---

## 12. Konvensi Penamaan (Naming Convention)

| Entitas | Konvensi | Contoh |
|---------|----------|--------|
| Model | PascalCase singular | `Proyek`, `SubTugas` |
| Controller | PascalCase + Controller | `ProyekController` |
| Form Request | Store/Update + Model + Request | `StoreUserRequest` |
| Migration | snake_case deskriptif | `create_proyek_table` |
| Route name | role.resource.action | `mentor.proyek.index` |
| View folder | snake_case, sesuai role | `mentor/proyek/index` |
| Blade component | kebab-case | `badge-status`, `modal-confirm` |
| Variable di view | camelCase | `$proyeks`, `$tugasList` |
| Method controller | camelCase sesuai REST | `index`, `store`, `update` |
| CSS class | Tailwind utility saja | Jangan buat custom CSS kecuali perlu |

---

## 13. Keamanan

- **Password** selalu di-hash dengan `bcrypt` — gunakan `Hash::make()` atau validator `hashed`
- **Mass assignment** — selalu gunakan `$fillable`, tidak ada `$guarded = []`
- **CSRF** — semua form wajib pakai `@csrf`
- **Authorization** — gunakan Policy atau pengecekan role yang ketat
- **Input validation** — selalu lewat Form Request, jangan percaya raw input

---

## 14. Policy

Buat Policy untuk setiap model yang butuh otorisasi kepemilikan.

---

## 15. Seeder

Seeder wajib menyediakan data awal untuk development dan testing.

---

## 16. Hal yang Tidak Boleh Dilakukan Agent

- **Jangan** mengubah nama tabel database yang sudah riil digunakan di migration
- **Jangan** menambah package baru tanpa konfirmasi
- **Jangan** membuat raw query SQL kecuali terpaksa
- **Jangan** menyimpan logika bisnis kompleks di Blade view
- **Jangan** menaruh validasi langsung di Controller — gunakan Form Request
- **Jangan** menggunakan `$guarded = []` pada model apapun
- **Jangan** menyimpan file upload di luar `storage/app/public/`
- **Jangan** membuat route tanpa nama (`->name()`)

---

## 17. Checklist Sebelum Setiap Fitur Selesai

- [ ] Migration sudah dibuat dan berjalan
- [ ] Model sudah punya `$fillable`, `$casts`, dan relasi lengkap
- [ ] Form Request sudah ada untuk store dan update
- [ ] Controller sudah menggunakan Form Request
- [ ] Route sudah diberi nama dan berada di grup middleware yang tepat
- [ ] View sudah mengextend layout TailAdmin
- [ ] Flash message sukses/gagal sudah muncul
- [ ] Tidak ada error di `php artisan test`

---

## 18. Skema Pembiayaan & Durasi Pelatihan (Per Juni 2026)

### 18.1 Daftar Program & Durasi Pelatihan

| Program | Jenis Kelas | Pilihan Durasi / Periode |
|---|---|---|
| **Desain Grafis & 3D Level 1** | Reguler <br> Privat | 3 Bulan, 6 Bulan <br> 6 Bulan |
| **Desain Grafis & 3D Level 2** | Reguler | 6 Bulan |
| **Coding & Ai Level 1** | Reguler <br> Privat | 3 Bulan, 6 Bulan <br> 12 X Pertemuan |
| **Coding & Ai Level 2** | Reguler <br> Privat | 6 Bulan <br> 12 X Pertemuan |
| **Robotika Pondasi Energi & Gerak** | Reguler | 1 Bulan |
| **Public Speaking Berani Cerita & Perkenalan Diri** | Reguler | 3 Bulan |
| **FOS Dewasa** | Privat | 12 X Pertemuan |
| **Desain Grafis Dewasa** | Privat | 12 X Pertemuan |

### 18.2 Rincian Biaya per Program

1. **Desain Grafis & 3D Level 1 - Reguler (3 Bulan)**
   - Per Sesi: Rp47.000
   - 1 Pertemuan (2 Sesi): Rp94.000
   - Per Bulan: Rp376.000
   - Total Pelatihan: Rp1.128.000
   - Biaya Project Akhir: Baju (Rp87.000) + Gantungan Kunci 3D (Rp20.000) = Total Rp107.000

2. **Desain Grafis & 3D Level 1 - Reguler (6 Bulan)**
   - Per Sesi: Rp45.000
   - 1 Pertemuan (2 Sesi): Rp90.000
   - Per Bulan: Rp360.000
   - Total Pelatihan: Rp2.160.000
   - Biaya Project Akhir: Baju (Rp87.000) + Gantungan Kunci 3D (Rp20.000) + Celengan (Rp37.000) = Total Rp144.000

3. **Desain Grafis & 3D Level 1 - Privat (6 Bulan)**
   - Per Sesi: Rp67.000
   - 1 Pertemuan (2 Sesi): Rp134.000
   - Cicilan: Cicilan I (Rp450.000), Cicilan II (Rp450.000), Cicilan III (Rp350.000), Cicilan IV (Rp300.000), Cicilan V (Rp58.000)
   - Total Pelatihan: Rp1.608.000
   - Biaya Project Akhir: Baju (Rp87.000) + Gantungan Kunci 3D (Rp20.000) + Celengan (Rp37.000) = Total Rp144.000

4. **Desain Grafis & 3D Level 2 - Reguler (6 Bulan)**
   - Per Sesi: Rp45.000
   - 1 Pertemuan (2 Sesi): Rp90.000
   - Per Bulan: Rp360.000
   - Total Pelatihan: Rp2.160.000
   - Biaya Project Akhir: Kotak Pensil (Rp37.000) + Tote bag / Tas Serut (Rp67.000) = Total Rp104.000

5. **Coding & Ai Level 1 - Reguler (3 Bulan)**
   - Per Sesi: Rp47.000
   - 1 Pertemuan (2 Sesi): Rp94.000
   - Per Bulan: Rp376.000
   - Total Pelatihan: Rp1.128.000

6. **Coding & Ai Level 1 - Reguler (6 Bulan)**
   - Per Sesi: Rp45.000
   - 1 Pertemuan (2 Sesi): Rp90.000
   - Per Bulan: Rp360.000
   - Total Pelatihan: Rp2.160.000

7. **Coding & Ai Level 2 - Reguler (6 Bulan)**
   - Per Sesi: Rp45.000
   - 1 Pertemuan (2 Sesi): Rp90.000
   - Per Bulan: Rp360.000
   - Total Pelatihan: Rp2.160.000

8. **Robotika Pondasi Energi & Gerak - Reguler (1 Bulan)**
   - Per Sesi: Rp67.000
   - 1 Pertemuan (2 Sesi): Rp134.000
   - Total Pelatihan: Rp536.000
   - *Fasilitas: Free Alat Robotika & Bisa dibawa Pulang*

9. **Public Speaking Berani Cerita & Perkenalan Diri - Reguler (3 Bulan)**
   - Per Sesi: Rp47.000
   - 1 Pertemuan (2 Sesi): Rp94.000
   - Per Bulan: Rp376.000
   - Total Pelatihan: Rp1.128.000
   - *Fasilitas: Notebook & Bolpoin Public Speaking Edugenzi*

10. **Kelas Privat Lainnya (Coding & Ai Level 1, Coding & Ai Level 2, FOS Dewasa, Desain Grafis Dewasa)**
    - Per Sesi: Rp67.000
    - 1 Pertemuan (2 Sesi): Rp134.000
    - Cicilan: Cicilan I (Rp450.000), Cicilan II (Rp450.000), Cicilan III (Rp350.000), Cicilan IV (Rp300.000), Cicilan V (Rp58.000)
    - Total Pelatihan: Rp1.608.000

*Catatan: Biaya Pendaftaran untuk seluruh program adalah Rp100.000 (berlaku hingga Juni 2026).*

### 18.3 Alur & Skema Penilaian (Bulan Ke-X)

Untuk menyelaraskan dengan durasi pelatihan peserta, skema penilaian diatur sebagai berikut:

**Alur Tampilan (Mentor):**
1. `GET /mentor/penilaian` → Halaman **index**: grid kartu semua peserta didik aktif, menampilkan program, durasi, progress bar periode yang sudah dinilai, dan rata-rata bintang.
2. `GET /mentor/penilaian/{peserta}/detail` → Halaman **detail**: info peserta, tracker per Bulan Ke-X (hijau = sudah dinilai), tabel rincian nilai Minggu 1–4 (Kelas & Proyek) per periode, serta catatan.
3. `GET /mentor/penilaian/create?peserta_id={id}` → Form tambah penilaian, dengan peserta pra-terpilih jika datang dari halaman detail.

**Aturan Penilaian:**
1. **Pilihan Periode & Pendaftaran**: Durasi/periode pelatihan (opsi: 1 Bulan, 3 Bulan, dan 6 Bulan) wajib dipilih saat menambahkan atau mengedit data mahasiswa/peserta didik.
2. **Batasan Durasi**:
   - Peserta dengan **durasi 1 Bulan** hanya dapat diberikan penilaian untuk **Bulan Ke-1**.
   - Peserta dengan **durasi 3 Bulan** hanya dapat diberikan penilaian untuk **Bulan Ke-1, Bulan Ke-2, dan Bulan Ke-3**.
   - Peserta dengan **durasi 6 Bulan** dapat diberikan penilaian dari **Bulan Ke-1 hingga Bulan Ke-6**.
3. **Validasi Unik**: Satu peserta hanya boleh memiliki maksimal 1 catatan penilaian untuk setiap `bulan_ke` tertentu (`unique(['peserta_id', 'bulan_ke'])`).
4. **Dropdown Dinamis / Auto-Terpilih**: Saat mentor mengakses/mengisi form penilaian untuk peserta tertentu, opsi penilaian periode/Bulan Ke-X otomatis disaring dan diset berdasarkan periode pelatihan yang terdaftar untuk peserta tersebut (via Alpine.js + data-max attribute).
5. **Auto-Terpilih Nilai**: Nilai/periode penilaian default otomatis terarah ke periode aktif/terdaftar yang sesuai.

---

*Dokumen ini dibuat untuk agent Antigravity — sistem manajemen proyek Edugenzi Banda Aceh.*
*Versi: 1.1 | Stack: Laravel 12 + Blade + TailAdmin + MySQL*
