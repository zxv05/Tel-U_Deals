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
