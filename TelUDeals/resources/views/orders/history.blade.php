<x-app-layout>
    <div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('{{ asset('images/telubg1.jpg') }}');">
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10">
            <div class="h-20"></div>
            <div class="max-w-4xl mx-auto px-4">
                
                {{-- HEADER --}}
                <div class="flex items-center gap-3 mb-8">
                    <div class="h-10 w-2 bg-yellow-400 rounded-full"></div>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter">Riwayat Transaksi</h2>
                </div>

                {{-- TAB SYSTEM MENGGUNAKAN ALPINE JS --}}
                <div x-data="{ tab: 'pembelian' }" class="space-y-6">
                    
                    {{-- Navigasi Tab --}}
                    <div class="flex p-1.5 bg-black/20 backdrop-blur-md rounded-2xl w-fit border border-white/10">
                        <button @click="tab = 'pembelian'" 
                            :class="tab === 'pembelian' ? 'bg-white text-[#7b0f2b] shadow-xl' : 'text-white hover:bg-white/10'"
                            class="px-8 py-3 rounded-xl font-black text-xs uppercase transition-all duration-300">
                            🛒 Pembelian
                        </button>
                        <button @click="tab = 'penjualan'" 
                            :class="tab === 'penjualan' ? 'bg-white text-[#7b0f2b] shadow-xl' : 'text-white hover:bg-white/10'"
                            class="px-8 py-3 rounded-xl font-black text-xs uppercase transition-all duration-300">
                            💰 Penjualan
                        </button>
                    </div>

                    {{-- KONTEN TAB PEMBELIAN --}}
                    <div x-show="tab === 'pembelian'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" class="space-y-4">
                        @forelse($purchases as $order)
                            <div class="bg-white rounded-2xl p-6 shadow-2xl flex flex-col md:flex-row justify-between items-center border-b-4 border-blue-500 hover:scale-[1.01] transition-transform">
                                <div class="flex gap-4 items-center">
                                    <div class="h-16 w-16 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden">
                                        @php
                                            $items = $order->orderDetails;
                                            $firstItem = $items->first();
                                            $extraCount = $items->count() - 1;
                                        @endphp
                                        @if($firstItem && $firstItem->product && $firstItem->product->image)
                                            <img src="{{ asset('storage/' . $firstItem->product->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl">📦</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest">{{ $order->order_id }}</p>
                                        </div>
                                        <h4 class="font-black text-gray-800 text-lg uppercase leading-tight">
                                            {{ $firstItem->product->name ?? 'Produk dihapus' }}
                                        </h4>
                                        @if($extraCount > 0)
                                            <p class="text-xs text-gray-400 font-bold">
                                                +{{ $extraCount }} produk lainnya
                                            </p>
                                        @endif
                                        <p class="text-xs text-gray-400 font-bold italic">{{ $order->created_at->format('d F Y • H:i') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-6 mt-4 md:mt-0">
                                    <div class="text-right">
                                        <p class="text-xs font-bold text-gray-400 uppercase">Total Bayar</p>
                                        <p class="font-black text-xl text-gray-900 tracking-tighter text-[#7b0f2b]">
                                            Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    {{-- Link ke Detail History (Invoice & Ulasan) --}}
                                    <a href="{{ route('orders.show', $order->id) }}" class="bg-black text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase shadow-lg hover:bg-[#7b0f2b] transition-all">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-20 bg-white/10 rounded-3xl border-2 border-dashed border-white/20">
                                <p class="text-white font-black uppercase tracking-widest opacity-50">Belum ada barang yang lu beli bang</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- KONTEN TAB PENJUALAN --}}
                    <div x-show="tab === 'penjualan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" style="display: none;" class="space-y-4">
                        @forelse($sales as $sale)
                            <div class="bg-white rounded-2xl p-6 shadow-2xl flex flex-col md:flex-row justify-between items-center border-b-4 border-green-500 hover:scale-[1.01] transition-transform">
                                <div class="flex gap-4 items-center">
                                    <div class="h-16 w-16 bg-green-50 rounded-xl flex items-center justify-center overflow-hidden">
                                        @php $saleItem = $sale->orderDetails->first(); @endphp
                                        @if($saleItem && $saleItem->product && $saleItem->product->image)
                                            <img src="{{ asset('storage/' . $saleItem->product->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl">💸</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-green-600 uppercase tracking-widest">Pembeli: {{ $sale->user->name }}</p>
                                        <h4 class="font-black text-gray-800 text-lg uppercase leading-tight">
                                            {{ $saleItem->product->name ?? 'Produk Terjual' }}
                                        </h4>
                                        <p class="text-xs text-gray-400 font-bold uppercase">Jumlah: {{ $saleItem->quantity ?? 0 }} Unit</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-6 mt-4 md:mt-0">
                                    <div class="text-right flex flex-col items-end gap-1">
                                        {{-- <p class="text-[10px] font-black uppercase px-3 py-1 rounded-full {{ $sale->payment_status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $sale->payment_status }}
                                        </p> --}}
                                        <p class="font-black text-xl text-green-600 tracking-tighter">
                                            + Rp{{ number_format($sale->total_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    {{-- Penjual juga bisa lihat detail/ulasan --}}
                                    <a href="{{ route('orders.show', $sale->id) }}" class="bg-black text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase shadow-lg hover:bg-[#7b0f2b] transition-all">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-20 bg-white/10 rounded-3xl border-2 border-dashed border-white/20">
                                <p class="text-white font-black uppercase tracking-widest opacity-50">Barang lu belum ada yang laku bang, sabar ya</p>
                            </div>
                        @endforelse
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