{{-- WRAPPER NAVBAR MELAYANG --}}
<div x-data="{ open: false }"
     class="fixed top-4 left-0 right-0 z-50 px-4 sm:px-6 lg:px-8">

    {{-- NAVBAR UTAMA --}}
    <nav
        class="mx-auto max-w-7xl bg-white border border-gray-100
               shadow-2xl rounded-full transition-all duration-300">

        <div class="px-8">
            <div class="flex justify-between h-16 items-center">

                {{-- LEFT --}}
                <div class="flex items-center gap-10">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <span class="text-xl font-black text-[#8B1538] tracking-tight">
                            Tel-U Deals<span class="text-yellow-500">.</span>
                        </span>
                    </a>

                    {{-- DESKTOP MENU --}}
                    <div class="hidden md:flex items-center space-x-2">
                        @php
                            $links = [
                                ['route' => 'dashboard', 'label' => 'Dashboard'],
                                ['route' => 'deals', 'label' => 'Tel-U Deals'],
                                ['route' => 'products.mine', 'label' => 'Produk Saya'],
                                ['route' => 'orders.history', 'label' => 'Riwayat'],
                                ['route' => 'cart.index', 'label' => 'Keranjang'],
                            ];
                        @endphp

                        @foreach($links as $link)
                            <a href="{{ route($link['route']) }}"
                               class="px-5 py-2 text-sm font-bold rounded-full transition
                               {{ request()->routeIs($link['route'])
                                   ? 'bg-[#8B1538] text-white shadow'
                                   : 'text-gray-700 hover:bg-gray-100 hover:text-[#8B1538]' }}">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-3">

                    {{-- USER DROPDOWN --}}
                    <x-dropdown align="right" width="48">
<x-slot name="trigger">
    <button
        class="flex items-center gap-3 px-4 py-2 bg-gray-50
               hover:bg-gray-200 border border-gray-200
               rounded-full transition shadow-sm">

        @php
            $user = auth()->user();
            $avatarUrl = $user && $user->avatar
                ? asset('storage/' . $user->avatar)
                : null;
        @endphp

        <div class="w-8 h-8 rounded-full overflow-hidden
                    bg-[#8B1538] flex items-center justify-center
                    text-white font-bold text-xs shadow-inner">
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}"
                     class="w-full h-full object-cover"
                     alt="Avatar">
            @else
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            @endif
        </div>

        @auth
            <span class="hidden sm:block text-sm font-bold text-gray-800">
                {{ $user->name }}
            </span>
        @endauth

        <svg class="w-4 h-4 text-gray-400" fill="none"
             stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
</x-slot>


                        <x-slot name="content">
                            <div class="p-2 bg-white rounded-2xl">
                                <x-dropdown-link :href="route('profile.edit')"
                                    class="flex items-center gap-2 py-3 rounded-xl">
                                    Profil
                                </x-dropdown-link>

                                <div class="border-t my-2"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link
                                        :href="route('logout')"
                                        class="text-red-600 hover:bg-red-50 flex items-center gap-2 py-3 rounded-xl font-bold"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>

                    {{-- HAMBURGER --}}
                    <button @click="open = !open"
                            class="md:hidden p-2 rounded-full border border-gray-200
                                   hover:bg-gray-100 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round"
                                  stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="open" stroke-linecap="round"
                                  stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                </div>
            </div>
        </div>
    </nav>

    {{-- MOBILE MENU (DI LUAR NAV) --}}
    <div
        x-show="open"
        x-transition
        @click.outside="open = false"
        class="md:hidden mt-4 mx-auto max-w-7xl
               bg-white rounded-3xl shadow-2xl border
               p-6 space-y-2"
    >
        @foreach($links as $link)
            <a href="{{ route($link['route']) }}"
               class="block px-4 py-3 text-base font-bold rounded-2xl transition
               {{ request()->routeIs($link['route'])
                   ? 'bg-[#8B1538] text-white'
                   : 'text-gray-700 hover:bg-gray-100' }}">
                {{ $link['label'] }}
            </a>
        @endforeach
    </div>

</div>
