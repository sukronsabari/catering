<div class="fixed top-5 right-5 z-[10000000000000]">
    <div x-data="{ show: true }"
        x-init="setTimeout(() => show = true, 100); setTimeout(() => show = false, {{ $duration }});" x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-x-20"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform translate-x-20"
        class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 rounded-lg shadow bg-white dark:text-gray-400 dark:bg-gray-800 {{ $type === 'success' ? 'bg-white text-green-500' : '' }} {{ $type === 'danger' ? 'bg-white text-red-500' : '' }} {{ $type === 'warning' ? 'bg-white text-orange-500' : '' }}"
        role="alert">
        <div
            class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg bg-gray-100 text-gray-600 {{ $type === 'success' ? 'bg-green-100 text-green-500' : '' }} {{ $type === 'danger' ? 'bg-red-100 text-red-500' : '' }} {{ $type === 'warning' ? 'bg-orange-100 text-orange-500' : '' }}">
            @if($type === 'success')
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
            </svg>
            @elseif ($type === 'danger')
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
            </svg>
            @elseif ($type === 'warning')
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z" />
            </svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-info-small">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 9h.01" />
                <path d="M11 12h1v4h1" />
            </svg>
            @endif
            {{-- <span class="sr-only" x-text="type.charAt(0).toUpperCase() + type.slice(1) + ' icon'"></span> --}}
        </div>
        <div class="ms-3 text-sm font-normal">{{ $message }}</div>
        <button type="button" x-on:click="show = false"
            class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
            aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>
</div>
