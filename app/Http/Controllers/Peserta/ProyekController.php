<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProyekController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $search = request('search');
        
        // Dapatkan proyek yang diikuti beserta relasi terkait
        $proyeks = $user->proyekDiikuti()
            ->when($search, function ($query, $search) {
                $query->where('nama_proyek', 'like', "%{$search}%");
            })
            ->with(['mentor', 'programPelatihan', 'jenisKelas'])
            ->orderBy('created_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        return view('peserta.proyek.index', compact('proyeks'));
    }

    public function show(Proyek $proyek): View
    {
        // Pastikan user benar-benar tergabung di proyek ini
        if (!$proyek->peserta()->where('user_id', Auth::id())->exists()) {
            abort(403, 'Anda tidak terdaftar di proyek ini.');
        }

        $proyek->load(['mentor', 'programPelatihan', 'jenisKelas']);
        
        // Ambil tugas yang ditugaskan ke user di proyek ini
        $tugasList = $proyek->tugas()
            ->where('user_id', Auth::id())
            ->withCount('subTugas')
            ->get();

        return view('peserta.proyek.show', compact('proyek', 'tugasList'));
    }
}
