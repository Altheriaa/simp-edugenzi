@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola User (Admin & Mentor)</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar admin dan mentor sistem</p>
            </div>
            <a href="{{ route('admin.pengguna.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pengguna
            </a>
        </div>

        <x-alert />

        {{-- Tabel --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            {{-- Search Bar --}}
            <div class="p-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                <form action="{{ route('admin.pengguna.index') }}" method="GET" class="max-w-md">
                    <div class="relative">
                        <span class="absolute -translate-y-1/2 pointer-events-none left-4 top-1/2 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama, NIK, email, no HP, alamat..."
                            class="h-10 w-full rounded-xl border border-gray-300 bg-white dark:bg-gray-800 px-4 pl-11 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700" />
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                #</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                NIK</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nama</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Email</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                No HP</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Alamat</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($penggunas as $pengguna)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration + ($penggunas->currentPage() - 1) * $penggunas->perPage() }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $pengguna->nik }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $pengguna->nama_lengkap }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $pengguna->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $pengguna->no_hp }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $pengguna->alamat }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                                {{ $pengguna->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : '' }}
                                                                {{ $pengguna->role === 'mentor' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                                                {{ $pengguna->role === 'peserta_didik' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}">
                                        {{ ucwords(str_replace('_', ' ', $pengguna->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <x-badge-status :status="$pengguna->status" />
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.pengguna.edit', $pengguna) }}"
                                            class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 transition-colors">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <x-modal-confirm :id="'delete-' . $pengguna->id"
                                            :action="route('admin.pengguna.destroy', $pengguna)">
                                            <button type="button"
                                                class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </x-modal-confirm>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-10 text-center text-sm text-gray-400">
                                    Belum ada pengguna terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($penggunas->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $penggunas->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection