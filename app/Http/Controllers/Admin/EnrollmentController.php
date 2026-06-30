<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\JenisKelas;
use App\Models\ProgramKelasDurasi;
use App\Models\ProgramPelatihan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(): View
    {
        $search = request('search');
        $statusFilter = request('status');

        $enrollments = Enrollment::with(['peserta', 'programPelatihan', 'jenisKelas'])
            ->when($search, function ($query, $search) {
                $query->whereHas('peserta', fn($q) =>
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('no_registrasi', 'like', "%{$search}%")
                )->orWhereHas('programPelatihan', fn($q) =>
                    $q->where('nama_program', 'like', "%{$search}%")
                );
            })
            ->when($statusFilter, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.enrollment.index', compact('enrollments'));
    }

    public function create(): View
    {
        $pesertas    = User::where('role', 'peserta_didik')->where('status', 'aktif')->orderBy('nama_lengkap')->get();
        $programs    = ProgramPelatihan::aktif()->orderBy('nama_program')->get();
        $jenisKelas  = JenisKelas::aktif()->get();
        $optionsJson = $this->buildOptionsJson();

        return view('admin.enrollment.create', compact('pesertas', 'programs', 'jenisKelas', 'optionsJson'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id'              => ['required', 'exists:users,id'],
            'program_pelatihan_id' => ['required', 'exists:program_pelatihans,id'],
            'jenis_kelas_id'       => ['nullable', 'exists:jenis_kelas,id'],
            'durasi_pelatihan'     => ['nullable', 'string', 'max:50'],
            'tgl_daftar'           => ['required', 'date'],
        ]);

        // Cek apakah peserta sudah aktif di program yang sama
        $existing = Enrollment::where('user_id', $data['user_id'])
            ->where('program_pelatihan_id', $data['program_pelatihan_id'])
            ->where('status', 'aktif')
            ->first();

        if ($existing) {
            return back()->with('error', 'Peserta ini sudah aktif di program tersebut.');
        }

        $data['status'] = 'aktif';
        Enrollment::create($data);

        return redirect()->route('admin.enrollment.index')
            ->with('success', 'Peserta berhasil didaftarkan ke program pelatihan.');
    }

    public function updateStatus(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:aktif,selesai'],
        ]);

        $enrollment->update(['status' => $request->status]);

        return back()->with('success', 'Status enrollment berhasil diperbarui.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()->route('admin.enrollment.index')
            ->with('success', 'Enrollment berhasil dihapus.');
    }

    /** Bangun mapping JSON: { programId: { kelasId: ['3 Bulan', ...] } } */
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
