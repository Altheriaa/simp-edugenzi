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
    /**
     * Halaman utama: daftar peserta didik yang sudah/belum diberi penilaian oleh mentor ini.
     */
    public function index(): View
    {
        // Ambil semua peserta aktif + data penilaian dari mentor ini (di-eager load)
        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->with(['programPelatihan', 'jenisKelas', 'penilaianSebagaiPeserta' => function ($q) {
                $q->where('mentor_id', Auth::id())->orderBy('bulan_ke');
            }])
            ->orderBy('nama_lengkap')
            ->paginate(12);

        return view('mentor.penilaian.index', compact('pesertas'));
    }

    /**
     * Halaman detail: semua penilaian (per bulan_ke) untuk satu peserta tertentu.
     */
    public function detail(User $peserta): View
    {
        abort_if($peserta->role !== 'peserta_didik', 404);

        $penilaians = Penilaian::where('peserta_id', $peserta->id)
            ->where('mentor_id', Auth::id())
            ->orderBy('bulan_ke')
            ->get();

        $maxBulan = $this->maxBulanFromDurasi($peserta->durasi_pelatihan);

        return view('mentor.penilaian.detail', compact('peserta', 'penilaians', 'maxBulan'));
    }

    /**
     * Form buat penilaian baru (bisa diakses langsung atau dari halaman detail).
     */
    public function create(): View
    {
        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->with(['programPelatihan', 'jenisKelas'])
            ->orderBy('nama_lengkap')
            ->get();

        $durasiMap = $pesertas->mapWithKeys(fn($p) => [
            $p->id => $this->maxBulanFromDurasi($p->durasi_pelatihan),
        ]);

        // Peserta pra-pilih jika datang dari halaman detail (?peserta_id=xxx)
        $selectedPesertaId = request('peserta_id');

        return view('mentor.penilaian.create', compact('pesertas', 'durasiMap', 'selectedPesertaId'));
    }

    public function store(StorePenilaianRequest $request): RedirectResponse
    {
        Penilaian::create([
            ...$request->validated(),
            'mentor_id' => Auth::id(),
        ]);

        // Redirect ke halaman detail peserta yang baru saja dinilai
        $peserta = User::findOrFail($request->peserta_id);
        return redirect()->route('mentor.penilaian.detail', $peserta)
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    public function edit(Penilaian $penilaian): View
    {
        $this->authorize('update', $penilaian);

        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->with(['programPelatihan', 'jenisKelas'])
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

        $peserta = User::findOrFail($penilaian->peserta_id);
        return redirect()->route('mentor.penilaian.detail', $peserta)
            ->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function destroy(Penilaian $penilaian): RedirectResponse
    {
        $this->authorize('delete', $penilaian);
        $pesertaId = $penilaian->peserta_id;
        $penilaian->delete();

        $peserta = User::findOrFail($pesertaId);
        return redirect()->route('mentor.penilaian.detail', $peserta)
            ->with('success', 'Penilaian berhasil dihapus.');
    }

    /** Hitung batas bulan dari string durasi_pelatihan */
    private function maxBulanFromDurasi(?string $durasi): int
    {
        if (!$durasi) return 6;
        if (str_contains($durasi, '1 Bulan')) return 1;
        if (str_contains($durasi, '3 Bulan')) return 3;
        return 6; // default 6 bulan (termasuk 6 Bulan & 12 X Pertemuan)
    }
}
