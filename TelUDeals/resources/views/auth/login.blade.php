<x-guest-layout>
    <div >

        <div >

            {{-- TITLE --}}
            <h2 class="text-center text-2xl font-bold text-gray-800 mb-2">
                Masuk ke Akun
            </h2>

            <p class="text-center text-sm text-gray-500 mb-8">
                Silakan login untuk melanjutkan ke Tel-U Deals
            </p>

            {{-- SESSION STATUS --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            {{-- FORM --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- EMAIL --}}
                <div>
                    <x-input-label
                        for="email"
                        value="Email"
                        class="font-semibold text-gray-700 mb-1"
                    />
                    <x-text-input
                        id="email"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        class="block w-full rounded-lg
                               bg-gray-50 border border-gray-300
                               text-gray-800 placeholder-gray-400
                               focus:border-red-500 focus:ring-red-500"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- PASSWORD --}}
                <div>
                    <x-input-label
                        for="password"
                        value="Password"
                        class="font-semibold text-gray-700 mb-1"
                    />
                    <x-text-input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="block w-full rounded-lg
                               bg-gray-50 border border-gray-300
                               text-gray-800 placeholder-gray-400
                               focus:border-red-500 focus:ring-red-500"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- REMEMBER + FORGOT --}}
                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center text-gray-600">
                        <input
                            type="checkbox"
                            name="remember"
                            class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                        >
                        <span class="ml-2">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-red-600 hover:text-red-700 font-medium">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="w-full py-3 bg-red-600 hover:bg-red-700
                           text-white rounded-lg font-semibold transition"
                >
                    Login
                </button>
            </form>

            {{-- REGISTER --}}
            <div class="mt-8 text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}"
                   class="text-red-600 hover:text-red-700 font-semibold">
                    Daftar sekarang
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
