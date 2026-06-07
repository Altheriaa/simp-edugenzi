<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi;
use App\Models\Proyek;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $mentorId = Auth::id();

        $totalProyek    = Proyek::where('user_id', $mentorId)->count();
        $proyekBerjalan = Proyek::where('user_id', $mentorId)->where('status_proyek', 'berjalan')->count();
        $proyekSelesai  = Proyek::where('user_id', $mentorId)->where('status_proyek', 'selesai')->count();

        $totalTugas   = Tugas::whereHas('proyek', fn($q) => $q->where('user_id', $mentorId))->count();
        $tugasSelesai = Tugas::whereHas('proyek', fn($q) => $q->where('user_id', $mentorId))
            ->where('status_task', 'done')->count();

        $recentProyek = Proyek::with(['tugas'])
            ->where('user_id', $mentorId)
            ->latest()
            ->take(5)
            ->get();

        $pesertaAktif = User::where('role', 'peserta_didik')
            ->where('status', 'aktif')
            ->count();

        return view('mentor.dashboard', compact(
            'totalProyek',
            'proyekBerjalan',
            'proyekSelesai',
            'totalTugas',
            'tugasSelesai',
            'recentProyek',
            'pesertaAktif',
        ));
    }
}
