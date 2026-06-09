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

        return view('mentor.penilaian.index', compact('penilaians', 'pesertas'));
    }

    public function create(): View
    {
        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        // Kirim data durasi per-peserta ke view (id => max_bulan)
        $durasiMap = $pesertas->mapWithKeys(fn($p) => [
            $p->id => $this->maxBulanFromDurasi($p->durasi_pelatihan),
        ]);

        return view('mentor.penilaian.create', compact('pesertas', 'durasiMap'));
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

        $durasiMap = $pesertas->mapWithKeys(fn($p) => [
            $p->id => $this->maxBulanFromDurasi($p->durasi_pelatihan),
        ]);

        return view('mentor.penilaian.edit', compact('penilaian', 'pesertas', 'durasiMap'));
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

    /** Hitung batas bulan dari string durasi_pelatihan */
    private function maxBulanFromDurasi(?string $durasi): int
    {
        if (!$durasi) return 6;
        if (str_contains($durasi, '3 Bulan')) return 3;
        return 6; // default 6 bulan
    }
}
