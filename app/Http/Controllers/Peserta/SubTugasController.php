<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\SubTugas;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SubTugasController extends Controller
{
    /**
     * Toggle status is_selesai dari sub-tugas.
     */
    public function toggle(SubTugas $subTugas): RedirectResponse
    {
        // Pastikan tugas induk milik peserta ini
        abort_if($subTugas->tugas->user_id !== Auth::id(), 403, 'Akses ditolak.');

        $subTugas->update([
            'is_selesai' => !$subTugas->is_selesai,
        ]);

        return back()->with('success', 'Status sub-tugas diperbarui.');
    }
}
