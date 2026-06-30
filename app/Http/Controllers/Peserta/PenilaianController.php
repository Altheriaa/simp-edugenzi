<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(): View
    {
        // Ambil semua enrollment aktif & selesai milik peserta ini beserta penilaians
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with(['programPelatihan', 'penilaian' => function ($q) {
                $q->with('mentor')->orderBy('bulan_ke');
            }])
            ->orderByDesc('tgl_daftar')
            ->get();

        return view('peserta.penilaian.index', compact('enrollments'));
    }
}
