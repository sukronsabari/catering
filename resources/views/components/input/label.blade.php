@props(['value', 'required' => true])

<label {{ $attributes->merge(["class" => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"]) }}>
    {{ $value ?? $slot }}
    @if ($required)
        <span class="after:content-['*'] after:ml-0.5 after:text-red-500"></span>
    @endif
</label>
