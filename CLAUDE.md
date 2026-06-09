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
│   ├── auth/               # Halaman login, dll
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
| `users` | `User` | Admin, Mentor, Peserta Didik |
| `proyek` | `Proyek` | Data proyek |
| `tugas` | `Tugas` | Task dalam proyek |
| `sub_tugas` | `SubTugas` | Checklist item per task |
| `lampiran` | `Lampiran` | File upload per task |
| `evaluasi` | `Evaluasi` | Catatan evaluasi Mentor |
| `penilaian` | `Penilaian` | Nilai bintang EAC bulanan siswa |
| `sertifikat` | `Sertifikat` | Sertifikat kelulusan/penghargaan siswa |

### 4.2 Skema Migrasi

**users**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id('id_user');
    $table->string('nama_lengkap', 100);
    $table->string('username', 50)->unique();
    $table->string('email', 100)->unique();
    $table->string('password');
    $table->enum('role', ['admin', 'mentor', 'peserta_didik']);
    $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
    $table->timestamps(); // created_at & updated_at
    $table->rememberToken();
});
```

**proyek**
```php
Schema::create('proyek', function (Blueprint $table) {
    $table->id('id_proyek');
    $table->string('nama_proyek', 100);
    $table->text('deskripsi')->nullable();
    $table->date('tgl_mulai');
    $table->date('tgl_selesai');
    $table->foreignId('id_mentor')->constrained('users', 'id_user')->cascadeOnDelete();
    $table->enum('status_proyek', ['berjalan', 'selesai', 'tertunda'])->default('berjalan');
    $table->timestamps();
});
```

**tugas**
```php
Schema::create('tugas', function (Blueprint $table) {
    $table->id('id_task');
    $table->foreignId('id_proyek')->constrained('proyek', 'id_proyek')->cascadeOnDelete();
    $table->foreignId('id_peserta')->constrained('users', 'id_user')->cascadeOnDelete();
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
    $table->id('id_sub_task');
    $table->foreignId('id_task')->constrained('tugas', 'id_task')->cascadeOnDelete();
    $table->string('judul_sub_task', 150);
    $table->tinyInteger('is_selesai')->default(0);
    $table->timestamps();
});
```

**lampiran**
```php
Schema::create('lampiran', function (Blueprint $table) {
    $table->id('id_lampiran');
    $table->foreignId('id_task')->constrained('tugas', 'id_task')->cascadeOnDelete();
    $table->string('nama_file', 200);
    $table->string('path_file', 255);
    $table->string('tipe_file', 50);
    $table->integer('ukuran_file'); // dalam KB
    $table->foreignId('uploaded_by')->constrained('users', 'id_user');
    $table->timestamps();
});
```

**evaluasi**
```php
Schema::create('evaluasi', function (Blueprint $table) {
    $table->id('id_evaluasi');
    $table->foreignId('id_proyek')->constrained('proyek', 'id_proyek')->cascadeOnDelete();
    $table->foreignId('id_mentor')->constrained('users', 'id_user');
    $table->foreignId('id_peserta')->constrained('users', 'id_user');
    $table->text('catatan');
    $table->timestamps();
});
```

**penilaian**
```php
Schema::create('penilaian', function (Blueprint $table) {
    $table->id('id_penilaian');
    $table->foreignId('id_peserta')->constrained('users', 'id_user')->cascadeOnDelete();
    $table->foreignId('id_mentor')->constrained('users', 'id_user')->cascadeOnDelete();
    $table->string('bulan', 20);
    $table->year('tahun');
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
    $table->unique(['id_peserta', 'bulan', 'tahun']);
});
```

**sertifikat**
```php
Schema::create('sertifikat', function (Blueprint $table) {
    $table->id('id_sertifikat');
    $table->string('nomor_sertifikat', 100)->unique();
    $table->foreignId('id_peserta')->constrained('users', 'id_user')->cascadeOnDelete();
    $table->foreignId('id_mentor')->constrained('users', 'id_user')->cascadeOnDelete();
    $table->string('nama_program', 150);
    $table->date('tgl_terbit');
    $table->string('predikat', 50);
    $table->timestamps();
});
```

### 4.3 Konvensi Model

- Nama model: **PascalCase singular** → `Proyek`, `SubTugas`, `Lampiran`
- Nama tabel: **snake_case plural** → tapi karena nama tabel custom, selalu definisikan `$table` secara eksplisit
- Selalu definisikan `$fillable` — **jangan pakai `$guarded = []`**
- Selalu definisikan relasi Eloquent lengkap (hasMany, belongsTo, dll)

**Contoh Model Proyek:**
```php
class Proyek extends Model
{
    protected $table = 'proyek';
    protected $primaryKey = 'id_proyek';

