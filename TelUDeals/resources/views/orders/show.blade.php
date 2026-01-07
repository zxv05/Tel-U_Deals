<x-app-layout>
    {{-- ================= BACKGROUND IMAGE ================= --}}
    <div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('{{ asset('images/telubg1.jpg') }}');">
        {{-- OVERLAY MERAH --}}
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10 flex items-center justify-center">
            <div class="max-w-2xl w-full px-4">
                <div class="h-20"></div>
                <div class="bg-white rounded-2xl shadow-2xl p-8">

                    {{-- ================= INFO ORDER ================= --}}
                    <div class="mb-8 space-y-3 border-b pb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm font-medium">Order ID</span>
                            <span class="font-bold text-gray-800">{{ $order->order_id }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm font-medium">Status Order</span>
    @if($order->status === 'pending')
        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold uppercase">
            Pending
        </span>
    @elseif($order->status === 'processing')
        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold uppercase">
            Processing
        </span>
    @elseif($order->status === 'completed')
        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase">
            Completed
        </span>
    @endif
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm font-medium">Status Pembayaran</span>
                            @if($order->payment_status === 'paid')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase">PAID</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold uppercase">UNPAID</span>
                            @endif
                        </div>
                    </div>

                    {{-- ================= ITEM LIST ================= --}}
                    <div class="space-y-4 mb-8">
                        @foreach($order->orderDetails as $item)
                            <div class="flex gap-5 border border-gray-100 rounded-xl p-4 items-center bg-gray-50/50">
                                <div class="w-20 h-20 bg-white rounded-lg border shadow-sm flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="object-contain w-full h-full">
                                    @else
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">No Image</span>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <p class="font-bold text-gray-800">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1 font-medium text-uppercase tracking-wider">Jumlah: {{ $item->quantity }} Item</p>
                                </div>

                                <div class="font-black text-gray-900 text-right">
                                    Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- ================= SELLER VIEW ================= --}}
                    @if($isSeller)
                        <div class="mb-8 border-t pt-6">
                            {{-- <h3 class="font-black text-gray-800 mb-4 uppercase tracking-wide">
                                Produk Saya (Penjual)
                            </h3> --}}

                            @foreach($order->orderDetails as $item)
                                @if($item->product->user_id === auth()->id())
                                    <div class="border rounded-xl p-4 mb-4 bg-gray-50">
                                        <p class="font-bold text-gray-900">
                                            {{ $item->product->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 mb-3">
                                            Jumlah: {{ $item->quantity }} item
                                        </p>

                                        {{-- ULASAN PEMBELI --}}
                                        @php
                                            $review = $item->product->reviews->first();
                                        @endphp

                                        @if($review)
                                            <div class="bg-white border rounded-lg p-4">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-sm">
                                                        {{ $review->user->name }}
                                                    </span>
                                                    <span class="text-yellow-400 text-sm">
                                                        {{ str_repeat('★', $review->rating) }}
                                                    </span>
                                                </div>
                                                <p class="text-sm italic text-gray-600">
                                                    "{{ $review->comment }}"
                                                </p>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-400 italic">
                                                Pembeli belum memberikan ulasan
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    {{-- ================= TOTAL ================= --}}
                    <div class="bg-[#7b0f2b]/5 rounded-xl p-5 mb-8 border border-[#7b0f2b]/10">
                        <div class="flex justify-between items-center text-[#7b0f2b]">
                            <span class="font-bold text-lg">Total Pembayaran</span>
                            <span class="font-black text-2xl tracking-tight">
                                Rp{{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- ================= DETAIL PENGIRIMAN ================= --}}
                    @if($isBuyer)
                    <div class="mb-8 border-b pb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#7b0f2b]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Detail Pengiriman
                            </h3>
                            <button 
                                type="button"
                                onclick="document.getElementById('modalAlamat').style.display = 'flex'"
                                class="text-[10px] bg-[#7b0f2b] hover:bg-[#9c1437] text-white px-3 py-1.5 rounded-md font-black transition-all shadow-md">
                                PILIH ALAMAT
                            </button>
                        </div>

<div id="alamatTerpilih"
     class="p-5 border-2 rounded-2xl transition-all duration-300
     {{ $order->address
        ? 'border-blue-500 bg-white shadow-xl'
        : 'border-dashed border-gray-200 bg-gray-50' }}">

    <p class="text-sm">
        <b>Penerima:</b>
        <span id="displayNama" class="text-gray-700">
            {{ $order->address->recipient_name ?? Auth::user()->name }}
        </span>
    </p>

    <p id="displayAlamat"
       class="text-sm mt-2 leading-relaxed
       {{ $order->address ? 'text-gray-700' : 'text-gray-500 italic' }}">
        {{ $order->address->full_address ?? 'Belum ada alamat yang dipilih.' }}
    </p>
</div>

                    </div>
                    @endif
                        {{-- FORM RATING --}}
                        @if($order->user_id == Auth::id() && $order->payment_status == 'paid' && $order->reviews->count() == 0)
                            <div class="border-t-2 border-dashed border-gray-100 pt-8" x-data="{ rating: 5 }">
                                <div class="text-center mb-6">
                                    <h3 class="text-xl font-black text-gray-800 uppercase italic">Gimana Barangnya?</h3>
                                    <p class="text-sm font-bold text-gray-400 uppercase tracking-tighter">Kasih rating buat {{ $order->orderDetails->first()->product->seller->name ?? 'Penjual' }}</p>
                                </div>

                                <form action="{{ route('orders.review') }}" method="POST" class="space-y-6">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <input type="hidden" name="rating" :value="rating">

                                    <div class="flex justify-center gap-2">
                                        <template x-for="i in 5">
                                            <button type="button" @click="rating = i" class="text-5xl transition-all transform hover:scale-110"
                                                :class="i <= rating ? 'text-yellow-400' : 'text-gray-200'">
                                                ★
                                            </button>
                                        </template>
                                    </div>

                                    <textarea name="comment" rows="3" 
                                        class="w-full border-2 border-gray-100 rounded-2xl p-5 focus:ring-0 focus:border-yellow-400 font-bold text-gray-700 placeholder:text-gray-300 transition-all"
                                        placeholder="Tulis ulasan lu di sini bang..."></textarea>
                                    <button type="submit" class="w-full bg-black text-white py-5 rounded-2xl font-black uppercase tracking-[0.2em] shadow-2xl hover:bg-[#7b0f2b] transition-all active:scale-95">
                                        Kirim Ulasan Sekarang
                                    </button>
                                </form>
                            </div>
                        @endif
                    {{-- BUTTON BAYAR --}}
                    @if($order->payment_status !== 'paid' && !empty($snapToken))
                        <button id="pay-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-black text-lg transition-all shadow-xl shadow-blue-100 active:scale-[0.98]">
                            BAYAR SEKARANG
                        </button>
                    @else
                        <div class="text-center text-green-700 mt-4 font-black bg-green-50 py-4 rounded-xl border-2 border-green-200 uppercase tracking-widest text-sm">
                            Pesanan Telah Dibayar ✔
                        </div>
                    @endif
                @if($isSeller && $order->payment_status === 'paid' && $order->status !== 'done')
                    <form method="POST" action="{{ route('orders.markDone', $order) }}" class="mt-6">
                        @csrf
                        <button
                            class="w-full bg-[#7b0f2b] hover:bg-[#9c1437]
                                text-white py-4 rounded-xl
                                font-black uppercase tracking-widest
                                shadow-xl transition-all active:scale-[0.98]">
                            Tandai Pesanan Selesai
                        </button>
                    </form>
                @endif
                </div>
            </div>
        </div>
    </div>
    {{-- ================= MODAL ALAMAT (DI TENGAH LAYAR) ================= --}}
    <div id="modalAlamat" style="display: none;" class="fixed inset-0 bg-black/70 z-[9999] backdrop-blur-sm items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full max-h-[85vh] overflow-hidden flex flex-col shadow-2xl animate-in zoom-in duration-300">
            <div class="p-6 border-b flex justify-between items-center bg-gray-50/80">
                <h3 class="font-black text-xl text-gray-800 tracking-tight uppercase">Alamat Saya</h3>
                <button onclick="document.getElementById('modalAlamat').style.display = 'none'" class="h-10 w-10 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 hover:bg-red-500 hover:text-white transition-all text-2xl font-light">&times;</button>
            </div>
            <div class="p-6 overflow-y-auto space-y-4 custom-scrollbar">
@forelse($addresses as $addr)
    <div class="border-2 border-gray-100 p-5 rounded-2xl hover:border-blue-500 hover:bg-blue-50/50 cursor-pointer transition-all group relative shadow-sm"
        onclick="pilihAlamat(
            '{{ $addr->recipient_name }}',
            '{{ $addr->full_address }}',
            {{ $addr->id }}
        )">

        <div class="flex justify-between items-start mb-3">
            <span class="text-[10px] font-black uppercase px-2 py-1 bg-[#7b0f2b] text-white rounded-md tracking-widest shadow-sm">
                {{ $addr->label }}
            </span>

            @if($addr->is_primary) 
                <span class="text-[9px] bg-green-500 text-white px-2 py-1 rounded-md font-black uppercase tracking-widest shadow-sm">
                    Utama
                </span> 
            @endif
        </div>

        <p class="font-black text-gray-900 text-lg">{{ $addr->recipient_name }}</p>
        <p class="text-sm text-blue-600 font-bold mb-2">{{ $addr->phone }}</p>
        <p class="text-xs text-gray-500 leading-relaxed font-medium">{{ $addr->full_address }}</p>

        {{-- <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
            <div class="bg-blue-500 text-white rounded-full p-1 shadow-lg">
                ✔
            </div>
        </div> --}}
    </div>
@empty

                    <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed">
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum ada alamat tersimpan</p>
                        <a href="{{ route('profile.edit') }}" class="mt-4 inline-block text-[#7b0f2b] font-black text-xs hover:underline decoration-2 underline-offset-4">TAMBAH ALAMAT BARU</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= SCRIPTS ================= --}}
    @if(!empty($snapToken))
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script>
            document.getElementById('pay-button')?.addEventListener('click', function () {
                const alamat = document.getElementById('displayAlamat').innerText;
                if (alamat.includes("Belum ada alamat")) {
                    alert('WAJIB: Pilih alamat pengiriman dulu bang!');
                    document.getElementById('modalAlamat').style.display = 'flex';
                    return;
                }
                snap.pay("{{ $snapToken }}", {
                    onSuccess: () => location.reload(),
                    onPending: () => location.reload(),
                    onError: () => alert('Pembayaran gagal, coba lagi bang')
                });
            });

