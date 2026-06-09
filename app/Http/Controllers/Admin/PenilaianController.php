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
        $query = Penilaian::with(['peserta', 'mentor'])->latest();

        // Filter berdasarkan peserta (opsional)
        if ($request->filled('peserta_id')) {
            $query->where('peserta_id', $request->peserta_id);
        }

        // Filter berdasarkan bulan_ke (opsional)
        if ($request->filled('bulan_ke')) {
            $query->where('bulan_ke', $request->bulan_ke);
        }

        $penilaians = $query->paginate(15)->withQueryString();
        $pesertas   = User::where('role', 'peserta_didik')->orderBy('nama_lengkap')->get();

        return view('admin.penilaian.index', compact('penilaians', 'pesertas'));
    }
}
