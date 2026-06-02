<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Masukkan Email" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Masukkan Password" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-gray-300 text-red-800 shadow-sm focus:ring-red-700"
                    name="remember"
                >

                <span class="ms-2 text-sm text-gray-600">
                    Ingat saya
                </span>
            </label>
        </div>

        <div class="mt-6">
            <button
                type="submit"
                class="w-full bg-red-800 hover:bg-red-900 text-white py-3 rounded-lg font-bold shadow-md transition">
                Login
            </button>
        </div>

        <div class="mt-5 text-center">
            @if (Route::has('password.request'))
                <a
                    class="text-sm text-gray-600 hover:text-red-800 font-semibold"
                    href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>