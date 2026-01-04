<x-app-layout>
  {{-- ================= BACKGROUND ================= --}}
<div class="bg-cover bg-center"
     style="background-image: url('{{ asset('images/telubg1.jpg') }}');">

<div class="bg-[#7b0f2b]/85 py-10">
    <div class="max-w-7xl mx-auto px-6 py-10">

        {{-- SELLER HEADER --}}
        <div class="bg-white rounded-xl shadow p-6 mb-8 flex items-center gap-4">
            <div class="h-14 w-14 rounded-full bg-gray-200
                        flex items-center justify-center
                        text-xl font-bold text-gray-700">
                {{ strtoupper(substr($seller->name, 0, 1)) }}
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $seller->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $products->count() }} produk dijual
                </p>
            </div>
        </div>

{{-- PRODUCT LIST (SAMA KAYAK DEALS) --}}
@if ($products->count())
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach ($products as $product)
        <a href="{{ route('products.show', $product->id) }}"
           class="group bg-white rounded-xl shadow
                  hover:shadow-lg hover:-translate-y-1
                  transition-all duration-300
                  overflow-hidden">

            {{-- IMAGE --}}
            <div class="h-60 bg-gray-100 flex items-center justify-center overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}"
                         class="max-h-full object-contain
                                transition-transform duration-300
                                group-hover:scale-105">
                @else
                    <span class="text-gray-400 text-sm">No Image</span>
                @endif
            </div>

            {{-- INFO --}}
            <div class="p-4">
                <h3 class="font-semibold text-base mb-1 line-clamp-2 text-gray-800">
                    {{ $product->name }}
                </h3>

                <span class="inline-block mb-2 px-2 py-1
                             text-xs rounded-full
                             bg-green-100 text-green-700">
                    {{ ucfirst($product->product_condition) }}
                </span>

                <p class="text-lg font-bold text-gray-900">
                    Rp{{ number_format($product->price, 0, ',', '.') }}
                </p>

                <p class="text-sm text-gray-500">
                    Stok: {{ $product->stock }}
                </p>
            </div>
        </a>
    @endforeach
</div>
@else
<div class="text-center text-gray-500 mt-16">
    Seller ini belum memiliki produk.
</div>
@endif

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
