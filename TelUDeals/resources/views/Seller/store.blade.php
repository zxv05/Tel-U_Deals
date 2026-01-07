<x-app-layout>
    <div class="bg-[#7b0f2b] min-h-screen py-12">
        <div class="max-w-6xl mx-auto px-4">
            
            {{-- TOMBOL KEMBALI --}}
            <div class="mb-8">
                <a href="{{ route('deals') }}" class="text-white/70 hover:text-white font-bold flex items-center gap-2 transition text-sm">
                    ← Kembali ke Marketplace
                </a>
            </div>

            {{-- CARD PROFIL PENJUAL --}}
            <div class="bg-white rounded-[40px] pt-16 pb-10 px-10 mb-12 mt-10 shadow-2xl flex flex-col items-center border-b-[10px] border-black/20">
                <div class="h-32 w-32 rounded-full overflow-hidden mb-6 shadow-xl border-4 border-white flex-shrink-0 bg-gray-50">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                            <span class="text-4xl font-black text-gray-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
                
                <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tighter mb-1">{{ $user->name }}</h1>
                <p class="text-gray-400 font-bold italic text-sm mb-6">Member Tel-U Deals sejak {{ $user->created_at->format('d M Y') }}</p>
                
                <div class="flex gap-3">
                    <span class="bg-red-100 text-red-600 px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-red-200">Verified Seller</span>
                    <span class="bg-blue-50 text-blue-600 px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-100">{{ $products->count() }} Produk Jualan</span>
                </div>
            </div>

            <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter mb-8">Koleksi Produk Jualan</h3>

            {{-- GRID PRODUK --}}
            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach($products as $item)
                        <a href="{{ route('products.show', $item->id) }}" class="bg-white rounded-[32px] p-5 shadow-xl flex flex-col transition-transform hover:-translate-y-2">
                            
                            {{-- WRAPPER GAMBAR (BADGE SUDAH DIHAPUS) --}}
                            <div class="relative aspect-square overflow-hidden rounded-[24px] bg-gray-50 mb-5 flex items-center justify-center">
                                @if($item->image)
                                    <img src="{{ asset('storage/'.$item->image) }}" class="h-full w-full object-cover">
                                @else
                                    <div class="text-gray-200 font-black uppercase italic text-xs">No Image</div>
                                @endif
                            </div>

                            {{-- DETAIL --}}
                            <div class="flex flex-col flex-1">
                                <h4 class="text-[12px] font-bold text-gray-800 line-clamp-2 mb-1 uppercase">{{ $item->name }}</h4>
                                <p class="text-lg font-black text-red-600 mb-4">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                
                                <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-[8px] font-bold text-gray-400 uppercase">{{ $item->category }}</span>
                                    <span class="text-[8px] font-bold text-green-500 uppercase">Stok: {{ $item->stock }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-black/10 rounded-[40px] py-20 text-center border-4 border-dashed border-white/10">
                    <p class="text-white/20 text-xl font-black italic uppercase">Kosong Bang!</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>