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
        $sertifikats = Sertifikat::with('mentor')
            ->where('peserta_id', Auth::id())
            ->latest('tgl_terbit')
            ->get();

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
