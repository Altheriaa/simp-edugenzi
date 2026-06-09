<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSertifikatRequest;
use App\Http\Requests\UpdateSertifikatRequest;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SertifikatController extends Controller
{
    public function index(): View
    {
        $sertifikats = Sertifikat::with('peserta')
            ->where('mentor_id', Auth::id())
            ->latest()
            ->paginate(10);

        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $predikatList = ['Dengan Pujian', 'Sangat Memuaskan', 'Memuaskan', 'Cukup'];

        return view('mentor.sertifikat.index', compact('sertifikats', 'pesertas', 'predikatList'));
    }

    public function create(): View
    {
        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $predikatList = ['Dengan Pujian', 'Sangat Memuaskan', 'Memuaskan', 'Cukup'];

        return view('mentor.sertifikat.create', compact('pesertas', 'predikatList'));
    }

    public function store(StoreSertifikatRequest $request): RedirectResponse
    {
        // Generate nomor sertifikat otomatis: EDG/YYYY/XXXX
        $tahun  = date('Y', strtotime($request->tgl_terbit));
        $urutan = Sertifikat::whereYear('tgl_terbit', $tahun)->count() + 1;
        $nomor  = 'EDG/' . $tahun . '/' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

        Sertifikat::create([
            ...$request->validated(),
            'mentor_id'        => Auth::id(),
            'nomor_sertifikat' => $nomor,
        ]);

        return redirect()->route('mentor.sertifikat.index')
            ->with('success', 'Sertifikat berhasil diterbitkan.');
    }

    public function show(Sertifikat $sertifikat): View
    {
        $this->authorize('update', $sertifikat);
        $sertifikat->load(['peserta', 'mentor']);

        return view('mentor.sertifikat.show', compact('sertifikat'));
    }

    public function edit(Sertifikat $sertifikat): View
    {
        $this->authorize('update', $sertifikat);

        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $predikatList = ['Dengan Pujian', 'Sangat Memuaskan', 'Memuaskan', 'Cukup'];

        return view('mentor.sertifikat.edit', compact('sertifikat', 'pesertas', 'predikatList'));
    }

    public function update(UpdateSertifikatRequest $request, Sertifikat $sertifikat): RedirectResponse
    {
        $this->authorize('update', $sertifikat);
        $sertifikat->update($request->validated());

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
}
