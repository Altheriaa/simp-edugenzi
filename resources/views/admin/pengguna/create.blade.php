@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-2xl space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.pengguna.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Pengguna</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Isi form berikut untuk menambah pengguna baru</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('admin.pengguna.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                {{-- Nama Lengkap --}}
                <div class="sm:col-span-2">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nama_lengkap') border-red-400 @enderror"
                        placeholder="Nama lengkap pengguna" />
                    @error('nama_lengkap') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- NIK --}}
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nik') border-red-400 @enderror"
                        placeholder="NIK (16 digit)" />
                    @error('nik') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('email') border-red-400 @enderror"
                        placeholder="email@edugenzi.id" />
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        No HP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('no_hp') border-red-400 @enderror"
                        placeholder="08xxxxxxxxxx" />
                    @error('no_hp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('alamat') border-red-400 @enderror"
                        placeholder="Alamat lengkap" />
                    @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('password') border-red-400 @enderror"
                        placeholder="Min. 8 karakter" />
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        placeholder="Ulangi password" />
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('role') border-red-400 @enderror">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="mentor" @selected(old('role') === 'mentor')>Mentor</option>
                    </select>
                    @error('role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.pengguna.index') }}"
                   class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
