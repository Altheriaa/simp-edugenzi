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

        $pesertaList = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        return view('mentor.tugas.create', compact('proyek', 'pesertaList'));
    }

    public function store(StoreTugasRequest $request, Proyek $proyek): RedirectResponse
    {
        Gate::authorize('update', $proyek);

        $proyek->tugas()->create($request->validated());

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

        $pesertaList = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        return view('mentor.tugas.edit', compact('tugas', 'pesertaList'));
    }

    public function update(UpdateTugasRequest $request, Tugas $tugas): RedirectResponse
    {
        Gate::authorize('update', $tugas->proyek);

        $tugas->update($request->validated());

        return redirect()->route('mentor.proyek.show', $tugas->proyek_id)
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Tugas $tugas): RedirectResponse
    {
        $proyekId = $tugas->proyek_id;
        Gate::authorize('delete', $tugas->proyek);
        $tugas->delete();

        return redirect()->route('mentor.proyek.show', $proyekId)
            ->with('success', 'Tugas berhasil dihapus.');
    }
}
