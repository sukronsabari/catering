<div class="p-2 bg-white border border-gray-200 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
    <div class="flex flex-col gap-2">
        @foreach ($options['keys'] as $key)
            @if ($key['is_table'])
                <div class="font-bold">{{ $key['label'] }} :</div>
                @isset($key['component'])
                    @include($key['component'], ['row' => $row])
                @else
                    <div class="text-red-500">Table component not found!</div>
                @endisset
            @else
                {{-- Non-table data --}}
                <div class="flex items-center gap-3">
                    <div class="font-bold">{{ $key['label'] }} :</div>
                    <div>{{ $row[$key['value']] }}</div>
                </div>
            @endif
        @endforeach
    </div>
</div>
