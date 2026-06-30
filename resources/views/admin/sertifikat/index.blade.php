@extends('layouts.app')

@section('title', 'Data Sertifikat')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Sertifikat</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau seluruh sertifikat yang diterbitkan oleh mentor.
            </p>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.sertifikat.index') }}"
            class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Cari</label>
                    <div class="relative">
                        <span class="absolute -translate-y-1/2 pointer-events-none left-3 top-1/2 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Cari nama peserta, nomor sertifikat, program..."
                            class="w-full rounded-lg border border-gray-300 bg-transparent pl-9 pr-3 py-1.5 text-sm text-gray-800 focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>
                </div>
                <div>
                    <label for="peserta_id"
                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Peserta</label>
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
                <button type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-1.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Filter
                </button>
                @if (request()->hasAny(['peserta_id', 'search']))
                    <a href="{{ route('admin.sertifikat.index') }}"
                        class="rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>

    {{-- Tabel --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        @if ($sertifikats->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <p class="text-sm">Tidak ada data sertifikat.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Diterbitkan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mentor
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($sertifikats as $sertifikat)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-3">
                                    <span
                                        class="font-mono text-xs text-gray-500 dark:text-gray-400">{{ $sertifikat->nomor_sertifikat }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $sertifikat->peserta->nama_lengkap }}</p>
                                </td>
                                <td class="px-6 py-3">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                        {{ $sertifikat->nama_program }}</p>
                                </td>

                                <td class="px-6 py-3">
                                    <span
                                        class="text-sm text-gray-600 dark:text-gray-400">{{ $sertifikat->tgl_terbit->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    <span
                                        class="text-sm text-gray-600 dark:text-gray-400">{{ $sertifikat->mentor->nama_lengkap }}</span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('admin.sertifikat.print', $sertifikat) }}" target="_blank"
                                        class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Cetak
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $sertifikats->links() }}
            </div>
        @endif
    </div>
    </div>
@endsection