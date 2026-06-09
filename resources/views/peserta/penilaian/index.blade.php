@extends('layouts.app')

@section('title', 'Nilai Saya')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nilai Saya</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Riwayat penilaian bintang EAC dari mentor Anda.</p>
    </div>

    <x-alert />

    @if ($penilaians->isEmpty())
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 py-16 text-center text-gray-400">
            <svg class="mx-auto h-12 w-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <p class="text-sm">Belum ada penilaian dari mentor.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($penilaians as $penilaian)
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-5 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-white font-bold text-base">{{ $penilaian->bulan }} {{ $penilaian->tahun }}</p>
                            <p class="text-white/70 text-xs">oleh {{ $penilaian->mentor->nama_lengkap }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-white">{{ $penilaian->rata_rata }}</p>
                            <div class="flex gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="h-3 w-3 {{ $i <= round($penilaian->rata_rata) ? 'text-amber-300' : 'text-white/30' }}"
                                         viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>

                    {{-- Detail Bintang --}}
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ([
                                ['m1_kls', 'M1 Kelas'],
                                ['m1_pr',  'M1 Proyek'],
                                ['m2_kls', 'M2 Kelas'],
                                ['m2_pr',  'M2 Proyek'],
                                ['m3_kls', 'M3 Kelas'],
                                ['m3_pr',  'M3 Proyek'],
                                ['m4_kls', 'M4 Kelas'],
                                ['m4_pr',  'M4 Proyek'],
                            ] as [$key, $label])
                                <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-800/50 px-3 py-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $label }}</span>
                                    <span class="flex items-center gap-0.5 text-xs font-semibold text-amber-500">
                                        {{ $penilaian->$key }}
                                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        @if ($penilaian->catatan)
                            <div class="mt-3 rounded-lg border border-blue-100 dark:border-blue-900 bg-blue-50 dark:bg-blue-900/20 p-3">
                                <p class="text-xs font-medium text-blue-700 dark:text-blue-400 mb-1">Catatan Mentor</p>
                                <p class="text-xs text-blue-600 dark:text-blue-300">{{ $penilaian->catatan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div>{{ $penilaians->links() }}</div>
    @endif
</div>
@endsection
