<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Lampiran;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LampiranController extends Controller
{
    public function store(Request $request, Tugas $tugas): RedirectResponse
    {
        // Ensure the mentor is assigned to the project this task belongs to
        abort_if($tugas->proyek->user_id !== Auth::id(), 403, 'Akses ditolak.');
        
        if ($tugas->proyek->status_proyek === 'selesai') {
            return back()->withErrors(['file' => 'Proyek sudah selesai, Anda tidak dapat mengunggah lampiran.']);
        }

        $request->validate([
            'file' => ['required', 'file', 'max:5120', 'mimes:pdf'], // As requested, PDF upload for mentor
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
            'file.mimes'    => 'Tipe file harus berupa PDF.',
        ]);

        $file     = $request->file('file');
        $namaAsli = $file->getClientOriginalName();
        $namaSimpan = time() . '_' . $namaAsli;
        $path     = $file->storeAs('lampiran', $namaSimpan, 'public');

        Lampiran::create([
            'task_id'     => $tugas->id,
            'nama_file'   => $namaAsli,
            'path_file'   => $path,
            'tipe_file'   => $file->getClientOriginalExtension(),
            'ukuran_file' => (int) ceil($file->getSize() / 1024),
            'uploaded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Panduan (PDF) berhasil diunggah.');
    }

    public function destroy(Lampiran $lampiran): RedirectResponse
    {
        abort_if($lampiran->tugas->proyek->user_id !== Auth::id(), 403, 'Akses ditolak.');
        
        if ($lampiran->tugas->proyek->status_proyek === 'selesai') {
            return back()->with('error', 'Proyek sudah selesai, Anda tidak dapat menghapus lampiran.');
        }

        Storage::disk('public')->delete($lampiran->path_file);
        $lampiran->delete();

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }
}
