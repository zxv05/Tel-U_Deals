<x-app-layout>
        {{-- ================= BACKGROUND IMAGE ================= --}}
    <div
        class="min-h-screen bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/telubg1.jpg') }}');"
    >
        {{-- OVERLAY MERAH --}}
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10">
<div class="h-20"></div>
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ $product->name }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Kategori</label>
                <select name="category" class="w-full border rounded p-2" required>
                    <option value="Elektronik" {{ $product->category == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="Furnitur" {{ $product->category == 'Furnitur' ? 'selected' : '' }}>Furnitur</option>
                    <option value="Pakaian" {{ $product->category == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Deskripsi</label>
                <textarea name="description" class="w-full border rounded p-2" required>{{ $product->description }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Harga</label>
                <input type="number" name="price" value="{{ $product->price }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Stok</label>
                <input type="number" name="stock" value="{{ $product->stock }}" class="w-full border rounded p-2" required>
            </div>
            
            <div class="mb-4">
    <label class="block text-sm font-medium mb-1">Kondisi Barang</label>
    <select name="product_condition"
            class="w-full border rounded px-3 py-2"
            required>
        <option value="baru" {{ $product->product_condition=='baru'?'selected':'' }}>Baru</option>
        <option value="bekas" {{ $product->product_condition=='bekas'?'selected':'' }}>Bekas</option>
    </select>
</div>


            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" class="w-32 mb-3 rounded">
            @endif

            <div class="mb-4">
                <label class="block font-semibold mb-1">Ganti Gambar</label>
                <input type="file" name="image" class="w-full border rounded p-2">
            </div>

            <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded">
                Simpan Perubahan
            </button>
        </form>
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
