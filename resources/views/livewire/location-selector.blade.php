<div class="grid grid-cols-6 gap-6">
    <!-- Province Select -->
    <div class="col-span-6 sm:col-span-3">
        <x-input.label for="province">Province</x-input.label>
        <select id="province" name="province" wire:model.live="selectedProvince" class="bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
            <option value="">Select Province</option>
            @foreach($provinces as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <!-- City Select -->
    <div class="col-span-6 sm:col-span-3">
        <x-input.label for="city">City</x-input.label>
        <select id="city" name="city" wire:model.live="selectedCity" class="bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
            <option value="">Select City</option>
            @foreach($cities as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <!-- District Select -->
    <div class="col-span-6 sm:col-span-3">
        <x-input.label for="district">District</x-input.label>
        <select id="district" name="district" wire:model.live="selectedDistrict" class="bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
            <option value="">Select District</option>
            @foreach($districts as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Village Select -->
    <div class="col-span-6 sm:col-span-3">
        <x-input.label for="village">Village</x-input.label>
        <select id="village" name="village" wire:model.live="selectedVillage" class="bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500">
            <option value="">Select Village</option>
            @foreach($villages as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>
</div>
