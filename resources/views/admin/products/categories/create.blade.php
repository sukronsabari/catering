@php
    $featuredOptions = [
        ['label' => 'Featured', 'value' => '1', 'selected' => false],
        ['label' => 'Not Featured', 'value' => '0', 'selected' => true],
    ];
@endphp


<x-admin-layout>
    {{-- Header --}}
    <x-header heading="Create New Category" />

    {{-- Content --}}
    <div class="px-4 pt-6">
        <div
            class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
            <form action="{{ route('admin.products.categories.store') }}" method="POST" enctype="multipart/form-data"
                novalidate>
                @method('POST')
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="name">Name</x-input.label>
                        <x-input.text id="name" type="text" name="name" class="mt-1"
                            value="{{ old('name') }}" placeholder="E.g: Electronics" required />
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
                        <x-input.text-area rows="4" id="description" name="description" class="mt-1" :text="old('description')" placeholder="Describe this category" required />
                        @error('description')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <div
                            x-data="{
                                open: false,
                                selectedCategoryId: null,
                                selectedCategoryName: '',

                                toggleDropdown() {
                                    this.open = !this.open;
                                },
                                selectCategory(id, name) {
                                    this.selectedCategoryId = id;
                                    this.selectedCategoryName = name;
                                    console.log(id, name);

                                    this.open = false;
                                },
                                isSelected(categoryId) {
                                    return this.selectedCategoryId == categoryId;
                                }
                            }"
                            class="relative">
                            <!-- Dropdown Button -->
                            <x-input.label for="" :required="false">Parent</x-input.label>
                            <button type="button" @click="toggleDropdown"
                                class="w-full text-sm flex px-5 py-2.5 items-center justify-between border border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm">
                                <span x-text="selectedCategoryName || 'Select Parent'"></span>
                                <svg class="w-2.5 h-2.5 text-sm" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            @error('parent_id')
                            <x-input.error :messages="$message" class="mt-2" />
                            @enderror

                            <!-- Dropdown Content -->
                            <div x-cloak x-show="open" @click.away="open = false"
                                class="absolute z-10 left-0 mt-1 w-full text-gray-700 bg-white dark:bg-gray-700 dark:text-gray-200 border border-gray-200 rounded-lg shadow-lg max-h-64 overflow-y-auto">
                                <ul class="list-none p-2 text-sm">
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
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="image" :required="false">Image</x-input.label>
                        <x-input.file id="image" type="file" name="image" />
                        @error('image')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="icon" :required="false">Icon</x-input.label>
                        <x-input.file id="icon" type="file" name="icon" />
                        @error('icon')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 flex justify-end">
                        <div class="flex items-center space-x-3">
                            <x-button type="submit">Create</x-button>
                            <x-button.light type="submit" name="create_another">Create & Create
                                Another</x-button.light>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
