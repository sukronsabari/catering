@props(['withIcon' => false, 'icon' => '', 'text' => null])

@if (!$withIcon)
    <button {{ $attributes->merge(["class" => "text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"]) }} type="button">
        {{ $text ?? $slot }}
    </button>
@else
    <button {{ $attributes->merge(["class" => "text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 inline-flex text-center items-center"]) }} type="button">
        <span class="flex items-center justify-center w-4 h-4 me-2">
            <i class="{{ $icon }}"></i>
        </span>
        {{ $text ?? $slot }}
    </button>
@endif
