@extends('layouts.app')

@section('title', 'Edit Penilaian')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('mentor.penilaian.index') }}"
           class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <span class="text-gray-300 dark:text-gray-700">/</span>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Penilaian</h1>
    </div>

    <x-alert />

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('mentor.penilaian.update', $penilaian) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Peserta & Bulan Ke --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="peserta_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Peserta Didik <span class="text-red-500">*</span>
                    </label>
                    <select name="peserta_id" id="peserta_id" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('peserta_id') border-red-400 @enderror"
                            @change="$dispatch('peserta-changed', { id: $event.target.value })">
                        <option value="">-- Pilih Peserta --</option>
                        @foreach ($pesertas as $peserta)
                            <option value="{{ $peserta->id }}"
                                    class="dark:bg-gray-900"
                                    data-max="{{ $durasiMap[$peserta->id] ?? 6 }}"
                                    {{ (old('peserta_id', $penilaian->peserta_id) == $peserta->id) ? 'selected' : '' }}>
                                {{ $peserta->no_registrasi }} - {{ $peserta->nama_lengkap }}
                                @if($peserta->programPelatihan)
                                    - {{ $peserta->programPelatihan->nama_program }}
                                @endif
                                @if($peserta->jenisKelas)
                                    ({{ $peserta->jenisKelas->nama }})
                                @endif
                                @if($peserta->durasi_pelatihan)
                                    - {{ $peserta->durasi_pelatihan }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('peserta_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                @php
                    $currentPeserta = $pesertas->find(old('peserta_id', $penilaian->peserta_id));
                    $initialMax = $durasiMap[$currentPeserta?->id ?? 0] ?? 6;
                @endphp

                <div x-data="{
                        maxBulan: {{ $initialMax }},
                        bulanKe: {{ old('bulan_ke', $penilaian->bulan_ke) }},
                        updateMax(id) {
                            const sel = document.getElementById('peserta_id');
                            const opt = sel.querySelector('option[value=\''+id+'\']');
                            this.maxBulan = opt ? parseInt(opt.dataset.max || 6) : 6;
                            if (this.bulanKe > this.maxBulan) this.bulanKe = this.maxBulan;
                        }
                    }"
                    @peserta-changed.window="updateMax($event.detail.id)">
                    <label for="bulan_ke" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Periode Pelatihan <span class="text-red-500">*</span>
                        <span class="font-normal text-gray-400" x-text="'(maks. Bulan Ke-' + maxBulan + ')'"></span>
                    </label>
                    <select name="bulan_ke" id="bulan_ke" required x-model="bulanKe"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('bulan_ke') border-red-400 @enderror">
                        <template x-for="n in maxBulan" :key="n">
                            <option :value="n" :selected="n == bulanKe" class="dark:bg-gray-900"
                                    x-text="'Bulan Ke-' + n"></option>
                        </template>
                    </select>
                    @error('bulan_ke')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Grid Penilaian Bintang --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Nilai Bintang EAC <span class="font-normal text-gray-400">(skala 2–5)</span>
                </h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([1,2,3,4] as $minggu)
                        <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30 p-4 space-y-3">
                            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Minggu {{ $minggu }}</h4>

                            {{-- Kelas --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Kelas</label>
                                <div class="flex items-center gap-1" x-data="starRating('m{{ $minggu }}_kls', {{ old('m'.$minggu.'_kls', $penilaian->{'m'.$minggu.'_kls'}) }})">
                                    @for ($i = 2; $i <= 5; $i++)
                                        <button type="button" @click="setValue({{ $i }})"
                                                class="transition-colors"
                                                :class="value >= {{ $i }} ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600'">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </button>
                                    @endfor
                                    <input type="hidden" name="m{{ $minggu }}_kls" :value="value">
                                    <span class="ml-1 text-sm font-semibold text-gray-700 dark:text-gray-300" x-text="value"></span>
                                </div>
                            </div>

                            {{-- Proyek --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Proyek</label>
                                <div class="flex items-center gap-1" x-data="starRating('m{{ $minggu }}_pr', {{ old('m'.$minggu.'_pr', $penilaian->{'m'.$minggu.'_pr'}) }})">
                                    @for ($i = 2; $i <= 5; $i++)
                                        <button type="button" @click="setValue({{ $i }})"
                                                class="transition-colors"
                                                :class="value >= {{ $i }} ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600'">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </button>
                                    @endfor
                                    <input type="hidden" name="m{{ $minggu }}_pr" :value="value">
                                    <span class="ml-1 text-sm font-semibold text-gray-700 dark:text-gray-300" x-text="value"></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Catatan --}}
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea name="catatan" id="catatan" rows="3" placeholder="Catatan tambahan untuk peserta..."
                          class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('catatan', $penilaian->catatan) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('mentor.penilaian.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function starRating(name, initialValue) {
    return {
        value: initialValue,
        setValue(v) { this.value = v; }
    }
}
</script>
@endsection
