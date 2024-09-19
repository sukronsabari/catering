<div>
    @foreach ($social_links as $index => $social_link)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pr-12 mb-4 relative">
            <div>
                <input
                    wire:model="social_links.{{ $index }}.platform"
                    type="text"
                    placeholder="Platform (Youtube | Instagram | Twitter | Facebook)"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-800 sm:text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                    name="social_links[{{ $index }}][platform]"
                />
                @error('social_links.' . $index . '.platform')
                <x-input.error :messages="$message" class="mt-2" />
                @enderror
            </div>
            <div>
                <input
                    wire:model="social_links.{{ $index }}.link"
                    type="url"
                    placeholder="Link (e.g., https://twitter.com/example)"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-800 sm:text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                    name="social_links[{{ $index }}][link]"
                />
                @error('social_links.' . $index . '.link')
                <x-input.error :messages="$message" class="mt-2" />
                @enderror
            </div>
            <button type="button" wire:click="removeSocialLink({{ $index }})" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 inline-flex text-center items-center absolute top-1/2 right-0 -translate-y-1/2">
                <span class="flex items-center justify-center w-10 h-10">
                    <i class="ti ti-x text-lg"></i>
                </span>
            </button>
        </div>
    @endforeach

    <button
        type="button"
        wire:click="addSocialLink"
        class="p-2 text-center inline-flex items-center text-gray-800 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
    >
        <span class="flex items-center justify-center w-5 h-5">
            <i class="ti ti-plus text-xl"></i>
        </span>
    </button>
</div>
