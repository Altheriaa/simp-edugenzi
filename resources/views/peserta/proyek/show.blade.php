@extends('layouts.app')

@section('title', 'Detail Proyek')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('peserta.proyek.index') }}" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Proyek</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Informasi detail proyek dan tugas-tugas di dalamnya.</p>
            </div>
        </div>
    </div>

    <!-- Informasi Proyek -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-500/10 dark:text-brand-400">
                        {{ $proyek->programPelatihan->nama_program ?? 'Program Umum' }}
                    </span>
                    <h2 class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ $proyek->nama_proyek }}</h2>
                </div>
                <div>
                    @if ($proyek->status_proyek === 'aktif')
                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                            Aktif
                        </span>
                    @elseif($proyek->status_proyek === 'selesai')
                        <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-500/10 dark:text-gray-400">
                            Selesai
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-medium text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400">
                            {{ ucfirst($proyek->status_proyek) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 divide-y divide-gray-200 lg:grid-cols-3 lg:divide-x lg:divide-y-0 dark:divide-gray-800">
            <div class="p-6">
                <h3 class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Periode</h3>
                <p class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{ $proyek->tgl_mulai->format('d M Y') }} - {{ $proyek->tgl_selesai->format('d M Y') }}
                </p>
            </div>
            <div class="p-6">
                <h3 class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Mentor</h3>
                <div class="mt-2 flex items-center">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-sm font-medium text-brand-700 dark:bg-brand-900/30 dark:text-brand-400">
                        {{ strtoupper(substr($proyek->mentor->nama_lengkap ?? 'M', 0, 1)) }}
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                        {{ $proyek->mentor->nama_lengkap ?? '-' }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Jenis Kelas</h3>
                <p class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{ $proyek->jenisKelas->nama ?? 'Tanpa Kelas' }}
                </p>
            </div>
        </div>
        @if ($proyek->deskripsi)
        <div class="border-t border-gray-200 p-6 dark:border-gray-800">
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Deskripsi Proyek</h3>
            <div class="prose prose-sm max-w-none text-gray-600 dark:prose-invert dark:text-gray-300">
                {{ $proyek->deskripsi }}
            </div>
        </div>
        @endif
    </div>

    <!-- Daftar Tugas di Proyek Ini -->
    <div>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tugas Anda di Proyek Ini</h2>
        @if ($tugasList->isEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-8 text-center dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada tugas yang diberikan kepada Anda dalam proyek ini.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">Tugas</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">Deadline</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($tugasList as $tugas)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $tugas->judul_task }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $tugas->sub_tugas_count }} Sub-Tugas
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @php
                                        $isOverdue = $tugas->status_task !== 'Selesai' && $tugas->tgl_selesai_task < now();
                                    @endphp
                                    <div class="flex items-center text-sm {{ $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($tugas->tgl_selesai_task)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($tugas->status_task === 'Selesai')
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                            Selesai
                                        </span>
                                    @elseif($tugas->status_task === 'Sedang Berjalan')
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                                            Sedang Berjalan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-500/10 dark:text-gray-400">
                                            Belum Mulai
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    <a href="{{ route('peserta.tugas.show', $tugas) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-50 px-3 py-1.5 text-xs font-medium text-brand-700 transition-colors hover:bg-brand-100 dark:bg-brand-500/10 dark:text-brand-400 dark:hover:bg-brand-500/20">
                                        Lihat Detail
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
