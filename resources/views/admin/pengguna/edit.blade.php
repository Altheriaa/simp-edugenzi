@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="max-w-2xl space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pengguna.index') }}"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Pengguna</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $pengguna->nama_lengkap }}</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900"
             x-data='{
                role: "{{ old('role', $pengguna->role) }}",
                programId: "{{ old('program_pelatihan_id', $pengguna->program_pelatihan_id) }}",
                kelasId:   "{{ old('jenis_kelas_id', $pengguna->jenis_kelas_id) }}",
                durasi:    "{{ old('durasi_pelatihan', $pengguna->durasi_pelatihan) }}",
                optionsMap: @json(json_decode($optionsJson, true)),
                allKelas: @json($jenisKelas->mapWithKeys(fn($jk) => [$jk->id => $jk->nama])),
                get availableKelas() {
                    if (!this.programId || !this.optionsMap[this.programId]) return [];
                    return Object.keys(this.optionsMap[this.programId]);
                },
                get availableDurasi() {
                    if (!this.programId || !this.kelasId) return [];
                    return (this.optionsMap[this.programId] || {})[this.kelasId] || [];
                },
                onProgramChange() { this.kelasId = ""; this.durasi = ""; },
                onKelasChange()   {
                    this.durasi = "";
                    const d = this.availableDurasi;
                    if (d.length === 1) this.durasi = d[0];
                }
             }'>
            <form action="{{ route('admin.pengguna.update', $pengguna) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Nama Lengkap --}}
                    <div class="sm:col-span-2">
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap"
                            value="{{ old('nama_lengkap', $pengguna->nama_lengkap) }}"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nama_lengkap') border-red-400 @enderror" />
                        @error('nama_lengkap') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- nik --}}
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nik" name="nik" value="{{ old('nik', $pengguna->nik) }}"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nik') border-red-400 @enderror" />
                        @error('nik') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $pengguna->email) }}"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('email') border-red-400 @enderror" />
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- NO HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            No HP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $pengguna->no_hp) }}"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('no_hp') border-red-400 @enderror" />
                        @error('no_hp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $pengguna->alamat) }}"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('alamat') border-red-400 @enderror" />
                        @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password (optional) --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Password Baru <span class="text-gray-400">(kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" id="password" name="password"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('password') border-red-400 @enderror" />
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white" />
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role" x-model="role"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="admin" @selected(old('role', $pengguna->role) === 'admin')>Admin</option>
                            <option value="mentor" @selected(old('role', $pengguna->role) === 'mentor')>Mentor</option>
                            <option value="peserta_didik" @selected(old('role', $pengguna->role) === 'peserta_didik')>Peserta
                                Didik</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="aktif" @selected(old('status', $pengguna->status) === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status', $pengguna->status) === 'nonaktif')>Nonaktif
                            </option>
                        </select>
                    </div>

                    {{-- Bidang Pelatihan (hanya muncul jika role = peserta_didik) --}}
                    <div class="sm:col-span-2 border-t border-gray-100 dark:border-gray-800 pt-4 space-y-4"
                         x-show="role === 'peserta_didik'" x-cloak>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Informasi Pelatihan</p>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            {{-- Program Pelatihan --}}
                            <div>
                                <label for="program_pelatihan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Program Pelatihan</label>
                                <select id="program_pelatihan_id" name="program_pelatihan_id"
                                    x-model="programId" @change="onProgramChange()"
                                    class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <option value="">-- Pilih Program --</option>
                                    @foreach($programs as $prog)
                                        <option value="{{ $prog->id }}" @selected(old('program_pelatihan_id', $pengguna->program_pelatihan_id) == $prog->id)>
                                            {{ $prog->nama_program }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Jenis Kelas --}}
                            <div>
                                <label for="jenis_kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jenis Kelas</label>
                                <select id="jenis_kelas_id" name="jenis_kelas_id"
                                    x-model="kelasId" @change="onKelasChange()"
                                    :disabled="!programId"
                                    class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                                    <option value="">-- Pilih Kelas --</option>
                                    <template x-for="k in availableKelas" :key="k">
                                        <option :value="k" :selected="kelasId == k" x-text="allKelas[k]"></option>
                                    </template>
                                </select>
                            </div>
                            {{-- Durasi Pelatihan --}}
                            <div>
                                <label for="durasi_pelatihan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Durasi / Periode</label>
                                <select id="durasi_pelatihan" name="durasi_pelatihan"
                                    x-model="durasi"
                                    :disabled="!kelasId"
                                    class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                                    <option value="">-- Pilih Durasi --</option>
                                    <template x-for="d in availableDurasi" :key="d">
                                        <option :value="d" :selected="d === durasi" x-text="d"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.pengguna.index') }}"
                        class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection