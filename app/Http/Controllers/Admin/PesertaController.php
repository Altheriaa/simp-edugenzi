<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PesertaController extends Controller
{
    public function index(): View
    {
        $search = request('search');

        $penggunas = User::query()
            ->where('role', 'peserta_didik')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('no_registrasi', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('role', 'like', "%{$search}%");
                });
            }) 
            ->with(['enrollments'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.peserta-didik.index', compact('penggunas'));
    }

    public function create(): View
    {
        return view('admin.peserta-didik.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['role'] = 'peserta_didik';
        $nextId = (User::max('id') ?? 0) + 1;
        $data['no_registrasi'] = 'EDU-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        User::create($data);

        return redirect()->route('admin.peserta-didik.index')
            ->with('success', 'Peserta Didik berhasil ditambahkan.');
    }

    public function edit(User $pengguna): View
    {
        return view('admin.peserta-didik.edit', compact('pengguna'));
    }

    public function update(UpdateUserRequest $request, User $pengguna): RedirectResponse
    {
        $data = $request->validated();
        $data['role'] = 'peserta_didik'; // just in case
        $pengguna->update($data);

        return redirect()->route('admin.peserta-didik.index')
            ->with('success', 'Data Peserta Didik berhasil diperbarui.');
    }

    public function destroy(User $pengguna): RedirectResponse
    {
        $pengguna->delete();

        return redirect()->route('admin.peserta-didik.index')
            ->with('success', 'Peserta Didik berhasil dihapus.');
    }
}
