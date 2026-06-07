<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EvaluasiController extends Controller
{
    public function index(): View
    {
        $evaluasis = Evaluasi::with(['proyek', 'peserta'])
            ->where('mentor_id', Auth::id())
            ->latest()
            ->paginate(10);

        $proyeks = Proyek::where('user_id', Auth::id())->get();
        $pesertas = User::where('role', 'peserta_didik')->where('status', 'aktif')->get();

        return view('mentor.evaluasi.index', compact('evaluasis', 'proyeks', 'pesertas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'proyek_id'  => ['required', 'exists:proyek,id'],
            'peserta_id' => ['required', 'exists:users,id'],
            'catatan'    => ['required', 'string'],
        ]);

        // Pastikan proyek milik mentor ini
        $proyek = Proyek::where('id', $request->proyek_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Evaluasi::create([
            'proyek_id'  => $proyek->id,
            'mentor_id'  => Auth::id(),
            'peserta_id' => $request->peserta_id,
            'catatan'    => $request->catatan,
        ]);

        return back()->with('success', 'Evaluasi berhasil disimpan.');
    }
}
