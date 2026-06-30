@extends('layouts.app')

@section('title', 'Detail Penilaian — ' . $enrollment->peserta->nama_lengkap)

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('mentor.penilaian.index') }}"
               class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Daftar Enrollment
            </a>
            <span class="text-gray-300 dark:text-gray-700">/</span>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Detail Penilaian</h1>
        </div>
        <a href="{{ route('mentor.penilaian.create', ['enrollment_id' => $enrollment->id]) }}"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Penilaian
        </a>

    </div>

    <x-alert />

    {{-- Info Enrollment --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 p-5">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow">
                <span class="text-xl font-bold text-white">
                    {{ strtoupper(substr($enrollment->peserta->nama_lengkap, 0, 1)) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">{{ $enrollment->peserta->nama_lengkap }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $enrollment->peserta->no_registrasi ?? '-' }}</p>
            </div>
            <div class="hidden sm:flex items-center gap-6 text-sm">
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-0.5">Program</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $enrollment->programPelatihan->nama_program ?? '-' }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-0.5">Kelas</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $enrollment->jenisKelas->nama_kelas ?? '-' }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-0.5">Durasi</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $enrollment->durasi_pelatihan ?? '-' }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-0.5">Periode Dinilai</p>
                    <p class="font-bold text-blue-600 dark:text-blue-400">{{ $penilaians->count() }} / {{ $maxBulan }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Periode --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 p-5">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Progress Periode Penilaian</h3>
        <div class="grid gap-3
            {{ $maxBulan === 1 ? 'grid-cols-1 max-w-xs' : ($maxBulan === 3 ? 'grid-cols-3' : 'grid-cols-3 sm:grid-cols-6') }}">
            @for ($b = 1; $b <= $maxBulan; $b++)
                @php $done = $penilaians->firstWhere('bulan_ke', $b); @endphp
                <div class="flex flex-col items-center gap-2 rounded-xl border p-3
                    {{ $done
                        ? 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20'
                        : 'border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/30' }}">
                    <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold
                        {{ $done
                            ? 'bg-green-500 text-white'
                            : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                        @if ($done)
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $b }}
                        @endif
                    </div>
                    <p class="text-xs font-medium text-center
                        {{ $done ? 'text-green-700 dark:text-green-400' : 'text-gray-400' }}">
                        Bulan Ke-{{ $b }}
                    </p>
                    @if ($done)
                        <span class="text-xs font-semibold text-amber-500">{{ $done->rata_rata }}★</span>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    {{-- Tabel Nilai Per Periode --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Rincian Nilai Per Periode</h3>
            <span class="text-xs text-gray-400">Skala 2–5 bintang</span>
        </div>

        @if ($penilaians->isEmpty())
            <div class="py-14 text-center text-gray-400">
                <svg class="mx-auto h-10 w-10 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <p class="text-sm">Belum ada penilaian untuk enrollment ini.</p>
                <a href="{{ route('mentor.penilaian.create', ['enrollment_id' => $enrollment->id]) }}"
                   class="mt-3 inline-flex items-center gap-1 rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700 transition-colors">
                    Beri Penilaian Pertama
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Periode</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">M1 Kls</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">M1 Pr</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">M2 Kls</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">M2 Pr</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">M3 Kls</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">M3 Pr</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">M4 Kls</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">M4 Pr</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rata-rata</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($penilaians as $penilaian)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-semibold text-blue-700 dark:text-blue-300">
                                        {{ $penilaian->label_bulan }}
                                    </span>
                                </td>
                                @foreach (['m1_kls','m1_pr','m2_kls','m2_pr','m3_kls','m3_pr','m4_kls','m4_pr'] as $key)
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center gap-0.5 text-sm font-medium text-amber-500">
                                            {{ $penilaian->$key }}
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </span>
                                    </td>
                                @endforeach
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center gap-0.5 text-sm font-bold text-blue-600 dark:text-blue-400">
                                        {{ $penilaian->rata_rata }}
                                        <svg class="h-3.5 w-3.5 text-amber-500" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('mentor.penilaian.edit', $penilaian) }}"
                                           class="inline-flex items-center gap-1 rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('mentor.penilaian.destroy', $penilaian) }}" method="POST"
                                              onsubmit="return confirm('Hapus penilaian {{ $penilaian->label_bulan }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-lg border border-red-200 dark:border-red-900 px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Summary footer --}}
            @if ($penilaians->count() > 1)
                @php
                    $avgAll = round($penilaians->avg('rata_rata'), 1);
                @endphp
                <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 flex items-center justify-end gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Rata-rata keseluruhan pelatihan:</span>
                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $avgAll }}★</span>
                </div>
            @endif
        @endif
    </div>

    {{-- Catatan jika ada --}}
    @foreach ($penilaians->filter(fn($p) => $p->catatan) as $penilaian)
        <div class="rounded-2xl border border-blue-100 dark:border-blue-900 bg-blue-50 dark:bg-blue-900/20 p-4">
            <p class="text-xs font-semibold text-blue-700 dark:text-blue-400 mb-1">
                Catatan — {{ $penilaian->label_bulan }}
            </p>
            <p class="text-sm text-blue-600 dark:text-blue-300">{{ $penilaian->catatan }}</p>
        </div>
    @endforeach
</div>
@endsection
