<x-app-layout>
    <div class="max-w-sm w-full mx-auto">
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Sign in</h5>

            <div>
                <x-input.label for="email">Email</x-input.label>
                <x-input.text type="email" name="email" id="email" value="{{ old('email') }}"
                    placeholder="name@company.com" required />
                <x-input.error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <x-input.label for="password">Password</x-input.label>
                <x-input.text type="password" name="password" id="password" placeholder="••••••••" required />
                <x-input.error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="flex items-start">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="remember" name="remember" type="checkbox"
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" />
                    </div>
                    <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember
                        me</label>
                </div>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="ms-auto text-sm text-blue-700 hover:underline dark:text-blue-500">{{ __('Forgot password?')
                    }}</a>
                @endif
            </div>
            <button type="submit"
                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login
                to your account</button>
            <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                Not registered? <a href="{{ route('register') }}"
                    class="text-blue-700 hover:underline dark:text-blue-500">Create account</a>
            </div>
        </form>
    </div>
</x-app-layout>
