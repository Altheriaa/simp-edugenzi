@extends('layouts.app')

@section('title', 'Dashboard Peserta')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selamat datang, {{ Auth::user()->nama_lengkap }}</p>
    </div>

    <x-alert />

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">Total Tugas</p>
            <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTugas }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">To Do</p>
            <h3 class="mt-1 text-2xl font-bold text-gray-600 dark:text-gray-300">{{ $tugasTodo }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">In Progress</p>
            <h3 class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $tugasProses }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">Selesai</p>
            <h3 class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">{{ $tugasSelesai }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Tugas Terbaru --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Tugas Terbaru</h3>
                <a href="{{ route('peserta.tugas.index') }}" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400">Lihat semua →</a>
            </div>
            @if ($recentTugas->isEmpty())
                <div class="px-5 py-10 text-center text-sm text-gray-400">Belum ada tugas yang diberikan.</div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($recentTugas as $tugas)
                        <a href="{{ route('peserta.tugas.show', $tugas) }}"
                           class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $tugas->judul_task }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tugas->proyek->nama_proyek }}</p>
                            </div>
                            <x-badge-status :status="$tugas->status_task" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Evaluasi Terbaru --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Evaluasi dari Mentor</h3>
            </div>
            @if ($evaluasis->isEmpty())
                <div class="px-5 py-10 text-center text-sm text-gray-400">Belum ada evaluasi.</div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($evaluasis as $ev)
                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $ev->proyek->nama_proyek }}</span>
                                <span class="text-xs text-gray-400">{{ $ev->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $ev->catatan }}</p>
                            <p class="text-xs text-gray-400 mt-1">— {{ $ev->mentor->nama_lengkap }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
