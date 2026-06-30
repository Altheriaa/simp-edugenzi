@extends('layouts.app')

@section('title', $tugas->judul_task)

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('peserta.tugas.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $tugas->judul_task }}</h1>
                    <x-badge-status :status="$tugas->status_task" />
                    <x-badge-prioritas :prioritas="$tugas->prioritas" />
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $tugas->proyek->nama_proyek }}</p>
            </div>
        </div>

        {{-- Update Status --}}
        {{-- Update Status --}}
        @if ($tugas->status_task !== 'done')
            <form action="{{ route('peserta.tugas.status', $tugas) }}" method="POST" class="flex items-center gap-2">
                @csrf @method('PATCH')
                <select name="status_task" class="h-9 rounded-xl border border-gray-300 bg-white px-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="to_do" @selected($tugas->status_task === 'to_do')>To Do</option>
                    <option value="in_progress" @selected($tugas->status_task === 'in_progress')>In Progress</option>
                    <option value="done" @selected($tugas->status_task === 'done')>Done</option>
                </select>
                <button type="submit" class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700 transition-colors">
                    Update Status
                </button>
            </form>
        @else
            <div class="flex items-center gap-2">
                <span class="rounded-xl border border-green-200 bg-green-50 px-3 py-2 text-xs font-medium text-green-700 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-400">
                    Tugas Selesai
                </span>
            </div>
        @endif
    </div>

    <x-alert />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Kolom Kiri --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Deskripsi --}}
            @if ($tugas->deskripsi_task)
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $tugas->deskripsi_task }}</p>
                </div>
            @endif

            {{-- Sub-Tugas --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Checklist</h3>
                    @if ($tugas->subTugas->count() > 0)
                        <span class="text-xs text-gray-400">
                            {{ $tugas->subTugas->where('is_selesai', true)->count() }}/{{ $tugas->subTugas->count() }}
                        </span>
                    @endif
                </div>
                @if ($tugas->subTugas->isEmpty())
                    <div class="px-5 py-6 text-center text-sm text-gray-400">Tidak ada checklist.</div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($tugas->subTugas as $sub)
                            @if ($tugas->status_task === 'done')
                                <div class="flex items-center gap-3 px-5 py-3 opacity-75">
                                    <div class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full border-2 border-green-500 bg-green-500">
                                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <span class="text-sm line-through text-gray-400">
                                        {{ $sub->judul_sub_task }}
                                    </span>
                                </div>
                            @else
                                <form action="{{ route('peserta.sub-tugas.toggle', $sub) }}" method="POST"
                                      class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors cursor-pointer"
                                      onsubmit="this.submit()">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full border-2 transition-all
                                        {{ $sub->is_selesai ? 'border-green-500 bg-green-500' : 'border-gray-300 hover:border-green-400 dark:border-gray-600' }}">
                                        @if($sub->is_selesai)
                                            <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        @endif
                                    </button>
                                    <span class="text-sm {{ $sub->is_selesai ? 'line-through text-gray-400' : 'text-gray-700 dark:text-gray-200' }}">
                                        {{ $sub->judul_sub_task }}
                                    </span>
                                </form>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            @php
                $panduanMentor = $tugas->lampiran->where('uploaded_by', $tugas->proyek->user_id);
                $lampiranPeserta = $tugas->lampiran->where('uploaded_by', $tugas->user_id);
            @endphp

            {{-- Panduan / Modul (Mentor) --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden mb-6">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Panduan / Modul (PDF)</h3>
                </div>

                @if ($panduanMentor->isEmpty())
                    <div class="px-5 py-6 text-center text-sm text-gray-400">Belum ada panduan.</div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($panduanMentor as $lamp)
                            <div class="flex items-center gap-3 px-5 py-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $lamp->nama_file }}</p>
                                    <p class="text-xs text-gray-400">{{ strtoupper($lamp->tipe_file) }} · {{ $lamp->ukuran_file }} KB · {{ $lamp->uploader->nama_lengkap }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ Storage::url($lamp->path_file) }}" target="_blank"
                                       class="text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">Download</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Upload Lampiran Tugas (Peserta) --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Lampiran / Hasil Tugas</h3>
                </div>

                {{-- Form Upload --}}
                @if ($tugas->status_task !== 'done')
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-800">
                        @error('file') <p class="mb-2 text-xs font-semibold text-red-500">{{ $message }}</p> @enderror
                        <form action="{{ route('peserta.lampiran.store', $tugas) }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                            @csrf
                            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.zip"
                                class="flex-1 text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-blue-600 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-400" />
                            <button type="submit" class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 transition-colors flex-shrink-0">
                                Upload
                            </button>
                        </form>
                    </div>
                @endif

                @if ($lampiranPeserta->isEmpty())
                    <div class="px-5 py-6 text-center text-sm text-gray-400">Belum ada lampiran hasil tugas.</div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($lampiranPeserta as $lamp)
                            <div class="flex items-center gap-3 px-5 py-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $lamp->nama_file }}</p>
                                    <p class="text-xs text-gray-400">{{ strtoupper($lamp->tipe_file) }} · {{ $lamp->ukuran_file }} KB · {{ $lamp->uploader->nama_lengkap }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ Storage::url($lamp->path_file) }}" target="_blank"
                                       class="text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">Download</a>
                                    @if ($lamp->uploaded_by === Auth::id() && $tugas->status_task !== 'done')
                                        <form action="{{ route('peserta.lampiran.destroy', $lamp) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Info --}}
        <div class="space-y-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Detail Tugas</h3>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Proyek</span>
                        <span class="font-medium text-gray-900 dark:text-white text-right">{{ $tugas->proyek->nama_proyek }}</span>
                    </div>
                    @if($tugas->deadline)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Deadline</span>
                            <span class="font-medium {{ $tugas->deadline->isPast() && $tugas->status_task !== 'done' ? 'text-red-500' : 'text-gray-900 dark:text-white' }}">
                                {{ $tugas->deadline->format('d M Y') }}
                            </span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Checklist</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $tugas->subTugas->where('is_selesai', true)->count() }}/{{ $tugas->subTugas->count() }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Terakhir diupdate</span>
                        <span class="font-medium text-gray-900 dark:text-white text-right">
                            {{ $tugas->tgl_update ? $tugas->tgl_update->diffForHumans() : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
