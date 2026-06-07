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
        $tugas = Tugas::with('proyek')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

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

        $request->validate([
            'status_task' => ['required', 'in:to_do,in_progress,done'],
        ]);

        $tugas->update([
            'status_task' => $request->status_task,
            'tgl_update'  => now(),
        ]);

        return back()->with('success', 'Status tugas berhasil diperbarui.');
    }
}
