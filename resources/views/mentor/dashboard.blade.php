@extends('layouts.app')

@section('title', 'Dashboard Mentor')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Mentor</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selamat datang, {{ Auth::user()->nama_lengkap }}</p>
        </div>
        <a href="{{ route('mentor.proyek.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Proyek
        </a>
    </div>

    <x-alert />

    {{-- Stats --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Proyek</p>
            <h3 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProyek }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Proyek Berjalan</p>
            <h3 class="mt-1 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $proyekBerjalan }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Tugas</p>
            <h3 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalTugas }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Peserta Aktif</p>
            <h3 class="mt-1 text-3xl font-bold text-green-600 dark:text-green-400">{{ $pesertaAktif }}</h3>
        </div>
    </div>

    {{-- Proyek Terbaru --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Proyek Saya</h3>
            <a href="{{ route('mentor.proyek.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                Lihat semua →
            </a>
        </div>
        @if ($recentProyek->isEmpty())
            <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                <svg class="h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                <p class="text-sm mb-3">Belum ada proyek. Mulai buat proyek pertama!</p>
                <a href="{{ route('mentor.proyek.create') }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">Buat Proyek</a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($recentProyek as $proyek)
                    <a href="{{ route('mentor.proyek.show', $proyek) }}"
                       class="block rounded-xl border border-gray-100 p-4 hover:border-blue-200 hover:shadow-sm dark:border-gray-800 dark:hover:border-blue-900 transition-all">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $proyek->nama_proyek }}</h4>
                            <x-badge-status :status="$proyek->status_proyek" />
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $proyek->tugas->count() }} tugas · Selesai {{ $proyek->tgl_selesai->format('d M Y') }}
                        </p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
