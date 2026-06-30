@extends('layouts.app')

@section('title', 'Daftarkan Peserta ke Program')

@section('content')
<div class="space-y-6" x-data="enrollmentForm({{ $optionsJson }})">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.enrollment.index') }}"
           class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <span class="text-gray-300 dark:text-gray-700">/</span>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Daftarkan Peserta ke Program</h1>
    </div>

    <x-alert />

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('admin.enrollment.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                {{-- Peserta --}}
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Peserta Didik <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('user_id') border-red-400 @enderror">
                        <option value="">-- Pilih Peserta --</option>
                        @foreach($pesertas as $p)
                            <option value="{{ $p->id }}" {{ old('user_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->no_registrasi }} - {{ $p->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal Daftar --}}
                <div>
                    <label for="tgl_daftar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Tanggal Daftar <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_daftar" id="tgl_daftar" required
                           value="{{ old('tgl_daftar', date('Y-m-d')) }}"
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('tgl_daftar') border-red-400 @enderror">
                    @error('tgl_daftar')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Program Pelatihan --}}
                <div>
                    <label for="program_pelatihan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Program Pelatihan <span class="text-red-500">*</span>
                    </label>
                    <select name="program_pelatihan_id" id="program_pelatihan_id" required
                            x-model="selectedProgram"
                            @change="selectedKelas = ''; selectedDurasi = ''"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('program_pelatihan_id') border-red-400 @enderror">
                        <option value="">-- Pilih Program --</option>
                        @foreach($programs as $prog)
                            <option value="{{ $prog->id }}" {{ old('program_pelatihan_id') == $prog->id ? 'selected' : '' }}>
                                {{ $prog->nama_program }}
                            </option>
                        @endforeach
                    </select>
                    @error('program_pelatihan_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Jenis Kelas --}}
                <div>
                    <label for="jenis_kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Jenis Kelas
                    </label>
                    <select name="jenis_kelas_id" id="jenis_kelas_id"
                            x-model="selectedKelas"
                            @change="selectedDurasi = ''"
                            :disabled="!selectedProgram"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-blue-500 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('jenis_kelas_id') border-red-400 @enderror">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($jenisKelas as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('jenis_kelas_id') == $kelas->id ? 'selected' : '' }}
                                x-show="!selectedProgram || (options[selectedProgram] && options[selectedProgram][{{ $kelas->id }}])">
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_kelas_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Durasi Pelatihan --}}
                <div>
                    <label for="durasi_pelatihan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Durasi Pelatihan
                    </label>
                    <select name="durasi_pelatihan" id="durasi_pelatihan"
                            x-model="selectedDurasi"
                            :disabled="!selectedKelas"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm focus:border-blue-500 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">-- Pilih Durasi --</option>
                        <template x-if="selectedProgram && selectedKelas && options[selectedProgram] && options[selectedProgram][selectedKelas]">
                            <template x-for="dur in options[selectedProgram][selectedKelas]" :key="dur">
                                <option :value="dur" x-text="dur"></option>
                            </template>
                        </template>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Daftarkan
                </button>
                <a href="{{ route('admin.enrollment.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function enrollmentForm(options) {
    return {
        options,
        selectedProgram: '{{ old("program_pelatihan_id", "") }}',
        selectedKelas: '{{ old("jenis_kelas_id", "") }}',
        selectedDurasi: '{{ old("durasi_pelatihan", "") }}',
    }
}
</script>
@endsection
