@php
    $activeOptions = [
        [
            'label' => 'Active',
            'value' => '1',
            'selected' => old('is_active', $product->is_active) == '1',
        ],
        [
            'label' => 'Non Active',
            'value' => '0',
            'selected' => old('is_active', $product->is_active) == '0',
        ],
    ];

    $oldSkus = old('skus', $skus);

    $mappedSkus = array_map(function ($sku) {
        return [
            ...$sku,
            'is_active' => isset($sku['is_active']) && $sku['is_active'] == 'on' ? true : false,
            'is_default' => isset($sku['is_default']) && $sku['is_default'] == 'on' ? true : false,
        ];
    }, $oldSkus);
@endphp

<x-admin-layout>
    {{-- Header --}}
    <div
        class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <x-breadcrumbs />
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Create New Product</h1>
        </div>
    </div>


    {{-- Content --}}
    <div class="px-4 pt-6" x-data="{
        variants: {{ json_encode(old('variants', $variants)) }},
        skus: {{ json_encode($mappedSkus) }},
        isDefaultIndex: null,
        addVariant() {
            this.variants.push({ type: '', options: [] });
        },
        removeVariant(index) {
            this.variants.splice(index, 1);
            this.generateSkus();
        },
        generateSkus() {
            this.skus = [];
            const combinations = this.getCombinations(this.variants.map(v => v.options));
            combinations.forEach((combination, index) => {
                this.skus.push({
                    attribute_value: combination.map((val, idx) => ({
                        attribute: this.variants[idx].type,
                        value: val
                    })),
                    price: '',
                    stock: '',
                    weight: '',
                    sku: '',
                    is_active: true,
                    is_default: index === 0,
                });
            });
        },
        getCombinations(arrays) {
            return arrays.reduce((a, b) =>
                a.flatMap(d => b.map(e => [...d, e])), [
                    []
                ]);
        }
    }">
        <div
            class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
            <form action="{{ route('admin.products.update', ['product' => $product, 'callbackUrl' => request()->query('callbackUrl')]) }}" method="POST" enctype="multipart/form-data" novalidate>
                @method('PUT')
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="name">Product Name</x-input.label>
                        <x-input.text id="name" type="text" name="name" class="mt-1"
                            value="{{ old('name', $product->name) }}" required />
                        @error('name')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3" x-show="!skus.length">
                        <x-input.label for="price">Price</x-input.label>
                        <x-input.text id="price" type="text" name="price" inputmode="numeric" class="mt-1"
                            value="{{ old('price', (int) $product->price) }}" x-bind:disabled="skus.length > 0" />
                        @error('price')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3" x-show="!skus.length">
                        <x-input.label for="stock">Stock</x-input.label>
                        <x-input.text id="stock" type="text" name="stock" inputmode="numeric" class="mt-1"
                            value="{{ old('stock', $product->stock) }}" x-bind:disabled="skus.length > 0" />
                        @error('stock')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3" x-show="!skus.length">
                        <x-input.label for="weight">Weight (Gram)</x-input.label>
                        <x-input.text id="weight" type="text" name="weight" inputmode="numeric" class="mt-1"
                            value="{{ old('weight', (int) $product->weight) }}" x-bind:disabled="skus.length > 0" />
                        @error('weight')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3" x-show="!skus.length">
                        <x-input.label :required="false" for="sku">SKU</x-input.label>
                        <x-input.text id="sku" type="text" name="sku" class="mt-1"
                            value="{{ old('sku', $product->sku) }}" x-bind:disabled="skus.length > 0" />
                        @error('sku')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3" x-show="!skus.length">
                        <x-input.label for="is_active">Status</x-input.label>
                        <x-input.select id="is_active" name="is_active" :options="$activeOptions" class="mt-1"
                            x-bind:disabled="skus.length > 0" />
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
                    <div class="col-span-6 sm:col-span-3">
                        <div x-data="{
                            open: false,
                            selectedCategoryId: {{ json_encode(old('category_id', $product->category_id) ?? null) }},
                            selectedCategoryName: {{ json_encode($categories->where('id', old('category_id', $product->category_id))?->first()?->name ?? '') }},

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
                        }" class="relative">
                            <!-- Dropdown Button -->
                            <x-input.label>Category</x-input.label>
                            <button x-cloak type="button" @click="toggleDropdown()"
                                class="w-full text-sm flex px-5 py-2.5 items-center justify-between border border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm">
                                <span x-text="selectedCategoryName || 'Select Category'"></span>
                                <svg class="w-2.5 h-2.5 text-sm" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
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
                    }">
                        <div class="flex items-center justify-between">
                            <x-input.label>Product Image (Max: 9)</x-input.label>
                            <x-button.light @click="addNewField()"
                                class="!p-2.5 text-center inline-flex items-center">
                                <span class="flex items-center justify-center w-5 h-5">
                                    <i class="ti ti-plus text-xl"></i>
                                </span>
                            </x-button.light>
                        </div>
                        <div class="flex items-center gap-4 flex-wrap">
                            <template x-for="(field, index) in fields" :key="field.id">
                                <div
                                    class="relative flex items-center justify-center w-36 h-36 overflow-hidden border-2 border-gray-300 border-dashed rounded-lg">
                                    <label
                                        class="h-full w-full flex flex-col items-center justify-center cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6"
                                            x-show="!field.image && !field.path">
                                            <span class="w-8 h-8 flex justify-center items-center text-gray-500">
                                                <i class="ti ti-photo-plus text-xl"></i>
                                            </span>
                                            <p class="mb-2 text-sm text-gray-500"
                                                x-text="field.isMain ? 'Main Image' : `Image ${index + 1}`"></p>
                                        </div>
                                        <input type="file" accept="image/*" class="hidden"
                                            :name="'images[' + index + '][file]'"
                                            @change="previewImage($event, index)" />

                                        <!-- Preview Gambar -->
                                        <img :src="field.image ?? field.path" x-show="field.image || field.path"
                                            class="object-cover w-full h-full" />

                                        @foreach ($errors->get('images.*') as $message)
                                            <x-input.error :messages="$message" class="" x-cloak
                                                x-show="!field.image && !field.path" />
                                        @endforeach
                                    </label>


                                    <!-- Tombol Hapus -->
                                    <button type="button"
                                        class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full"
                                        @click="removeField(index)"
                                        x-show="fields.length > 1 && !field.isMain">✕</button>

                                    <!-- Tombol Set Foto Utama -->
                                    <button type="button"
                                        class="absolute bottom-1 left-1 bg-green-500 text-white p-1 rounded"
                                        @click="setMainPhoto(index)" x-show="field.image || field.path"
                                        x-text="field.isMain ? 'Main Image' : 'Set Main'"></button>

                                    <!-- Input Tersembunyi untuk Menyimpan Status Gambar Utama -->
                                    <input type="hidden" :name="'images[' + index + '][is_main]'"
                                        :value="field.isMain ? '1' : '0'" />
                                    <input type="hidden" :name="'images[' + index + '][path]'"
                                        :value="field.path" />
                                </div>
                            </template>
                        </div>

                    </div>

                    <div class="col-span-6">

                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="text-gray-800 font-semibold text-lg sm:text-lg mb-1">Product Variant</h4>
                                <p class="text-gray-600 dark:text-white text-sm">You can add up to 2 product variations
                                </p>
                            </div>
                            <div>
                                <x-button.light @click="addVariant" x-show="variants.length < 2"
                                    class="!p-2.5 text-center inline-flex items-center">
                                    <span class="flex items-center justify-center w-5 h-5">
                                        <i class="ti ti-plus text-xl"></i>
                                    </span>
                                </x-button.light>
                            </div>
                        </div>

                        <div>
                            <template x-for="(variant, variantIndex) in variants" :key="variantIndex">
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 border border-gray-200 dark:border-gray-600 rounded p-4 pt-8 relative">
                                    <!-- Variant Type Input -->
                                    <div>
                                        <x-input.label :required="false">Type</x-input.label>
                                        <x-input.text type="text" class="mt-1"
                                            x-bind:name="'variants[' + variantIndex + '][type]'"
                                            x-model="variant.type" x-on:change="generateSkus()" required />
                                    </div>

                                    <!-- Variant Options Dropdown -->
                                    <div>
                                        <x-input.label :required="false">Option</x-input.label>
                                        <select
                                            class="bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                                            multiple x-bind:name="'variants[' + variantIndex + '][options][]'"
                                            x-model="variant.options" x-on:change="generateSkus()"
                                            x-init="let tomSelect = new TomSelect($el, {
                                                valueField: 'value',
                                                labelField: 'label',
                                                create: true,
                                                addPrecedence: true,
                                                createFilter: function(input) {
                                                    input = input.toLowerCase();
                                                    return !(input in this.options);
                                                },
                                                options: variant?.options.map((option) => ({
                                                    label: option,
                                                    value: option,
                                                })),
                                            });

                                            variant?.options?.map((option) => {
                                                tomSelect.addItem(option);
                                            });">
                                        </select>
                                    </div>

                                    <!-- Button to remove -->
                                    <x-button.danger @click="removeVariant(variantIndex)"
                                        class="!p-2 text-center inline-flex items-center absolute top-1.5 right-1.5">
                                        <span class="flex items-center justify-center w-4 h-4">
                                            <i class="ti ti-x text-base"></i>
                                        </span>
                                    </x-button.danger>
                                </div>
                            </template>
                        </div>

                        <div x-show="skus.length" x-cloak class="mt-6 relative overflow-x-auto">
                            <table
                                class="w-full min-w-max text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-2">
                                            <x-input.label :required="false">Variant</x-input.label>
                                        </th>
                                        <th class="px-4 py-2">
                                            <x-input.label>Price</x-input.label>
                                        </th>
                                        <th class="px-4 py-2">
                                            <x-input.label>Stock</x-input.label>
                                        </th>
                                        <th class="px-4 py-2">
                                            <x-input.label>Weight (Gram)</x-input.label>
                                        </th>
                                        <th class="px-4 py-2">
                                            <x-input.label :required="false">SKU</x-input.label>
                                        </th>
                                        <th class="px-4 py-2">
                                            <x-input.label>Active</x-input.label>
                                        </th>
                                        <th class="px-4 py-2">
                                            <x-input.label>Default Variant?</x-input.label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(sku, index) in skus" :key="index">
                                        <tr>
                                            <td class="px-4 py-2">
                                                <div class="flex items-center gap-2">
                                                    <template x-for="(attr, attrIndex) in sku.attribute_value"
                                                        :key="attrIndex">
                                                        <div>
                                                            <input type="hidden"
                                                                :name="'skus[' + index + '][attribute_value][' + attrIndex +
                                                                    '][attribute]'"
                                                                :value="attr.attribute">
                                                            <input type="hidden"
                                                                :name="'skus[' + index + '][attribute_value][' + attrIndex +
                                                                    '][value]'"
                                                                :value="attr.value">
                                                            <span class="inline-block" x-text="attr.value"></span>
                                                            <template
                                                                x-if="attrIndex < sku.attribute_value.length - 1">
                                                                <span class="inline-block ms-2">-</span>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <x-input.text type="text" placeholder="Price" x-model="sku.price"
                                                    x-bind:name="'skus[' + index + '][price]'" />
                                            <td class="px-4 py-2">
                                                <x-input.text type="text" placeholder="Stock" x-model="sku.stock"
                                                    x-bind:name="'skus[' + index + '][stock]'" />
                                            </td>
                                            <td class="px-4 py-2">
                                                <x-input.text type="text" placeholder="Weight"
                                                    x-model="sku.weight"
                                                    x-bind:name="'skus[' + index + '][weight]'" />
                                            </td>
                                            <td class="px-4 py-2">
                                                <x-input.text type="text" placeholder="SKU" x-model="sku.sku"
                                                    x-bind:name="'skus[' + index + '][sku]'" />
                                            </td>

                                            <td class="px-4 py-2">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer"
                                                        x-model.boolean="sku.is_active"
                                                        x-bind:name="'skus[' + index + '][is_active]'"
                                                    />
                                                    <div
                                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600">
                                                    </div>
                                                </label>
                                            </td>
                                            <td class="px-4 py-2">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer"
                                                        x-bind:name="'skus[' + index + '][is_default]'"
                                                        x-bind:value="sku.is_default"
                                                        x-on:change="
                                                            skus.forEach((item, idx) => {
                                                                item.is_default = false;
                                                            });

                                                            // Set the current sku to is_default = true
                                                            sku.is_default = true;
                                                        "
                                                    />
                                                    <div
                                                        class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600">
                                                    </div>
                                                </label>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-span-6">
                        <div class="w-full mt-6 flex justify-end">
                            <div class="flex items-center space-x-3">
                                <x-button type="submit">Create</x-button>
                                <x-button.light type="submit" name="create_another">Create & Create
                                    Another</x-button.light>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
