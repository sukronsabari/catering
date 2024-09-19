@props(['withIcon' => false, 'icon' => '', 'text' => null])

@if (!$withIcon)
<a {{ $attributes->merge(["class" => "text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"]) }}>
    {{ $text ?? $slot }}
</a>
@else
<button {{ $attributes->merge(["class" => "text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none
    focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-gray-800
    dark:hover:bg-gray-700 dark:focus:ring-gray-700"]) }} type="button">
    <span class="flex items-center justify-center w-5 h-5 me-2">
        <i class="{{ $icon }}"></i>
    </span>
    {{ $text ?? $slot }}
</button>
@endif
