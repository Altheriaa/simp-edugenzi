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
        $search = request('search');

        $sertifikats = Sertifikat::with('peserta')
            ->where('mentor_id', Auth::id())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_sertifikat', 'like', "%{$search}%")
                      ->orWhere('nama_program', 'like', "%{$search}%")
                      ->orWhere('predikat', 'like', "%{$search}%")
                      ->orWhereHas('peserta', fn($sub) => $sub->where('nama_lengkap', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->whereHas('proyekDiikuti', function ($query) {
                $query->where('proyek.user_id', Auth::id());
            })
            ->orderBy('nama_lengkap')
            ->get();

        $predikatList = ['Dengan Pujian', 'Sangat Memuaskan', 'Memuaskan', 'Cukup'];

        return view('mentor.sertifikat.index', compact('sertifikats', 'pesertas', 'predikatList'));
    }

    public function create(): View
    {
        $pesertas = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->whereHas('proyekDiikuti', function ($query) {
                $query->where('proyek.user_id', Auth::id());
            })
            ->orderBy('nama_lengkap')
            ->get();

        $predikatList = ['Dengan Pujian', 'Sangat Memuaskan', 'Memuaskan', 'Cukup'];

        return view('mentor.sertifikat.create', compact('pesertas', 'predikatList'));
    }

    public function store(StoreSertifikatRequest $request): RedirectResponse
    {
        $peserta = User::with('programPelatihan')->findOrFail($request->peserta_id);
        
        if (!$peserta->proyekDiikuti()->where('proyek.user_id', Auth::id())->exists()) {
            return back()->with('error', 'Peserta tidak terdaftar di proyek Anda.');
        }

        // Generate nomor sertifikat otomatis: EDG/YYYY/XXXX
        $tahun  = date('Y', strtotime($request->tgl_terbit));
        $lastSertifikat = Sertifikat::whereYear('tgl_terbit', $tahun)->orderBy('id', 'desc')->first();
        if ($lastSertifikat) {
            $parts = explode('/', $lastSertifikat->nomor_sertifikat);
            $urutan = intval(end($parts)) + 1;
        } else {
            $urutan = 1;
        }
        $nomor  = 'EDG/' . $tahun . '/' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

        $namaProgram = $peserta->programPelatihan ? $peserta->programPelatihan->nama_program : '-';

        Sertifikat::create([
            ...$request->validated(),
            'nama_program'     => $namaProgram,
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
            ->whereHas('proyekDiikuti', function ($query) {
                $query->where('proyek.user_id', Auth::id());
            })
            ->orderBy('nama_lengkap')
            ->get();

        $predikatList = ['Dengan Pujian', 'Sangat Memuaskan', 'Memuaskan', 'Cukup'];

        return view('mentor.sertifikat.edit', compact('sertifikat', 'pesertas', 'predikatList'));
    }

    public function update(UpdateSertifikatRequest $request, Sertifikat $sertifikat): RedirectResponse
    {
        $this->authorize('update', $sertifikat);

        $peserta = User::with('programPelatihan')->findOrFail($request->peserta_id);
        $namaProgram = $peserta->programPelatihan ? $peserta->programPelatihan->nama_program : '-';

        $sertifikat->update([
            ...$request->validated(),
            'nama_program' => $namaProgram,
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
}
