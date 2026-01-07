<x-guest-layout>
    <div >
        <div >

            {{-- TITLE --}}
            <h2 class="text-center text-2xl font-bold text-gray-800 mb-2">
                Daftar Akun
            </h2>

            <p class="text-center text-sm text-gray-500 mb-8">
                Buat akun baru untuk melanjutkan ke Tel-U Deals
            </p>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                {{-- NAME --}}
                <div>
                    <x-input-label
                        for="name"
                        value="Name"
                        class="font-semibold text-gray-700 mb-1"
                    />
                    <x-text-input
                        id="name"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                        class="block w-full rounded-lg
                               bg-gray-50 border border-gray-300
                               text-gray-800 placeholder-gray-400
                               focus:bg-white
                               focus:border-red-700
                               focus:ring-red-700"
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

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
                        class="block w-full rounded-lg
                               bg-gray-50 border border-gray-300
                               text-gray-800 placeholder-gray-400
                               focus:bg-white
                               focus:border-red-700
                               focus:ring-red-700"
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
                               focus:bg-white
                               focus:border-red-700
                               focus:ring-red-700"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div>
                    <x-input-label
                        for="password_confirmation"
                        value="Confirm Password"
                        class="font-semibold text-gray-700 mb-1"
                    />
                    <x-text-input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        class="block w-full rounded-lg
                               bg-gray-50 border border-gray-300
                               text-gray-800 placeholder-gray-400
                               focus:bg-white
                               focus:border-red-700
                               focus:ring-red-700"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                {{-- ACTION --}}
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('login') }}"
                       class="text-sm text-red-700 hover:text-red-800 font-medium">
                        Sudah punya akun?
                    </a>

                    <button
                        type="submit"
                        class="px-6 py-2.5
                               bg-red-700 hover:bg-red-800
                               text-white rounded-lg
                               font-semibold transition">
                        Register
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
