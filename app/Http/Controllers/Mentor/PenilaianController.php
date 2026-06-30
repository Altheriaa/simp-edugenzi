<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenilaianRequest;
use App\Http\Requests\UpdatePenilaianRequest;
use App\Models\Enrollment;
use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    /**
     * Halaman utama: daftar enrollment (peserta + program) yang sedang aktif di proyek mentor ini.
     */
    public function index(): View
    {
        $search = request('search');

        // Ambil ID peserta yang ada di proyek mentor ini
        $pesertaIds = User::where('role', 'peserta_didik')
            ->whereHas('proyekDiikuti', function ($query) {
                $query->where('proyek.user_id', Auth::id());
            })
            ->pluck('id');

        // Ambil semua enrollment aktif dari peserta-peserta tersebut
        $enrollments = Enrollment::whereIn('user_id', $pesertaIds)
            ->where('status', 'aktif')
            ->with(['peserta', 'programPelatihan', 'jenisKelas', 'penilaian' => function ($q) {
                $q->where('mentor_id', Auth::id())->orderBy('bulan_ke');
            }])
            ->when($search, function ($query, $search) {
                $query->whereHas('peserta', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%");
                })->orWhereHas('programPelatihan', function ($q) use ($search) {
                    $q->where('nama_program', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('mentor.penilaian.index', compact('enrollments'));
    }

    /**
     * Halaman detail: semua penilaian (per bulan_ke) untuk satu enrollment tertentu.
     */
    public function detail(Enrollment $enrollment): View
    {
        // Pastikan peserta dari enrollment ini ada di proyek mentor
        $isEligible = $enrollment->peserta->proyekDiikuti()
            ->where('proyek.user_id', Auth::id())
            ->exists();
            
        abort_unless($isEligible, 403, 'Peserta tidak terdaftar di proyek Anda.');

        $penilaians = Penilaian::where('enrollment_id', $enrollment->id)
            ->where('mentor_id', Auth::id())
            ->orderBy('bulan_ke')
            ->get();

        $maxBulan = $enrollment->getDurasiBulan() ?: 6;

        return view('mentor.penilaian.detail', compact('enrollment', 'penilaians', 'maxBulan'));
    }

    /**
     * Form buat penilaian baru.
     */
    public function create(): View
    {
        $pesertaIds = User::where('role', 'peserta_didik')
            ->whereHas('proyekDiikuti', function ($query) {
                $query->where('proyek.user_id', Auth::id());
            })
            ->pluck('id');

        $enrollments = Enrollment::whereIn('user_id', $pesertaIds)
            ->where('status', 'aktif')
            ->with(['peserta', 'programPelatihan', 'jenisKelas'])
            ->get();

        $durasiMap = $enrollments->mapWithKeys(fn($e) => [
            $e->id => $e->getDurasiBulan() ?: 6,
        ]);

        $selectedEnrollmentId = request('enrollment_id');
        
        $nextBulan = 1;
        if ($selectedEnrollmentId) {
            $penilaiansAda = Penilaian::where('enrollment_id', $selectedEnrollmentId)
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

        return view('mentor.penilaian.create', compact('enrollments', 'durasiMap', 'selectedEnrollmentId', 'nextBulan'));
    }

    public function store(StorePenilaianRequest $request): RedirectResponse
    {
        $enrollment = Enrollment::with('peserta')->findOrFail($request->enrollment_id);
        
        // Pastikan peserta dari enrollment ini terdaftar di proyek mentor
        if (!$enrollment->peserta->proyekDiikuti()->where('proyek.user_id', Auth::id())->exists()) {
            return back()->with('error', 'Peserta tidak terdaftar di proyek Anda.');
        }

        Penilaian::create([
            ...$request->validated(),
            'mentor_id' => Auth::id(),
            'peserta_id' => $enrollment->user_id, // keep it for redundancy / compatibility if needed, though enrollment_id is enough
        ]);

        return redirect()->route('mentor.penilaian.detail', $enrollment)
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    public function edit(Penilaian $penilaian): View
    {
        $this->authorize('update', $penilaian);

        $pesertaIds = User::where('role', 'peserta_didik')
            ->whereHas('proyekDiikuti', function ($query) {
                $query->where('proyek.user_id', Auth::id());
            })
            ->pluck('id');

        $enrollments = Enrollment::whereIn('user_id', $pesertaIds)
            ->where('status', 'aktif')
            ->with(['peserta', 'programPelatihan', 'jenisKelas'])
            ->get();

        $durasiMap = $enrollments->mapWithKeys(fn($e) => [
            $e->id => $e->getDurasiBulan() ?: 6,
        ]);

        return view('mentor.penilaian.edit', compact('penilaian', 'enrollments', 'durasiMap'));
    }

    public function update(UpdatePenilaianRequest $request, Penilaian $penilaian): RedirectResponse
    {
        $this->authorize('update', $penilaian);
        
        $enrollment = Enrollment::findOrFail($request->enrollment_id);
        $data = $request->validated();
        $data['peserta_id'] = $enrollment->user_id;

        $penilaian->update($data);

        return redirect()->route('mentor.penilaian.detail', $enrollment)
            ->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function destroy(Penilaian $penilaian): RedirectResponse
    {
        $this->authorize('delete', $penilaian);
        $enrollmentId = $penilaian->enrollment_id;
        $penilaian->delete();

        $enrollment = Enrollment::findOrFail($enrollmentId);
        return redirect()->route('mentor.penilaian.detail', $enrollment)
            ->with('success', 'Penilaian berhasil dihapus.');
    }
}
