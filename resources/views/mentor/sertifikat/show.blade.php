@extends('layouts.app')

@section('title', 'Preview Sertifikat — ' . $sertifikat->nomor_sertifikat)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('mentor.sertifikat.index') }}"
               class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <span class="text-gray-300 dark:text-gray-700">/</span>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Preview Sertifikat</h1>
        </div>
        <a href="{{ route('mentor.sertifikat.edit', $sertifikat) }}"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            Edit
        </a>
    </div>

    {{-- Sertifikat Preview --}}
    @php
        $gradientMap = [
            'Dengan Pujian'    => 'from-purple-600 via-purple-500 to-indigo-600',
            'Sangat Memuaskan' => 'from-blue-600 via-blue-500 to-cyan-600',
            'Memuaskan'        => 'from-green-600 via-emerald-500 to-teal-600',
            'Cukup'            => 'from-gray-600 via-gray-500 to-slate-600',
        ];
        $gradient = $gradientMap[$sertifikat->predikat] ?? 'from-blue-600 to-indigo-600';
    @endphp

    <div class="mx-auto max-w-2xl">
        <div class="rounded-3xl overflow-hidden shadow-2xl border border-gray-200 dark:border-gray-700">
            {{-- Sertifikat Header --}}
            <div class="bg-gradient-to-br {{ $gradient }} p-8 text-center relative overflow-hidden">
                {{-- Decorative circles --}}
                <div class="absolute top-0 right-0 h-32 w-32 rounded-full bg-white/10 -translate-y-8 translate-x-8"></div>
                <div class="absolute bottom-0 left-0 h-24 w-24 rounded-full bg-white/10 translate-y-6 -translate-x-6"></div>

                <div class="relative">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-white/20 mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <p class="text-white/80 text-xs uppercase tracking-widest font-semibold mb-1">Edugenzi Banda Aceh</p>
                    <h2 class="text-2xl font-bold text-white">SERTIFIKAT</h2>
                    <p class="text-white/70 text-sm">Penghargaan Kelulusan Program</p>
                </div>
            </div>

            {{-- Sertifikat Body --}}
            <div class="bg-white dark:bg-gray-900 px-8 py-8 text-center space-y-4">
                <p class="text-gray-500 dark:text-gray-400 text-sm">Diberikan kepada</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $sertifikat->peserta->nama_lengkap }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">atas keberhasilan menyelesaikan</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $sertifikat->nama_program }}</p>

                <div class="inline-flex items-center gap-2 rounded-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-5 py-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Predikat:</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $sertifikat->predikat }}</span>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800 pt-4 mt-4">
                    <div class="grid grid-cols-2 gap-6 text-left">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Nomor Sertifikat</p>
                            <p class="text-sm font-mono font-semibold text-gray-900 dark:text-white mt-1">{{ $sertifikat->nomor_sertifikat }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Tanggal Terbit</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $sertifikat->tgl_terbit->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Diterbitkan oleh</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $sertifikat->mentor->nama_lengkap }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
