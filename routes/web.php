<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Mentor;
use App\Http\Controllers\Peserta;

// --- Public: redirect ke login ---
Route::get('/', fn() => redirect()->route('login'));

// --- Auth ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/profile', 'pages.profile')->name('profile');
});

// --- Admin ---
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')we
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('pengguna', Admin\PenggunaController::class)->except(['show']);
    });

// --- Mentor ---
Route::middleware(['auth', 'role:mentor'])
    ->prefix('mentor')
    ->name('mentor.')
    ->group(function () {
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

        // Evaluasi
        Route::resource('evaluasi', Mentor\EvaluasiController::class)
            ->only(['index', 'store']);
    });

// --- Peserta Didik ---
Route::middleware(['auth', 'role:peserta_didik'])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {
        Route::get('/dashboard', [Peserta\DashboardController::class, 'index'])->name('dashboard');

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
    });
























