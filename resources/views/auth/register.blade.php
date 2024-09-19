<x-app-layout>
    <div class="w-full max-w-sm mx-auto">
        <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
            @csrf
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Sign Up</h5>
            <div>
                <x-input.label for="name" :value="__('Name')" />
                <x-input.text id="name" type="text" name="name" :value="old('name')" required autofocus
                    autocomplete="name" />
                <x-input.error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input.label for="email" :value="__('Email')" />
                <x-input.text id="email" type="email" name="email" :value="old('email')" required
                    autocomplete="username" />
                <x-input.error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input.label for="password" :value="__('Password')" />
                <x-input.text id="password" type="password" name="password" required autocomplete="new-password" />
                <x-input.error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input.label for="password_confirmation" :value="__('Confirm Password')" />
                <x-input.text id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password" />
                <x-input.error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
                <x-button class="ms-4" type="submit">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </div>
</x-app-layout>
