@extends('layouts.app')

@section('title', 'Sertifikat Peserta')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sertifikat Peserta</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola dan terbitkan sertifikat kelulusan untuk peserta didik.</p>
        </div>
        <a href="{{ route('mentor.sertifikat.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Terbitkan Sertifikat
        </a>
    </div>

    <x-alert />

    {{-- Search --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        <x-search-bar :action="route('mentor.sertifikat.index')" placeholder="Cari nama peserta, nomor sertifikat, program..." />
    </div>

    {{-- Grid Sertifikat --}}
    @if ($sertifikats->isEmpty())
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 py-16 text-center text-gray-400">
            <svg class="mx-auto h-12 w-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <p class="text-sm">Belum ada sertifikat yang diterbitkan.</p>
            <a href="{{ route('mentor.sertifikat.create') }}"
               class="mt-3 inline-flex items-center gap-1 rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700 transition-colors">
                Terbitkan Pertama
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($sertifikats as $sertifikat)
                @php
                    $predikatColor = match($sertifikat->predikat) {
                        'Dengan Pujian'    => 'from-purple-500 to-purple-700',
                        'Sangat Memuaskan' => 'from-blue-500 to-blue-700',
                        'Memuaskan'        => 'from-green-500 to-green-700',
                        default            => 'from-gray-500 to-gray-700',
                    };
                    $predikatBadge = match($sertifikat->predikat) {
                        'Dengan Pujian'    => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300',
                        'Sangat Memuaskan' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                        'Memuaskan'        => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
                        default            => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                    };
                @endphp
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                    {{-- Header Gradien --}}
                    <div class="bg-gradient-to-r {{ $predikatColor }} px-5 py-4">
                        <div class="flex items-center justify-between">
                            <svg class="h-8 w-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            <span class="text-xs text-white/70 font-mono">{{ $sertifikat->nomor_sertifikat }}</span>
                        </div>
                        <p class="mt-2 text-sm font-semibold text-white">{{ $sertifikat->peserta->nama_lengkap }}</p>
                    </div>

                    {{-- Body --}}
                    <div class="p-5 space-y-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $sertifikat->nama_program }}</p>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $predikatBadge }}">
                                {{ $sertifikat->predikat }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $sertifikat->tgl_terbit->format('d M Y') }}</span>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <a href="{{ route('mentor.sertifikat.show', $sertifikat) }}"
                               class="flex-1 text-center rounded-lg border border-gray-200 dark:border-gray-700 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                Preview
                            </a>
                            <a href="{{ route('mentor.sertifikat.edit', $sertifikat) }}"
                               class="flex-1 text-center rounded-lg border border-gray-200 dark:border-gray-700 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('mentor.sertifikat.destroy', $sertifikat) }}" method="POST"
                                  onsubmit="return confirm('Hapus sertifikat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="rounded-lg border border-red-200 dark:border-red-900 px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>{{ $sertifikats->links() }}</div>
    @endif
</div>
@endsection
