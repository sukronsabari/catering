@php
    $activeOptions = [
        [
            'label' => 'Active',
            'value' => '1',
            'selected' => old('is_active', $slider->is_active) == 1
        ],
        [
            'label' => 'Non Active',
            'value' => '0',
            'selected' => old('is_active', $slider->is_active) == 0
        ],
    ];
@endphp

<x-admin-layout>
    {{-- Header --}}
    <div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <x-breadcrumbs />
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Edit Slider</h1>
        </div>
    </div>

    {{-- Content --}}
    <div class="px-4 pt-6">
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
            <form action="{{ route('admin.sliders.update', ['slider' => $slider, 'callbackUrl' => request()->query('callbackUrl')]) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="title">Title</x-input.label>
                        <x-input.text id="title" type="text" name="title" class="mt-1" value="{{ old('title', $slider->title) }}" required />
                        @error('title')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="subtitle">Subtitle</x-input.label>
                        <x-input.text id="subtitle" type="text" name="subtitle" class="mt-1" value="{{ old('subtitle', $slider->subtitle) }}" required />
                        @error('subtitle')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="starting_price">Starting Price</x-input.label>
                        <x-input.text id="starting_price" type="text" name="starting_price" inputmode="numeric" class="mt-1" value="{{ old('starting_price', $slider->starting_price) }}" required />
                        @error('starting_price')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="url">Button Url</x-input.label>
                        <x-input.text id="url" type="text" name="url" class="mt-1" value="{{ old('url', $slider->url) }}" required />
                        @error('url')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="position">Position</x-input.label>
                        <x-input.text id="position" type="text" name="position" class="mt-1" value="{{ old('position', $slider->position) }}" required />
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

                    <div class="col-span-6 bg-white border border-gray-200 rounded-lg shadow-sm  dark:border-gray-700 p-4 sm:p-6 dark:bg-gray-800">
                        <div class="flex flex-col gap-4">
                            <div>
                                <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">Image Preview</h3>
                                <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">Upload a new image to replace the category image</p>
                                <img id="slider-image-preview" class="rounded-lg w-full h-full object-cover aspect-[1300/500]" src="{{ \Illuminate\Support\Facades\Storage::url($slider->image) }}" alt="Image Profile">
                            </div>
                            <div>
                                <x-input.file id="image-input" type="file" name="image" />
                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    JPG, JPEG or PNG. Max size of 5MB with 13:5 Ratio
                                </div>
                                @error('image')
                                <x-input.error :messages="$message" class="mt-2" />
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6 flex justify-end">
                        <x-button type="submit">Save</x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const input = document.getElementById('image-input');
        const previewPhoto = () => {
            const file = input?.files;

            if (file) {
                const fileReader = new FileReader();
                const preview = document.getElementById('slider-image-preview');

                fileReader.onload = function (event) {
                    preview.setAttribute('src', event.target.result);
                }

                fileReader.readAsDataURL(file[0]);
            }
        }

        input.addEventListener("change", previewPhoto);
    </script>
</x-admin-layout>
