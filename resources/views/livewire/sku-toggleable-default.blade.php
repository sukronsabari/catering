<div>
    @if ($is_default)
    <button class="bg-green-600 text-white px-4 py-2 rounded" disabled>Default</button>
    @else
    <button wire:click="setAsDefault()" class="bg-gray-200 text-black px-4 py-2 rounded hover:bg-gray-300">
        Set Default
    </button>
    @endif
</div>
