@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selamat datang, {{ Auth::user()->nama_lengkap }}</p>
        </div>
    </div>

    <x-alert />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {{-- Total Pengguna --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Pengguna</p>
                    <h3 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalPengguna }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/20">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Proyek --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Proyek</p>
                    <h3 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProyek }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-900/20">
                    <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Tugas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Tugas</p>
                    <h3 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalTugas }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 dark:bg-green-900/20">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Proyek Berjalan --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Proyek Berjalan</p>
                    <h3 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $proyekBerjalan }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 dark:bg-orange-900/20">
                    <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Proyek & Tabel Proyek Terbaru --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Status Breakdown --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Status Proyek</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Berjalan</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $proyekBerjalan }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Selesai</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $proyekSelesai }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-yellow-500"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Tertunda</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $proyekTertunda }}</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ route('admin.pengguna.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Kelola Pengguna
                </a>
            </div>
        </div>

        {{-- Proyek Terbaru --}}
        <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Proyek Terbaru</h3>
            @if ($recentProyek->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                    <svg class="h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    <p class="text-sm">Belum ada proyek</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead>
                            <tr>
                                <th class="pb-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Proyek</th>
                                <th class="pb-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mentor</th>
                                <th class="pb-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($recentProyek as $proyek)
                                <tr>
                                    <td class="py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $proyek->nama_proyek }}</td>
                                    <td class="py-3 text-sm text-gray-500 dark:text-gray-400">{{ $proyek->mentor->nama_lengkap ?? '-' }}</td>
                                    <td class="py-3"><x-badge-status :status="$proyek->status_proyek" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
