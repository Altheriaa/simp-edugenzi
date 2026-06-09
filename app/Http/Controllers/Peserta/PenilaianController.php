<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(): View
    {
        $penilaians = Penilaian::with('mentor')
            ->where('peserta_id', Auth::id())
            ->orderByDesc('tahun')
            ->orderByRaw("FIELD(bulan, 'Desember','November','Oktober','September','Agustus','Juli','Juni','Mei','April','Maret','Februari','Januari')")
            ->paginate(6);

        return view('peserta.penilaian.index', compact('penilaians'));
    }
}
