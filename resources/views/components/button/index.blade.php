@props(['withIcon' => false, 'icon' => '', 'text' => null])

@if (!$withIcon)
    <button {{ $attributes->merge(["class" => "text-white bg-green-400 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600"]) }}
        type="button"
    >
        {{ $text ?? $slot }}
    </button>
@else
    <button {{ $attributes->merge(["class" => "text-white bg-green-400 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 text-center inline-flex items-center"]) }} type="button">
        <span class="flex items-center justify-center w-5 h-5 me-2">
            <i class="{{ $icon }}"></i>
        </span>
        {{ $text ?? $slot }}
    </button>
@endif
