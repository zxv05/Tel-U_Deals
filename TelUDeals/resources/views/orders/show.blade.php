<x-app-layout>
    {{-- ================= BACKGROUND IMAGE ================= --}}
    <div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('{{ asset('images/telubg1.jpg') }}');">
        {{-- OVERLAY MERAH --}}
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10 flex items-center justify-center">
            <div class="max-w-2xl w-full px-4"> {{-- Dipersempit agar lebih fokus di tengah --}}
                <div class="bg-white rounded-2xl shadow-2xl p-8">

                    {{-- ================= INFO ORDER ================= --}}
                    <div class="mb-8 space-y-3 border-b pb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm font-medium">Order ID</span>
                            <span class="font-bold text-gray-800">{{ $order->order_id }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm font-medium">Status Order</span>
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-bold uppercase">
                                {{ ucfirst($order->status) }}
                            </span>
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
                    <div class="mb-8">
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

                        <div id="alamatTerpilih" class="p-5 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50 transition-all duration-300">
                            <p class="text-sm"><b>Penerima:</b> <span id="displayNama" class="text-gray-700">{{ Auth::user()->name }}</span></p>
                            <p class="text-sm text-gray-500 mt-2 leading-relaxed italic" id="displayAlamat">Belum ada alamat yang dipilih.</p>
                        </div>
                    </div>

                    {{-- BUTTON BAYAR --}}
                    @if($order->payment_status !== 'paid' && !empty($snapToken))
                        <button id="pay-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-black text-lg transition-all shadow-xl shadow-blue-100 active:scale-[0.98]">
                            BAYAR SEKARANG
                        </button>
                    @else
                        <div class="text-center text-green-700 font-black bg-green-50 py-4 rounded-xl border-2 border-green-200 uppercase tracking-widest text-sm">
                            Pesanan Telah Dibayar ✔
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL ALAMAT (DI TENGAH LAYAR) ================= --}}
    <div id="modalAlamat" style="display: none;" class="fixed inset-0 bg-black/70 z-[9999] backdrop-blur-sm items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full max-h-[85vh] overflow-hidden flex flex-col shadow-2xl animate-in zoom-in duration-300">
            {{-- Header Modal --}}
            <div class="p-6 border-b flex justify-between items-center bg-gray-50/80">
                <h3 class="font-black text-xl text-gray-800 tracking-tight uppercase">Alamat Saya</h3>
                <button onclick="document.getElementById('modalAlamat').style.display = 'none'" class="h-10 w-10 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 hover:bg-red-500 hover:text-white transition-all text-2xl font-light">&times;</button>
            </div>
            
            {{-- List Alamat --}}
            <div class="p-6 overflow-y-auto space-y-4 custom-scrollbar">
                @forelse($addresses as $addr)
                    <div class="border-2 border-gray-100 p-5 rounded-2xl hover:border-blue-500 hover:bg-blue-50/50 cursor-pointer transition-all group relative shadow-sm"
                         onclick="pilihAlamat('{{ $addr->recipient_name }}', '{{ $addr->full_address }}')">
                        
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-[10px] font-black uppercase px-2 py-1 bg-[#7b0f2b] text-white rounded-md tracking-widest shadow-sm">{{ $addr->label }}</span>
                            @if($addr->is_primary) 
                                <span class="text-[9px] bg-green-500 text-white px-2 py-1 rounded-md font-black uppercase tracking-widest shadow-sm">Utama</span> 
                            @endif
                        </div>
                        
                        <p class="font-black text-gray-900 text-lg">{{ $addr->recipient_name }}</p>
                        <p class="text-sm text-blue-600 font-bold mb-2">{{ $addr->phone }}</p>
                        <p class="text-xs text-gray-500 leading-relaxed font-medium">{{ $addr->full_address }}</p>

                        {{-- Icon Check (Muncul saat hover) --}}
                        <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="bg-blue-500 text-white rounded-full p-1 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
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
            // Fungsi Bayar
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

            // Fungsi Pilih Alamat
            function pilihAlamat(nama, alamat) {
                document.getElementById('displayNama').innerText = nama;
                document.getElementById('displayAlamat').innerText = alamat;
                document.getElementById('displayAlamat').classList.remove('italic', 'text-gray-500');

                // Efek visual box terpilih
                const box = document.getElementById('alamatTerpilih');
                box.classList.replace('border-gray-200', 'border-blue-500');
                box.classList.replace('border-dashed', 'border-solid');
                box.classList.replace('bg-gray-50', 'bg-white');
                box.classList.add('shadow-xl', 'shadow-blue-50');

                // Tutup Modal
                document.getElementById('modalAlamat').style.display = 'none';
            }

            // Klik di luar modal untuk menutup
            window.onclick = function(event) {
                let modal = document.getElementById('modalAlamat');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    @endif
</x-app-layout>