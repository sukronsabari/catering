<div class="flex items-center space-x-2 whitespace-nowrap">
    @isset ( $viewLink )
        <a
            x-data="{ queryParams: '' }"
            x-init="queryParams = new URLSearchParams(window.location.search).toString();"
            x-on:click.prevent="window.location.href = '{{ $viewLink }}' + (queryParams ? '?' + queryParams : '')"
            class="text-white bg-green-400 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-100 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center dark:focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2"
                    d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span class="sr-only">View item</span>
        </a>
    @endif

    @isset ( $editLink )
        <button
        x-data="{ queryParams: '' }"
        x-init="queryParams = new URLSearchParams(window.location.search).toString();"
        x-on:click.prevent="window.location.href = '{{ $editLink }}' + (queryParams ? '?' + queryParams : '')"
        class="text-white bg-green-400 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-100 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center dark:focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                </path>
                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Edit item</span>
        </button>
    @endif

    @isset ( $deleteLink )
    <div x-data="{ showDeleteModal: false }">
        <button x-on:click="showDeleteModal = true" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center dark:focus:ring-red-800">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Delete item</span>
        </button>
        <div
            class="fixed top-0 left-0 right-0 z-[9999999] bg-gray-900/50 dark:bg-gray-900/90 items-center justify-center hidden overflow-x-hidden overflow-y-auto md:inset-0 h-modal sm:h-full"
            x-cloak
            x-show="showDeleteModal"
            x-bind:class="{ 'hidden': !showDeleteModal, 'flex': showDeleteModal }"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="relative w-full max-w-md px-4 md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex justify-end p-2">
                        <button
                            x-on:click="showDeleteModal = false"
                            type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 pt-0 text-center">
                        <svg class="w-16 h-16 mx-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-5 mb-6 text-lg text-gray-500 dark:text-gray-400">Are you sure you want to delete this item?</h3>
                        <div class="flex items-center justify-center gap-3">
                            <form
                                x-data="{ queryParams: '' }"
                                x-init="queryParams = new URLSearchParams(window.location.search).toString();"
                                :action="queryParams ? '{{ $deleteLink }}' + '?' + queryParams : '{{ $deleteLink }}'"
                                method="POST"
                            >
                                    @method('DELETE')
                                    @csrf

                                    <x-button.danger type="submit">
                                        Delete
                                    </x-button.danger>
                            </form>
                            <x-button.light x-on:click="showDeleteModal = false">
                                Cancel
                            </x-button.light>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

