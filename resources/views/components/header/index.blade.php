<div
    class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div>
            <x-breadcrumbs />
            <h1 class="text-[#253D4E] dark:text-white text-xl font-semibold sm:text-2xl">{{ $heading }}</h1>
        </div>
    </div>

    {{ $slot }}
</div>
