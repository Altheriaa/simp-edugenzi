@extends('layouts.app')

@section('title', 'Sertifikat Saya')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sertifikat Saya</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sertifikat penghargaan dan kelulusan program yang Anda terima.</p>
    </div>

    <x-alert />

    @if ($sertifikats->isEmpty() && !request('search'))
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 py-16 text-center text-gray-400">
            <svg class="mx-auto h-12 w-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <p class="text-sm">Belum ada sertifikat yang diterima.</p>
        </div>
    @else
        {{-- Search --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <x-search-bar :action="route('peserta.sertifikat.index')" placeholder="Cari nomor sertifikat, program, predikat..." />
        </div>

        @if ($sertifikats->isEmpty())
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 py-16 text-center text-gray-400">
                <p class="text-sm">Tidak ada sertifikat yang cocok dengan pencarian.</p>
            </div>
        @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($sertifikats as $sertifikat)
                @php
                    $gradientMap = [
                        'Dengan Pujian'    => 'from-purple-500 to-purple-700',
                        'Sangat Memuaskan' => 'from-blue-500 to-blue-700',
                        'Memuaskan'        => 'from-green-500 to-green-700',
                        'Cukup'            => 'from-gray-500 to-gray-700',
                    ];
                    $gradient = $gradientMap[$sertifikat->predikat] ?? 'from-blue-500 to-blue-700';
                @endphp
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                    <div class="bg-gradient-to-r {{ $gradient }} px-5 py-6 text-center">
                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-white/20 mb-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <p class="text-white font-bold text-sm">{{ $sertifikat->predikat }}</p>
                        <p class="text-white/60 text-xs font-mono mt-1">{{ $sertifikat->nomor_sertifikat }}</p>
                    </div>

                    <div class="p-4 space-y-2">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $sertifikat->nama_program }}</h4>
                        <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $sertifikat->tgl_terbit->format('d F Y') }}
                        </div>
                        <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $sertifikat->mentor->nama_lengkap }}
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('peserta.sertifikat.print', $sertifikat) }}"
                               target="_blank"
                               class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 py-2 text-xs font-medium text-white hover:bg-blue-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Cetak Sertifikat
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($sertifikats->hasPages())
            <div>{{ $sertifikats->links() }}</div>
        @endif
        @endif
    @endif
</div>
@endsection
