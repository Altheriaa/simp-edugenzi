<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisKelas;
use App\Models\ProgramKelasDurasi;
use App\Models\ProgramPelatihan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramPelatihanController extends Controller
{
    public function index(): View
    {
        $programs = ProgramPelatihan::withCount(['users', 'kelasDurasi'])
            ->orderBy('nama_program')
            ->get();

        return view('admin.program-pelatihan.index', compact('programs'));
    }

    public function create(): View
    {
        return view('admin.program-pelatihan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_program' => 'required|string|max:150|unique:program_pelatihans,nama_program',
            'is_aktif' => 'nullable|boolean',
        ]);

        $data['is_aktif'] = $request->has('is_aktif');

        ProgramPelatihan::create($data);

        return redirect()->route('admin.program-pelatihan.index')
            ->with('success', 'Program Pelatihan berhasil ditambahkan.');
    }

    public function edit(ProgramPelatihan $programPelatihan): View
    {
        $jenisKelas = JenisKelas::aktif()->orderBy('nama')->get();
        $combinations = $programPelatihan->kelasDurasi()->with('jenisKelas')->get();

        return view('admin.program-pelatihan.edit', [
            'program' => $programPelatihan,
            'jenisKelas' => $jenisKelas,
            'combinations' => $combinations,
        ]);
    }

    public function update(Request $request, ProgramPelatihan $programPelatihan): RedirectResponse
    {
        $data = $request->validate([
            'nama_program' => 'required|string|max:150|unique:program_pelatihans,nama_program,' . $programPelatihan->id,
            'is_aktif' => 'nullable|boolean',
        ]);

        $data['is_aktif'] = $request->has('is_aktif');

        $programPelatihan->update($data);

        return redirect()->route('admin.program-pelatihan.index')
            ->with('success', 'Program Pelatihan berhasil diperbarui.');
    }

    public function destroy(ProgramPelatihan $programPelatihan): RedirectResponse
    {
        if ($programPelatihan->users()->exists()) {
            return redirect()->route('admin.program-pelatihan.index')
                ->with('error', 'Tidak dapat menghapus program pelatihan karena masih memiliki peserta terdaftar.');
        }

        $programPelatihan->delete();

        return redirect()->route('admin.program-pelatihan.index')
            ->with('success', 'Program Pelatihan berhasil dihapus.');
    }

    /** Add a duration combination to the program */
    public function addDurasi(Request $request, ProgramPelatihan $program): RedirectResponse
    {
        $data = $request->validate([
            'jenis_kelas_id' => 'required|exists:jenis_kelas,id',
            'durasi_pelatihan' => 'required|string|max:50',
        ]);

        // Clean duration input whitespace
        $data['durasi_pelatihan'] = trim($data['durasi_pelatihan']);

        // Check if combination already exists
        $exists = ProgramKelasDurasi::where([
            'program_pelatihan_id' => $program->id,
            'jenis_kelas_id' => $data['jenis_kelas_id'],
            'durasi_pelatihan' => $data['durasi_pelatihan'],
        ])->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Kombinasi kelas dan durasi ini sudah terdaftar untuk program ini.')
                ->withInput();
        }

        ProgramKelasDurasi::create([
            'program_pelatihan_id' => $program->id,
            'jenis_kelas_id' => $data['jenis_kelas_id'],
            'durasi_pelatihan' => $data['durasi_pelatihan'],
        ]);

        return redirect()->back()->with('success', 'Kombinasi kelas dan durasi berhasil ditambahkan.');
    }

    /** Remove a duration combination */
    public function removeDurasi($id): RedirectResponse
    {
        $combination = ProgramKelasDurasi::findOrFail($id);
        $combination->delete();

        return redirect()->back()->with('success', 'Kombinasi kelas dan durasi berhasil dihapus.');
    }
}
