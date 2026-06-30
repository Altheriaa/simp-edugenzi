@extends('layouts.app')

@section('title', 'Penilaian Peserta')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Penilaian</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau seluruh penilaian EAC peserta didik dari semua mentor.</p>
            </div>
        </div>

        <x-alert />

        {{-- Search --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <x-search-bar :action="route('admin.penilaian.index')" placeholder="Cari nama peserta atau program..." />
        </div>

        {{-- Grid Kartu Enrollment --}}
        @if ($enrollments->isEmpty())
            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 py-16 text-center text-gray-400">
                <svg class="mx-auto h-12 w-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-sm">Belum ada enrollment peserta didik aktif terdaftar.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($enrollments as $enrollment)
                    @php
                        $peserta = $enrollment->peserta;
                        $nilaiList = $enrollment->penilaian;
                        $jumlahNilai = $nilaiList->count();
                        $maxBulan = $enrollment->getDurasiBulan() ?: 6;
                        $rataRata = $jumlahNilai > 0
                            ? round($nilaiList->avg('rata_rata'), 1)
                            : 0;
                        $progress = $jumlahNilai > 0 ? round(($jumlahNilai / $maxBulan) * 100) : 0;
                    @endphp

                    <div
                        class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 flex flex-col overflow-hidden hover:shadow-md transition-shadow">
                        {{-- Top banner --}}
                        <div class="flex items-center gap-4 p-5 border-b border-gray-100 dark:border-gray-800">
                            <div
                                class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow">
                                <span class="text-lg font-bold text-white">
                                    {{ strtoupper(substr($peserta->nama_lengkap, 0, 1)) }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $peserta->nama_lengkap }}
                                </p>
                                <p class="text-xs text-gray-400 truncate">{{ $peserta->no_registrasi ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Info pelatihan --}}
                        <div class="px-5 pt-4 pb-2 space-y-2 flex-1">
                            @if ($enrollment->programPelatihan)
                                <div class="flex items-center gap-2">
                                    <svg class="h-3.5 w-3.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span
                                        class="text-xs text-gray-600 dark:text-gray-300 truncate">{{ $enrollment->programPelatihan->nama_program }}</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $enrollment->durasi_pelatihan ?? 'Durasi belum diset' }}
                                </span>
                                @if ($enrollment->jenisKelas)
                                    <span class="capitalize">{{ $enrollment->jenisKelas->nama_kelas ?? $enrollment->jenisKelas->nama }}</span>
                                @endif
                            </div>

                            {{-- Progress bar periode --}}
                            <div class="pt-1">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs text-gray-400">Progress Penilaian</span>
                                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">{{ $jumlahNilai }} /
                                        {{ $maxBulan }} Periode</span>
                                </div>
                                <div class="h-1.5 w-full rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-1.5 rounded-full transition-all
                                                            {{ $jumlahNilai >= $maxBulan ? 'bg-green-500' : 'bg-blue-500' }}"
                                        style="width: {{ $progress }}%"></div>
                                </div>
                            </div>

                            {{-- Rata-rata bintang --}}
                            @if ($jumlahNilai > 0)
                                <div class="flex items-center gap-1.5 pt-1">
                                    <div class="flex gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="h-3.5 w-3.5 {{ $i <= round($rataRata) ? 'text-amber-400' : 'text-gray-200 dark:text-gray-700' }}"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs font-semibold text-amber-500">{{ $rataRata }} ★ rata-rata</span>
                                </div>
                            @else
                                <p class="text-xs text-gray-400 italic">Belum ada penilaian</p>
                            @endif
                        </div>

                        {{-- Footer actions --}}
                        <div class="px-5 pb-5 pt-3 flex items-center gap-2">
                            <a href="{{ route('admin.penilaian.detail', $enrollment) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs font-medium text-white hover:bg-blue-700 transition-colors">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Lihat Detail Penilaian
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($enrollments->hasPages())
                <div>{{ $enrollments->links() }}</div>
            @endif
        @endif
    </div>
@endsection

