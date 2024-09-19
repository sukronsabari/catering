<div
    x-data="{ open: false, inputDisabled: false }"
    @click.outside="open = false"
    class="w-full relative"
>
    <input
        type="text"
        class="w-full shadow-sm bg-gray-50 border border-gray-300 text-gray-800 sm:text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500 disabled:text-gray-500"
        x-bind:class="inputDisabled ? 'cursor-not-allowed' : ''"
        {{-- x-bind:disabled="inputDisabled || $wire.searchDisabled" --}}
        {{-- x-bind:readonly="inputDisabled" --}}
        wire:model.live.debounce.300ms="search"
        placeholder="Search users..."
        @focus="open = true"
        @keydown.arrow-down.prevent="if (open) { $wire.incrementHighlight() }"
        @keydown.arrow-up.prevent="if (open) { $wire.decrementHighlight() }"
        @keydown.enter.prevent="if (open) { $wire.selectUser(); inputDisabled = true; open = false }"
    />

    <!-- Input hidden untuk menyimpan user_id yang dipilih -->
    <input type="number" name="user_id" wire:model='selectedUser' class="hidden" />

    @if (!empty($users) && strlen($search) > 2)
    <ul class="absolute top-full left-0 border border-gray-300 mt-2 bg-white w-full z-10 rounded-lg overflow-x-hidden" x-show="open">
        @foreach ($users as $index => $user)
        <li @click="$wire.selectUser({{ $index }}); inputDisabled = true; open = false"
            class="p-2 hover:bg-gray-200 cursor-pointer {{ $highlightIndex == $index ? 'bg-gray-200' : '' }}">
            {{ $user->name }} ({{ $user->email }})
        </li>
        @endforeach
    </ul>

    @else
    <ul class="absolute top-full left-0 border border-gray-300 mt-2 bg-white w-full z-10 rounded-lg overflow-x-hidden"
        x-show="open">
        <li class="p-2 cursor-default">
            No user found!
        </li>
    </ul>
    @endif
</div>
