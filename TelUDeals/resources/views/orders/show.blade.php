<x-app-layout>
    {{-- ================= BACKGROUND IMAGE ================= --}}
    <div
        class="min-h-screen bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/telubg1.jpg') }}');"
    >
        {{-- OVERLAY MERAH --}}
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10">

<div class="bg-[#7b0f2b] min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4">

        <div class="bg-white rounded-xl shadow p-6">

            {{-- ================= INFO ORDER ================= --}}
            <div class="mb-6 space-y-2">
                <p><b>Order ID:</b> {{ $order->order_id }}</p>

                <p>
                    <b>Status Order:</b>
                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>

                <p>
                    <b>Status Pembayaran:</b>
                    @if($order->payment_status === 'paid')
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">PAID</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">UNPAID</span>
                    @endif
                </p>
            </div>

            {{-- ================= ITEM LIST ================= --}}
@foreach($order->orderDetails as $item)
<div class="flex gap-4 border rounded-lg p-4 mb-3 items-center">

    {{-- IMAGE --}}
    <div class="w-20 h-20 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
        @if($item->product->image)
            <img
                src="{{ asset('storage/' . $item->product->image) }}"
                alt="{{ $item->product->name }}"
                class="object-contain w-full h-full"
            >
        @else
            <span class="text-xs text-gray-400">No Image</span>
        @endif
    </div>

    {{-- INFO --}}
    <div class="flex-1">
        <p class="font-semibold">
            {{ $item->product->name }}
        </p>
        <p class="text-sm text-gray-500">
            Qty: {{ $item->quantity }}
        </p>
    </div>

    {{-- PRICE --}}
    <div class="font-semibold text-right">
        Rp{{ number_format($item->price * $item->quantity, 0) }}
    </div>

</div>
@endforeach


            {{-- ================= TOTAL ================= --}}
            <div class="bg-gray-100 rounded-lg p-4 mb-6">
                <p class="font-bold text-lg">
                    Total Pembayaran:
                    <span class="float-right">
                        Rp{{ number_format($order->total_price, 0) }}
                    </span>
                </p>
            </div>

            {{-- ================= LOKASI PENGIRIMAN ================= --}}
            <h3 class="font-semibold mb-3">Lokasi Pengiriman</h3>

    {{-- MAP --}}
    <div id="map" class="w-full h-[300px] rounded mb-4"></div>

    {{-- SEARCH --}}
    <div class="flex gap-2 mb-3">
        <input
            type="text"
            id="searchLocation"
            placeholder="Cari lokasi (contoh: Telkom University)"
            class="flex-1 border rounded px-3 py-2"
        >
        <button
            onclick="searchLocation()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            🔍Cari Lokasi
        </button>

        <button
            onclick="getMyLocation()"
            class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">
            📍Lokasi Saya
        </button>
    </div>

    {{-- COORDINATE --}}
    <div class="grid grid-cols-2 gap-3">
        <input id="latitude" class="border rounded px-3 py-2" placeholder="Latitude">
        <input id="longitude" class="border rounded px-3 py-2" placeholder="Longitude">
    </div>
{{-- BUTTON BAYAR --}}
@if($order->payment_status !== 'paid' && !empty($snapToken))
    <div class="mt-6">
        <button id="pay-button"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
            Bayar Sekarang
        </button>
    </div>
@else
    <div class="mt-6 text-center text-green-600 font-semibold">
        Pembayaran Berhasil ✔
    </div>
@endif


</div>
        </div>
    </div>
</div>

{{-- ================= MIDTRANS ================= --}}
@if(!empty($snapToken))
<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.getElementById('pay-button')?.addEventListener('click', function () {
    snap.pay("{{ $snapToken }}", {
        onSuccess: () => location.reload(),
        onPending: () => location.reload(),
        onError: () => alert('Pembayaran gagal')
    });
});
</script>
@endif

@push('scripts')
<script>
    let map = L.map('map').setView([-6.914744, 107.60981], 13);
    let marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    function setMarker(lat, lng) {
        if (marker) map.removeLayer(marker);

        marker = L.marker([lat, lng]).addTo(map);
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    map.on('click', function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    function getMyLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                map.setView([lat, lng], 15);
                setMarker(lat, lng);
            });
        } else {
            alert("Browser tidak mendukung lokasi");
        }
    }

    function searchLocation() {
        const query = document.getElementById('searchLocation').value;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = data[0].lat;
                    const lon = data[0].lon;

                    map.setView([lat, lon], 15);
                    setMarker(lat, lon);
                } else {
                    alert('Lokasi tidak ditemukan');
                }
            });
    }
</script>
@endpush
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
