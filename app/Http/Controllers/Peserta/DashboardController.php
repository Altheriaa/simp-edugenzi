<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $pesertaId = Auth::id();

        $totalTugas   = Tugas::where('user_id', $pesertaId)->count();
        $tugasTodo    = Tugas::where('user_id', $pesertaId)->where('status_task', 'to_do')->count();
        $tugasProses  = Tugas::where('user_id', $pesertaId)->where('status_task', 'in_progress')->count();
        $tugasSelesai = Tugas::where('user_id', $pesertaId)->where('status_task', 'done')->count();

        $recentTugas = Tugas::with('proyek')
            ->where('user_id', $pesertaId)
            ->latest()
            ->take(5)
            ->get();

        $evaluasis = Evaluasi::with(['proyek', 'mentor'])
            ->where('peserta_id', $pesertaId)
            ->latest()
            ->take(3)
            ->get();

        return view('peserta.dashboard', compact(
            'totalTugas',
            'tugasTodo',
            'tugasProses',
            'tugasSelesai',
            'recentTugas',
            'evaluasis',
        ));
    }
}
