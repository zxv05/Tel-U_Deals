<x-app-layout>

    {{-- ================= BACKGROUND IMAGE ================= --}}
    <div
        class="min-h-screen bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/telubg1.jpg') }}');"
    >
        {{-- OVERLAY MERAH --}}
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10">
<div class="h-20"></div>
            <div class="max-w-6xl mx-auto px-4">

                <div class="bg-white rounded-xl shadow p-6">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">
                            Daftar Produk Saya
                        </h3>

                        <a href="{{ route('products.create') }}"
                           class="bg-red-600 hover:bg-red-600 text-white px-4 py-2 rounded">
                            + Tambah Produk
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($products as $product)
                            <div class="bg-white border rounded-xl shadow flex flex-col">

                                {{-- IMAGE --}}
                                @if($product->image)
                                    <div class="w-full h-56 bg-gray-100 flex items-center justify-center rounded-t-xl">
                                        <img
                                            src="{{ asset('storage/' . $product->image) }}"
                                            class="max-h-full max-w-full object-contain"
                                            alt="{{ $product->name }}"
                                        >
                                    </div>
                                @endif

                                {{-- CONTENT --}}
                                <div class="p-4 flex flex-col flex-1">

                                    <h4 class="font-semibold mb-1">
                                        {{ $product->name }}
                                    </h4>

                                    <p class="text-sm text-gray-500 mb-2 line-clamp-3">
                                        {{ $product->description }}
                                    </p>

                                    <p class="font-bold mb-1">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </p>

                                    <p class="text-sm mb-3">
                                        Stok:
                                        <span class="font-semibold {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </p>

                                    {{-- BUTTON --}}
                                    <div class="mt-auto flex gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                           class="flex-1 text-center bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded text-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route('products.destroy', $product->id) }}"
                                              method="POST"
                                              class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm"
                                                onclick="return confirm('Yakin hapus produk ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">Belum ada produk.</p>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>
<footer class="bg-[#7b0f2b] text-white mt-16">
    <div class="max-w-7xl mx-auto px-6 py-10">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- BRAND --}}
            <div>
                <h3 class="text-xl font-bold mb-2">Tel-U Deals</h3>
                <p class="text-sm text-gray-200">
                    Marketplace internal Telkom University untuk jual beli aman,
                    cepat, dan terpercaya.
                </p>
            </div>

            {{-- MENU --}}
            <div>
                <h4 class="font-semibold mb-3">Menu</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:underline">Marketplace</a></li>
                    <li><a href="{{ route('orders.history') }}" class="hover:underline">Pesanan</a></li>
                </ul>
            </div>

            {{-- ACCOUNT --}}
            <div>
                <h4 class="font-semibold mb-3">Akun</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('profile.edit') }}" class="hover:underline">Profil</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="hover:underline">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>

        {{-- COPYRIGHT --}}
        <div class="border-t border-white/20 mt-8 pt-4 text-center text-sm text-gray-200">
            © {{ date('Y') }} Tel-U Deals. All rights reserved.
        </div>
    </div>
</footer>
</x-app-layout>
