<x-app-layout>
    {{-- ================= BACKGROUND IMAGE ================= --}}
    <div
        class="min-h-screen bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/telubg1.jpg') }}');"
    >
            {{-- OVERLAY MERAH --}}
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10">
<div class="max-w-6xl mx-auto py-8">

    {{-- BACK BUTTON (KIRI ATAS, DI LUAR CARD) --}}
    <div class="mb-5">
    <a href="{{ route('deals') }}"
       class="inline-flex items-center gap-2
              bg-black/40 hover:bg-black/60
              text-white font-semibold
              px-5 py-2 rounded-lg
              shadow backdrop-blur-sm
              transition">
        ← Kembali ke Deals
    </a>
</div>

    {{-- CARD DETAIL PRODUK --}}
    <div class="bg-white rounded-2xl shadow-lg p-8 grid grid-cols-1 md:grid-cols-2 gap-10">

        {{-- IMAGE --}}
        <div class="bg-gray-50 rounded-xl flex items-center justify-center p-6">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}"
                     class="max-h-[420px] object-contain">
            @else
                <span class="text-gray-400">No Image</span>
            @endif
        </div>

        {{-- INFO --}}
        <div class="flex flex-col justify-between">

            {{-- TEXT INFO --}}
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ $product->name }}
                </h1>

                <p class="text-xl font-semibold text-red-700 mb-4">
                    Rp{{ number_format($product->price,0,',','.') }}
                </p>

                <p class="text-gray-600 leading-relaxed mb-6">
                    {{ $product->description }}
                </p>

                <div class="space-y-1 text-sm text-gray-700">
                    <p>
                        <span class="font-semibold">Kondisi:</span>
                        {{ ucfirst($product->product_condition) }}
                    </p>
                    <p>
                        <span class="font-semibold">Stok:</span>
                        {{ $product->stock }}
                    </p>
                </div>
            </div>

           {{-- ACTION CARD (TOKOPEDIA STYLE) --}}
<div class="mt-8 border rounded-xl p-5 bg-gray-50 max-w-sm">

    <h4 class="font-semibold mb-4">Atur jumlah</h4>

    {{-- QTY --}}
    <div class="flex items-center justify-between mb-4">

        <div class="flex items-center border rounded-lg overflow-hidden">
            <button type="button"
                    onclick="decreaseQty()"
                    class="px-3 py-2 bg-white hover:bg-gray-100 text-lg">
                −
            </button>

            <input id="qty"
                   type="number"
                   value="1"
                   min="1"
                   max="{{ $product->stock }}"
                   class="w-14 text-center outline-none border-x"
                   readonly>

            <button type="button"
                    onclick="increaseQty()"
                    class="px-3 py-2 bg-white hover:bg-gray-100 text-lg">
                +
            </button>
        </div>

        <span class="text-sm text-gray-500">
            Stok: {{ $product->stock }}
        </span>
    </div>

    {{-- SUBTOTAL --}}
    <div class="flex justify-between items-center mb-5">
        <span class="text-sm text-gray-600">Subtotal</span>
        <span id="subtotal"
              class="text-lg font-bold text-gray-800">
            Rp{{ number_format($product->price,0,',','.') }}
        </span>
    </div>

   {{-- ADD TO CART --}}
<form id="cartForm" action="{{ route('cart.store') }}" method="POST">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="quantity" id="cartQty" value="1">

    <button
        type="button"
        onclick="handleAddToCart()"
        class="w-full bg-green-600 hover:bg-green-700
               text-white py-2.5 rounded-lg font-semibold
               {{ $product->stock <= 0 ? 'opacity-50' : '' }}">
        + Keranjang
    </button>
</form>

{{-- BUY NOW --}}
<form id="buyForm" action="{{ route('orders.buyNow') }}" method="POST">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="quantity" id="buyQty" value="1">

    <button
        type="button"
        onclick="handleBuyNow()"
        class="w-full border border-green-600 text-green-700
               hover:bg-green-50 py-2.5 rounded-lg font-semibold
               {{ $product->stock <= 0 ? 'opacity-50' : '' }}">
        Beli Langsung
    </button>
</form>

</div>
        </div>

    </div>
</div>
<script>
    // ===== INIT DATA =====
    let qty = 1;
    const price = {{ $product->price }};
    const stock = {{ $product->stock }};

    // ===== FORMAT RUPIAH =====
    function formatRupiah(angka) {
        return 'Rp' + angka.toLocaleString('id-ID');
    }

    // ===== UPDATE QTY + SUBTOTAL =====
    function updateQty() {
        const qtyInput = document.getElementById('qty');
        const cartQty  = document.getElementById('cartQty');
        const buyQty   = document.getElementById('buyQty');
        const subtotal = document.getElementById('subtotal');

        if (stock <= 0) {
            qty = 0;
            qtyInput.value = 0;
            cartQty.value = 0;
            buyQty.value = 0;
            subtotal.innerText = formatRupiah(0);
            return;
        }

        qtyInput.value = qty;
        cartQty.value  = qty;
        buyQty.value   = qty;
        subtotal.innerText = formatRupiah(qty * price);
    }

    // ===== QTY PLUS =====
    function increaseQty() {
        if (stock <= 0) {
            showToast('error', 'Maaf, barang sudah habis');
            return;
        }

        if (qty < stock) {
            qty++;
            updateQty();
        }
    }

    // ===== QTY MINUS =====
    function decreaseQty() {
        if (qty > 1) {
            qty--;
            updateQty();
        }
    }

    // ===== ADD TO CART =====
    function handleAddToCart() {
        if (stock <= 0) {
            showToast('error', 'Maaf, barang sudah habis');
            return;
        }

        document.getElementById('cartForm').submit();
    }

    // ===== BUY NOW =====
    function handleBuyNow() {
        if (stock <= 0) {
            showToast('error', 'Maaf, barang sudah habis');
            return;
        }

        document.getElementById('buyForm').submit();
    }

    // ===== INIT =====
    document.addEventListener('DOMContentLoaded', updateQty);
</script>

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
                    <li><a href="{{ route('orders.index') }}" class="hover:underline">Pesanan</a></li>
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
