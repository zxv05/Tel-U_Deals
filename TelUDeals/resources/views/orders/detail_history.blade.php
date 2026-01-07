<x-app-layout>
    <div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('{{ asset('images/telubg1.jpg') }}');">
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10">
            <div class="max-w-3xl mx-auto px-4">
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-500 text-white rounded-2xl font-black uppercase text-xs tracking-widest text-center shadow-xl">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Header --}}
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('orders.history') }}" class="text-white font-bold flex items-center gap-2 hover:underline">
                        ⬅️ Kembali ke Riwayat
                    </a>
                    <span class="px-4 py-1 bg-white/20 rounded-full text-white text-[10px] font-black uppercase tracking-widest border border-white/30">
                        Invoice Detail
                    </span>
                </div>

                <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl border-b-[10px] border-yellow-400">
                    {{-- Banner Status --}}
                    <div class="bg-gradient-to-r from-gray-100 to-gray-50 p-8 border-b flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Transaction ID</p>
                            <h2 class="text-xl font-black text-gray-800">{{ $order->order_id }}</h2>
                        </div>
<div class="text-right space-y-1">
    {{-- STATUS PEMBAYARAN --}}
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
        Status Pembayaran
    </p>
                            @if($order->payment_status === 'paid')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase">PAID</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold uppercase">UNPAID</span>
                            @endif

    {{-- STATUS PESANAN --}}
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-2">
        Status Pesanan
    </p>

    @if($order->status === 'pending')
        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold uppercase">
            Pending
        </span>
    @elseif($order->status === 'processing')
        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold uppercase">
            Processing
        </span>
    @elseif($order->status === 'completed')
        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-bold uppercase">
            Completed
        </span>
    @endif
</div>

                    </div>

                    <div class="p-8">
                        {{-- List Barang --}}
                        <div class="space-y-6 mb-10">
                            <h3 class="text-xs font-black text-[#7b0f2b] uppercase tracking-widest mb-4">Daftar Barang</h3>
                            @foreach($order->orderDetails as $item)
                                <div class="flex items-center gap-6 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-20 h-20 object-cover rounded-xl shadow-md">
                                    <div class="flex-1">
                                        <h4 class="font-black text-gray-800 text-lg uppercase leading-tight">{{ $item->product->name }}</h4>
                                        <p class="text-sm font-bold text-gray-400">QTY: {{ $item->quantity }} • Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                        <p class="text-[10px] font-black text-[#7b0f2b] mt-1 uppercase tracking-tighter italic">
                                            Penjual: {{ $item->product->seller->name ?? 'User' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-black text-gray-800">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Total --}}
                        <div class="bg-[#7b0f2b] rounded-2xl p-6 text-white flex justify-between items-center mb-10 shadow-xl shadow-[#7b0f2b]/20">
                            <span class="font-black uppercase tracking-widest text-xs">Total Pembayaran</span>
                            <span class="text-2xl font-black italic tracking-tighter">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        @if($order->payment_status === 'unpaid' && $snapToken)
                            <div class="mt-6 flex justify-end">
                                <button
                                    id="pay-button"
                                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold shadow-lg">
                                    💳 Bayar Sekarang
                                </button>
                            </div>
                        @endif
                        @if($order->payment_status === 'paid')
                            <span class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-xl font-semibold">
                                ✅ Sudah Dibayar
                            </span>
                        @endif

                        {{-- FORM RATING --}}
                        @if($order->user_id == Auth::id() && $order->payment_status == 'paid' && $order->reviews->count() == 0)
                            <div class="border-t-2 border-dashed border-gray-100 pt-8" x-data="{ rating: 5 }">
                                <div class="text-center mb-6">
                                    <h3 class="text-xl font-black text-gray-800 uppercase italic">Gimana Barangnya Bang?</h3>
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

                        {{-- TAMPILAN JIKA SUDAH DI-REVIEW --}}
                        @if($order->reviews->count() > 0)
                            <div class="mt-8 p-6 bg-yellow-50 rounded-[2rem] border-2 border-yellow-200 relative">
                                <div class="absolute -top-3 left-8 bg-yellow-400 px-4 py-1 rounded-full text-[10px] font-black uppercase text-white shadow-md">
                                    Ulasan Lu
                                </div>
                                <div class="flex items-center gap-1 mb-2 text-yellow-500 text-xl">
                                    @for($i=1; $i<=5; $i++)
                                        {{ $i <= $order->reviews->first()->rating ? '★' : '☆' }}
                                    @endfor
                                </div>
                                <p class="text-gray-800 font-black italic text-lg leading-snug">"{{ $order->reviews->first()->comment }}"</p>
                                <p class="text-[10px] font-bold text-yellow-600 uppercase mt-2 opacity-60">Dikirim pada {{ $order->reviews->first()->created_at->format('d/m/Y') }}</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>
@if($order->payment_status === 'unpaid' && $snapToken)
<script>
document.getElementById('pay-button').addEventListener('click', function () {
    window.snap.pay('{{ $snapToken }}', {
        onSuccess: function () {
            window.location.href = "{{ route('orders.history') }}";
        },
        onPending: function () {
            window.location.href = "{{ route('orders.history') }}";
        },
        onError: function () {
            alert('Pembayaran gagal');
        },
        onClose: function () {
            alert('Pembayaran dibatalkan');
        }
    });
});
</script>
@endif
</x-app-layout>