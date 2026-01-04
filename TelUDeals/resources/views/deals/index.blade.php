<x-app-layout>

{{-- ================= BACKGROUND ================= --}}
<div class="min-h-screen bg-cover bg-center bg-fixed"
     style="background-image: url('{{ asset('images/telubg1.jpg') }}');">

    <div class="min-h-screen bg-[#7b0f2b]/85 py-10">

        <div class="max-w-7xl mx-auto px-4">

            {{-- ================= SEARCH ================= --}}
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
                    <form method="GET"
                          class="bg-white rounded-xl shadow p-4 space-y-4 sticky top-6">

                        <input type="hidden" name="search" value="{{ request('search') }}">

                        <h4 class="font-semibold text-lg">Filter</h4>

                        {{-- KATEGORI --}}
                        <div>
                            <label class="text-sm font-medium">Kategori</label>
                            <select name="category" class="w-full border rounded px-3 py-2 mt-1">
                                <option value="">Semua</option>
                                <option value="Elektronik" {{ request('category')=='Elektronik'?'selected':'' }}>Elektronik</option>
                                <option value="Pakaian" {{ request('category')=='Pakaian'?'selected':'' }}>Pakaian</option>
                                <option value="Furnitur" {{ request('category')=='Furnitur'?'selected':'' }}>Furnitur</option>
                            </select>
                        </div>

                        {{-- HARGA --}}
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

                        {{-- KONDISI --}}
                        <div>
                            <label class="text-sm font-medium block mb-2">Kondisi</label>

                            <div class="space-y-2 text-sm">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox"
                                           name="product_condition[]"
                                           value="baru"
                                           class="rounded text-green-600"
                                           {{ in_array('baru', (array) request('product_condition')) ? 'checked' : '' }}>
                                    <span>Baru</span>
                                </label>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox"
                                           name="product_condition[]"
                                           value="bekas"
                                           class="rounded text-yellow-600"
                                           {{ in_array('bekas', (array) request('product_condition')) ? 'checked' : '' }}>
                                    <span>Bekas</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white rounded py-2">
                            Terapkan
                        </button>

                    </form>
                </div>

                {{-- ================= PRODUK GRID ================= --}}
                <div class="col-span-12 md:col-span-9">
                    <div class="bg-white rounded-xl shadow p-6">

                        <h3 class="text-lg font-semibold mb-6">DEALS</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                            @forelse($products as $product)
                            <a href="{{ route('products.show', $product->id) }}"
                               class="bg-white rounded-xl shadow hover:shadow-lg transition flex flex-col overflow-hidden">

                                {{-- IMAGE --}}
                                <div class="bg-gray-100 h-56 flex items-center justify-center cursor-pointer">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}"
                                             class="max-h-full max-w-full object-contain hover:scale-105 transition">
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
                                        {{-- SELLER --}}
                                        <p class="text-xs text-gray-500 mb-1">
                                            Seller:
                                            <span class="font-medium text-gray-700">
                                                {{ $product->seller->name ?? 'Unknown Seller' }}
                                            </span>
                                        </p>
                                        {{-- BADGE KONDISI --}}
                                        <span class="inline-block text-xs px-2 py-1 rounded-full mb-2 font-semibold
                                            {{ $product->product_condition === 'baru'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ ucfirst($product->product_condition) }}
                                        </span>

                                        {{-- <p class="text-sm text-gray-500 line-clamp-2 mb-2">
                                            {{ $product->description }}
                                        </p> --}}

                                        <p class="font-bold text-lg">
                                            Rp{{ number_format($product->price,0,',','.') }}
                                        </p>

                                        {{-- <p class="text-sm text-green-600">
                                            Stok: {{ $product->stock }}
                                        </p> --}}
                                    </div>

                                </div>
                            </a>
                            @empty
                            <p class="col-span-3 text-center text-gray-500">
                                Tidak ada produk
                            </p>
                            @endforelse

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ================= FOOTER ================= --}}
<footer class="bg-[#7b0f2b] text-white">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div>
                <h3 class="text-xl font-bold mb-3">Tel-U Deals</h3>
                <p class="text-sm text-gray-200">
                    Marketplace internal Telkom University untuk jual beli aman,
                    cepat, dan terpercaya.
                </p>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Menu</h4>
                <ul class="space-y-2 text-sm text-gray-200">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('deals') }}">Marketplace</a></li>
                    <li><a href="{{ route('orders.index') }}">Pesanan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Akun</h4>
                <ul class="space-y-2 text-sm text-gray-200">
                    <li><a href="{{ route('profile.edit') }}">Profil</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Keamanan & Privasi</h4>
                <ul class="space-y-2 text-sm text-gray-200">
                    <li>✔ Transaksi aman</li>
                    <li>✔ Data pengguna terlindungi</li>
                    <li>✔ Sistem internal Tel-U</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/20 mt-10 pt-6 text-sm text-gray-200">
            © {{ date('Y') }} Tel-U Deals. All rights reserved.
        </div>
    </div>
</footer>
</x-app-layout>
