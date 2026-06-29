<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProyekRequest;
use App\Http\Requests\UpdateProyekRequest;
use App\Models\JenisKelas;
use App\Models\ProgramKelasDurasi;
use App\Models\ProgramPelatihan;
use App\Models\Proyek;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProyekController extends Controller
{
    public function index(): View
    {
        $search = request('search');

        $proyeks = Proyek::where('user_id', Auth::id())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_proyek', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->with(['programPelatihan', 'jenisKelas'])
            ->withCount('tugas')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('mentor.proyek.index', compact('proyeks'));
    }

    public function create(): View
    {
        $programs    = ProgramPelatihan::aktif()->orderBy('nama_program')->get();
        $jenisKelas  = JenisKelas::aktif()->get();
        $optionsJson = $this->buildOptionsJson();

        return view('mentor.proyek.create', compact('programs', 'jenisKelas', 'optionsJson'));
    }

    public function store(StoreProyekRequest $request): RedirectResponse
    {
        Proyek::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('mentor.proyek.index')
            ->with('success', 'Proyek berhasil dibuat.');
    }

    public function show(Proyek $proyek): View
    {
        Gate::authorize('view', $proyek);

        $proyek->load(['tugas.peserta', 'tugas.subTugas', 'evaluasi.peserta']);

        return view('mentor.proyek.show', compact('proyek'));
    }

    public function edit(Proyek $proyek): View
    {
        Gate::authorize('update', $proyek);

        $programs    = ProgramPelatihan::aktif()->orderBy('nama_program')->get();
        $jenisKelas  = JenisKelas::aktif()->get();
        $optionsJson = $this->buildOptionsJson();

        return view('mentor.proyek.edit', compact('proyek', 'programs', 'jenisKelas', 'optionsJson'));
    }

    public function update(UpdateProyekRequest $request, Proyek $proyek): RedirectResponse
    {
        Gate::authorize('update', $proyek);
        
        if ($request->status_proyek === 'selesai') {
            $hasPendingTasks = $proyek->tugas()->where('status_task', '!=', 'done')->exists();
            if ($hasPendingTasks) {
                return back()->with('error', 'Tidak dapat menyelesaikan proyek. Masih ada tugas yang belum selesai.');
            }
        }

        $proyek->update($request->validated());

        return redirect()->route('mentor.proyek.index')
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Proyek $proyek): RedirectResponse
    {
        Gate::authorize('delete', $proyek);
        $proyek->delete();

        return redirect()->route('mentor.proyek.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }

    /**
     * Bangun mapping JSON: { programId: { kelasId: ['1 Bulan', ...] } }
     */
    private function buildOptionsJson(): string
    {
        $rows = ProgramKelasDurasi::with(['programPelatihan', 'jenisKelas'])->get();

        $map = [];
        foreach ($rows as $row) {
            $pid = $row->program_pelatihan_id;
            $kid = $row->jenis_kelas_id;
            $map[$pid][$kid][] = $row->durasi_pelatihan;
        }

        return json_encode($map);
    }
}
