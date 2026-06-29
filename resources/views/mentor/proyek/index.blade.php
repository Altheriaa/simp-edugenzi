@extends('layouts.app')

@section('title', 'Proyek Saya')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Proyek Saya</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua proyek yang Anda pegang</p>
        </div>
        <a href="{{ route('mentor.proyek.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Proyek
        </a>
    </div>

    <x-alert />

    {{-- Search --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        <x-search-bar :action="route('mentor.proyek.index')" placeholder="Cari nama proyek atau deskripsi..." />
    </div>
    @if ($proyeks->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-16 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada proyek</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Mulai dengan membuat proyek baru untuk tim Anda.</p>
            <a href="{{ route('mentor.proyek.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Buat Proyek Pertama
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($proyeks as $proyek)
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white line-clamp-2">{{ $proyek->nama_proyek }}</h3>
                            <x-badge-status :status="$proyek->status_proyek" />
                        </div>
                        @if ($proyek->deskripsi)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">{{ $proyek->deskripsi }}</p>
                        @endif

                        @if($proyek->program_pelatihan_id)
                        <div class="mb-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-900/30 dark:text-blue-400 dark:ring-blue-400/20">
                                {{ $proyek->programPelatihan->nama_program }}
                            </span>
                            @if($proyek->jenis_kelas_id)
                                <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/30 dark:text-purple-400 dark:ring-purple-400/20">
                                    {{ $proyek->jenisKelas->nama }}
                                </span>
                            @endif
                            @if($proyek->durasi_pelatihan)
                                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10 dark:bg-green-900/30 dark:text-green-400 dark:ring-green-400/20">
                                    {{ $proyek->durasi_pelatihan }}
                                </span>
                            @endif
                        </div>
                        @endif

                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $proyek->tugas_count }} tugas
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ $proyek->tgl_selesai->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 dark:border-gray-800 px-5 py-3 flex items-center justify-between">
                        <a href="{{ route('mentor.proyek.show', $proyek) }}"
                           class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                            Lihat Detail →
                        </a>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('mentor.proyek.edit', $proyek) }}"
                               class="rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <x-modal-confirm :id="'hapus-'.$proyek->id" :action="route('mentor.proyek.destroy', $proyek)">
                                <button type="button" class="rounded-lg p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </x-modal-confirm>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>{{ $proyeks->links() }}</div>
    @endif
</div>
@endsection
