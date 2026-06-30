<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTugasRequest;
use App\Http\Requests\UpdateTugasRequest;
use App\Models\Proyek;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TugasController extends Controller
{
    public function create(Proyek $proyek): View
    {
        Gate::authorize('update', $proyek);

        $query = User::where('role', 'peserta_didik')
            ->where('status', 'aktif');

        if ($proyek->program_pelatihan_id || $proyek->jenis_kelas_id || $proyek->durasi_pelatihan) {
            $query->whereHas('enrollments', function ($q) use ($proyek) {
                $q->where('status', 'aktif');
                if ($proyek->program_pelatihan_id) {
                    $q->where('program_pelatihan_id', $proyek->program_pelatihan_id);
                }
                if ($proyek->jenis_kelas_id) {
                    $q->where('jenis_kelas_id', $proyek->jenis_kelas_id);
                }
                if ($proyek->durasi_pelatihan) {
                    $q->where('durasi_pelatihan', $proyek->durasi_pelatihan);
                }
            });
        }

        $pesertaList = $query->orderBy('nama_lengkap')->get();

        return view('mentor.tugas.create', compact('proyek', 'pesertaList'));
    }

    public function store(StoreTugasRequest $request, Proyek $proyek): RedirectResponse
    {
        Gate::authorize('update', $proyek);

        $proyek->tugas()->create($request->validated());

        // Daftarkan peserta ke proyek secara otomatis
        $proyek->peserta()->syncWithoutDetaching([$request->user_id]);

        return redirect()->route('mentor.proyek.show', $proyek)
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function show(Tugas $tugas): View
    {
        Gate::authorize('view', $tugas->proyek);

        $tugas->load(['proyek', 'peserta', 'subTugas', 'lampiran.uploader']);

        return view('mentor.tugas.show', compact('tugas'));
    }

    public function edit(Tugas $tugas): View
    {
        Gate::authorize('update', $tugas->proyek);

        $proyek = $tugas->proyek;

        $query = User::where('role', 'peserta_didik')
            ->where(function ($q) use ($proyek, $tugas) {
                $q->where(function ($sub) use ($proyek) {
                    $sub->where('status', 'aktif');
                    if ($proyek->program_pelatihan_id || $proyek->jenis_kelas_id || $proyek->durasi_pelatihan) {
                        $sub->whereHas('enrollments', function ($eq) use ($proyek) {
                            $eq->where('status', 'aktif');
                            if ($proyek->program_pelatihan_id) {
                                $eq->where('program_pelatihan_id', $proyek->program_pelatihan_id);
                            }
                            if ($proyek->jenis_kelas_id) {
                                $eq->where('jenis_kelas_id', $proyek->jenis_kelas_id);
                            }
                            if ($proyek->durasi_pelatihan) {
                                $eq->where('durasi_pelatihan', $proyek->durasi_pelatihan);
                            }
                        });
                    }
                })->orWhere('id', $tugas->user_id);
            });

        $pesertaList = $query->orderBy('nama_lengkap')->get();

        return view('mentor.tugas.edit', compact('tugas', 'pesertaList'));
    }

    public function update(UpdateTugasRequest $request, Tugas $tugas): RedirectResponse
    {
        Gate::authorize('update', $tugas->proyek);

        $oldUserId = $tugas->user_id;

        $tugas->update($request->validated());

        // Pastikan peserta yang baru diassign tugas ini terdaftar di proyek
        $tugas->proyek->peserta()->syncWithoutDetaching([$request->user_id]);

        // Jika assignee berubah, cek apakah user lama masih punya tugas di proyek ini
        if ($oldUserId != $request->user_id) {
            $hasOtherTasks = $tugas->proyek->tugas()->where('user_id', $oldUserId)->exists();
            if (!$hasOtherTasks) {
                // Keluarkan dari proyek karena sudah tidak ada tugas
                $tugas->proyek->peserta()->detach($oldUserId);
            }
        }

        // Jika mentor mengubah tugas kembali menjadi belum selesai, otomatis kembalikan proyek menjadi berjalan
        if (in_array($request->status_task, ['to_do', 'in_progress']) && $tugas->proyek->status_proyek === 'selesai') {
            $tugas->proyek->update(['status_proyek' => 'berjalan']);
        }

        return redirect()->route('mentor.proyek.show', $tugas->proyek_id)
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Tugas $tugas): RedirectResponse
    {
        $proyekId = $tugas->proyek_id;
        $userId = $tugas->user_id;
        $proyek = $tugas->proyek;

        Gate::authorize('delete', $proyek);
        $tugas->delete();

        // Cek apakah user masih punya tugas lain di proyek ini
        $hasOtherTasks = $proyek->tugas()->where('user_id', $userId)->exists();
        if (!$hasOtherTasks) {
            $proyek->peserta()->detach($userId);
        }

        return redirect()->route('mentor.proyek.show', $proyekId)
            ->with('success', 'Tugas berhasil dihapus.');
    }
}