async function pilihAlamat(nama, alamat, addressId) {
    try {
        // 1. Jalankan fetch ke database
        const response = await fetch("{{ route('orders.setAddress', $order->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },  
            body: JSON.stringify({
                address_id: addressId
            })
        });

        // 2. Cek apakah respon server sukses (status 200-299)
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Gagal menyimpan alamat ke server');
        }

        // Jika sampai sini, berarti database berhasil diupdate
        // 3. UPDATE UI
        document.getElementById('displayNama').innerText = nama;
        document.getElementById('displayAlamat').innerText = alamat;
        document.getElementById('displayAlamat').classList.remove('italic', 'text-gray-500');

        const box = document.getElementById('alamatTerpilih');
        box.classList.replace('border-gray-200', 'border-blue-500');
        box.classList.replace('border-dashed', 'border-solid');
        box.classList.replace('bg-gray-50', 'bg-white');
        box.classList.add('shadow-xl', 'shadow-blue-50');

        // Tutup modal
        document.getElementById('modalAlamat').style.display = 'none';

    } catch (error) {
        // 4. TANGKAP ERROR (Client-side maupun Server-side)
        console.error("Error Detail:", error);
        alert('Maaf, terjadi kesalahan: ' + error.message);
    }
}

            window.onclick = function(event) {
                let modal = document.getElementById('modalAlamat');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    @endif

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