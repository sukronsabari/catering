<div
    class="fixed left-0 right-0 z-[9999999] bg-gray-900/50 dark:bg-gray-900/90 items-center justify-center hidden overflow-x-hidden overflow-y-auto top-0 md:inset-0 h-modal sm:h-full"
    x-cloak
    x-show="showAddSliderModal"
    x-bind:class="{ 'hidden': !showAddSliderModal, 'flex': showAddSliderModal }"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div class="relative w-full h-full max-w-2xl px-4 pt-8 md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold dark:text-white">
                    Add new slider
                </h3>
                <button
                    x-on:slider-created.window="showAddSliderModal = false"
                    x-on:click="showAddSliderModal = false"
                    type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white">
                    <span class="flex items-center justify-center w-5 h-5">
                        <i class="ph ph-x text-xl"></i>
                    </span>
                </button>
            </div>
            <form wire:submit="save" novalidate>
                <!-- Modal body -->
                @csrf
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="title">Title</x-input.label>
                            <x-input.text wire:model="title" id="title" type="text" class="mt-1"
                                placeholder="E.g: New Arrivals" required />
                            @error('title')
                                <x-input.error :messages="$message" class="mt-2" />
                            @enderror
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="subtitle">Subtitle</x-input.label>
                            <x-input.text wire:model="subtitle" id="subtitle" type="text" class="mt-1"
                                placeholder="E.g: Men's Fashion" required />
                            @error('subtitle')
                                <x-input.error :messages="$message" class="mt-2" />
                            @enderror
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="starting_price">Starting Price</x-input.label>
                            <x-input.text wire:model="starting_price" id="starting_price" type="text"
                                inputmode="numeric" class="mt-1" required />
                            @error('starting_price')
                                <x-input.error :messages="$message" class="mt-2" />
                            @enderror
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="url">Button Url</x-input.label>
                            <x-input.text wire:model="url" id="url" type="text" class="mt-1"
                                placeholder="E.g: /products/hot-promo" required />
                            @error('url')
                                <x-input.error :messages="$message" class="mt-2" />
                            @enderror
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="position">Position</x-input.label>
                            <x-input.text wire:model="position" id="position" type="text" class="mt-1"
                                placeholder="E.g: 1" required />
                            @error('position')
                                <x-input.error :messages="$message" class="mt-2" />
                            @enderror
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="is_active">Status</x-input.label>
                            <x-input.select wire:model="is_active" id="is_active" :options="$activeOptions"
                                class="mt-1" required />
                            @error('is_active')
                                <x-input.error :messages="$message" class="mt-2" />
                            @enderror
                        </div>
                        <div class="col-span-6">
                            <x-input.label for="banner">Banner</x-input.label>
                            <input wire:model="banner" id="banner" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />
                            @error('banner')
                                <x-input.error :messages="$message" class="mt-2" />
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                    <button
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        type="submit">
                        Add Slider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


