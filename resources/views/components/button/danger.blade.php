@props(['withIcon' => false, 'icon' => '', 'text' => null])

@if (!$withIcon)
    <button {{ $attributes->merge(["class" => "text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"]) }}
        type="button"
    >
        {{ $text ?? $slot }}
    </button>
@else
    <button {{ $attributes->merge(["class" => "text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-red-600 dark:hover:bg-red-800 dark:focus:ring-red-900"]) }} type="button">
        <span class="flex items-center justify-center w-4 h-4 me-2">
            <i class="{{ $icon }}"></i>
        </span>
        {{ $text ?? $slot }}
    </button>
@endif
