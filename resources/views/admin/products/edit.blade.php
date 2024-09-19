@php
    $activeOptions = [
        [
            'label' => 'Active',
            'value' => '1',
            'selected' => old('is_active', $product->is_active) == '1'
        ],
        [
            'label' => 'Non Active',
            'value' => '0',
            'selected' => old('is_active', $product->is_active) == '0'
        ],
    ];
@endphp

<x-admin-layout>
    {{-- Header --}}
    <div
        class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <x-breadcrumbs />
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Edit Product</h1>
        </div>
    </div>
    {{-- @dd($productImages); --}}
    {{-- Content --}}
    <div class="px-4 pt-6">
        <div
            class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
            <form action="{{  route('admin.products.update', ['product' => $product, 'callbackUrl' => request()->query('callbackUrl')]) }}" method="POST" enctype="multipart/form-data" novalidate>
                @method('PUT')
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="name">Product Name</x-input.label>
                        <x-input.text id="name" type="text" name="name" class="mt-1" value="{{ old('name', $product->name) }}"
                            required />
                        @error('name')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="price">Price</x-input.label>
                        <x-input.text id="price" type="text" name="price" inputmode="numeric" class="mt-1"
                            value="{{ old('price', (int) $product->price) }}" required />
                        @error('price')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="stock">Stock</x-input.label>
                        <x-input.text id="stock" type="text" name="stock" inputmode="numeric" class="mt-1"
                            value="{{ old('stock', $product->stock) }}" required />
                        @error('stock')
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
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label>Merchant</x-input.label>
                        <livewire:search-merchants :isEdit="true" :selectedMerchantId="$product->merchant_id" />
                        @error('merchant_id')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>

                    <div
                        x-data="{
                            open: false,
                            selectedCategoryId: {{ json_encode($product->category_id ?? '') }},
                            selectedCategoryName: {{ json_encode($product->category->name ?? '') }},

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
                        class="col-span-6 sm:col-span-3"
                    >
                        <div class="relative">
                            <!-- Dropdown Button -->
                            <x-input.label>Category</x-input.label>
                            <button type="button" x-cloak x-on:click="toggleDropdown()"
                                class="w-full text-sm flex px-5 py-2.5 items-center justify-between border border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm">
                                <span x-text="selectedCategoryName || 'Select Parent'"></span>
                                <svg class="w-2.5 h-2.5 text-sm" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            @error('category_id')
                            <x-input.error :messages="$message" class="mt-2" />
                            @enderror

                            <!-- Dropdown Content -->
                            <div x-cloak x-show="open" @click.outside="open = false"
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
                            <input type="number" class="hidden" x-model="selectedCategoryId" name="category_id" />
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label :required="false" for="description">Description</x-input.label>
                        <x-input.text-area id="description" rows="4" name="description" class="mt-1"
                            :text="old('description', $product->description)" />
                        @error('description')
                        <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>

                    <div class="col-span-6" x-data="{
                            fields: {{ $productImages }},
                            addNewField() {
                                if (this.fields.length < 9) {
                                    this.fields.push({
                                        id: Date.now(),
                                        image: null,
                                        isMain: false,
                                        path: null,
                                    });
                                }
                            },
                            removeField(index) {
                                if (this.fields.length > 1) {
                                    this.fields.splice(index, 1);
                                }
                            },
                            previewImage(event, index) {
                                const input = event.target;
                                const file = input.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        this.fields[index].image = e.target.result;
                                        this.fields[index].path = null;
                                    };
                                    reader.readAsDataURL(file);
                                }
                            },
                            setMainPhoto(index) {
                                this.fields.forEach((field, i) => field.isMain = i === index);
                            }
                        }"
                    >
                        <div class="flex items-center justify-between">
                            <x-input.label>Product Image (Max: 9)</x-input.label>
                            <x-button.light @click="addNewField()" class="!p-2.5 text-center inline-flex items-center">
                                <span class="flex items-center justify-center w-5 h-5">
                                    <i class="ti ti-plus text-xl"></i>
                                </span>
                            </x-button.light>
                        </div>
                        <div class="flex items-center gap-4 flex-wrap">
                            <template x-for="(field, index) in fields" :key="field.id">
                                <div class="relative flex items-center justify-center w-36 h-36 overflow-hidden">
                                    <label
                                        class="h-full w-full flex flex-col items-center justify-center border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6"
                                            x-show="!field.image && !field.path">
                                            <span class="w-8 h-8 flex justify-center items-center text-gray-500">
                                                <i class="ti ti-photo-plus text-xl"></i>
                                            </span>
                                            <p class="mb-2 text-sm text-gray-500" x-text="field.isMain ? 'Main Image' : `Foto ${index + 1}`"></p>
                                        </div>
                                        <input type="file" accept="image/*" class="hidden" :name="'images[' + index + '][file]'" @change="previewImage($event, index)" />

                                        <!-- Preview Gambar -->
                                        <img :src="field.image ?? field.path" x-show="field.image || field.path" class="object-cover w-full h-full" />

                                        @foreach ($errors->get('images.*') as $message)
                                        <x-input.error :messages="$message" class="" />
                                        @endforeach
                                    </label>


                                    <!-- Tombol Hapus -->
                                    <button type="button"
                                        class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full"
                                        @click="removeField(index)"
                                        x-show="fields.length > 1 && !field.isMain">✕</button>

                                    <!-- Tombol Set Foto Utama -->
                                    <button
                                        type="button"
                                        class="absolute bottom-1 left-1 bg-green-500 text-white p-1 rounded"
                                        @click="setMainPhoto(index)"
                                        x-show="field.image || field.path"
                                        x-text="field.isMain ? 'Main Image' : 'Set Main'"
                                    ></button>

                                    <!-- Input Tersembunyi untuk Menyimpan Status Gambar Utama -->
                                    <input type="hidden" :name="'images[' + index + '][is_main]'" :value="field.isMain ? '1' : '0'" />
                                    <input type="hidden" :name="'images[' + index + '][path]'" :value="field.path" />
                                </div>
                            </template>
                        </div>

                    </div>

                    <div class="col-span-6">
                        <div class="w-full mt-6 flex justify-end">
                            <x-button type="submit">Save</x-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
