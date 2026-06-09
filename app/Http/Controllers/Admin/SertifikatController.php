<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SertifikatController extends Controller
{
    public function index(Request $request): View
    {
        $query = Sertifikat::with(['peserta', 'mentor'])
            ->latest('tgl_terbit');

        // Filter berdasarkan peserta (opsional)
        if ($request->filled('peserta_id')) {
            $query->where('peserta_id', $request->peserta_id);
        }

        // Filter berdasarkan predikat (opsional)
        if ($request->filled('predikat')) {
            $query->where('predikat', $request->predikat);
        }

        $sertifikats  = $query->paginate(15)->withQueryString();
        $pesertas     = User::where('role', 'peserta_didik')->orderBy('nama_lengkap')->get();
        $predikatList = ['Dengan Pujian', 'Sangat Memuaskan', 'Memuaskan', 'Cukup'];

        return view('admin.sertifikat.index', compact('sertifikats', 'pesertas', 'predikatList'));
    }
}
