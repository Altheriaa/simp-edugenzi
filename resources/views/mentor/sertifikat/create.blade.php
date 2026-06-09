@extends('layouts.app')

@section('title', 'Terbitkan Sertifikat')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('mentor.sertifikat.index') }}"
           class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <span class="text-gray-300 dark:text-gray-700">/</span>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Terbitkan Sertifikat Baru</h1>
    </div>

    <x-alert />

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <p class="text-xs text-gray-400 mb-5">Nomor sertifikat akan digenerate otomatis dengan format <code class="bg-gray-100 dark:bg-gray-800 px-1 rounded">EDG/TAHUN/XXXX</code>.</p>

        <form action="{{ route('mentor.sertifikat.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                {{-- Peserta --}}
                <div>
                    <label for="peserta_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Peserta Didik <span class="text-red-500">*</span>
                    </label>
                    <select name="peserta_id" id="peserta_id" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('peserta_id') border-red-400 @enderror">
                        <option value="">-- Pilih Peserta --</option>
                        @foreach ($pesertas as $peserta)
                            <option value="{{ $peserta->id }}" class="dark:bg-gray-900" {{ old('peserta_id') == $peserta->id ? 'selected' : '' }}>
                                {{ $peserta->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                    @error('peserta_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Predikat --}}
                <div>
                    <label for="predikat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Predikat <span class="text-red-500">*</span>
                    </label>
                    <select name="predikat" id="predikat" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('predikat') border-red-400 @enderror">
                        <option value="">-- Pilih Predikat --</option>
                        @foreach ($predikatList as $p)
                            <option value="{{ $p }}" class="dark:bg-gray-900" {{ old('predikat') == $p ? 'selected' : '' }}>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                    @error('predikat')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Program --}}
                <div class="sm:col-span-2">
                    <label for="nama_program" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Program <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_program" id="nama_program" required
                           value="{{ old('nama_program') }}"
                           placeholder="cth. Program Magang Industri Kreatif 2026"
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('nama_program') border-red-400 @enderror">
                    @error('nama_program')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Terbit --}}
                <div>
                    <label for="tgl_terbit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Tanggal Terbit <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_terbit" id="tgl_terbit" required
                           value="{{ old('tgl_terbit', date('Y-m-d')) }}"
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('tgl_terbit') border-red-400 @enderror">
                    @error('tgl_terbit')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Terbitkan Sertifikat
                </button>
                <a href="{{ route('mentor.sertifikat.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
