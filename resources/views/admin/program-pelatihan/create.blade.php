@extends('layouts.app')

@section('title', 'Tambah Program Pelatihan')

@section('content')
<div class="max-w-2xl space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.program-pelatihan.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Program Pelatihan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Isi form berikut untuk menambah program pelatihan baru</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('admin.program-pelatihan.store') }}" method="POST">
            @csrf
            <div class="space-y-5">
                {{-- Nama Program --}}
                <div>
                    <label for="nama_program" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Program Pelatihan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_program" name="nama_program" value="{{ old('nama_program') }}"
                        class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nama_program') border-red-400 @enderror"
                        placeholder="Contoh: Digital Marketing, Fullstack Web Developer" required />
                    @error('nama_program') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-start">
                    <div class="flex h-5 items-center">
                        <input id="is_aktif" name="is_aktif" type="checkbox" value="1" checked
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:focus:ring-offset-gray-900" />
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_aktif" class="font-medium text-gray-700 dark:text-gray-300">Aktifkan Program</label>
                        <p class="text-gray-500 dark:text-gray-400">Program yang aktif akan muncul sebagai pilihan saat mendaftarkan peserta.</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('admin.program-pelatihan.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 h-11 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-800/80 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 h-11 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        Simpan Program
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
