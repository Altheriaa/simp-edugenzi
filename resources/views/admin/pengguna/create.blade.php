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
                <div x-data="{ role: '{{ old('role') }}' }">
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" x-model="role"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('role') border-red-400 @enderror">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="mentor" @selected(old('role') === 'mentor')>Mentor</option>
                        <option value="peserta_didik" @selected(old('role') === 'peserta_didik')>Peserta Didik</option>
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

                {{-- Bidang Pelatihan (hanya muncul jika role = peserta_didik) --}}
                <div class="sm:col-span-2 border-t border-gray-100 dark:border-gray-800 pt-4 space-y-4"
                     x-data="{ role: '{{ old('role') }}' }"
                     x-show="$root.querySelector('#role').value === 'peserta_didik'"
                     x-cloak>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Informasi Pelatihan</p>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        {{-- Program Pelatihan --}}
                        <div>
                            <label for="program_pelatihan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Program Pelatihan</label>
                            <select id="program_pelatihan" name="program_pelatihan"
                                class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <option value="">-- Pilih Program --</option>
                                <option value="Desain Grafis & 3D Level 1" @selected(old('program_pelatihan') === 'Desain Grafis & 3D Level 1')>Desain Grafis & 3D Level 1</option>
                                <option value="Desain Grafis & 3D Level 2" @selected(old('program_pelatihan') === 'Desain Grafis & 3D Level 2')>Desain Grafis & 3D Level 2</option>
                                <option value="Coding & Ai Level 1" @selected(old('program_pelatihan') === 'Coding & Ai Level 1')>Coding & Ai Level 1</option>
                                <option value="Coding & Ai Level 2" @selected(old('program_pelatihan') === 'Coding & Ai Level 2')>Coding & Ai Level 2</option>
                                <option value="Robotika Pondasi Energi & Gerak" @selected(old('program_pelatihan') === 'Robotika Pondasi Energi & Gerak')>Robotika Pondasi Energi & Gerak</option>
                                <option value="Public Speaking Berani Cerita & Perkenalan Diri" @selected(old('program_pelatihan') === 'Public Speaking Berani Cerita & Perkenalan Diri')>Public Speaking</option>
                                <option value="FOS Dewasa" @selected(old('program_pelatihan') === 'FOS Dewasa')>FOS Dewasa</option>
                                <option value="Desain Grafis Dewasa" @selected(old('program_pelatihan') === 'Desain Grafis Dewasa')>Desain Grafis Dewasa</option>
                            </select>
                        </div>
                        {{-- Jenis Kelas --}}
                        <div>
                            <label for="jenis_kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jenis Kelas</label>
                            <select id="jenis_kelas" name="jenis_kelas"
                                class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <option value="">-- Pilih Kelas --</option>
                                <option value="reguler" @selected(old('jenis_kelas') === 'reguler')>Reguler</option>
                                <option value="privat" @selected(old('jenis_kelas') === 'privat')>Privat</option>
                            </select>
                        </div>
                        {{-- Durasi Pelatihan --}}
                        <div>
                            <label for="durasi_pelatihan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Durasi Pelatihan</label>
                            <select id="durasi_pelatihan" name="durasi_pelatihan"
                                class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <option value="">-- Pilih Durasi --</option>
                                <option value="1 Bulan" @selected(old('durasi_pelatihan') === '1 Bulan')>1 Bulan</option>
                                <option value="3 Bulan" @selected(old('durasi_pelatihan') === '3 Bulan')>3 Bulan</option>
                                <option value="6 Bulan" @selected(old('durasi_pelatihan') === '6 Bulan')>6 Bulan</option>
                                <option value="12 X Pertemuan" @selected(old('durasi_pelatihan') === '12 X Pertemuan')>12 X Pertemuan</option>
                            </select>
                        </div>
                    </div>
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
