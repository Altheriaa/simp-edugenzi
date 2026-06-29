@extends('layouts.app')

@section('title', 'Edit Program Pelatihan')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.program-pelatihan.index') }}"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Program Pelatihan</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola detail program dan kombinasi durasi kelas
                </p>
            </div>
        </div>

        <x-alert />

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Edit Form --}}
            <div class="lg:col-span-5">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Detail Program</h2>
                    <form action="{{ route('admin.program-pelatihan.update', $program) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-5">
                            {{-- Nama Program --}}
                            <div>
                                <label for="nama_program"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Nama Program Pelatihan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_program" name="nama_program"
                                    value="{{ old('nama_program', $program->nama_program) }}"
                                    class="h-11 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nama_program') border-red-400 @enderror"
                                    required />
                                @error('nama_program') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Status Aktif --}}
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="is_aktif" name="is_aktif" type="checkbox" value="1" {{ $program->is_aktif ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:focus:ring-offset-gray-900" />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_aktif" class="font-medium text-gray-700 dark:text-gray-300">Aktifkan
                                        Program</label>
                                    <p class="text-gray-500 dark:text-gray-400">Program yang tidak aktif tidak akan muncul
                                        saat pendaftaran peserta didik.</p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div
                                class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                                <a href="{{ route('admin.program-pelatihan.index') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 h-11 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-800/80 transition-colors">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 h-11 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                                    Perbarui
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kelola Durasi & Kelas --}}
            <div class="lg:col-span-7">
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Kombinasi Kelas & Durasi</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Tentukan jenis kelas dan durasi waktu
                            pelatihan yang valid untuk program ini</p>
                    </div>

                    {{-- List Kombinasi yang sudah ada --}}
                    <div class="border border-gray-100 dark:border-gray-800 rounded-xl overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                            <thead class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <th
                                        class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        Jenis Kelas</th>
                                    <th
                                        class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        Durasi Pelatihan</th>
                                    <th
                                        class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        Durasi (Bulan)</th>
                                    <th
                                        class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        Dipegang Oleh</th>
                                    <th
                                        class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($combinations as $comb)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $comb->jenisKelas->nama }}
                                            <span
                                                class="text-xs text-gray-400 font-normal">({{ $comb->jenisKelas->slug }})</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $comb->durasi_pelatihan }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-center text-sm font-medium text-brand-600 dark:text-brand-400">
                                            {{ $comb->durasi_bulan }} Bulan
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                            @if($comb->dipegang_oleh)
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">
                                                    {{ $comb->dipegang_oleh }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Belum ada</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <form action="{{ route('admin.program-kelas-durasi.destroy', $comb->id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kombinasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-xs font-semibold dark:hover:text-red-400">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-400">
                                            Belum ada kombinasi kelas dan durasi yang dikonfigurasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Form tambah kombinasi --}}
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/30 rounded-xl border border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Tambah Kombinasi Baru</h3>
                        <form action="{{ route('admin.program-pelatihan.add-durasi', $program) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="jenis_kelas_id"
                                        class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                                        Jenis Kelas <span class="text-red-500">*</span>
                                    </label>
                                    <select id="jenis_kelas_id" name="jenis_kelas_id" required
                                        class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                        <option value="" disabled selected>Pilih kelas...</option>
                                        @foreach ($jenisKelas as $kelas)
                                            <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="durasi_pelatihan"
                                        class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                                        Label Durasi Pelatihan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="durasi_pelatihan" name="durasi_pelatihan" required
                                        class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                        placeholder="Contoh: 3 Bulan, 12 X Pertemuan" />
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="durasi_bulan"
                                        class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                                        Durasi Aktual (Bulan) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="durasi_bulan" name="durasi_bulan" required min="1" max="60"
                                        class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                        placeholder="Contoh: 3" />
                                    <p class="mt-1 text-[11px] text-gray-400">Berapa bulan aktual untuk keperluan
                                        perhitungan batas maksimal penilaian bulanan.</p>
                                </div>
                                <div class="sm:col-span-2 flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 h-10 text-xs font-medium text-white hover:bg-blue-700 transition-colors">
                                        Tambah Kombinasi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection