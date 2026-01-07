<x-app-layout>
{{-- ================= BACKGROUND ================= --}}
<div class="bg-cover bg-center min-h-screen" style="background-image: url('{{ asset('images/telubg1.jpg') }}');">

<div class="bg-[#7b0f2b]/85 pt-6 pb-10 min-h-screen">
    <div class="h-20"></div>
    {{-- ================= CONTENT ================= --}}
    <div class="max-w-6xl mx-auto px-4 mt-6">

        {{-- BACK BUTTON --}}
        <div class="mb-6">
            <a href="{{ route('deals') }}"
               class="inline-flex items-center gap-2 bg-black/40 hover:bg-black/60 text-white font-semibold px-5 py-2 rounded-lg backdrop-blur-sm transition">
                ← Kembali ke Deals
            </a>
        </div>

{{-- ================= PRODUCT CARD ================= --}}
<div id="section-detail" class="scroll-mt-[160px] bg-white rounded-2xl shadow-lg p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    {{-- IMAGE --}}
    <div class="lg:col-span-5 bg-gray-50 rounded-xl flex items-center justify-center p-6 sticky top-28">
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" class="max-h-[420px] object-contain">
        @else
            <span class="text-gray-400">No Image</span>
        @endif
    </div>

    {{-- INFO --}}
    <div class="lg:col-span-4">
        <h1 class="text-2xl font-bold mb-2 text-gray-800">{{ $product->name }}</h1>
        <p class="text-xl font-semibold text-red-700 mb-1">Rp{{ number_format($product->price,0,',','.') }}</p>
        <p class="text-sm text-gray-500 mb-3">Stok: {{ $product->stock }}</p>

        {{-- INFO PENJUAL --}}
        @if($product->seller)
        <div class="flex items-center gap-3 mb-4 text-sm">
            <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-200 border border-gray-100 flex items-center justify-center flex-shrink-0">
                @if($product->seller->avatar) 
                    <img src="{{ asset('storage/' . $product->seller->avatar) }}" alt="{{ $product->seller->name }}" class="h-full w-full object-cover">
                @else
                    <span class="font-semibold text-gray-700">{{ strtoupper(substr($product->seller->name, 0, 1)) }}</span>
                @endif
            </div>
            <a href="{{ route('seller.store', $product->seller->id) }}" class="font-medium hover:text-red-700 transition">
                {{ $product->seller->name }}
            </a>
        </div>
        @endif

        {{-- DESKRIPSI PRODUK --}}
        <div class="border rounded-xl p-4 text-sm text-gray-700">
            <p><b>Kondisi:</b> {{ ucfirst($product->product_condition) }}</p>
            <p><b>Kategori:</b> {{ $product->category }}</p>
            <hr class="my-3">
            
            <div class="relative">
                <div id="descWrapper" style="max-height: 96px; overflow: hidden;" class="transition-all duration-500 relative">
                    <p id="descContent" class="whitespace-pre-line">{{ $product->description }}</p>
                    <div id="descGradient" class="absolute bottom-0 left-0 w-full h-10 bg-gradient-to-t from-white to-transparent"></div>
                </div>

                <button type="button" onclick="toggleDescription()" id="descBtn" class="hidden text-red-700 font-bold mt-2 hover:text-red-800 transition">
                    Lihat Selengkapnya
                </button>
            </div>
        </div>
    </div>

{{-- ACTION BOX --}}
<div class="lg:col-span-3 sticky top-28"> {{-- Tambahkan sticky di sini --}}
    <div class="border rounded-xl p-5 bg-gray-50 shadow-sm">
        <h4 class="font-semibold mb-4 text-gray-800">Atur jumlah</h4>
        
        <div class="flex items-center gap-3 mb-4">
            <button onclick="decreaseQty()" class="px-3 py-2 border rounded bg-white hover:bg-gray-100 transition">−</button>
            <input id="qty" readonly class="w-14 text-center border rounded bg-white" value="1">
            <button onclick="increaseQty()" class="px-3 py-2 border rounded bg-white hover:bg-gray-100 transition">+</button>
        </div>

        <div class="flex justify-between mb-4 text-gray-800">
            <span class="text-sm">Subtotal</span>
            <span id="subtotal" class="font-bold">Rp{{ number_format($product->price,0,',','.') }}</span>
        </div>

        <form id="cartForm" method="POST" action="{{ route('cart.store') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" id="cartQty" value="1">
            <button type="button" onclick="handleAddToCartAjax()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-semibold mb-2 transition">
                + Keranjang
            </button>
        </form>

        <form id="buyForm" method="POST" action="{{ route('orders.buyNow') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" id="buyQty" value="1">
            <button type="button" onclick="handleBuyNow()" class="w-full border border-red-600 text-red-700 py-2 rounded-lg font-semibold hover:bg-red-50 transition">
                Beli Langsung
            </button>
        </form>
    </div>
</div>
</div>

