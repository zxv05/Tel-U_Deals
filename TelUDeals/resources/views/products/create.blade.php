<x-app-layout>
        {{-- ================= BACKGROUND IMAGE ================= --}}
    <div
        class="min-h-screen bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/telubg1.jpg') }}');"
    >
        {{-- OVERLAY MERAH --}}
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10">
    <x-slot name="header">
        <h2 class="fw-bold">Tambah Produk</h2>
    </x-slot>

    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama Produk</label>
                <input 
                    type="text" 
                    name="name" 
                    class="w-full border rounded p-2" 
                    required
                >
            </div>

            {{-- Kategori --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Kategori</label>
                <select name="category" class="w-full border rounded p-2" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Furnitur">Furnitur</option>
                    <option value="Pakaian">Pakaian</option>
                </select>
            </div>

            {{-- DESKRIPSI (WAJIB ADA) --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Deskripsi</label>
                <textarea 
                    name="description" 
                    rows="3"
                    class="w-full border rounded p-2"
                    required
                ></textarea>
            </div>
            {{-- Gambar Produk --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Gambar Produk</label>
                <input
                    type="file"
                    name="image"
                    accept="image/*"
                    class="w-full border rounded p-2"
                >
            </div>

             {{-- KONDISI BARANG --}}
          <div>
         <label class="block font-medium mb-1">
                Kondisi Barang
         </label>

        <select name="product_condition" class="w-full border rounded-lg px-3 py-2" required>
        <option value="">-- Pilih Kondisi --</option>
        <option value="baru" {{ old('product_condition') == 'baru' ? 'selected' : '' }}>
            Baru
        </option>
        <option value="bekas" {{ old('product_condition') == 'bekas' ? 'selected' : '' }}>
            Bekas
        </option>
        </select>

        @error('product_condition')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        </div>


            {{-- Harga --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Harga</label>
                <input 
                    type="number" 
                    name="price" 
                    class="w-full border rounded p-2" 
                    required
                >
            </div>

            {{-- Stok --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Stok</label>
                <input 
                    type="number" 
                    name="stock" 
                    class="w-full border rounded p-2" 
                    required
                >
            </div>

            <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded">
                Tambahkan Produk
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
