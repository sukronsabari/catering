@php
    $activeOptions = [
        ['label' => 'Official', 'value' => '1', 'selected' => old('is_official') == 1],
        ['label' => 'Non Official', 'value' => '0', 'selected' => old('is_official') == 0],
    ];
@endphp

<x-admin-layout>
    {{-- Header --}}
    <div
        class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <x-breadcrumbs />
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Create New Merchant</h1>
        </div>
    </div>

    {{-- Content --}}
    <div class="px-4 pt-6">
        <div
            class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
            <form action="{{ route('admin.merchants.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @method('POST')
                @csrf
                <h3 class="text-gray-800 font-semibold text-lg sm:text-xl mb-4">Basic Information</h3>
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="user_id">Select User</x-input.label>
                        <livewire:user-search-auto-complete />
                        @error('user_id')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="name">Store Name</x-input.label>
                        <x-input.text id="name" type="text" name="name" class="mt-1"
                            value="{{ old('name') }}" required />
                        @error('name')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="is_official">Is Official?</x-input.label>
                        <x-input.select id="is_official" name="is_official" :options="$activeOptions" class="mt-1" required />
                        @error('is_official')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="phone">Phone Number</x-input.label>
                        <x-input.text id="phone" type="text" name="phone" class="mt-1"
                            value="{{ old('phone') }}" required />
                        @error('phone')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label :required="false" for="description">Description</x-input.label>
                        <x-input.text-area id="description" rows="4" name="description" class="mt-1"
                            :text="old('description')" />
                        @error('description')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label :required="false" for="banner_image">Banner Image</x-input.label>
                        <x-input.file id="banner_image" type="file" name="banner_image" />
                        @error('banner_image')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6">
                        <x-input.label :required="false" for="social_links">Social Links</x-input.label>
                        <div x-data="{
                            socialLinks:
                            {{

                                json_encode(
                                    old('social_links', [['platform' => '', 'link' => '']])
                                )
                            }},
                            addSocialLink() {
                                this.socialLinks.push({ platform: '', link: '' });
                            },
                            removeSocialLink(index) {
                                this.socialLinks.splice(index, 1);
                            }
                        }"
                        x-init="console.log(socialLinks)"
                    >
                            <template x-for="(socialLink, index) in socialLinks" :key="index">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pr-12 mb-4 relative">
                                    <div>
                                        <input x-model="socialLink.platform" type="text"
                                            placeholder="Platform (Youtube | Instagram | Twitter | Facebook)"
                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-800 sm:text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block w-full p-2.5"
                                            x-bind:name="'social_links[' + index + '][platform]'" />
                                    </div>
                                    <div>
                                        <input x-model="socialLink.link" type="url" placeholder="Link (e.g., https://twitter.com/example)"
                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-800 sm:text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block w-full p-2.5"
                                            x-bind:name="'social_links[' + index + '][link]'" />
                                    </div>
                                    <button type="button" @click="removeSocialLink(index)"
                                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm inline-flex text-center items-center absolute top-1/2 right-0 -translate-y-1/2">
                                        <span class="flex items-center justify-center w-10 h-10">
                                            <i class="ti ti-x text-lg"></i>
                                        </span>
                                    </button>
                                </div>
                            </template>

                            <button type="button" @click="addSocialLink"
                                class="p-2 text-center inline-flex items-center text-gray-800 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm">
                                <span class="flex items-center justify-center w-5 h-5">
                                    <i class="ti ti-plus text-xl"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <h3 class="text-gray-800 font-semibold text-lg sm:text-xl mb-4 mt-12">Address Information</h3>
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="address_detail">Address Detail</x-input.label>
                        <x-input.text id="address_detail" type="text" name="address_detail" class="mt-1"
                            value="{{ old('address_detail') }}" required />
                        @error('address_detail')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <x-input.label for="postal_code">Postal Code</x-input.label>
                        <x-input.text id="postal_code" type="text" name="postal_code" class="mt-1"
                            value="{{ old('postal_code') }}" required />
                        @error('postal_code')
                            <x-input.error :messages="$message" class="mt-2" />
                        @enderror
                    </div>
                    <div class="col-span-6">
                        <livewire:location-selector />
                    </div>
                </div>

                <div class="w-full mt-6 flex justify-end">
                    <div class="flex items-center space-x-3">
                        <x-button type="submit">Create</x-button>
                        <x-button.light type="submit" name="create_another">Create & Create Another</x-button.light>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
