<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenilaianRequest;
use App\Http\Requests\UpdatePenilaianRequest;
use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(): View
    {
        $penilaians = Penilaian::with('peserta')
            ->where('mentor_id', Auth::id())
            ->latest()
            ->paginate(10);

        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        return view('mentor.penilaian.index', compact('penilaians', 'pesertas', 'bulanList'));
    }

    public function create(): View
    {
        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        return view('mentor.penilaian.create', compact('pesertas', 'bulanList'));
    }

    public function store(StorePenilaianRequest $request): RedirectResponse
    {
        Penilaian::create([
            ...$request->validated(),
            'mentor_id' => Auth::id(),
        ]);

        return redirect()->route('mentor.penilaian.index')
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    public function edit(Penilaian $penilaian): View
    {
        $this->authorize('update', $penilaian);

        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        return view('mentor.penilaian.edit', compact('penilaian', 'pesertas', 'bulanList'));
    }

    public function update(UpdatePenilaianRequest $request, Penilaian $penilaian): RedirectResponse
    {
        $this->authorize('update', $penilaian);
        $penilaian->update($request->validated());

        return redirect()->route('mentor.penilaian.index')
            ->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function destroy(Penilaian $penilaian): RedirectResponse
    {
        $this->authorize('delete', $penilaian);
        $penilaian->delete();

        return redirect()->route('mentor.penilaian.index')
            ->with('success', 'Penilaian berhasil dihapus.');
    }
}
