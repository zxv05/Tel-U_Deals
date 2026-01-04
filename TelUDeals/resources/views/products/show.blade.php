<x-app-layout>

{{-- TAB SHORTCUT --}}
{{-- <div class="sticky top-[80px] z-40 bg-[#7b0f2b]/95 backdrop-blur border-b border-white/20">
    <div class="max-w-6xl mx-auto px-4 flex gap-8 h-[56px] items-center">
        <button class="tab-btn active" onclick="scrollToSection('section-detail')">
            Detail Produk
        </button>
        <button class="tab-btn" onclick="scrollToSection('section-recommendation')">
            Rekomendasi
        </button>
    </div>
</div> --}}
{{-- ================= BACKGROUND ================= --}}
<div class="bg-cover bg-center"
     style="background-image: url('{{ asset('images/telubg1.jpg') }}');">

<div class="bg-[#7b0f2b]/85 pt-6 pb-10">
{{-- ================= CONTENT ================= --}}
<div class="max-w-6xl mx-auto px-4 mt-6">

{{-- BACK BUTTON --}}
<div class="mb-6">
    <a href="{{ route('deals') }}"
       class="inline-flex items-center gap-2
              bg-black/40 hover:bg-black/60
              text-white font-semibold
              px-5 py-2 rounded-lg
              backdrop-blur-sm transition">
        ← Kembali ke Deals
    </a>
</div>

{{-- ================= PRODUCT CARD ================= --}}
<div id="section-detail"
     class="scroll-mt-[160px]
            bg-white rounded-2xl shadow-lg p-8
            grid grid-cols-1 lg:grid-cols-12 gap-8">

{{-- IMAGE --}}
<div class="lg:col-span-5 bg-gray-50 rounded-xl
            flex items-center justify-center p-6">
    @if($product->image)
        <img src="{{ asset('storage/'.$product->image) }}"
             class="max-h-[420px] object-contain">
    @else
        <span class="text-gray-400">No Image</span>
    @endif
</div>

{{-- INFO --}}
<div class="lg:col-span-4">
    <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>

    <p class="text-xl font-semibold text-red-700 mb-1">
        Rp{{ number_format($product->price,0,',','.') }}
    </p>

    <p class="text-sm text-gray-500 mb-3">
        Stok: {{ $product->stock }}
    </p>

    {{-- SELLER --}}
    @if($product->seller)
    <div class="flex items-center gap-2 mb-4 text-sm">
        <div class="h-7 w-7 rounded-full bg-gray-300
                    flex items-center justify-center font-semibold">
            {{ strtoupper(substr($product->seller->name,0,1)) }}
        </div>
        <a href="{{ route('seller.store', $product->seller->id) }}"
           class="text-blue-600 hover:underline">
            {{ $product->seller->name }}
        </a>
    </div>
    @endif

    {{-- DETAIL BOX --}}
    <div class="border rounded-xl p-4 text-sm text-gray-700">
        <p><b>Kondisi:</b> {{ ucfirst($product->product_condition) }}</p>
        <p><b>Kategori:</b> {{ $product->category }}</p>
        <hr class="my-3">
        <p class="whitespace-pre-line">{{ $product->description }}</p>
    </div>
</div>

{{-- ACTION --}}
<div class="lg:col-span-3">
    <div class="border rounded-xl p-5 bg-gray-50 sticky top-28">

        <h4 class="font-semibold mb-4">Atur jumlah</h4>

        <div class="flex items-center gap-3 mb-4">
            <button onclick="decreaseQty()" class="px-3 py-2 border rounded">−</button>
            <input id="qty" readonly class="w-14 text-center border rounded" value="1">
            <button onclick="increaseQty()" class="px-3 py-2 border rounded">+</button>
            <span class="text-sm text-gray-500">Stok {{ $product->stock }}</span>
        </div>

        <div class="flex justify-between mb-4">
            <span class="text-sm">Subtotal</span>
            <span id="subtotal" class="font-bold">
                Rp{{ number_format($product->price,0,',','.') }}
            </span>
        </div>

        <form id="cartForm" method="POST" action="{{ route('cart.store') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" id="cartQty" value="1">
            <button type="button" onclick="handleAddToCart()"
                    class="w-full bg-green-600 hover:bg-green-700
                           text-white py-2 rounded-lg font-semibold mb-2">
                + Keranjang
            </button>
        </form>

        <form id="buyForm" method="POST" action="{{ route('orders.buyNow') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" id="buyQty" value="1">
            <button type="button" onclick="handleBuyNow()"
                    class="w-full border border-green-600
                           text-green-700 py-2 rounded-lg font-semibold">
                Beli Langsung
            </button>
        </form>
    </div>
