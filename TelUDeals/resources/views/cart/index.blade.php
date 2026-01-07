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

                    @forelse($cartItems as $item)
                        <div class="flex items-center border-b py-4 gap-4">

                            {{-- IMAGE --}}
                            <img
                                src="{{ asset('storage/'.$item->product->image) }}"
                                class="w-24 h-24 object-cover rounded"
                                alt="{{ $item->product->name }}"
                            >

                            {{-- INFO --}}
                            <div class="flex-1">
                                <h4 class="font-semibold">
                                    {{ $item->product->name }}
                                </h4>
                                <p class="text-sm text-gray-500 line-clamp-2">
                                    {{ $item->product->description }}
                                </p>
                                <p class="font-bold">
                                    Rp{{ number_format($item->product->price,0,',','.') }}
                                </p>
                            </div>

                            {{-- QTY --}}
                            <div class="w-20 text-center font-semibold">
                                {{ $item->quantity }}x
                            </div>

                            {{-- TOTAL --}}
                            <div class="w-32 font-bold text-right">
                                Rp{{ number_format($item->total_price,0,',','.') }}
                            </div>

                            {{-- DELETE --}}
                            <form action="{{ route('cart.destroy',$item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-10">
                            Keranjang masih kosong
                        </p>
                    @endforelse

                    {{-- TOTAL --}}
                    @if($cartItems->count())
                        <div class="text-right mt-6">
                            <p class="text-lg font-bold">
                                Total: Rp{{ number_format($total,0,',','.') }}
                            </p>

                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <button
                                    class="mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                                    Checkout
                                </button>
                            </form>
                        </div>
                    @endif

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
