@props(['withIcon' => false, 'icon' => '', 'text' => null])

@if (!$withIcon)
    <button {{ $attributes->merge(["class" => "focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium
    rounded-lg text-sm px-5 py-2.5 dark:focus:ring-yellow-900"]) }}
        type="button"
        >
        {{ $text ?? $slot }}
    </button>
@else
    <button {{ $attributes->merge(["class" => "focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium
    rounded-lg text-sm px-5 py-2.5 dark:focus:ring-yellow-900 inline-flex items-center"]) }} type="button">
        <span class="flex items-center justify-center w-5 h-5 me-2">
            <i class="{{ $icon }}"></i>
        </span>
        {{ $text ?? $slot }}
    </button>
@endif

