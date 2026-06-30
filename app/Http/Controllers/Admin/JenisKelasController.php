<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisKelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JenisKelasController extends Controller
{
    public function index(): View
    {
        $jenisKelas = JenisKelas::withCount(['enrollments', 'programKelasDurasi'])
            ->orderBy('nama')
            ->get();

        return view('admin.jenis-kelas.index', compact('jenisKelas'));
    }

    public function create(): View
    {
        return view('admin.jenis-kelas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'slug' => $request->slug ?: Str::slug($request->nama)
        ]);

        $data = $request->validate([
            'nama' => 'required|string|max:50',
            'slug' => 'required|string|max:20|unique:jenis_kelas,slug',
            'is_aktif' => 'nullable|boolean',
        ]);

        $data['is_aktif'] = $request->has('is_aktif');

        JenisKelas::create($data);

        return redirect()->route('admin.jenis-kelas.index')
            ->with('success', 'Jenis Kelas berhasil ditambahkan.');
    }

    public function edit($id): View
    {
        $jenisKelas = JenisKelas::findOrFail($id);
        return view('admin.jenis-kelas.edit', compact('jenisKelas'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $jenisKelas = JenisKelas::findOrFail($id);

        $request->merge([
            'slug' => $request->slug ?: Str::slug($request->nama)
        ]);

        $data = $request->validate([
            'nama' => 'required|string|max:50',
            'slug' => 'required|string|max:20|unique:jenis_kelas,slug,' . $jenisKelas->id,
            'is_aktif' => 'nullable|boolean',
        ]);

        $data['is_aktif'] = $request->has('is_aktif');

        $jenisKelas->update($data);

        return redirect()->route('admin.jenis-kelas.index')
            ->with('success', 'Jenis Kelas berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $jenisKelas = JenisKelas::findOrFail($id);

        if ($jenisKelas->enrollments()->exists()) {
            return redirect()->route('admin.jenis-kelas.index')
                ->with('error', 'Tidak dapat menghapus jenis kelas karena masih memiliki peserta terdaftar.');
        }

        $jenisKelas->delete();

        return redirect()->route('admin.jenis-kelas.index')
            ->with('success', 'Jenis Kelas berhasil dihapus.');
    }
}
