<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TugasController extends Controller
{
    public function index(): View
    {
        $search = request('search');

        $tugas = Tugas::with('proyek')
            ->where('user_id', Auth::id())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul_tugas', 'like', "%{$search}%")
                      ->orWhereHas('proyek', fn($sub) => $sub->where('nama_proyek', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peserta.tugas.index', compact('tugas'));
    }

    public function show(Tugas $tugas): View
    {
        // Pastikan tugas milik peserta ini
        abort_if($tugas->user_id !== Auth::id(), 403, 'Akses ditolak.');

        $tugas->load(['proyek', 'subTugas', 'lampiran.uploader']);

        return view('peserta.tugas.show', compact('tugas'));
    }

    public function updateStatus(Request $request, Tugas $tugas): RedirectResponse
    {
        abort_if($tugas->user_id !== Auth::id(), 403, 'Akses ditolak.');

        if ($tugas->proyek->status_proyek === 'selesai') {
            return back()->with('error', 'Proyek sudah selesai, Anda tidak dapat mengubah data.');
        }

        if ($tugas->status_task === 'done') {
            return back()->with('error', 'Tugas yang sudah selesai tidak dapat diubah kembali statusnya.');
        }

        $request->validate([
            'status_task' => ['required', 'in:to_do,in_progress,done'],
        ]);

        if ($request->status_task === 'done') {
            if ($tugas->subTugas()->where('is_selesai', false)->exists()) {
                return back()->with('error', 'Tidak dapat menyelesaikan tugas utama, masih ada sub-tugas yang belum selesai.');
            }

            if (!$tugas->lampiran()->exists()) {
                return back()->with('error', 'Tidak dapat menyelesaikan tugas utama, Anda harus mengunggah setidaknya satu lampiran.');
            }
        }

        $tugas->update([
            'status_task' => $request->status_task,
            'tgl_update'  => now(),
        ]);

        return back()->with('success', 'Status tugas berhasil diperbarui.');
    }
}
