<x-admin-layout>
    <div
        class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <x-breadcrumbs />
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">All Product</h1>
                <x-button.light :withIcon="true" icon="ti ti-plus text-xl" x-data x-on:click="$dispatch('add-product')">
                    Add Product
                </x-button.light>
            </div>
        </div>
    </div>

    <div>
        <livewire:tables.products-table />
    </div>

    <x-datatables.delete-modal
        x-on:open-delete-product-modal.window="showDeleteModal = true; deletedId = $event.detail.id"
        action="
            queryParams = decodeURIComponent(new URLSearchParams(window.location.search).toString());
            $dispatch('delete-product', { id: deletedId, queryParams: queryParams });
            showDeleteModal = false;
        "
    />
    <x-datatables.bulk-delete-modal
        x-on:open-bulk-delete-product-modal.window="showDeleteModal = true;"
        action="
            queryParams = decodeURIComponent(new URLSearchParams(window.location.search).toString());
            $dispatch('bulk-delete-product', { queryParams: queryParams });
            showDeleteModal = false;
        "
        />
</x-admin-layout>
