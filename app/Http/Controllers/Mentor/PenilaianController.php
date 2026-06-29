<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenilaianRequest;
use App\Http\Requests\UpdatePenilaianRequest;
use App\Models\Penilaian;
use App\Models\User;
use App\Models\ProgramKelasDurasi;
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
        $search = request('search');

        // Ambil semua peserta aktif + data penilaian dari mentor ini (di-eager load)
        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->whereHas('proyekDiikuti', function ($query) {
                $query->where('proyek.user_id', Auth::id());
            })
            ->when($search, function ($query, $search) {
                $query->where('nama_lengkap', 'like', "%{$search}%");
            })
            ->with(['programPelatihan', 'jenisKelas', 'penilaianSebagaiPeserta' => function ($q) {
                $q->where('mentor_id', Auth::id())->orderBy('bulan_ke');
            }])
            ->orderBy('nama_lengkap')
            ->paginate(12)
            ->withQueryString();

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

        $maxBulan = $this->maxBulanFromPeserta($peserta);

        return view('mentor.penilaian.detail', compact('peserta', 'penilaians', 'maxBulan'));
    }

    /**
     * Form buat penilaian baru (bisa diakses langsung atau dari halaman detail).
     */
    public function create(): View
    {
        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->whereHas('proyekDiikuti', function ($query) {
                $query->where('proyek.user_id', Auth::id());
            })
            ->with(['programPelatihan', 'jenisKelas'])
            ->orderBy('nama_lengkap')
            ->get();

        $durasiMap = $pesertas->mapWithKeys(fn($p) => [
            $p->id => $this->maxBulanFromPeserta($p),
        ]);

        // Peserta pra-pilih jika datang dari halaman detail (?peserta_id=xxx)
        $selectedPesertaId = request('peserta_id');
        
        // Hitung default bulan_ke (bulan berikutnya yang belum dinilai)
        $nextBulan = 1;
        if ($selectedPesertaId) {
            $penilaiansAda = Penilaian::where('peserta_id', $selectedPesertaId)
                ->where('mentor_id', Auth::id())
                ->pluck('bulan_ke')
                ->toArray();
                
            for ($i = 1; $i <= 12; $i++) {
                if (!in_array($i, $penilaiansAda)) {
                    $nextBulan = $i;
                    break;
                }
            }
        }

        return view('mentor.penilaian.create', compact('pesertas', 'durasiMap', 'selectedPesertaId', 'nextBulan'));
    }

    public function store(StorePenilaianRequest $request): RedirectResponse
    {
        $peserta = User::findOrFail($request->peserta_id);
        
        // Pastikan peserta terdaftar di setidaknya satu proyek mentor ini
        if (!$peserta->proyekDiikuti()->where('proyek.user_id', Auth::id())->exists()) {
            return back()->with('error', 'Peserta tidak terdaftar di proyek Anda.');
        }

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
            ->whereHas('proyekDiikuti', function ($query) {
                $query->where('proyek.user_id', Auth::id());
            })
            ->with(['programPelatihan', 'jenisKelas'])
            ->orderBy('nama_lengkap')
            ->get();

        $durasiMap = $pesertas->mapWithKeys(fn($p) => [
            $p->id => $this->maxBulanFromPeserta($p),
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

    /** Hitung batas bulan dinamis dari master data durasi berdasarkan kombinasi peserta */
    private function maxBulanFromPeserta(User $peserta): int
    {
        if (!$peserta->program_pelatihan_id || !$peserta->jenis_kelas_id || !$peserta->durasi_pelatihan) {
            return 6; // default fallback
        }

        $kombinasi = ProgramKelasDurasi::where([
            'program_pelatihan_id' => $peserta->program_pelatihan_id,
            'jenis_kelas_id'       => $peserta->jenis_kelas_id,
            'durasi_pelatihan'     => $peserta->durasi_pelatihan,
        ])->first();

        return $kombinasi ? $kombinasi->durasi_bulan : 6;
    }
}
