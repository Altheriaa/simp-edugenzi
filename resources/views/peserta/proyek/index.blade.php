@extends('layouts.app')

@section('title', 'Proyek Saya')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Proyek Saya</h1>
    </div>

    @if ($proyeks->isEmpty() && !request('search'))
        <div class="rounded-xl border border-gray-200 bg-white p-8 text-center dark:border-gray-800 dark:bg-gray-900">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Belum Ada Proyek</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum tergabung dalam proyek apa pun.</p>
        </div>
    @else
        {{-- Search --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <x-search-bar :action="route('peserta.proyek.index')" placeholder="Cari nama proyek..." />
        </div>

        @if ($proyeks->isEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-8 text-center dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm text-gray-400">Tidak ada proyek yang cocok dengan pencarian.</p>
            </div>
        @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($proyeks as $proyek)
                <a href="{{ route('peserta.proyek.show', $proyek) }}" class="block w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all hover:border-brand-500 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-brand-500">
                    <div class="p-5">
                        <div class="mb-4 flex items-start justify-between">
                            <div class="flex-1">
                                <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-500/10 dark:text-brand-400">
                                    {{ $proyek->programPelatihan->nama_program ?? 'Program Umum' }}
                                </span>
                                <h3 class="mt-2 text-lg font-semibold text-gray-900 line-clamp-1 dark:text-white">
                                    {{ $proyek->nama_proyek }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Mentor: {{ $proyek->mentor->nama_lengkap ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $proyek->tgl_mulai->format('d M Y') }} - {{ $proyek->tgl_selesai->format('d M Y') }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-800">
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $proyek->jenisKelas->nama ?? 'Tanpa Kelas' }}
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
                </a>
            @endforeach
        </div>

        @if ($proyeks->hasPages())
            <div>{{ $proyeks->links() }}</div>
        @endif
        @endif
    @endif
</div>
@endsection
