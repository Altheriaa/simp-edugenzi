<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\JenisKelas;
use App\Models\ProgramKelasDurasi;
use App\Models\ProgramPelatihan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function index(): View
    {
        $search = request('search');

        $penggunas = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('no_registrasi', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengguna.index', compact('penggunas'));
    }

    public function create(): View
    {
        $programs    = ProgramPelatihan::aktif()->orderBy('nama_program')->get();
        $jenisKelas  = JenisKelas::aktif()->get();
        $optionsJson = $this->buildOptionsJson();

        return view('admin.pengguna.create', compact('programs', 'jenisKelas', 'optionsJson'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($data['role'] === 'peserta_didik') {
            $count = User::where('role', 'peserta_didik')->count() + 1;
            $data['no_registrasi'] = 'EDU' . '-' . time() . str_pad($count, 4, '0', STR_PAD_LEFT);
        }

        User::create($data);

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna): View
    {
        $programs    = ProgramPelatihan::aktif()->orderBy('nama_program')->get();
        $jenisKelas  = JenisKelas::aktif()->get();
        $optionsJson = $this->buildOptionsJson();

        return view('admin.pengguna.edit', compact('pengguna', 'programs', 'jenisKelas', 'optionsJson'));
    }

    public function update(UpdateUserRequest $request, User $pengguna): RedirectResponse
    {
        $pengguna->update($request->validated());

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna): RedirectResponse
    {
        $pengguna->delete();

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Bangun mapping JSON: { programId: { kelasId: ['1 Bulan', ...] } }
     * Digunakan oleh Alpine.js di form create/edit untuk dropdown dinamis.
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
