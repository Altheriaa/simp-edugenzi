<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->search;

        $enrollments = Enrollment::where('status', 'aktif')
            ->with(['peserta', 'programPelatihan', 'jenisKelas', 'penilaian' => function ($q) {
                $q->orderBy('bulan_ke');
            }])
            ->when($search, function ($query, $search) {
                $query->whereHas('peserta', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%");
                })->orWhereHas('programPelatihan', function ($q) use ($search) {
                    $q->where('nama_program', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.penilaian.index', compact('enrollments'));
    }

    public function detail(Enrollment $enrollment): View
    {
        $penilaians = Penilaian::where('enrollment_id', $enrollment->id)
            ->orderBy('bulan_ke')
            ->get();

        $maxBulan = $enrollment->getDurasiBulan() ?: 6;

        return view('admin.penilaian.detail', compact('enrollment', 'penilaians', 'maxBulan'));
    }
}
