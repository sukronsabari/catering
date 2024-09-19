<x-admin-layout>
    @slot('title', 'Settings')

    <x-header heading="Admin Settings" />
    <div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
        {{-- <div class="mb-4 col-span-full xl:mb-2">
            <x-breadcrumbs />
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Admin settings</h1>
        </div> --}}

        <!-- Right Content -->
        <div class="col-span-full xl:col-auto">
            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
                    <img id="profile-image" class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0 object-cover"
                        src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->image) }}" alt="Image Profile">
                    <div>
                        <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">Profile picture</h3>
                        <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            JPG, JPEG or PNG. Max size of 2MB
                        </div>
                        <form id="profile-image-form" action="{{ route('admin.profile.image.update') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                            @method("PATCH")
                            @csrf
                            <div>
                                <label role="button" for="profile-image-input"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-green-400 hover:bg-green-500 focus:ring-4 focus:ring-green-100 dark:focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600">
                                    <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z">
                                        </path>
                                        <path d="M9 13h2v5a1 1 0 11-2 0v-5z"></path>
                                    </svg>
                                    Upload picture
                                </label>
                                <input id="profile-image-input" name="image" type="file" class="hidden" />
                            </div>

                            <x-button.light type="submit" class="!py-2">Save</x-button.light>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-2">
            <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
                @csrf
            </form>

            <div
                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <h3 class="mb-4 text-xl font-semibold dark:text-white">General information</h3>
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="name" value="Name" />
                            <x-input.text id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" />
                            <x-input.error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="email" value="Email" />
                            <x-input.text id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" />
                            <x-input.error :messages="$errors->get('email')" class="mt-2" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="flex flex-wrap">
                                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                        {{ __('Your email address is unverified.') }}

                                    </p>
                                    <button form="send-verification"
                                        class="underline text-sm text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="phone" value="Phone" />
                            <x-input.text id="phone" name="phone" type="text" inputmode="numeric" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                            <x-input.error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-full">
                            <x-button type="submit" class="!py-2">Save</x-button>
                        </div>
                    </div>
                </form>
            </div>
            <div
                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <h3 class="mb-4 text-xl font-semibold dark:text-white">Password information</h3>
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="update_password_current_password" :value="__('Current Password')" />
                            <x-input.text id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" placeholder="••••••••" />
                            <x-input.error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="update_password_password" :value="__('New Password')" />
                            <x-input.text id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" placeholder="••••••••" />
                            <x-input.error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-input.label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                            <x-input.text id="update_password_password_confirmation" name="password_confirmation" type="password"
                                class="mt-1 block w-full" autocomplete="new-password" placeholder="••••••••" />
                            <x-input.error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-full">
                            <x-button type="submit" class="!py-2">Save</x-button>
                        </div>
                    </div>
                </form>
            </div>
            <div
                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="flow-root">
                    <h3 class="text-xl font-semibold dark:text-white">Sessions</h3>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 dark:text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-base font-semibold text-gray-900 truncate dark:text-white">
                                        California 123.123.123.123
                                    </p>
                                    <p class="text-sm font-normal text-gray-500 truncate dark:text-gray-400">
                                        Chrome on macOS
                                    </p>
                                </div>
                                <div class="inline-flex items-center">
                                    <a href="#"
                                        class="px-3 py-2 mb-3 mr-3 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Revoke</a>
                                </div>
                            </div>
                        </li>
                        <li class="pt-4 pb-6">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 dark:text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-base font-semibold text-gray-900 truncate dark:text-white">
                                        Rome 24.456.355.98
                                    </p>
                                    <p class="text-sm font-normal text-gray-500 truncate dark:text-gray-400">
                                        Safari on iPhone
                                    </p>
                                </div>
                                <div class="inline-flex items-center">
                                    <a href="#"
                                        class="px-3 py-2 mb-3 mr-3 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Revoke</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div>
                        <x-button class="!py-2">See more</x-button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const input = document.getElementById('profile-image-input');
        const previewPhoto = () => {
            const file = input?.files;

            if (file) {
                const fileReader = new FileReader();
                const preview = document.getElementById('profile-image');

                fileReader.onload = function (event) {
                    preview.setAttribute('src', event.target.result);
                }

                fileReader.readAsDataURL(file[0]);
            }
        }

        input.addEventListener("change", previewPhoto);
    </script>
</x-admin-layout>