    protected $fillable = [
        'nama_proyek', 'deskripsi', 'tgl_mulai',
        'tgl_selesai', 'id_mentor', 'status_proyek',
    ];

    protected $casts = [
        'tgl_mulai'   => 'date',
        'tgl_selesai' => 'date',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mentor', 'id_user');
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'id_proyek', 'id_proyek');
    }

    public function evaluasi(): HasMany
    {
        return $this->hasMany(Evaluasi::class, 'id_proyek', 'id_proyek');
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

// --- Auth ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// --- Admin ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('pengguna', Admin\PenggunaController::class);
    Route::get('laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [Admin\LaporanController::class, 'exportPdf'])->name('laporan.export');
    Route::get('penilaian', [Admin\PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('sertifikat', [Admin\SertifikatController::class, 'index'])->name('sertifikat.index');
});

// --- Mentor ---
Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/dashboard', [Mentor\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('proyek', Mentor\ProyekController::class);
    Route::resource('proyek.tugas', Mentor\TugasController::class)->shallow();
    Route::post('tugas/{tugas}/sub-tugas', [Mentor\SubTugasController::class, 'store'])->name('sub-tugas.store');
    Route::patch('sub-tugas/{subTugas}', [Mentor\SubTugasController::class, 'update'])->name('sub-tugas.update');
    Route::delete('sub-tugas/{subTugas}', [Mentor\SubTugasController::class, 'destroy'])->name('sub-tugas.destroy');
    Route::get('laporan', [Mentor\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [Mentor\LaporanController::class, 'exportPdf'])->name('laporan.export');
    Route::resource('evaluasi', Mentor\EvaluasiController::class)->only(['store', 'index']);
    Route::resource('penilaian', Mentor\PenilaianController::class);
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

### Konvensi Route

- Gunakan **resource route** jika ada operasi CRUD penuh
- Gunakan `shallow()` untuk nested resource agar URL lebih bersih
- Selalu beri `name()` pada setiap route
- Prefix sesuai role: `admin.`, `mentor.`, `peserta.`

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
- Gunakan `authorize()` dari Policy di setiap method yang butuh otorisasi
- Redirect setelah operasi tulis (POST/PUT/DELETE) selalu dengan `->with('success', '...')` atau `->with('error', '...')`
- Tidak ada logika bisnis kompleks di controller — pindahkan ke Service jika perlu

**Contoh Controller:**
```php
// app/Http/Controllers/Mentor/ProyekController.php

class ProyekController extends Controller
{
    public function index(): View
    {
        $proyeks = Proyek::where('id_mentor', Auth::id())
            ->withCount('tugas')
            ->latest()
            ->paginate(10);

        return view('mentor.proyek.index', compact('proyeks'));
    }

    public function store(StoreProyekRequest $request): RedirectResponse
    {
        Proyek::create([
            ...$request->validated(),
            'id_mentor' => Auth::id(),
        ]);

        return redirect()->route('mentor.proyek.index')
            ->with('success', 'Proyek berhasil dibuat.');
    }

    public function update(UpdateProyekRequest $request, Proyek $proyek): RedirectResponse
    {
        $this->authorize('update', $proyek);
        $proyek->update($request->validated());

        return redirect()->route('mentor.proyek.index')
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Proyek $proyek): RedirectResponse
    {
        $this->authorize('delete', $proyek);
        $proyek->delete();

        return redirect()->route('mentor.proyek.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }
}
```

---

## 8. Form Request & Validasi

- Buat Form Request **terpisah** untuk store dan update
- Nama file: `StoreNamaModelRequest.php` dan `UpdateNamaModelRequest.php`
- Letakkan di `app/Http/Requests/`
- Selalu override `authorize()` — return `true` jika auth check ada di middleware, atau gunakan Policy

**Contoh:**
```php
// app/Http/Requests/StoreTugasRequest.php

class StoreTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'mentor';
    }

    public function rules(): array
    {
        return [
            'judul_task'     => ['required', 'string', 'max:150'],
            'deskripsi_task' => ['nullable', 'string'],
            'prioritas'      => ['required', 'in:rendah,sedang,tinggi'],
            'deadline'       => ['nullable', 'date', 'after_or_equal:today'],
            'id_peserta'     => ['required', 'exists:users,id_user'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul_task.required' => 'Judul tugas wajib diisi.',
            'prioritas.in'        => 'Prioritas harus rendah, sedang, atau tinggi.',
            'id_peserta.exists'   => 'Peserta didik tidak ditemukan.',
        ];
    }
}
```

---

## 9. View & Blade

### Konvensi

- Semua view diletakkan di folder sesuai role: `resources/views/admin/`, `mentor/`, `peserta/`
- Setiap halaman **extend layout utama** TailAdmin: `@extends('layouts.app')`
- Gunakan `@section('title', '...')` untuk judul halaman
- Gunakan `@section('content')` untuk konten utama
- Komponen reusable (tabel, modal, alert) dibuat sebagai **Blade component** di `resources/views/components/`

### Struktur View per Modul

```
resources/views/
├── layouts/
│   └── app.blade.php
├── auth/
│   └── login.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── pengguna/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── laporan/
│       └── index.blade.php
├── mentor/
│   ├── dashboard.blade.php
│   ├── proyek/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── tugas/
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   └── laporan/
│       └── index.blade.php
├── peserta/
│   ├── dashboard.blade.php
│   └── tugas/
│       ├── index.blade.php
│       └── show.blade.php
└── components/
    ├── alert.blade.php
    ├── badge-status.blade.php
    ├── badge-prioritas.blade.php
    ├── modal-confirm.blade.php
    └── pagination.blade.php
```

### Flash Message

Selalu tampilkan flash message di layout. Gunakan komponen alert:

```blade
{{-- resources/views/components/alert.blade.php --}}
@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="rounded-lg bg-green-100 p-4 text-green-800 mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show"
         class="rounded-lg bg-red-100 p-4 text-red-800 mb-4">
        {{ session('error') }}
    </div>
@endif
```

### Badge Komponen

```blade
{{-- resources/views/components/badge-status.blade.php --}}
@props(['status'])

@php
$classes = match($status) {
    'berjalan'    => 'bg-blue-100 text-blue-800',
    'selesai'     => 'bg-green-100 text-green-800',
    'tertunda'    => 'bg-yellow-100 text-yellow-800',
    'to_do'       => 'bg-gray-100 text-gray-700',
    'in_progress' => 'bg-blue-100 text-blue-800',
    'done'        => 'bg-green-100 text-green-800',
    default       => 'bg-gray-100 text-gray-600',
};
$labels = match($status) {
    'berjalan'    => 'Berjalan',
    'selesai'     => 'Selesai',
    'tertunda'    => 'Tertunda',
    'to_do'       => 'To Do',
    'in_progress' => 'In Progress',
    'done'        => 'Done',
    default       => $status,
};
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classes }}">
    {{ $labels }}
</span>
```

**Penggunaan di view:**
```blade
<x-badge-status :status="$proyek->status_proyek" />
<x-badge-status :status="$tugas->status_task" />
```

---

## 10. Autentikasi & Redirect Setelah Login

Setelah login, redirect berdasarkan role:

```php
// app/Http/Controllers/Auth/AuthController.php

public function login(Request $request): RedirectResponse
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (!Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    $request->session()->regenerate();

    return redirect()->intended($this->redirectBasedOnRole());
}

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
- Validasi tipe file: `pdf`, `jpg`, `jpeg`, `png`, `fig`, `zip`
- Batas ukuran: **10MB per file**
- Nama file disimpan dengan format: `{timestamp}_{nama_asli}`

```php
// Dalam LampiranController@store

public function store(Request $request, Tugas $tugas): RedirectResponse
{
    $request->validate([
        'file' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,zip'],
    ]);

    $file      = $request->file('file');
    $namaAsli  = $file->getClientOriginalName();
    $namaSimp  = time() . '_' . $namaAsli;
    $path      = $file->storeAs('lampiran', $namaSimp, 'public');

    Lampiran::create([
        'id_task'     => $tugas->id_task,
        'nama_file'   => $namaAsli,
        'path_file'   => $path,
        'tipe_file'   => $file->getClientOriginalExtension(),
        'ukuran_file' => (int) ceil($file->getSize() / 1024),
        'uploaded_by' => Auth::id(),
    ]);

    return back()->with('success', 'File berhasil diunggah.');
}
```

---

## 12. Konvensi Penamaan (Naming Convention)

| Entitas | Konvensi | Contoh |
|---------|----------|--------|
| Model | PascalCase singular | `Proyek`, `SubTugas` |
| Controller | PascalCase + Controller | `ProyekController` |
| Form Request | Store/Update + Model + Request | `StoreProyekRequest` |
| Migration | snake_case deskriptif | `create_proyek_table` |
| Route name | role.resource.action | `mentor.proyek.index` |
| View folder | snake_case, sesuai role | `mentor/proyek/index` |
| Blade component | kebab-case | `badge-status`, `modal-confirm` |
| Variable di view | camelCase | `$proyeks`, `$tugasList` |
| Method controller | camelCase sesuai REST | `index`, `store`, `update` |
| CSS class | Tailwind utility saja | Jangan buat custom CSS kecuali perlu |

---

## 13. Keamanan

- **Password** selalu di-hash dengan `bcrypt` — gunakan `Hash::make()`, jangan `md5`
- **Mass assignment** — selalu gunakan `$fillable`, tidak ada `$guarded = []`
- **CSRF** — semua form wajib pakai `@csrf`
- **Authorization** — gunakan Policy untuk setiap resource; cek `$this->authorize()` di controller
- **Input validation** — selalu lewat Form Request, jangan percaya raw input
- **File upload** — validasi `mimes` dan `max` size di setiap upload
- **SQL** — selalu gunakan Eloquent atau Query Builder; tidak ada raw query kecuali sangat perlu, dan wajib gunakan `DB::select()` dengan binding

---

## 14. Policy

Buat Policy untuk setiap model yang butuh otorisasi kepemilikan:

```php
// app/Policies/ProyekPolicy.php

class ProyekPolicy
{
    public function update(User $user, Proyek $proyek): bool
    {
        return $user->id_user === $proyek->id_mentor;
    }

    public function delete(User $user, Proyek $proyek): bool
    {
        return $user->id_user === $proyek->id_mentor;
    }
}
```

**Daftarkan di `AppServiceProvider`:**
```php
Gate::policy(Proyek::class, ProyekPolicy::class);
Gate::policy(Tugas::class, TugasPolicy::class);
Gate::policy(Lampiran::class, LampiranPolicy::class);
```

---

## 15. Seeder

Seeder wajib menyediakan data awal untuk development dan testing:

```php
// database/seeders/DatabaseSeeder.php

public function run(): void
{
    // Admin
    User::create([
        'nama_lengkap' => 'Admin Edugenzi',
        'username'     => 'admin',
        'email'        => 'admin@edugenzi.id',
        'password'     => Hash::make('password'),
        'role'         => 'admin',
        'status'       => 'aktif',
    ]);

    // Mentor
    User::create([
        'nama_lengkap' => 'Mentor Satu',
        'username'     => 'mentor1',
        'email'        => 'mentor1@edugenzi.id',
        'password'     => Hash::make('password'),
        'role'         => 'mentor',
        'status'       => 'aktif',
    ]);

    // Peserta Didik
    User::create([
        'nama_lengkap' => 'Peserta Satu',
        'username'     => 'peserta1',
        'email'        => 'peserta1@edugenzi.id',
        'password'     => Hash::make('password'),
        'role'         => 'peserta_didik',
        'status'       => 'aktif',
    ]);
}
```

---

## 16. Hal yang Tidak Boleh Dilakukan Agent

- **Jangan** mengubah nama tabel database — gunakan yang sudah didefinisikan di bagian 4
- **Jangan** menambah package baru tanpa konfirmasi — gunakan yang sudah ada di Laravel
- **Jangan** membuat raw query SQL kecuali Eloquent benar-benar tidak bisa
- **Jangan** menyimpan logika bisnis kompleks di Blade view
- **Jangan** menaruh validasi langsung di Controller — selalu gunakan Form Request
- **Jangan** menggunakan `$guarded = []` pada model apapun
- **Jangan** menyimpan file upload di luar `storage/app/public/`
- **Jangan** membuat route tanpa nama (`->name()`)
- **Jangan** mengubah struktur TailAdmin yang sudah ada kecuali diminta

---

## 17. Checklist Sebelum Setiap Fitur Selesai

Sebelum menyatakan suatu fitur selesai, pastikan:

- [ ] Migration sudah dibuat dan berjalan
- [ ] Model sudah punya `$fillable`, `$casts`, dan relasi lengkap
- [ ] Form Request sudah ada untuk store dan update
- [ ] Controller sudah menggunakan Form Request dan Policy
- [ ] Route sudah diberi nama dan berada di grup middleware yang tepat
- [ ] View sudah mengextend layout TailAdmin
- [ ] Flash message sukses/gagal sudah muncul
- [ ] Tidak ada error di `php artisan test`
- [ ] Tidak ada N+1 query (gunakan `with()` pada relasi yang dipakai di view)

---

*Dokumen ini dibuat untuk agent Antigravity — sistem manajemen proyek Edugenzi Banda Aceh.*
*Versi: 1.0 | Stack: Laravel 12 + Blade + TailAdmin + MySQL*
