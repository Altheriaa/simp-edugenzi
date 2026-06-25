@extends('layouts.app')

@section('title', 'Edit Jenis Kelas')

@section('content')
<div class="max-w-2xl space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.jenis-kelas.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Jenis Kelas</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Perbarui detail tipe/jenis kelas sistem</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('admin.jenis-kelas.update', $jenisKelas->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                {{-- Nama Kelas --}}
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Jenis Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $jenisKelas->nama) }}"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nama') border-red-400 @enderror"
                        required />
                    @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Slug <span class="text-xs text-gray-400 font-normal">(Opsional - akan dibuat otomatis jika dikosongkan)</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $jenisKelas->slug) }}"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('slug') border-red-400 @enderror" />
                    @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-start">
                    <div class="flex h-5 items-center">
                        <input id="is_aktif" name="is_aktif" type="checkbox" value="1" {{ $jenisKelas->is_aktif ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:focus:ring-offset-gray-900" />
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_aktif" class="font-medium text-gray-700 dark:text-gray-300">Aktifkan Jenis Kelas</label>
                        <p class="text-gray-500 dark:text-gray-400">Kelas yang tidak aktif tidak akan muncul sebagai pilihan saat mendaftarkan peserta didik.</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('admin.jenis-kelas.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 h-11 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-800/80 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 h-11 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        Perbarui Kelas
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
