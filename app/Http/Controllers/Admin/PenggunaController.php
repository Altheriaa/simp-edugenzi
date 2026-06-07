<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function index(): View
    {
        $penggunas = User::latest()->paginate(10);

        return view('admin.pengguna.index', compact('penggunas'));
    }

    public function create(): View
    {
        return view('admin.pengguna.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna): View
    {
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    public function update(UpdateUserRequest $request, User $pengguna): RedirectResponse
    {
        $pengguna->update($request->validated());

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna): RedirectResponse
    {
        $pengguna->delete();

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
