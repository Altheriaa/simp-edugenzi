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
            <div>
                <label for="peserta_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Peserta</label>
                <select name="peserta_id" id="peserta_id"
                        class="rounded-lg border border-gray-300 bg-transparent px-3 py-1.5 text-sm text-gray-800 focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">Semua Peserta</option>
                    @foreach ($pesertas as $p)
                        <option value="{{ $p->id }}" class="dark:bg-gray-900" {{ request('peserta_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_lengkap }}
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
            @if (request()->hasAny(['peserta_id','bulan_ke']))
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
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $penilaian->peserta->nama_lengkap }}</p>
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
