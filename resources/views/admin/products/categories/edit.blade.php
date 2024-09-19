@php
    $featuredOptions = [
        ['label' => 'Featured', 'value' => '1', 'selected' => old('featured', $category->featured) == 1],
        ['label' => 'Not Featured', 'value' => '0', 'selected' => old('featured', $category->featured) == 0],
    ];
@endphp

<x-admin-layout>
    {{-- Header --}}
    <x-header heading="Edit Category" />

    {{-- Content --}}
    <div class="px-4 pt-6">
        <div
            class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
            <form action="{{ route('admin.products.categories.update', ['category' => $category, 'callbackUrl' => request()->query('callbackUrl')]) }}" method="POST" enctype="multipart/form-data"
                novalidate>
                @method('PUT')
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="name">Name</x-input.label>
                        <x-input.text id="name" type="text" name="name" class="mt-1"
                            value="{{ old('name', $category->name) }}" value="{{ $category->name }}" required />
                        @error('name')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="featured">Featured</x-input.label>
                        <x-input.select id="featured" name="featured" :options="$featuredOptions" class="mt-1" required />
                        @error('featured')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="description" :required="false">Description</x-input.label>
                        <x-input.text-area rows="4" id="description" name="description" class="mt-1" :text="old('description', $category->description)" />
                        @error('description')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <div
                            x-data="{
                                open: false,
                                selectedCategoryId: {{ json_encode(optional($category->parent_id)) }},
                                selectedCategoryName: {{ json_encode(optional($category->parent->name)) }},

                                toggleDropdown() {
                                    this.open = !this.open;
                                },
                                selectCategory(id, name) {
                                    this.selectedCategoryId = id;
                                    this.selectedCategoryName = name;

                                    this.open = false;
                                },
                                isSelected(categoryId) {
                                    return this.selectedCategoryId == categoryId;
                                }
                            }"
                            class="relative"
                        >
                            <!-- Dropdown Button -->
                            <x-input.label for="" :required="false">Parent</x-input.label>
                            <button type="button" @click="toggleDropdown"
                                class="w-full text-sm flex px-5 py-2.5 items-center justify-between border border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm">
                                <span x-text="selectedCategoryName || 'Select Parent'"></span>
                                <svg class="w-2.5 h-2.5 text-sm" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            @error('parent_id')
                                <x-input.error :messages="$message" class="mt-2" />
                            @enderror

                            <!-- Dropdown Content -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-10 left-0 mt-1 w-full text-gray-700 bg-white dark:bg-gray-700 dark:text-gray-200 border border-gray-200 rounded-lg shadow-lg max-h-64 overflow-y-auto">
                                <ul class="list-none p-2 text-sm">
                                    <div class="py-2 px-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        x-on:click="selectCategory(null, null)">
                                        <span>No Parent (Null)</span>
                                    </div>

                                    @foreach ($categories as $cat)
                                        @include('components.partials.category-item', [
                                            'category' => $cat,
                                            'level' => 0,
                                        ])
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Hidden Input for Form Submission -->
                            <input type="number" class="hidden" x-model="selectedCategoryId" name="parent_id" />
                        </div>
                    </div>
                    <div class="col-span-6 md:col-span-3 bg-white border border-gray-200 rounded-lg shadow-sm  dark:border-gray-700 p-4 sm:p-6 dark:bg-gray-800">
                        <div class="flex flex-col gap-4">
                            <div>
                                <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">Image Preview</h3>
                                @if ($category->image)
                                    <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">Upload a new image to replace the category image</p>
                                @else
                                    <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-medium">No Image</span>
                                        Upload a new image to set the category image
                                    </p>
                                @endif
                                <img id="category-image-preview" class="rounded-lg w-full h-full min-h-44 aspect-video object-contain"
                                    src="{{ \Illuminate\Support\Facades\Storage::url($category->image) }}" alt="Image preview">
                            </div>
                            <div>
                                <x-input.file id="image-input" type="file" name="image" />
                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    JPG, JPEG, or PNG. Max size of 5MB
                                </div>
                                @error('image')
                                <x-input.error :messages="$message" class="mt-2" />
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6 md:col-span-3 bg-white border border-gray-200 rounded-lg shadow-sm  dark:border-gray-700 p-4 sm:p-6 dark:bg-gray-800">
                        <div class="flex flex-col gap-4">
                            <div>
                                <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">Icon Preview</h3>
                                @if ($category->icon)
                                    <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">Upload a new icon to replace the category icon</p>
                                @else
                                    <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-medium">No Icon</span>
                                        Upload a new icon to set the category icon
                                    </p>
                                @endif
                                <img id="category-icon-preview" class="rounded-lg w-full h-full aspect-video object-contain"
                                    src="{{ \Illuminate\Support\Facades\Storage::url($category->icon) }}" alt="Icon preview">
                            </div>
                            <div>
                                <x-input.file id="icon-input" type="file" name="icon" />
                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    SVG or PNG. Max size of 5MB
                                </div>
                                @error('icon')
                                <x-input.error :messages="$message" class="mt-2" />
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6 flex justify-end">
                        <div class="flex items-center space-x-3">
                            <x-button type="submit">Update</x-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</x-admin-layout>
