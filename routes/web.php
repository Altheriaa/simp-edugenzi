<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mentor;
use App\Http\Controllers\Peserta;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;


Route::get('/', fn() => redirect()->route('login'));

// --- Auth ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    // Route::get('/signup', [RegisterController::class, 'index'])->name('signup');
    // Route::post('/signup', [RegisterController::class, 'store'])->name('signup.post');
});

use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// --- Admin ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pengguna', Admin\PenggunaController::class)->except(['show']);
    Route::resource('peserta-didik', Admin\PesertaController::class)->except(['show']);
    Route::resource('program-pelatihan', Admin\ProgramPelatihanController::class)->except(['show']);
    Route::post('program-pelatihan/{program}/durasi', [Admin\ProgramPelatihanController::class, 'addDurasi'])->name('program-pelatihan.add-durasi');
    Route::delete('program-kelas-durasi/{id}', [Admin\ProgramPelatihanController::class, 'removeDurasi'])->name('program-kelas-durasi.destroy');
    Route::resource('jenis-kelas', Admin\JenisKelasController::class)->except(['show']);
    Route::get('penilaian', [Admin\PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('penilaian/{enrollment}/detail', [Admin\PenilaianController::class, 'detail'])->name('penilaian.detail');
    Route::get('sertifikat', [Admin\SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('sertifikat/{sertifikat}/print', [Admin\SertifikatController::class, 'print'])->name('sertifikat.print');

    // Enrollment (pendaftaran peserta ke program pelatihan)
    Route::resource('enrollment', Admin\EnrollmentController::class)->except(['show', 'edit', 'update']);
    Route::patch('enrollment/{enrollment}/status', [Admin\EnrollmentController::class, 'updateStatus'])
        ->name('enrollment.status');
});

// --- Mentor ---
Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    
    Route::get('/dashboard', [Mentor\DashboardController::class, 'index'])->name('dashboard');

    // Proyek (CRUD)
    Route::resource('proyek', Mentor\ProyekController::class);

    // Tugas (nested di bawah proyek, shallow)
    Route::resource('proyek.tugas', Mentor\TugasController::class)
        ->parameters(['tugas' => 'tugas'])
        ->shallow()
        ->except(['index']);

    // Sub-Tugas
    Route::post('tugas/{tugas}/sub-tugas', [Mentor\SubTugasController::class, 'store'])
        ->name('sub-tugas.store');
    Route::patch('sub-tugas/{subTugas}', [Mentor\SubTugasController::class, 'update'])
        ->name('sub-tugas.update');
    Route::delete('sub-tugas/{subTugas}', [Mentor\SubTugasController::class, 'destroy'])
        ->name('sub-tugas.destroy');

    // Lampiran
    Route::post('tugas/{tugas}/lampiran', [Mentor\LampiranController::class, 'store'])
        ->name('lampiran.store');
    Route::delete('lampiran/{lampiran}', [Mentor\LampiranController::class, 'destroy'])
        ->name('lampiran.destroy');

    // Evaluasi
    Route::resource('evaluasi', Mentor\EvaluasiController::class)
        ->only(['index', 'store']);

    // Penilaian — list enrollment, lalu detail per enrollment
    Route::get('penilaian/{enrollment}/detail', [Mentor\PenilaianController::class, 'detail'])
        ->name('penilaian.detail');
    Route::resource('penilaian', Mentor\PenilaianController::class)
        ->except(['show']);

    // Sertifikat
    Route::resource('sertifikat', Mentor\SertifikatController::class);
});

// --- Peserta Didik ---
Route::middleware(['auth', 'role:peserta_didik'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [Peserta\DashboardController::class, 'index'])->name('dashboard');
    // Proyek
    Route::get('proyek', [Peserta\ProyekController::class, 'index'])->name('proyek.index');
    Route::get('proyek/{proyek}', [Peserta\ProyekController::class, 'show'])->name('proyek.show');

    // Tugas
    Route::get('tugas', [Peserta\TugasController::class, 'index'])->name('tugas.index');
    Route::get('tugas/{tugas}', [Peserta\TugasController::class, 'show'])->name('tugas.show');
    Route::patch('tugas/{tugas}/status', [Peserta\TugasController::class, 'updateStatus'])
        ->name('tugas.status');

    // Sub-Tugas toggle
    Route::patch('sub-tugas/{subTugas}/toggle', [Peserta\SubTugasController::class, 'toggle'])
        ->name('sub-tugas.toggle');

    // Lampiran
    Route::post('tugas/{tugas}/lampiran', [Peserta\LampiranController::class, 'store'])
        ->name('lampiran.store');
    Route::delete('lampiran/{lampiran}', [Peserta\LampiranController::class, 'destroy'])
        ->name('lampiran.destroy');

    // Penilaian
    Route::get('penilaian', [Peserta\PenilaianController::class, 'index'])->name('penilaian.index');

    // Sertifikat
    Route::get('sertifikat', [Peserta\SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('sertifikat/{sertifikat}/print', [Peserta\SertifikatController::class, 'print'])->name('sertifikat.print');
});














