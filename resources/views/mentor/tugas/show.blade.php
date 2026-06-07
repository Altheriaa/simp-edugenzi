@extends('layouts.app')

@section('title', $tugas->judul_task)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('mentor.proyek.show', $tugas->proyek) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $tugas->judul_task }}</h1>
                    <x-badge-status :status="$tugas->status_task" />
                    <x-badge-prioritas :prioritas="$tugas->prioritas" />
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $tugas->proyek->nama_proyek }} · {{ $tugas->peserta->nama_lengkap }}</p>
            </div>
        </div>
        <a href="{{ route('mentor.tugas.edit', $tugas) }}"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            Edit
        </a>
    </div>

    <x-alert />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Kolom Kiri: Detail & Sub-Tugas --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Detail --}}
            @if ($tugas->deskripsi_task)
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $tugas->deskripsi_task }}</p>
                </div>
            @endif

            {{-- Sub-Tugas --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Sub-Tugas / Checklist</h3>
                </div>

                {{-- Form tambah sub-tugas --}}
                <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-800">
                    <form action="{{ route('mentor.sub-tugas.store', $tugas) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="judul_sub_task" placeholder="Tambah checklist item..."
                            class="flex-1 h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white" />
                        <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700 transition-colors">
                            Tambah
                        </button>
                    </form>
                </div>

                @if ($tugas->subTugas->isEmpty())
                    <div class="px-5 py-6 text-center text-sm text-gray-400">Belum ada sub-tugas.</div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($tugas->subTugas as $sub)
                            <div class="flex items-center gap-3 px-5 py-3">
                                <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full border-2
                                    {{ $sub->is_selesai ? 'border-green-500 bg-green-500' : 'border-gray-300 dark:border-gray-600' }}">
                                    @if($sub->is_selesai)
                                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    @endif
                                </span>
                                <span class="flex-1 text-sm {{ $sub->is_selesai ? 'line-through text-gray-400' : 'text-gray-700 dark:text-gray-200' }}">
                                    {{ $sub->judul_sub_task }}
                                </span>
                                <form action="{{ route('mentor.sub-tugas.destroy', $sub) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Lampiran --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Lampiran</h3>
                </div>
                @if ($tugas->lampiran->isEmpty())
                    <div class="px-5 py-6 text-center text-sm text-gray-400">Belum ada lampiran.</div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($tugas->lampiran as $lamp)
                            <div class="flex items-center gap-3 px-5 py-3">
                                <svg class="h-8 w-8 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $lamp->nama_file }}</p>
                                    <p class="text-xs text-gray-400">{{ $lamp->tipe_file }} · {{ $lamp->ukuran_file }} KB · {{ $lamp->uploader->nama_lengkap }}</p>
                                </div>
                                <a href="{{ Storage::url($lamp->path_file) }}" target="_blank"
                                   class="text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                    Download
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Info --}}
        <div class="space-y-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Informasi Tugas</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Peserta</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $tugas->peserta->nama_lengkap }}</span>
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
                        <span class="text-gray-500">Sub-tugas</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $tugas->subTugas->where('is_selesai', true)->count() }}/{{ $tugas->subTugas->count() }} selesai
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
