@extends('layouts.app')

@section('title', $proyek->nama_proyek)

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('mentor.proyek.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $proyek->nama_proyek }}</h1>
                    <x-badge-status :status="$proyek->status_proyek" />
                </div>
                @if($proyek->deskripsi)
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $proyek->deskripsi }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('mentor.proyek.edit', $proyek) }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                Edit Proyek
            </a>
        </div>
    </div>

    <x-alert />

    {{-- Info Proyek --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Mulai</p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $proyek->tgl_mulai->format('d M Y') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Selesai</p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $proyek->tgl_selesai->format('d M Y') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">Total Tugas</p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $proyek->tugas->count() }} tugas</p>
        </div>
    </div>

    {{-- Daftar Tugas --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Daftar Tugas</h3>
            <a href="{{ route('mentor.proyek.tugas.create', $proyek) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Tugas
            </a>
        </div>

        @if ($proyek->tugas->isEmpty())
            <div class="px-6 py-10 text-center text-gray-400">
                <svg class="mx-auto h-10 w-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-sm">Belum ada tugas. Tambah tugas untuk proyek ini.</p>
            </div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($proyek->tugas as $tugas)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex-1 min-w-0 mr-4">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $tugas->judul_task }}</h4>
                                <x-badge-status :status="$tugas->status_task" />
                                <x-badge-prioritas :prioritas="$tugas->prioritas" />
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $tugas->peserta->nama_lengkap }}</span>
                                @if($tugas->deadline)
                                    <span>· Deadline: {{ $tugas->deadline->format('d M Y') }}</span>
                                @endif
                                <span>· {{ $tugas->subTugas->count() }} sub-tugas</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('mentor.tugas.show', $tugas) }}"
                               class="rounded-lg px-3 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 transition-colors">
                                Detail
                            </a>
                            <a href="{{ route('mentor.tugas.edit', $tugas) }}"
                               class="rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <x-modal-confirm :id="'hapus-tugas-'.$tugas->id" :action="route('mentor.tugas.destroy', $tugas)">
                                <button type="button" class="rounded-lg p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </x-modal-confirm>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Evaluasi --}}
    @if ($proyek->evaluasi->count() > 0)
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Catatan Evaluasi</h3>
            <div class="space-y-3">
                @foreach ($proyek->evaluasi as $ev)
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-4">
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $ev->peserta->nama_lengkap }}</span>
                            <span class="text-xs text-gray-400">{{ $ev->created_at->format('d M Y') }}</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $ev->catatan }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
