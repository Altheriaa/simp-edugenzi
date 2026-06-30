{{-- resources/views/components/badge-status.blade.php --}}
@props(['status'])

@php
$classes = match($status) {
    'berjalan'    => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
    'selesai'     => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
    'tertunda'    => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
    'to_do'       => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
    'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
    'done'        => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
    'aktif'       => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
    'nonaktif'    => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
    'lulus'       => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',
    default       => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
};

$labels = match($status) {
    'berjalan'    => 'Berjalan',
    'selesai'     => 'Selesai',
    'tertunda'    => 'Tertunda',
    'to_do'       => 'To Do',
    'in_progress' => 'In Progress',
    'done'        => 'Done',
    'aktif'       => 'Aktif',
    'nonaktif'    => 'Nonaktif',
    'lulus'       => 'Lulus / Alumni',
    default       => ucfirst($status),
};
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $classes }}">
    {{ $labels }}
</span>
