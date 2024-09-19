@php
    $activeOptions = [
        ['label' => 'Active', 'value' => '1', 'selected' => true],
        ['label' => 'Non Active', 'value' => '0', 'selected' => false],
    ];
@endphp

<x-admin-layout>
    {{-- Header --}}
    <div
        class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <x-breadcrumbs />
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Create new slider</h1>
        </div>
    </div>

    {{-- Content --}}
    <div class="px-4 pt-6">
        <div
            class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @method('POST')
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="title">Title</x-input.label>
                        <x-input.text id="title" type="text" name="title" class="mt-1" value="{{ old('title') }}" placeholder="E.g: New Arrivals" required />
                        @error('title')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="subtitle">Subtitle</x-input.label>
                        <x-input.text id="subtitle" type="text" name="subtitle" class="mt-1" value="{{ old('subtitle') }}" placeholder="E.g: Men's Fashion" required />
                        @error('subtitle')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="starting_price">Starting Price</x-input.label>
                        <x-input.text id="starting_price" type="text" name="starting_price" inputmode="numeric" class="mt-1" value="{{ old('starting_price', 0) }}" required />
                        @error('starting_price')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="url">Button Url</x-input.label>
                        <x-input.text id="url" type="text" name="url" class="mt-1" value="{{ old('url') }}" placeholder="E.g: /products/hot-promo" required />
                        @error('url')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="position">Position</x-input.label>
                        <x-input.text id="position" type="text" name="position" class="mt-1" value="{{ old('position') }}" placeholder="E.g: 1" required />
                        @error('position')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="is_active">Status</x-input.label>
                        <x-input.select id="is_active" name="is_active" :options="$activeOptions" class="mt-1" required />
                        @error('is_active')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6">
                        <x-input.label for="image">Image</x-input.label>
                        <x-input.file id="image" type="file" name="image" />
                        @error('image')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 flex justify-end">
                        <div class="flex items-center space-x-3">
                            <x-button type="submit">Create</x-button>
                            <x-button.light type="submit" name="create_another">Create & Create Another</x-button.light>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
