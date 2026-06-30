<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SertifikatController extends Controller
{
    public function index(Request $request): View
    {
        $query = Sertifikat::with(['peserta', 'mentor'])
            ->latest('tgl_terbit');

        // Search berdasarkan nama peserta, nomor sertifikat, nama program
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sertifikat', 'like', "%{$search}%")
                  ->orWhere('nama_program', 'like', "%{$search}%")
                  ->orWhereHas('peserta', fn($sub) => $sub->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        // Filter berdasarkan peserta (opsional)
        if ($request->filled('peserta_id')) {
            $query->where('peserta_id', $request->peserta_id);
        }

        $sertifikats  = $query->paginate(15)->withQueryString();
        $pesertas     = User::where('role', 'peserta_didik')->orderBy('nama_lengkap')->get();

        return view('admin.sertifikat.index', compact('sertifikats', 'pesertas'));
    }

    public function print(Sertifikat $sertifikat): View
    {
        $sertifikat->load(['peserta', 'mentor']);

        return view('peserta.sertifikat.print', compact('sertifikat'));
    }
}