</div>
</div>

{{-- ================= RECOMMENDATION ================= --}}
@if($relatedProducts->count())
<div id="section-recommendation"
     class="scroll-mt-[130px] mt-20">
    <h3 class="text-xl font-bold text-white mb-6">
        Mungkin Anda tertarik
    </h3>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($relatedProducts as $item)
        <a href="{{ route('products.show', $item->id) }}"
           class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden group">

            <div class="h-44 bg-gray-100 flex items-center justify-center">
                <img src="{{ asset('storage/'.$item->image) }}"
                     class="max-h-full object-contain group-hover:scale-105 transition">
            </div>

            <div class="p-4">
                <h4 class="text-sm font-semibold line-clamp-2 mb-1">{{ $item->name }}</h4>
                <p class="text-red-600 font-bold text-sm mb-1">
                    Rp{{ number_format($item->price,0,',','.') }}
                </p>
                <p class="text-xs text-gray-500">Stok {{ $item->stock }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

</div>
</div>
{{-- ================= TAB STYLE + LOGIC ================= --}}
<style>
.tab-btn {
    padding: 14px 0;
    font-weight: 600;
    color: #d1d5db;
    border-bottom: 3px solid transparent;
    transition: .2s;
}
.tab-btn:hover { color: white; }
.tab-btn.active {
    color: #22c55e;
    border-bottom-color: #22c55e;
}
</style>

<script>
/* =========================
   SCROLL TO SECTION
   ========================= */
function scrollToSection(id) {
    const target = document.getElementById(id);
    if (!target) return;

    target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

/* =========================
   ACTIVE TAB ON SCROLL
   ========================= */
const sections = [
    { id: 'section-detail', tabIndex: 0 },
    { id: 'section-recommendation', tabIndex: 1 }
];

const tabs = document.querySelectorAll('.tab-btn');

window.addEventListener('scroll', () => {
    let current = -1;

    sections.forEach((section, index) => {
        const el = document.getElementById(section.id);
        if (!el) return;

        const rect = el.getBoundingClientRect();

        // 130 = navbar (64px) + tab (56px) + sedikit buffer
        if (rect.top <= 130 && rect.bottom > 130) {
            current = index;
        }
    });

    if (current !== -1) {
        tabs.forEach(tab => tab.classList.remove('active'));
        tabs[current].classList.add('active');
    }
});
</script>

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
<script>
    function switchTab(tab) {
        const tabDetail = document.getElementById('tab-detail');
        const tabInfo = document.getElementById('tab-info');
        const contentDetail = document.getElementById('content-detail');
        const contentInfo = document.getElementById('content-info');

        if (tab === 'detail') {
            tabDetail.classList.add('text-green-600', 'border-b-2', 'border-green-600');
            tabDetail.classList.remove('text-gray-500');

            tabInfo.classList.remove('text-green-600', 'border-b-2', 'border-green-600');
            tabInfo.classList.add('text-gray-500');

            contentDetail.classList.remove('hidden');
            contentInfo.classList.add('hidden');
        } else {
            tabInfo.classList.add('text-green-600', 'border-b-2', 'border-green-600');
            tabInfo.classList.remove('text-gray-500');

            tabDetail.classList.remove('text-green-600', 'border-b-2', 'border-green-600');
            tabDetail.classList.add('text-gray-500');

            contentInfo.classList.remove('hidden');
            contentDetail.classList.add('hidden');
        }
    }
</script>
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
