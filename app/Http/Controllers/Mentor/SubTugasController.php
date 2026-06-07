<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\SubTugas;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubTugasController extends Controller
{
    public function store(Request $request, Tugas $tugas): RedirectResponse
    {
        Gate::authorize('update', $tugas->proyek);

        $request->validate([
            'judul_sub_task' => ['required', 'string', 'max:150'],
        ]);

        $tugas->subTugas()->create([
            'judul_sub_task' => $request->judul_sub_task,
            'is_selesai'     => false,
        ]);

        return back()->with('success', 'Sub-tugas berhasil ditambahkan.');
    }

    public function update(Request $request, SubTugas $subTugas): RedirectResponse
    {
        Gate::authorize('update', $subTugas->tugas->proyek);

        $request->validate([
            'judul_sub_task' => ['required', 'string', 'max:150'],
        ]);

        $subTugas->update($request->only('judul_sub_task'));

        return back()->with('success', 'Sub-tugas berhasil diperbarui.');
    }

    public function destroy(SubTugas $subTugas): RedirectResponse
    {
        Gate::authorize('delete', $subTugas->tugas->proyek);

        $subTugas->delete();

        return back()->with('success', 'Sub-tugas berhasil dihapus.');
    }
}