<div class="mt-8 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
     Ulasan Pembeli
    </h3>

    <div class="space-y-6">
        @forelse($product->reviews as $review)
            <div class="border-b border-gray-50 pb-4 last:border-0">
                <div class="flex items-start gap-4">
                    
                    {{-- FOTO PROFIL ATAU INISIAL --}}
                    <div class="flex-shrink-0 mt-1">
                        @if($review->user && $review->user->avatar)
                            <img src="{{ asset('storage/' . $review->user->avatar) }}" 
                                 class="h-10 w-10 rounded-full object-cover border border-gray-200" 
                                 alt="{{ $review->user->name }}">
                        @else
                            {{-- Warna background ini gue sesuain sama tema merah gelap lo --}}
                            <div class="h-10 w-10 rounded-full bg-[#7b0f2b] flex items-center justify-center shadow-sm">
                                <span class="text-white font-bold text-xs uppercase">
                                    {{ substr($review->user->name ?? '?', 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- KONTEN ULASAN --}}
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <div>
                                <span class="font-bold text-sm text-gray-900 block leading-tight">
                                    {{ $review->user->name ?? 'Anonim' }}
                                </span>
                                <span class="text-[10px] text-gray-400">
                                    {{ $review->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            {{-- RATING BINTANG --}}
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }} text-xl leading-none">★</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 italic mt-1 leading-relaxed">
                            "{{ $review->comment }}"
                        </p>
                    </div>

                </div>
            </div>
        @empty
            <div class="py-4 text-center">
                <p class="text-gray-400 text-sm italic">Belum ada ulasan untuk produk ini.</p>
            </div>
        @endforelse
    </div>
</div>

        {{-- RECOMMENDATION --}}
        @if(isset($relatedProducts) && $relatedProducts->count())
        <div class="mt-20">
            <h3 class="text-xl font-bold text-white mb-6">Mungkin Anda tertarik</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $item)
                <a href="{{ route('products.show', $item->id) }}" class="bg-white rounded-xl shadow p-4">
                    <img src="{{ asset('storage/'.$item->image) }}" class="h-40 w-full object-contain">
                    <h4 class="text-sm font-semibold mt-2 text-gray-800">{{ $item->name }}</h4>
                    <p class="text-red-600 font-bold">Rp{{ number_format($item->price,0,',','.') }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

</div>


    <div id="toast"
         class="fixed top-6 right-6 bg-white shadow-xl rounded-xl px-6 py-4 flex items-center gap-3
                opacity-0 translate-y-[-10px] transition-all duration-300 z-[9999]">
        <span id="toastIcon" class="text-green-600 text-xl">✔</span>
        <span id="toastMessage" class="text-gray-800 font-semibold text-sm"></span>
    </div>
    {{-- ================= FOOTER ================= --}}
    <footer class="bg-[#7b0f2b] text-white">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <div>
                    <h3 class="text-xl font-bold mb-3">Tel-U Deals</h3>
                    <p class="text-sm text-gray-200">Marketplace internal Telkom University untuk jual beli aman, cepat, dan terpercaya.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Menu</h4>
                    <ul class="space-y-2 text-sm text-gray-200">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('deals') }}">Marketplace</a></li>
                        <li><a href="{{ route('orders.history') }}">Pesanan</a></li>
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

<script>
    let qty = 1;
    const price = {{ $product->price }};
    const stock = {{ $product->stock }};

    function updateQty() {
        document.getElementById('qty').value = qty;
        document.getElementById('cartQty').value = qty;
        document.getElementById('buyQty').value = qty;
        document.getElementById('subtotal').innerText = 'Rp' + (qty * price).toLocaleString('id-ID');
    }
    function increaseQty() { if (qty < stock) { qty++; updateQty(); } }
    function decreaseQty() { if (qty > 1) { qty--; updateQty(); } }
    function handleAddToCart() { document.getElementById('cartForm').submit(); }
    function handleBuyNow() { document.getElementById('buyForm').submit(); }
</script>
<script>
function handleAddToCartAjax() {
    const form = document.getElementById('cartForm');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(() => {
        // ⬅️ PAKSA TOAST MANUAL
        showToast('Produk ditambahkan ke keranjang', true);
    })
    .catch(() => {
        showToast('Gagal menambahkan ke keranjang', false);
    });
}

function showToast(message, success = true) {
    const toast = document.getElementById('toast');
    const text  = document.getElementById('toastMessage');
    const icon  = document.getElementById('toastIcon');

    text.innerText = 'Berhasil ditambahkan ke keranjang' ? message : message;
    icon.innerText = success ? '✔' : '✖';
    icon.className = success ? 'text-green-600 text-xl' : 'text-red-600 text-xl';

    toast.classList.remove('opacity-0', 'translate-y-[-10px]');
    toast.classList.add('opacity-100', 'translate-y-0');

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-[-10px]');
    }, 2500);
}
function toggleDescription() {
    const wrapper = document.getElementById('descWrapper');
    const gradient = document.getElementById('descGradient');
    const btn = document.getElementById('descBtn');

    if (wrapper.style.maxHeight === '96px') {
        wrapper.style.maxHeight = '2000px'; 
        gradient.classList.add('hidden');
        btn.innerText = 'Sembunyikan';
    } else {
        wrapper.style.maxHeight = '96px';
        gradient.classList.remove('hidden');
        btn.innerText = 'Lihat Selengkapnya';
    }
}

// Cek otomatis saat halaman pertama kali dibuka
document.addEventListener("DOMContentLoaded", function() {
    const content = document.getElementById('descContent');
    const btn = document.getElementById('descBtn');
    
    // Munculkan tombol hanya jika deskripsi lebih tinggi dari 96px (sekitar 4 baris)
    if (content && content.offsetHeight > 96) {
        btn.classList.remove('hidden');
    }
});
</script>
