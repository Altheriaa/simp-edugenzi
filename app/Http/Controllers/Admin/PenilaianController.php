<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(Request $request): View
    {
        $query = Penilaian::with(['peserta.programPelatihan', 'peserta.jenisKelas', 'mentor'])->latest();

        // Search berdasarkan nama peserta atau mentor
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('peserta', fn($sub) => $sub->where('nama_lengkap', 'like', "%{$search}%"))
                  ->orWhereHas('mentor', fn($sub) => $sub->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        // Filter berdasarkan peserta (opsional)
        if ($request->filled('peserta_id')) {
            $query->where('peserta_id', $request->peserta_id);
        }

        // Filter berdasarkan bulan_ke (opsional)
        if ($request->filled('bulan_ke')) {
            $query->where('bulan_ke', $request->bulan_ke);
        }

        $penilaians = $query->paginate(15)->withQueryString();
        $pesertas   = User::where('role', 'peserta_didik')->with(['programPelatihan', 'jenisKelas'])->orderBy('nama_lengkap')->get();

        return view('admin.penilaian.index', compact('penilaians', 'pesertas'));
    }
}
