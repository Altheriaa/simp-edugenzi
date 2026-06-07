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

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
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
