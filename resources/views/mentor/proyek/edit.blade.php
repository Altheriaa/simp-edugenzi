@extends('layouts.app')

@section('title', 'Edit Proyek')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('mentor.proyek.show', $proyek) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Proyek</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $proyek->nama_proyek }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900"
         x-data='{
            programId: "{{ old('program_pelatihan_id', $proyek->program_pelatihan_id) }}",
            kelasId:   "{{ old('jenis_kelas_id', $proyek->jenis_kelas_id) }}",
            durasi:    "{{ old('durasi_pelatihan', $proyek->durasi_pelatihan) }}",
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
        <form action="{{ route('mentor.proyek.update', $proyek) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div>
                    <label for="nama_proyek" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Proyek <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_proyek" name="nama_proyek" value="{{ old('nama_proyek', $proyek->nama_proyek) }}"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nama_proyek') border-red-400 @enderror" />
                    @error('nama_proyek') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white resize-none">{{ old('deskripsi', $proyek->deskripsi) }}</textarea>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="tgl_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tgl_mulai" name="tgl_mulai" value="{{ old('tgl_mulai', $proyek->tgl_mulai->format('Y-m-d')) }}"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('tgl_mulai') border-red-400 @enderror" />
                        @error('tgl_mulai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tgl_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tgl_selesai" name="tgl_selesai" value="{{ old('tgl_selesai', $proyek->tgl_selesai->format('Y-m-d')) }}"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('tgl_selesai') border-red-400 @enderror" />
                        @error('tgl_selesai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="status_proyek" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status</label>
                    <select id="status_proyek" name="status_proyek"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="berjalan" @selected(old('status_proyek', $proyek->status_proyek) === 'berjalan')>Berjalan</option>
                        <option value="tertunda" @selected(old('status_proyek', $proyek->status_proyek) === 'tertunda')>Tertunda</option>
                        <option value="selesai" @selected(old('status_proyek', $proyek->status_proyek) === 'selesai')>Selesai</option>
                    </select>
                </div>

                {{-- Informasi Pelatihan --}}
                <div class="border-t border-gray-100 dark:border-gray-800 pt-4 space-y-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kategori Program</p>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        {{-- Program Pelatihan --}}
                        <div>
                            <label for="program_pelatihan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Program Pelatihan</label>
                            <select id="program_pelatihan_id" name="program_pelatihan_id"
                                x-model="programId" @change="onProgramChange()"
                                class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <option value="">-- Opsional --</option>
                                @foreach($programs as $prog)
                                    <option value="{{ $prog->id }}" @selected(old('program_pelatihan_id', $proyek->program_pelatihan_id) == $prog->id)>
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
                <a href="{{ route('mentor.proyek.show', $proyek) }}"
                   class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                    Batal
                </a>
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
