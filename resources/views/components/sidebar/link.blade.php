@props([
    'type' => '',
    'icon' => '',
    'text' => '',
    'href' => '#',
    'collapseItems' => [],
    'valueIndicator' => null,
])

@if ($type === 'single')
    <a href="{{ $href }}"
        class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
        @if ($icon)
            <i class="{{ $icon }} block flex-shrink-0 text-2xl text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
        @endif

        <span class="ml-3">{{ $text }}</span>

        @if ($valueIndicator)
            <span
                class="inline-flex items-center justify-center w-3 h-3 p-3 ms-auto text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $valueIndicator }}</span>
        @endif
    </a>
@elseif ($type === 'collapse')
    <div x-data="{ open: false }">
        <button
            x-on:click="open = !open"
            type="button"
            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
        >
            @if ($icon)
                <i class="{{ $icon }} block flex-shrink-0 text-2xl text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"></i>
            @endif
            <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item="">{{ $text }}</span>
            <svg sidebar-toggle-item="" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
        <ul x-show="open" x-bind:class="!open ? 'hidden' : ''" class="py-2 space-y-2">
            @foreach ($collapseItems as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                        class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                        {{ $item['text'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
