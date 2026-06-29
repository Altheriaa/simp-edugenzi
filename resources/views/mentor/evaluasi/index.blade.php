@extends('layouts.app')

@section('title', 'Evaluasi Peserta')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Evaluasi Peserta</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Berikan catatan evaluasi hasil kerja proyek kepada peserta didik.</p>
        </div>
    </div>

    <x-alert />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- List Evaluasi (Left 2 cols) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Riwayat Evaluasi</h3>

                {{-- Search --}}
                <div class="mb-4">
                    <form action="{{ route('mentor.evaluasi.index') }}" method="GET" class="max-w-md">
                        <div class="relative">
                            <span class="absolute -translate-y-1/2 pointer-events-none left-3 top-1/2 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama peserta, proyek, atau catatan..."
                                class="h-9 w-full rounded-lg border border-gray-300 bg-transparent pl-9 pr-3 text-sm text-gray-800 focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white placeholder:text-gray-400" />
                        </div>
                    </form>
                </div>
                @if ($evaluasis->isEmpty())
                    <div class="py-12 text-center text-gray-400">
                        <svg class="mx-auto h-12 w-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-sm">Belum ada evaluasi yang diberikan.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($evaluasis as $evaluasi)
                            <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-800 dark:bg-gray-800/30">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $evaluasi->peserta->nama_lengkap }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Proyek: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $evaluasi->proyek->nama_proyek }}</span>
                                        </p>
                                    </div>
                                    <span class="text-xs text-gray-400">
                                        {{ $evaluasi->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <div class="mt-3 text-sm text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-900 p-3 rounded-lg border border-gray-100 dark:border-gray-800">
                                    {{ $evaluasi->catatan }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $evaluasis->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Form Evaluasi (Right 1 col) -->
        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Beri Evaluasi</h3>

                <form action="{{ route('mentor.evaluasi.store') }}" method="POST" class="space-y-4"
                      x-data="{
                          pesertaMap: {{ $pesertaMapJson }},
                          selectedProyek: '{{ old('proyek_id') }}',
                          selectedPeserta: '{{ old('peserta_id') }}'
                      }">
                    @csrf

                    <!-- Proyek -->
                    <div>
                        <label for="proyek_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Proyek <span class="text-red-500">*</span>
                        </label>
                        <select name="proyek_id" id="proyek_id" required
                                x-model="selectedProyek"
                                @change="selectedPeserta = ''"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="" class="dark:bg-gray-900">-- Pilih Proyek --</option>
                            @foreach ($proyeks as $proyek)
                                <option value="{{ $proyek->id }}" class="dark:bg-gray-900" {{ old('proyek_id') == $proyek->id ? 'selected' : '' }}>
                                    {{ $proyek->nama_proyek }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Peserta -->
                    <div>
                        <label for="peserta_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Peserta Didik <span class="text-red-500">*</span>
                        </label>
                        <select name="peserta_id" id="peserta_id" required
                                x-model="selectedPeserta"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="" class="dark:bg-gray-900">-- Pilih Peserta --</option>
                            <template x-if="selectedProyek && pesertaMap[selectedProyek]">
                                <template x-for="p in pesertaMap[selectedProyek]" :key="p.id">
                                    <option :value="p.id" x-text="p.nama_lengkap" class="dark:bg-gray-900"></option>
                                </template>
                            </template>
                        </select>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Catatan Evaluasi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="catatan" id="catatan" rows="5" required placeholder="Tuliskan catatan evaluasi hasil belajar..."
                                  class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('catatan') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Simpan Evaluasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
