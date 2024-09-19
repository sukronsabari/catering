@props(['text' => ''])

<textarea {{ $attributes->merge(["class" => "block p-2.5 w-full text-sm text-gray-800 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-400
focus:border-green-400 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
dark:focus:ring-green-500 dark:focus:border-green-500"]) }}>{{ $slot->isEmpty() ? $text : $slot }}</textarea>
