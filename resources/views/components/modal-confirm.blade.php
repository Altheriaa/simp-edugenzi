{{-- resources/views/components/modal-confirm.blade.php --}}
@props(['id', 'title' => 'Konfirmasi Hapus', 'message' => 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.', 'action', 'method' => 'DELETE'])

<div x-data="{ open: false }" @open-modal-{{ $id }}.window="open = true">
    {{-- Trigger slot --}}
    <span @click="open = true">{{ $slot }}</span>

    {{-- Modal Overlay --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         @click.self="open = false">

        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-md w-full mx-4">

            {{-- Icon --}}
            <div class="flex items-center justify-center w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full mx-auto mb-4">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.07 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-center text-gray-900 dark:text-white mb-2">{{ $title }}</h3>
            <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6">{{ $message }}</p>

            <div class="flex gap-3">
                <button type="button" @click="open = false"
                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <form action="{{ $action }}" method="POST" class="flex-1">
                    @csrf
                    @method($method)
                    <button type="submit"
                        class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
