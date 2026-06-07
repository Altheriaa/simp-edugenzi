{{-- resources/views/components/badge-prioritas.blade.php --}}
@props(['prioritas'])

@php
$classes = match($prioritas) {
    'tinggi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
    'sedang' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
    'rendah' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
    default  => 'bg-gray-100 text-gray-600',
};

$labels = match($prioritas) {
    'tinggi' => '🔴 Tinggi',
    'sedang' => '🟡 Sedang',
    'rendah' => '🟢 Rendah',
    default  => $prioritas,
};
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $classes }}">
    {{ $labels }}
</span>
