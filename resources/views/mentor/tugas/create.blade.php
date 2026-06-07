@extends('layouts.app')

@section('title', 'Tambah Tugas')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('mentor.proyek.show', $proyek) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Tugas</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $proyek->nama_proyek }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('mentor.proyek.tugas.store', $proyek) }}" method="POST">
            @csrf
            <div class="space-y-5">
                {{-- Peserta --}}
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Peserta Didik <span class="text-red-500">*</span>
                    </label>
                    <select id="user_id" name="user_id"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('user_id') border-red-400 @enderror">
                        <option value="">-- Pilih Peserta Didik --</option>
                        @foreach ($pesertaList as $peserta)
                            <option value="{{ $peserta->id }}" @selected(old('user_id') == $peserta->id)>{{ $peserta->nama_lengkap }}</option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Judul Tugas --}}
                <div>
                    <label for="judul_task" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Judul Tugas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="judul_task" name="judul_task" value="{{ old('judul_task') }}"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('judul_task') border-red-400 @enderror"
                        placeholder="Judul tugas..." />
                    @error('judul_task') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi_task" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                    <textarea id="deskripsi_task" name="deskripsi_task" rows="3"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white resize-none"
                        placeholder="Detail tugas...">{{ old('deskripsi_task') }}</textarea>
                </div>

                {{-- Prioritas & Deadline --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="prioritas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Prioritas <span class="text-red-500">*</span>
                        </label>
                        <select id="prioritas" name="prioritas"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="rendah" @selected(old('prioritas') === 'rendah')>🟢 Rendah</option>
                            <option value="sedang" @selected(old('prioritas', 'sedang') === 'sedang')>🟡 Sedang</option>
                            <option value="tinggi" @selected(old('prioritas') === 'tinggi')>🔴 Tinggi</option>
                        </select>
                    </div>
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deadline</label>
                        <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}"
                            class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white" />
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label for="status_task" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status</label>
                    <select id="status_task" name="status_task"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="to_do" @selected(old('status_task', 'to_do') === 'to_do')>To Do</option>
                        <option value="in_progress" @selected(old('status_task') === 'in_progress')>In Progress</option>
                        <option value="done" @selected(old('status_task') === 'done')>Done</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('mentor.proyek.show', $proyek) }}"
                   class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                    Batal
                </a>
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Tambah Tugas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
