<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <h2 class="text-2xl font-bold mb-6">🛒 Keranjang Belanja</h2>

                @if($KeranjangItems->isEmpty())
                    <div class="text-center text-gray-600 py-10">
                        Keranjang kamu masih kosong 😢
                    </div>
                @else

                {{-- TABEL --}}
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200 rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3">#</th>
                                <th class="p-3 text-left">Produk</th>
                                <th class="p-3 text-center">Qty</th>
                                <th class="p-3 text-right">Harga</th>
                                <th class="p-3 text-right">Total</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($KeranjangItems as $item)
                            <tr class="border-t">
                                <td class="p-3">{{ $loop->iteration }}</td>

                                <td class="p-3 font-semibold">
                                    {{ $item->product->nama_barang }}
                                </td>

                                <td class="p-3 text-center">
                                    <form id="form-{{ $item->id }}"
                                          action="{{ route('keranjang.update', $item->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('PUT')

                                        <input type="number"
                                               name="quantity"
                                               value="{{ $item->quantity }}"
                                               min="1"
                                               max="{{ $item->product->stok + $item->quantity }}"
                                               class="w-16 border rounded text-center">
                                    </form>
                                </td>

                                <td class="p-3 text-right">
                                    Rp{{ number_format($item->product->harga_product, 0, ',', '.') }}
                                </td>

                                <td class="p-3 text-right font-semibold">
                                    Rp{{ number_format($item->total_price, 0, ',', '.') }}
                                </td>

                                <td class="p-3 flex gap-2 justify-center">
                                    <button form="form-{{ $item->id }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                        Perbarui
                                    </button>

                                    <form action="{{ route('keranjang.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MAP SECTION --}}
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-3">Lokasi Pengiriman</h3>

                    <div id="map" class="w-full h-[350px] rounded border"></div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="text" id="search-location"
                               placeholder="Cari lokasi (contoh: Telkom University)"
                               class="border p-2 rounded">

                        <button type="button"
                                onclick="searchLocation()"
                                class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4">
                            🔍 Cari Lokasi
                        </button>

                        <button type="button"
                                onclick="getCurrentLocation()"
                                class="bg-gray-700 hover:bg-gray-800 text-white rounded px-4">
                            📍 Lokasi Saya
                        </button>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-4">
                        <input id="latitude" name="latitude" class="border p-2 rounded" placeholder="Latitude">
                        <input id="longitude" name="longitude" class="border p-2 rounded" placeholder="Longitude">
                    </div>
                </div>

                {{-- RINGKASAN --}}
                <div class="mt-6 flex justify-between items-center">
                    <div>
                        <p><strong>Total Item:</strong> {{ $KeranjangCount }}</p>
                        <p class="text-lg font-bold">
                            Total Harga: Rp{{ number_format($KeranjangItems->sum('total_price'), 0, ',', '.') }}
                        </p>
                    </div>

                    <form action="{{ route('keranjang.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="latitude" id="lat_submit">
                        <input type="hidden" name="longitude" id="lng_submit">

                        <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-semibold">
                            Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>

                @endif
            </div>
        </div>
    </div>

    {{-- LEAFLET --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            let map = L.map('map').setView([-6.914744, 107.609810], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([-6.914744, 107.609810], { draggable: true }).addTo(map);

            function updateLatLng(lat, lng) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('lat_submit').value = lat;
                document.getElementById('lng_submit').value = lng;
            }

            updateLatLng(-6.914744, 107.609810);

            marker.on('dragend', e => {
                updateLatLng(e.target.getLatLng().lat, e.target.getLatLng().lng);
            });

            map.on('click', e => {
                marker.setLatLng(e.latlng);
                updateLatLng(e.latlng.lat, e.latlng.lng);
            });

            function searchLocation() {
                const query = document.getElementById('search-location').value;
                if (!query) return alert("Masukkan lokasi");

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.length) return alert("Lokasi tidak ditemukan");

                        const lat = data[0].lat;
                        const lon = data[0].lon;

                        map.setView([lat, lon], 15);
                        marker.setLatLng([lat, lon]);
                        updateLatLng(lat, lon);
                    });
            }

            function getCurrentLocation() {
                if (!navigator.geolocation) {
                    alert("Browser tidak mendukung GPS");
                    return;
                }

                navigator.geolocation.getCurrentPosition(pos => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;

                    map.setView([lat, lng], 15);
                    marker.setLatLng([lat, lng]);
                    updateLatLng(lat, lng);
                });
            }
        </script>
    @endpush

</x-app-layout>
