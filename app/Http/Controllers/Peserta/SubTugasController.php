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
        abort_if($subTugas->tugas->user_id !== Auth::id(), 403, 'Akses ditolak.');
        
        if ($subTugas->tugas->proyek->status_proyek === 'selesai') {
            return back()->with('error', 'Proyek sudah selesai, Anda tidak dapat mengubah data.');
        }

        if ($subTugas->tugas->status_task === 'done') {
            return back()->with('error', 'Tugas utama sudah selesai, Anda tidak dapat mengubah sub-tugas.');
        }

        $subTugas->update(['is_selesai' => !$subTugas->is_selesai]);

        return back()->with('success', 'Status sub-tugas berhasil diperbarui.');
    }
}
