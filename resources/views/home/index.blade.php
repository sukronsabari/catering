<x-app-layout>
    <div id="indicators-carousel" class="relative w-full" data-carousel="slide">
        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
            @foreach ($sliders as $index => $slider)
                <div class="hidden duration-700 ease-in-out" data-carousel-item="{{ $index === 0 ? 'active' : '' }}">
                    <div class="absolute z-[2] w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 pl-[10%]">
                        <div class="space-y-4">
                            <div>
                                <h2 class="font-bold text-xl text-gray-800 sm:text-2xl">{{ $slider['title'] }}</h2>
                                <h3 class="font-black text-2xl text-gray-800 sm:text-4xl">{{ $slider['subtitle'] }}</h3>
                            </div>
                            <h4 class="font-medium text-base text-gray-800 sm:text-xl">Start at
                                IDR {{ $slider['starting_price'] }}
                            </h4>
                            <x-link.dark href="{{ $slider['url'] }}" class="inline-block bg-gray-800 hover:bg-gray-800/90 dark:bg-gray-800 dark:hover:bg-gray-800/90">
                                Shop Now
                            </x-link.dark>
                        </div>
                    </div>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($slider['image']) }}"
                        class="absolute block w-full min-h-72 -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 object-cover"
                        alt="...">
                </div>
            @endforeach
        </div>
        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 space-x-3 rtl:space-x-reverse bottom-5 left-1/2">
            @foreach ($sliders as $index => $slider)
                <button type="button" class="w-3 h-3 rounded-full" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $index + 1 }}" data-carousel-slide-to="{{ $index }}"></button>
            @endforeach
        </div>
        <!-- Slider controls -->
        <button type="button"
            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 1 1 5l4 4" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 9 4-4-4-4" />
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>

    <div class="w-full overflow-x-hidden hide-scrollbar mt-8">
        <div class="w-max flex gap-2 sm:gap-4">
            @foreach ($categories as $category)
                <div class="w-28 p-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex flex-col items-center w-full">
                        <img class="text-center w-14 h-14 flex-shrink" src="{{ \Illuminate\Support\Facades\Storage::url($category['icon']) }}" alt="category"/>
                        <span class="mt-2 text-center text-sm text-gray-600">{{ $category->name }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @php
        $items = [1, 2, 3, 4, 5, 6];
    @endphp

    {{-- TOP PRODUCTS --}}
    <section class="py-8 antialiased md:py-12">
        <div class="mx-auto max-w-screen-xl 2xl:px-0">
            <div class="flex items-center justify-between mb-4 px-4">
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-sm sm:text-base">Flash Sale</h2>
                    <div class="flex items-center text-white bg-red-600 py-1 px-2 rounded-full">
                        <i class="ph ph-clock text-2xl me-1 block"></i>
                        <div id="countdown" class="flex items-center gap-1" class="font-bold">
                            <span id="days" class="hidden">00</span>
                            {{-- <span>:</span> --}}
                            <span id="hours">00</span>
                            <span>:</span>
                            <span id="minutes">00</span>
                            <span>:</span>
                            <span id="seconds">00</span>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="">
                        <i class="ph ph-arrow-circle-right block text-2xl"></i>
                    </a>
                </div>
            </div>

            {{-- Carousel Product --}}
            <div class="w-full overflow-x-scroll hide-scrollbar">
                {{-- <div
                    class="mb-4 grid grid-cols-2 gap-2 sm:grid-cols-3 sm:gap-4 md:mb-8 lg:grid-cols-4 xl:grid-cols-4">
                    --}}
                <div class="w-max flex gap-1 sm:gap-3">
                    @foreach ($items as $item)
                        <div
                            class="w-[180px] h-[340px] sm:h-[385px] sm:w-[220px] overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <div class="h-44 sm:h-56 w-full overflow-hidden">
                                <img class="h-full w-full object-cover dark:hidden"
                                    src="https://images.tokopedia.net/img/cache/200-square/VqbcmM/2024/3/19/8a402875-360d-4f30-9b5f-09e6d07d8f45.jpg"
                                    alt="" />
                            </div>
                            <div class="p-4 pt-6">
                                <a href="#"
                                    class="text-sm font-medium leading-tight text-gray-900 hover:underline dark:text-white multiline-ellipsis">
                                    Apple iMac 27", 1TB HDD, Retina 5K Display, M3 Max
                                </a>
                                <p class="mt-2 text-sm font-bold leading-tight text-gray-900 dark:text-white">$1,699</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 text-yellow-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M13.8 4.2a2 2 0 0 0-3.6 0L8.4 8.4l-4.6.3a2 2 0 0 0-1.1 3.5l3.5 3-1 4.4c-.5 1.7 1.4 3 2.9 2.1l3.9-2.3 3.9 2.3c1.5 1 3.4-.4 3-2.1l-1-4.4 3.4-3a2 2 0 0 0-1.1-3.5l-4.6-.3-1.8-4.2Z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">5.0</p>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">(455)</p>
                                </div>
                                <div class="mt-1">
                                    <div class="flex items-center">
                                        <i class="ph ph-map-pin-line block me-1 text-lg"></i>
                                        @if ($loop->iteration % 2 == 0)
                                            <p class="text-xs">Mekka</p>
                                        @else
                                            <p class="text-xs">Samarinda</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{--
                </div> --}}
            </div>
        </div>
    </section>

    <!-- component -->
    {{-- <div class="flex justify-center items-center">
        <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->
        <div class="2xl:mx-auto 2xl:container py-12 px-4 sm:px-6 xl:px-20 2xl:px-0 w-full">
            <div class="flex flex-col jusitfy-center items-center space-y-10">
                <div class="flex flex-col justify-center items-center ">
                    <h1 class="text-3xl xl:text-4xl font-semibold leading-7 xl:leading-9 text-gray-800 dark:text-white">
                        Shop
                        By Category</h1>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-x-4 md:gap-x-8 w-full">
                    <div class="relative group flex justify-center items-center h-full w-full">
                        <img class="object-center object-cover h-full w-full"
                            src="https://i.ibb.co/ThPFmzv/omid-armin-m-VSb6-PFk-VXw-unsplash-1-1.png"
                            alt="girl-image" />
                        <button
                            class="dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 bottom-4 z-10 absolute text-base font-medium leading-none text-gray-800 py-3 w-36 bg-white">Women</button>
                        <div
                            class="absolute opacity-0 group-hover:opacity-100 transition duration-500 bottom-3 py-6 z-0 px-20 w-36 bg-white bg-opacity-50">
                        </div>
                    </div>

                    <div class="flex flex-col space-y-4 md:space-y-8 mt-4 md:mt-0">
                        <div class="relative group flex justify-center items-center h-full w-full">
                            <img class="object-center object-cover h-full w-full"
                                src="https://i.ibb.co/SXZvYHs/irene-kredenets-DDqx-X0-7v-KE-unsplash-1.png"
                                alt="shoe-image" />
                            <button
                                class="dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 bottom-4 z-10 absolute text-base font-medium leading-none text-gray-800 py-3 w-36 bg-white">Shoes</button>
                            <div
                                class="absolute opacity-0 group-hover:opacity-100 transition duration-500 bottom-3 py-6 z-0 px-20 w-36 bg-white bg-opacity-50">
                            </div>
                        </div>
                        <div class="relative group flex justify-center items-center h-full w-full">
                            <img class="object-center object-cover h-full w-full"
                                src="https://i.ibb.co/Hd1pVxW/louis-mornaud-Ju-6-TPKXd-Bs-unsplash-1-2.png"
                                alt="watch-image" />
                            <button
                                class="dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 bottom-4 z-10 absolute text-base font-medium leading-none text-gray-800 py-3 w-36 bg-white">Watches</button>
                            <div
                                class="absolute opacity-0 group-hover:opacity-100 transition duration-500 bottom-3 py-6 z-0 px-20 w-36 bg-white bg-opacity-50">
                            </div>
                        </div>
                    </div>

                    <div class="relative group justify-center items-center h-full w-full hidden lg:flex">
                        <img class="object-center object-cover h-full w-full"
                            src="https://i.ibb.co/PTtRBLL/olive-tatiane-Im-Ez-F9-B91-Mk-unsplash-1.png"
                            alt="girl-image" />
                        <button
                            class="dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 bottom-4 z-10 absolute text-base font-medium leading-none text-gray-800 py-3 w-36 bg-white">Accessories</button>
                        <div
                            class="absolute opacity-0 group-hover:opacity-100 transition duration-500 bottom-3 py-6 z-0 px-20 w-36 bg-white bg-opacity-50">
                        </div>
                    </div>
                    <div
                        class="relative group flex justify-center items-center h-full w-full mt-4 md:hidden md:mt-8 lg:hidden">
                        <img class="object-center object-cover h-full w-full hidden md:block"
                            src="https://i.ibb.co/6FjW19n/olive-tatiane-Im-Ez-F9-B91-Mk-unsplash-2.png"
                            alt="girl-image" />
                        <img class="object-center object-cover h-full w-full md:hidden"
                            src="https://i.ibb.co/sQgHwHn/olive-tatiane-Im-Ez-F9-B91-Mk-unsplash-1.png"
                            alt="olive-tatiane-Im-Ez-F9-B91-Mk-unsplash-2" />
                        <button
                            class="dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 bottom-4 z-10 absolute text-base font-medium leading-none text-gray-800 py-3 w-36 bg-white">Accessories</button>
                        <div
                            class="absolute opacity-0 group-hover:opacity-100 transition duration-500 bottom-3 py-6 z-0 px-20 w-36 bg-white bg-opacity-50">
                        </div>
                    </div>
                </div>
                <div
                    class="relative group hidden md:flex justify-center items-center h-full w-full mt-4 md:mt-8 lg:hidden">
                    <img class="object-center object-cover h-full w-full hidden md:block"
                        src="https://i.ibb.co/6FjW19n/olive-tatiane-Im-Ez-F9-B91-Mk-unsplash-2.png" alt="girl-image" />
                    <img class="object-center object-cover h-full w-full sm:hidden"
                        src="https://i.ibb.co/sQgHwHn/olive-tatiane-Im-Ez-F9-B91-Mk-unsplash-1.png"
                        alt="olive-tatiane-Im-Ez-F9-B91-Mk-unsplash-2" />
                    <button
                        class="dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 bottom-4 z-10 absolute text-base font-medium leading-none text-gray-800 py-3 w-36 bg-white">Accessories</button>
                    <div
                        class="absolute opacity-0 group-hover:opacity-100 transition duration-500 bottom-3 py-6 z-0 px-20 w-36 bg-white bg-opacity-50">
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</x-app-layout>
