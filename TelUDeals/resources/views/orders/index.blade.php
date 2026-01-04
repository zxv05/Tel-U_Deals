<x-app-layout>
    {{-- ================= BACKGROUND IMAGE ================= --}}
    <div
        class="min-h-screen bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/telubg1.jpg') }}');"
    >
        {{-- OVERLAY MERAH --}}
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg text-white">
                Pesanan Saya
            </h2>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4">

        {{-- CONTAINER PUTIH --}}
        <div class="bg-white rounded-xl shadow p-6 mt-6">

            {{-- STEPPER --}}
            <div class="flex rounded-lg overflow-hidden border mb-6">
                <div class="flex-1 text-center py-3 bg-gray-100 text-gray-500">
                    Cart
                </div>
                <div class="flex-1 text-center py-3 bg-red-600 text-white font-semibold">
                    Order
                </div>
                <div class="flex-1 text-center py-3 bg-gray-100 text-gray-500">
                    Payment
                </div>
            </div>

            {{-- CONTENT --}}
            @if($orders->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500 mb-4">
                        Belum ada pesanan.
                    </p>

                    <a href="/dashboard"
                       class="inline-block px-5 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                        Mulai Belanja
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100">
                            <tr class="text-left">
                                <th class="py-3 px-4">Order ID</th>
                                <th class="py-3 px-4 text-center">Total</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-center">Pembayaran</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        {{ $order->order_id }}
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        Rp{{ number_format($order->total_price, 0) }}
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        @if($order->payment_status === 'paid')
                                            <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                                PAID
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                                UNPAID
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('orders.show', $order->id) }}"
                                           class="inline-block px-4 py-2 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">
                                            Bayar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

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
