<x-app-layout>

    {{-- ================= BACKGROUND IMAGE ================= --}}
    <div
        class="min-h-screen bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/telubg1.jpg') }}');"
    >
        {{-- OVERLAY MERAH --}}
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10">

            <div class="max-w-7xl mx-auto px-4">

                {{-- ================= SEARCH BAR ================= --}}
                <form method="GET" class="mb-6">
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari barang..."
                        value="{{ request('search') }}"
                        class="w-full border rounded-lg px-4 py-3"
                    >
                </form>

                <div class="grid grid-cols-12 gap-6">

                    {{-- ================= FILTER SIDEBAR ================= --}}
                    <div class="col-span-12 md:col-span-3">
                        <form method="GET" class="bg-white rounded-xl shadow p-4 space-y-4 sticky top-6">

                            <input type="hidden" name="search" value="{{ request('search') }}">

                            <h4 class="font-semibold text-lg">Filter</h4>

                            <div>
                                <label class="text-sm font-medium">Kategori</label>
                                <select name="category" class="w-full border rounded px-3 py-2 mt-1">
                                    <option value="">Semua</option>
                                    <option value="Elektronik" {{ request('category')=='Elektronik'?'selected':'' }}>Elektronik</option>
                                    <option value="Pakaian" {{ request('category')=='Pakaian'?'selected':'' }}>Pakaian</option>
                                    <option value="Furnitur" {{ request('category')=='Furnitur'?'selected':'' }}>Furnitur</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Harga</label>
                                <div class="flex gap-2 mt-1">
                                    <input type="number" name="min_price" placeholder="Min"
                                           value="{{ request('min_price') }}"
                                           class="w-full border rounded px-2 py-2">
                                    <input type="number" name="max_price" placeholder="Max"
                                           value="{{ request('max_price') }}"
                                           class="w-full border rounded px-2 py-2">
                                </div>
                            </div>

                            <button class="w-full bg-red-600 hover:bg-red-700 text-white rounded py-2">
                                Terapkan
                            </button>
                        </form>
                    </div>

                    {{-- ================= PRODUK GRID ================= --}}
                    <div class="col-span-12 md:col-span-9">
                        <div class="bg-white rounded-xl shadow p-6">

                            <h3 class="text-lg font-semibold mb-6">DEALS</h3>

                            @if($products->isEmpty())
                                <div class="text-center text-gray-500 py-20">
                                    Tidak ada produk yang sesuai filter.
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                                @foreach($products as $product)
                                <div class="bg-white rounded-xl shadow hover:shadow-lg transition flex flex-col overflow-hidden">

                                    {{-- IMAGE --}}
                                    <div class="bg-gray-100 h-56 flex items-center justify-center">
                                        @if($product->image)
                                            <img
                                                src="{{ asset('storage/'.$product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="max-h-full max-w-full object-contain"
                                            >
                                        @else
                                            <span class="text-gray-400 text-sm">No Image</span>
                                        @endif
                                    </div>

                                    {{-- CONTENT --}}
                                    <div class="p-4 flex flex-col flex-1 justify-between">

                                        <div>
                                            <h3 class="font-semibold text-base mb-1">
                                                {{ $product->name }}
                                            </h3>

                                            <p class="text-sm text-gray-500 line-clamp-2 mb-2">
                                                {{ $product->description }}
                                            </p>

                                            <p class="font-bold text-lg">
                                                Rp{{ number_format($product->price,0,',','.') }}
                                            </p>

                                            <p class="text-sm text-green-600">
                                                Stok: {{ $product->stock }}
                                            </p>
                                        </div>

                                        @if($product->user_id !== auth()->id())
                                        <div class="mt-4 space-y-2">

                                            {{-- ADD TO CART --}}
                                            <form action="{{ route('cart.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="number"
                                                       name="quantity"
                                                       min="1"
                                                       max="{{ $product->stock }}"
                                                       value="1"
                                                       class="w-full border rounded px-3 py-2 mb-2">

                                                <button class="w-full bg-green-600 text-white py-2 rounded">
                                                    Tambah ke Keranjang
                                                </button>
                                            </form>

                                            {{-- BUY NOW --}}
                                            <form action="{{ route('orders.buyNow') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">

                                                <button
                                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                                                    Beli Langsung
                                                </button>
                                            </form>

                                        </div>
                                        @endif

                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-[#7b0f2b] text-white mt-16">
        <div class="max-w-7xl mx-auto px-6 py-10">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div>
                    <h3 class="text-xl font-bold mb-2">Tel-U Deals</h3>
                    <p class="text-sm text-gray-200">
                        Marketplace internal Telkom University untuk jual beli aman,
                        cepat, dan terpercaya.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-3">Menu</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
                        <li><a href="{{ route('products.mine') }}" class="hover:underline">Produk Saya</a></li>
                        <li><a href="{{ route('orders.index') }}" class="hover:underline">Pesanan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-3">Akun</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('profile.edit') }}" class="hover:underline">Profil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="hover:underline">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-white/20 mt-8 pt-4 text-center text-sm text-gray-200">
                © {{ date('Y') }} Tel-U Deals. All rights reserved.
            </div>
        </div>
    </footer>
</x-app-layout>
