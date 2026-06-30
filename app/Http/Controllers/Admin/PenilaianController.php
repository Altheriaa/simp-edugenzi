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
        $query = Penilaian::with(['enrollment.peserta', 'enrollment.programPelatihan', 'enrollment.jenisKelas', 'mentor'])->latest();

        // Search berdasarkan nama peserta atau mentor
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('peserta', fn($sub) => $sub->where('nama_lengkap', 'like', "%{$search}%"))
                  ->orWhereHas('mentor', fn($sub) => $sub->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        // Filter berdasarkan enrollment (opsional)
        if ($request->filled('enrollment_id')) {
            $query->where('enrollment_id', $request->enrollment_id);
        }

        // Filter berdasarkan bulan_ke (opsional)
        if ($request->filled('bulan_ke')) {
            $query->where('bulan_ke', $request->bulan_ke);
        }

        $penilaians = $query->paginate(15)->withQueryString();
        $enrollments = Enrollment::with(['peserta', 'programPelatihan', 'jenisKelas'])
            ->whereHas('peserta', fn($q) => $q->orderBy('nama_lengkap'))
            ->get();

        return view('admin.penilaian.index', compact('penilaians', 'enrollments'));
    }
}
