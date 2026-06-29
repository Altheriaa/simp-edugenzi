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
        $search = request('search');

        $evaluasis = Evaluasi::with(['proyek', 'peserta'])
            ->where('mentor_id', Auth::id())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('catatan', 'like', "%{$search}%")
                      ->orWhereHas('peserta', fn($sub) => $sub->where('nama_lengkap', 'like', "%{$search}%"))
                      ->orWhereHas('proyek', fn($sub) => $sub->where('nama_proyek', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $proyeks = Proyek::with(['peserta' => function ($q) {
            $q->where('status', 'aktif')->orderBy('nama_lengkap');
        }])->where('user_id', Auth::id())->get();

        $pesertaMap = [];
        foreach ($proyeks as $proyek) {
            $pesertaMap[$proyek->id] = $proyek->peserta->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama_lengkap' => $p->no_registrasi . ' - ' . $p->nama_lengkap
                ];
            })->toArray();
        }
        $pesertaMapJson = json_encode($pesertaMap);

        return view('mentor.evaluasi.index', compact('evaluasis', 'proyeks', 'pesertaMapJson'));
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

        // Pastikan peserta terdaftar di proyek ini
        if (!$proyek->peserta()->where('users.id', $request->peserta_id)->exists()) {
            return back()->with('error', 'Peserta tidak terdaftar di proyek ini.');
        }

        Evaluasi::create([
            'proyek_id'  => $proyek->id,
            'mentor_id'  => Auth::id(),
            'peserta_id' => $request->peserta_id,
            'catatan'    => $request->catatan,
        ]);

        return back()->with('success', 'Evaluasi berhasil disimpan.');
    }
}
