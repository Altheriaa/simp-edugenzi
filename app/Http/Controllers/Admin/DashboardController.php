<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPengguna  = User::count();
        $totalProyek    = Proyek::count();
        $totalTugas     = Tugas::count();
        $proyekBerjalan = Proyek::where('status_proyek', 'berjalan')->count();
        $proyekSelesai  = Proyek::where('status_proyek', 'selesai')->count();
        $proyekTertunda = Proyek::where('status_proyek', 'tertunda')->count();

        $recentProyek = Proyek::with('mentor')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPengguna',
            'totalProyek',
            'totalTugas',
            'proyekBerjalan',
            'proyekSelesai',
            'proyekTertunda',
            'recentProyek',
        ));
    }
}
