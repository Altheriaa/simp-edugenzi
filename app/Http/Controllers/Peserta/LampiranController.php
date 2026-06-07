<?php

namespace App\Http\Controllers\Peserta;

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
        abort_if($tugas->user_id !== Auth::id(), 403, 'Akses ditolak.');

        $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,zip'],
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.max'      => 'Ukuran file maksimal 10MB.',
            'file.mimes'    => 'Tipe file harus: pdf, jpg, jpeg, png, atau zip.',
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

        return back()->with('success', 'File berhasil diunggah.');
    }

    public function destroy(Lampiran $lampiran): RedirectResponse
    {
        abort_if($lampiran->uploaded_by !== Auth::id(), 403, 'Akses ditolak.');

        Storage::disk('public')->delete($lampiran->path_file);
        $lampiran->delete();

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }
}
