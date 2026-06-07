@extends('layouts.app')

@section('title', 'Tugas Saya')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tugas Saya</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar semua tugas yang diberikan kepada Anda</p>
    </div>

    <x-alert />

    @if ($tugas->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-16 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada tugas</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tugas akan muncul di sini ketika mentor memberikannya kepada Anda.</p>
        </div>
    @else
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tugas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prioritas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deadline</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($tugas as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $item->judul_task }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $item->proyek->nama_proyek }}</td>
                                <td class="px-6 py-4"><x-badge-prioritas :prioritas="$item->prioritas" /></td>
                                <td class="px-6 py-4 text-sm {{ $item->deadline?->isPast() && $item->status_task !== 'done' ? 'text-red-500 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $item->deadline?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4"><x-badge-status :status="$item->status_task" /></td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('peserta.tugas.show', $item) }}"
                                       class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                        Buka →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($tugas->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $tugas->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
