<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SertifikatController extends Controller
{
    public function index(): View
    {
        $search = request('search');

        $sertifikats = Sertifikat::with('mentor')
            ->where('peserta_id', Auth::id())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_sertifikat', 'like', "%{$search}%")
                      ->orWhere('nama_program', 'like', "%{$search}%")
                      ->orWhere('predikat', 'like', "%{$search}%");
                });
            })
            ->latest('tgl_terbit')
            ->paginate(9)
            ->withQueryString();

        return view('peserta.sertifikat.index', compact('sertifikats'));
    }

    public function print(Sertifikat $sertifikat): View
    {
        // Pastikan peserta hanya bisa print sertifikat miliknya sendiri
        abort_if($sertifikat->peserta_id !== Auth::id(), 403);

        $sertifikat->load(['peserta', 'mentor']);

        return view('peserta.sertifikat.print', compact('sertifikat'));
    }
}
