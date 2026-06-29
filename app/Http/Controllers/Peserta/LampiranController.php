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
        
        if ($tugas->proyek->status_proyek === 'selesai') {
            return back()->withErrors(['file' => 'Proyek sudah selesai, Anda tidak dapat mengunggah lampiran.']);
        }

        if ($tugas->status_task === 'done') {
            return back()->with('error', 'Tugas yang sudah selesai tidak dapat ditambahkan lampiran.');
        }

        $request->validate([
            'file' => ['required', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png,zip'],
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
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
        abort_if($lampiran->tugas->user_id !== Auth::id(), 403, 'Akses ditolak.');
        
        if ($lampiran->tugas->proyek->status_proyek === 'selesai') {
            return back()->with('error', 'Proyek sudah selesai, Anda tidak dapat menghapus lampiran.');
        }

        if ($lampiran->tugas->status_task === 'done') {
            return back()->with('error', 'Lampiran dari tugas yang sudah selesai tidak dapat dihapus.');
        }

        Storage::disk('public')->delete($lampiran->path_file);
        $lampiran->delete();

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }
}
