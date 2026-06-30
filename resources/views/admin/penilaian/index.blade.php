@extends('layouts.app')

@section('title', 'Data Penilaian')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Penilaian</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau seluruh penilaian EAC peserta didik dari semua mentor.</p>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.penilaian.index') }}"
          class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Cari</label>
                <div class="relative">
                    <span class="absolute -translate-y-1/2 pointer-events-none left-3 top-1/2 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari nama peserta atau mentor..."
                        class="w-full rounded-lg border border-gray-300 bg-transparent pl-9 pr-3 py-1.5 text-sm text-gray-800 focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                </div>
            </div>
            <div>
                <label for="enrollment_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Peserta & Program</label>
                <select name="enrollment_id" id="enrollment_id"
                        class="rounded-lg border border-gray-300 bg-transparent px-3 py-1.5 text-sm text-gray-800 focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">Semua</option>
                    @foreach ($enrollments as $e)
                        <option value="{{ $e->id }}" class="dark:bg-gray-900" {{ request('enrollment_id') == $e->id ? 'selected' : '' }}>
                            {{ $e->peserta->nama_lengkap }}
                            @if($e->programPelatihan) - {{ $e->programPelatihan->nama_program }} @endif
                            @if($e->jenisKelas) ({{ $e->jenisKelas->nama_kelas }}) @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="bulan_ke" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Periode</label>
                <select name="bulan_ke" id="bulan_ke"
                        class="rounded-lg border border-gray-300 bg-transparent px-3 py-1.5 text-sm text-gray-800 focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">Semua Periode</option>
                    @for ($b = 1; $b <= 6; $b++)
                        <option value="{{ $b }}" class="dark:bg-gray-900" {{ request('bulan_ke') == $b ? 'selected' : '' }}>
                            Bulan Ke-{{ $b }}
                        </option>
                    @endfor
                </select>
            </div>
            <button type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-1.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                Filter
            </button>
            @if (request()->hasAny(['enrollment_id','bulan_ke','search']))
                <a href="{{ route('admin.penilaian.index') }}"
                   class="rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Tabel --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        @if ($penilaians->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <p class="text-sm">Tidak ada data penilaian.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mentor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">M1</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">M2</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">M3</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">M4</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($penilaians as $penilaian)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $penilaian->enrollment->peserta->nama_lengkap ?? 'Peserta' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        @if($penilaian->enrollment && $penilaian->enrollment->programPelatihan)
                                            {{ $penilaian->enrollment->programPelatihan->nama_program }}
                                        @endif
                                        @if($penilaian->enrollment && $penilaian->enrollment->jenisKelas)
                                            <span class="capitalize">({{ $penilaian->enrollment->jenisKelas->nama_kelas }})</span>
                                        @endif
                                    </p>
                                </td>
                                <td class="px-6 py-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $penilaian->mentor->nama_lengkap }}</p>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300">
                                        {{ $penilaian->label_bulan }}
                                    </span>
                                </td>
                                {{-- Per minggu: rata-rata kls & pr --}}
                                @foreach ([1,2,3,4] as $m)
                                    @php $avg = round(($penilaian->{"m{$m}_kls"} + $penilaian->{"m{$m}_pr"}) / 2, 1); @endphp
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-sm font-medium text-amber-500">{{ $avg }}★</span>
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center gap-0.5 text-sm font-bold text-blue-600 dark:text-blue-400">
                                        {{ $penilaian->rata_rata }}★
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $penilaians->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
