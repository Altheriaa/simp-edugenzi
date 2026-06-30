<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSertifikatRequest;
use App\Http\Requests\UpdateSertifikatRequest;
use App\Models\Enrollment;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SertifikatController extends Controller
{
    public function index(): View
    {
        $search = request('search');

        $sertifikats = Sertifikat::with('peserta')
            ->where('mentor_id', Auth::id())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_sertifikat', 'like', "%{$search}%")
                      ->orWhere('nama_program', 'like', "%{$search}%")
                      ->orWhereHas('peserta', fn($sub) => $sub->where('nama_lengkap', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Ambil enrollments peserta di proyek mentor yang nilai lengkap & belum ada sertifikat
        $enrollmentsEligible = $this->getEligibleEnrollments();

        return view('mentor.sertifikat.index', compact('sertifikats', 'enrollmentsEligible'));
    }

    public function create(): View
    {
        $enrollmentsEligible = $this->getEligibleEnrollments();

        return view('mentor.sertifikat.create', compact('enrollmentsEligible'));
    }

    public function store(StoreSertifikatRequest $request): RedirectResponse
    {
        $enrollment = Enrollment::with(['peserta', 'programPelatihan'])
            ->findOrFail($request->enrollment_id);

        // Pastikan peserta ini ada di proyek mentor
        if (!$enrollment->peserta->proyekDiikuti()->where('proyek.user_id', Auth::id())->exists()) {
            return back()->with('error', 'Peserta tidak terdaftar di proyek Anda.');
        }

        // Pastikan nilai sudah lengkap
        if (!$enrollment->isPenilaianLengkap()) {
            return back()->with('error', 'Nilai peserta belum lengkap untuk program ini.');
        }

        // Cek duplikat sertifikat untuk enrollment yang sama
        if ($enrollment->sertifikat()->exists()) {
            return back()->with('error', 'Sertifikat untuk enrollment ini sudah ada.');
        }

        $namaProgram = $enrollment->programPelatihan->nama_program ?? '-';

        // Generate nomor sertifikat otomatis: EDG/YYYY/XXXX
        $tahun = date('Y', strtotime($request->tgl_terbit));
        $lastSertifikat = Sertifikat::whereYear('tgl_terbit', $tahun)->orderBy('id', 'desc')->first();
        if ($lastSertifikat) {
            $parts  = explode('/', $lastSertifikat->nomor_sertifikat);
            $urutan = intval(end($parts)) + 1;
        } else {
            $urutan = 1;
        }
        $nomor = 'EDG/' . $tahun . '/' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

        Sertifikat::create([
            ...$request->validated(),
            'enrollment_id'    => $enrollment->id,
            'peserta_id'       => $enrollment->user_id,
            'nama_program'     => $namaProgram,
            'mentor_id'        => Auth::id(),
            'nomor_sertifikat' => $nomor,
        ]);

        // Tandai enrollment sebagai selesai
        $enrollment->update(['status' => 'selesai']);

        return redirect()->route('mentor.sertifikat.index')
            ->with('success', 'Sertifikat berhasil diterbitkan.');
    }

    public function show(Sertifikat $sertifikat): View
    {
        $this->authorize('update', $sertifikat);
        $sertifikat->load(['peserta', 'mentor', 'enrollment.programPelatihan']);

        return view('mentor.sertifikat.show', compact('sertifikat'));
    }

    public function edit(Sertifikat $sertifikat): View
    {
        $this->authorize('update', $sertifikat);

        $enrollmentsEligible = $this->getEligibleEnrollments($sertifikat);

        return view('mentor.sertifikat.edit', compact('sertifikat', 'enrollmentsEligible'));
    }

    public function update(UpdateSertifikatRequest $request, Sertifikat $sertifikat): RedirectResponse
    {
        $this->authorize('update', $sertifikat);

        $enrollment = Enrollment::with(['peserta', 'programPelatihan'])
            ->findOrFail($request->enrollment_id);

        // Cek duplikat jika enrollment diubah
        if ($enrollment->id !== $sertifikat->enrollment_id) {
            if ($enrollment->sertifikat()->exists()) {
                return back()->with('error', 'Sertifikat untuk program tersebut sudah ada.');
            }
        }

        $namaProgram = $enrollment->programPelatihan->nama_program ?? '-';

        $sertifikat->update([
            ...$request->validated(),
            'enrollment_id' => $enrollment->id,
            'peserta_id'    => $enrollment->user_id,
            'nama_program'  => $namaProgram,
        ]);

        return redirect()->route('mentor.sertifikat.index')
            ->with('success', 'Sertifikat berhasil diperbarui.');
    }

    public function destroy(Sertifikat $sertifikat): RedirectResponse
    {
        $this->authorize('delete', $sertifikat);
        $sertifikat->delete();

        return redirect()->route('mentor.sertifikat.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    /**
     * Ambil semua enrollment yang:
     * - Peserta terdaftar di proyek mentor ini
     * - Nilai sudah lengkap
     * - Belum ada sertifikat
     * Jika ada sertifikat existing (edit mode), sertakan enrollment-nya.
     */
    private function getEligibleEnrollments(?Sertifikat $existing = null)
    {
        $mentorId = Auth::id();

        // Ambil semua peserta yang ada di proyek mentor ini
        $pesertaIds = User::where('role', 'peserta_didik')
            ->whereHas('proyekDiikuti', fn($q) => $q->where('proyek.user_id', $mentorId))
            ->pluck('id');

        return Enrollment::with(['peserta', 'programPelatihan', 'jenisKelas'])
            ->whereIn('user_id', $pesertaIds)
            ->where('status', 'aktif')
            ->get()
            ->filter(function (Enrollment $enrollment) use ($existing) {
                // Selalu sertakan enrollment milik sertifikat yang sedang diedit
                if ($existing && $enrollment->id === $existing->enrollment_id) {
                    return true;
                }
                // Harus nilai lengkap dan belum ada sertifikat
                return $enrollment->isPenilaianLengkap() && !$enrollment->sertifikat()->exists();
            });
    }
}
