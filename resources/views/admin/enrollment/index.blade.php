@extends('layouts.app')

@section('title', 'Manajemen Enrollment')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Enrollment</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftarkan peserta ke program pelatihan.</p>
        </div>
        <a href="{{ route('admin.enrollment.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Daftarkan Peserta
        </a>
    </div>

    <x-alert />

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.enrollment.index') }}"
          class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Cari</label>
                <div class="relative">
                    <span class="absolute -translate-y-1/2 pointer-events-none left-3 top-1/2 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama peserta, program..."
                        class="w-full rounded-lg border border-gray-300 bg-transparent pl-9 pr-3 py-1.5 text-sm focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"/>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                <select name="status" class="rounded-lg border border-gray-300 bg-transparent px-3 py-1.5 text-sm focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-1.5 text-sm font-medium text-white hover:bg-blue-700">Filter</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('admin.enrollment.index') }}" class="rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800">Reset</a>
            @endif
        </div>
    </form>

    {{-- Tabel --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        @if($enrollments->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <svg class="mx-auto h-12 w-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-sm">Belum ada enrollment.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Pelatihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas / Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($enrollments as $enrollment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $enrollment->peserta->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-500">{{ $enrollment->peserta->no_registrasi }}</p>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $enrollment->programPelatihan->nama_program ?? '-' }}
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $enrollment->jenisKelas->nama ?? '-' }}
                                    @if($enrollment->durasi_pelatihan)
                                        <span class="text-gray-400">· {{ $enrollment->durasi_pelatihan }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $enrollment->tgl_daftar->format('d M Y') }}
                                </td>
                                <td class="px-6 py-3">
                                    @if($enrollment->status === 'aktif')
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/40 dark:text-green-300">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">Selesai</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Toggle Status --}}
                                        <form action="{{ route('admin.enrollment.status', $enrollment) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $enrollment->status === 'aktif' ? 'selesai' : 'aktif' }}">
                                            <button type="submit"
                                                class="rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-1 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                                {{ $enrollment->status === 'aktif' ? 'Tandai Selesai' : 'Aktifkan Kembali' }}
                                            </button>
                                        </form>
                                        {{-- Hapus --}}
                                        <form action="{{ route('admin.enrollment.destroy', $enrollment) }}" method="POST"
                                              onsubmit="return confirm('Hapus enrollment ini? Data penilaian terkait tidak akan dihapus.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 dark:border-red-900 px-3 py-1 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
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
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
