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
        $query = Penilaian::with(['peserta', 'mentor'])
            ->latest();

        // Filter berdasarkan peserta (opsional)
        if ($request->filled('peserta_id')) {
            $query->where('peserta_id', $request->peserta_id);
        }

        // Filter berdasarkan bulan (opsional)
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        $penilaians = $query->paginate(15)->withQueryString();

        $pesertas  = User::where('role', 'peserta_didik')->orderBy('nama_lengkap')->get();
        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        return view('admin.penilaian.index', compact('penilaians', 'pesertas', 'bulanList'));
    }
}
