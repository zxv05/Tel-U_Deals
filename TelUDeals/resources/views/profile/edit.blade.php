<x-app-layout>

<div class="bg-[#7b0f2b]/85 min-h-screen">
    {{-- ================= BACKGROUND ================= --}}
<div class="bg-cover bg-center min-h-screen" style="background-image: url('{{ asset('images/telubg1.jpg') }}');">

<div class="bg-[#7b0f2b]/85 pt-6 pb-10 min-h-screen">
    <div class="h-20"></div>
<div class="max-w-7xl mx-auto px-4">

<h1 class="text-2xl font-bold text-white mb-6">Profile</h1>

<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
<div class="grid grid-cols-1 md:grid-cols-4">

{{-- ================= SIDEBAR ================= --}}
<aside class="border-r p-6 space-y-6">

<div class="flex flex-col items-center">
<div class="w-28 h-28 rounded-full bg-gray-200 overflow-hidden mb-3">
@if(auth()->user()->avatar)
<img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
@else
<div class="w-full h-full flex items-center justify-center text-3xl font-bold text-gray-500">
{{ strtoupper(substr(auth()->user()->name,0,1)) }}
</div>
@endif
</div>

<form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
@csrf @method('POST')
<label class="cursor-pointer text-sm text-green-600 hover:underline">
Pilih Foto
<input type="file" name="avatar" accept="image/png,image/jpeg" class="hidden" onchange="this.form.submit()">
</label>
</form>

<p class="text-xs text-gray-500 mt-2">JPG / PNG • Maks 10MB</p>
</div>

<nav class="space-y-1 text-sm mt-6">
<button data-tab="biodata" class="tab-btn active">Biodata Diri</button>
<button data-tab="privasi" class="tab-btn">Privasi</button>
<button data-tab="alamat" class="tab-btn">Daftar Alamat</button>
</nav>

</aside>

{{-- ================= CONTENT ================= --}}
<section class="md:col-span-3 p-8">
{{-- TAB BIODATA --}}
<div id="tab-biodata" class="tab-content space-y-8">
    <h2 class="text-lg font-semibold">Biodata Diri</h2>

    <div class="grid grid-cols-3 gap-4 text-sm">
    <span class="text-gray-500">Nama</span>
    <span class="col-span-2 font-medium">{{ auth()->user()->name }}</span>

    <span class="text-gray-500">Tanggal Lahir</span>
    <span class="col-span-2 font-medium">
        {{ auth()->user()->tanggal_lahir ? \Carbon\Carbon::parse(auth()->user()->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
    </span>

    <span class="text-gray-500">Email</span>
    <span class="col-span-2 font-medium">{{ auth()->user()->email }}</span>

    <span class="text-gray-500">Nomor HP</span>
    <span class="col-span-2 font-medium">{{ auth()->user()->phone ?? '-' }}</span>
</div>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 max-w-xl">
        @csrf @method('PATCH')
        
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full rounded-lg border-gray-300">

        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="{{ auth()->user()->tanggal_lahir }}" class="w-full rounded-lg border-gray-300">

        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full rounded-lg border-gray-300">

        <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
        <input type="text" name="phone" value="{{ auth()->user()->phone }}" class="w-full rounded-lg border-gray-300" placeholder="Contoh: 08123456789">

        <button class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Simpan</button>
    </form>
</div>

{{-- TAB PRIVASI --}}
<div id="tab-privasi" class="tab-content hidden space-y-6">
<h2 class="text-lg font-semibold">Keamanan Akun</h2>

<form method="POST" action="{{ route('password.update') }}" class="space-y-4 max-w-xl">
@csrf @method('PUT')
<input type="password" name="current_password" placeholder="Password saat ini" class="w-full rounded-lg border-gray-300">
<input type="password" name="password" placeholder="Password baru" class="w-full rounded-lg border-gray-300">
<input type="password" name="password_confirmation" placeholder="Konfirmasi password baru" class="w-full rounded-lg border-gray-300">
<button class="border border-green-600 text-green-700 px-6 py-2 rounded-lg">Update Password</button>
</form>
</div>

{{-- TAB ALAMAT --}}
<div id="tab-alamat" class="tab-content hidden space-y-6">
<div class="flex items-center gap-4">
<div class="flex-1 relative">
</div>

<button onclick="openAddressModal()" class="bg-green-600 text-white px-5 py-2 rounded-lg">
+ Tambah Alamat Baru
</button>
</div>

<div id="addressList" class="space-y-4">
    @forelse($addresses as $addr)
        {{-- Card Alamat --}}
        <div class="address-card border rounded-xl p-4 flex justify-between items-start hover:border-green-600 transition"
             data-id="{{ $addr->id }}"
             data-label="{{ $addr->label }}"
             data-name="{{ $addr->recipient_name }}"
             data-phone="{{ $addr->phone }}"
             data-address="{{ $addr->full_address }}"
             data-primary="{{ $addr->is_primary ? 'true' : 'false' }}">
            
            <div class="space-y-1 text-sm">
                <div class="flex items-center gap-2">
                    <span class="font-semibold">{{ $addr->label }}</span>
                    @if($addr->is_primary)
                        <span class="primary-badge text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">
                            Utama
                        </span>
                    @endif
                </div>

                <p class="font-medium">{{ $addr->recipient_name }}</p>
                <p class="text-gray-500">{{ $addr->phone }}</p>
                <p class="text-gray-600">{{ $addr->full_address }}</p>

                <div class="flex gap-4 text-green-600 text-xs mt-2 font-semibold">
                    <button type="button" onclick="editAddress(this)" class="hover:underline">
                        Ubah
                    </button>
                    
                    {{-- Form Hapus --}}
                    <form action="{{ route('addresses.destroy', $addr->id) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>

            @if($addr->is_primary)
                <div class="primary-check text-green-600 text-xl font-bold">✓</div>
            @endif
        </div>
    @empty
        {{-- Tampilan jika alamat kosong --}}
        <p id="emptyAddress" class="text-sm text-gray-400">
            Belum ada alamat tersimpan.
        </p>
    @endforelse
</div>


</section>
</div>
</div>
</div>
</div>

{{-- ================= MODAL TAMBAH ALAMAT ================= --}}
<div id="addressModal"
     class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center">

    <div class="bg-white w-full max-w-2xl rounded-xl
                max-h-[90vh] overflow-y-auto shadow-lg">

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div class="flex items-center gap-3">
                <h3 class="font-semibold text-lg">Tambah Alamat</h3>
            </div>
            <button onclick="closeAddressModal()" class="text-xl">✕</button>
        </div>

        {{-- BODY --}}
        <div class="p-6 space-y-6 text-sm">

            <h4 class="font-semibold text-base">
                Lengkapi detail alamat
            </h4>

            {{-- NAMA PENERIMA --}}
            <div>
                <label class="block text-gray-600 mb-1">
                    Nama Penerima <span class="text-red-500">*</span>
                </label>

                <input
                    id="recipientName"
                    type="text"
                    value="{{ auth()->user()->name }}"
                    placeholder="Nama Penerima"
                    maxlength="50"
                    oninput="validateNama()"
                    class="w-full rounded-lg border-gray-300
                        focus:ring-green-500 focus:border-green-500">

                <p id="namaError"
                class="text-xs text-red-500 mt-1 hidden">
                    Wajib diisi
                </p>

                <p class="text-xs text-gray-400 text-right mt-1">
                    <span id="namaCount">{{ strlen(auth()->user()->name) }}</span>/50
                </p>
            </div>
            {{-- NOMOR HP --}}
            <div>
                <label class="block text-gray-600 mb-1">
                    Nomor HP <span class="text-red-500">*</span>
                </label>

                <input
                    id="phoneNumber"
                    type="text"
                    value="{{ auth()->user()->phone }}"
                    placeholder="Nomor HP"
                    maxlength="15"
                    oninput="validateHp(this)"
                    class="w-full rounded-lg border-gray-300
                        focus:ring-green-500 focus:border-green-500">

                <p id="hpError"
                class="text-xs text-red-500 mt-1 hidden">
                    Nomor HP wajib diisi & hanya angka
                </p>

                <p class="text-xs text-gray-400 text-right mt-1">
                    <span id="hpCount">0</span>/15
                </p>
            </div>

            <hr>

            {{-- LABEL ALAMAT --}}
            <div>
                <label class="block text-gray-600 mb-1">
                    Label Alamat <span class="text-red-500">*</span>
                </label>

                <input
                    id="addressLabel"
                    type="text"
                    placeholder="Label Alamat"
                    maxlength="30"
                    onfocus="showLabelOptions()"
                    oninput="validateLabel()"
                    class="w-full rounded-lg border-gray-300
                        focus:ring-green-500 focus:border-green-500">

                {{-- ERROR --}}
                <p id="labelError"
                class="text-xs text-red-500 mt-1 hidden">
                    Wajib diisi
                </p>

                <p class="text-xs text-gray-400 text-right mt-1">
                    <span id="labelCount">0</span>/30
                </p>

                {{-- PILIHAN LABEL --}}
                <div id="labelOptions"
                    class="flex gap-2 mt-3 hidden flex-wrap">

                    <button type="button"
                            onclick="selectLabel('Rumah')"
                            class="px-4 py-1.5 rounded-full border
                                text-sm hover:border-green-600
                                hover:text-green-600">
                        Rumah
                    </button>

                    <button type="button"
                            onclick="selectLabel('Apartemen')"
                            class="px-4 py-1.5 rounded-full border
                                text-sm hover:border-green-600
                                hover:text-green-600">
                        Apartemen
                    </button>

                    <button type="button"
                            onclick="selectLabel('Kantor')"
                            class="px-4 py-1.5 rounded-full border
                                text-sm hover:border-green-600
                                hover:text-green-600">
                        Kantor
                    </button>

                    <button type="button"
                            onclick="selectLabel('Kos')"
                            class="px-4 py-1.5 rounded-full border
                                text-sm hover:border-green-600
                                hover:text-green-600">
                        Kos
                    </button>
                </div>
            </div>

            {{-- ALAMAT LENGKAP --}}
            <div>
                <label class="block text-gray-600 mb-1">
                    Alamat Lengkap <span class="text-red-500">*</span>
                </label>

                <textarea
                    id="fullAddress"
                    rows="3"
                    placeholder="Alamat Lengkap"
                    maxlength="200"
                    oninput="validateAlamat()"
                    class="w-full rounded-lg border-gray-300
                        focus:ring-green-500 focus:border-green-500"></textarea>

                <p id="alamatError"
                class="text-xs text-red-500 mt-1 hidden">
                    Wajib diisi
                </p>

                <p class="text-xs text-gray-400 text-right mt-1">
                    <span id="alamatCount">0</span>/200
                </p>
            </div>


            {{-- CATATAN KURIR --}}
            <div>
                <input id="courierNote"
                    class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
                    placeholder="Catatan Untuk Kurir (Opsional)"
                    maxlength="45">
                <p class="text-xs text-gray-400 text-right">0/45</p>
            </div>

            {{-- PINPOINT --}}
            <div class="flex items-center justify-between
                        border rounded-lg px-4 py-3">
                <div class="flex items-center gap-2 text-gray-600">
                    📍 Tambah Pinpoint
                    <span class="text-gray-400">(Opsional)</span>
                </div>
                <button onclick="openMapModal()"
                        class="text-green-600 font-semibold text-sm">
                    Atur
                </button>
            </div>
            <hr>
            {{-- CHECKBOX --}}
            <div class="space-y-3 pt-2">

                <label class="flex items-center gap-2">
                    <input type="checkbox" id="isPrimary">
                    Jadikan alamat utama
                </label>

                <label class="flex items-start gap-2 text-xs text-gray-500">
                    <input type="checkbox" id="agree"
                           onchange="toggleSaveBtn(this)">
                    <span>
                        Saya menyetujui
                        <span class="text-green-600 font-semibold">
                            Syarat & Ketentuan
                        </span>
                        serta
                        <span class="text-green-600 font-semibold">
                            Kebijakan Privasi
                        </span>
                        pengaturan alamat
                    </span>
                </label>

            </div>

            {{-- SAVE --}}
            <button id="saveAddressBtn"
                    onclick="saveAddress()"
                    class="w-full py-3 rounded-lg bg-green-600 text-white font-semibold">
                Simpan
            </button>

        </div>
    </div>
</div>


{{-- ================= MODAL MAP ================= --}}
<div id="mapModal" class="fixed inset-0 bg-black/40 z-[60] hidden flex items-center justify-center">
<div class="bg-white w-full max-w-3xl rounded-xl overflow-hidden">

<div class="flex justify-between px-6 py-4 border-b">
<h3 class="font-semibold">Pilih Lokasi</h3>
<button onclick="closeMapModal()">✕</button>
</div>

<div id="map" class="h-[420px]"></div>

<div class="flex justify-between p-4 border-t">
<button onclick="locateMe()" class="text-green-600 font-semibold">
    📍 Locate Me
</button>
<button onclick="savePinpoint()" class="bg-green-600 text-white px-5 py-2 rounded-lg">
Simpan Lokasi
</button>
</div>

</div>
</div>

{{-- ================= LEAFLET ================= --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map, marker;
let selectedLat = null;
let selectedLng = null;

function openMapModal() {
    document.getElementById('mapModal').classList.remove('hidden');

    setTimeout(() => {
        map = L.map('map').setView([-6.9147, 107.6098], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        map.on('click', e => {
            selectedLat = e.latlng.lat;
            selectedLng = e.latlng.lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        });
    }, 200);
}

function closeMapModal() {
    document.getElementById('mapModal').classList.add('hidden');
    if (map) {
        map.remove();
        map = null;
        marker = null;
    }
}
</script>
<script>
function locateMe() {
    if (!navigator.geolocation) {
        alert('Browser tidak mendukung GPS');
        return;
    }

    navigator.geolocation.getCurrentPosition(pos => {
        selectedLat = pos.coords.latitude;
        selectedLng = pos.coords.longitude;

        map.setView([selectedLat, selectedLng], 16);

        if (marker) {
            marker.setLatLng([selectedLat, selectedLng]);
        } else {
            marker = L.marker([selectedLat, selectedLng]).addTo(map);
        }
    });
}
</script>
<script>
async function reverseGeocode(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;

    const res = await fetch(url, {
        headers: {
            'Accept': 'application/json'
        }
    });

    const data = await res.json();
    return data.display_name ?? '';
}
</script>
<script>
async function savePinpoint() {
    if (!selectedLat || !selectedLng) {
        alert('Silakan pilih lokasi di peta');
        return;
    }

    const alamat = await reverseGeocode(selectedLat, selectedLng);

    if (alamat) {
        const textarea = document.getElementById('fullAddress');
        textarea.value = alamat;

        // trigger counter / validasi
        textarea.dispatchEvent(new Event('input'));
    }

    closeMapModal();
}
</script>
<script>
function showLabelOptions() {
    document.getElementById('labelOptions').classList.remove('hidden');
}

function selectLabel(value) {
    const input = document.getElementById('addressLabel');
    input.value = value;
    document.getElementById('labelOptions').classList.add('hidden');
    validateLabel();
}

function validateLabel() {
    const input = document.getElementById('addressLabel');
    const error = document.getElementById('labelError');
    const count = document.getElementById('labelCount');

    count.innerText = input.value.length;

    if (input.value.trim() === '') {
        input.classList.add('border-red-500');
        error.classList.remove('hidden');
        return false;
    } else {
        input.classList.remove('border-red-500');
        error.classList.add('hidden');
        return true;
    }
}
</script>
<script>
function validateNama() {
    const input = document.getElementById('recipientName');
    const error = document.getElementById('namaError');
    const count = document.getElementById('namaCount');

    count.innerText = input.value.length;

    if (input.value.trim() === '') {
        input.classList.add('border-red-500');
        error.classList.remove('hidden');
        return false;
    } else {
        input.classList.remove('border-red-500');
        error.classList.add('hidden');
        return true;
    }
}

function validateHp(el) {
    const error = document.getElementById('hpError');
    const count = document.getElementById('hpCount');

    // hanya angka
    el.value = el.value.replace(/[^0-9]/g, '');
    count.innerText = el.value.length;

    if (el.value.trim() === '') {
        el.classList.add('border-red-500');
        error.classList.remove('hidden');
        return false;
    } else {
        el.classList.remove('border-red-500');
        error.classList.add('hidden');
        return true;
    }
}
</script>
<script>
function validateAlamat() {
    const textarea = document.getElementById('fullAddress');
    const error = document.getElementById('alamatError');
    const count = document.getElementById('alamatCount');

    count.innerText = textarea.value.length;

    if (textarea.value.trim() === '') {
        textarea.classList.add('border-red-500');
        error.classList.remove('hidden');
        return false;
    } else {
        textarea.classList.remove('border-red-500');
        error.classList.add('hidden');
        return true;
    }
}
</script>
<script>
let editingAddressId = null;

async function saveAddress() {
    const name      = document.getElementById('recipientName').value.trim();
    const phone     = document.getElementById('phoneNumber').value.trim();
    const label     = document.getElementById('addressLabel').value.trim();
    const address   = document.getElementById('fullAddress').value.trim();
    const isPrimary = document.getElementById('isPrimary').checked;

    if (!name || !phone || !label || !address) {
        alert('Nama, Nomor HP, Label, dan Alamat wajib diisi');
        return;
    }

    // Persiapkan data untuk dikirim ke Laravel
    const formData = {
        recipient_name: name,
        phone:          phone,
        label:          label,
        full_address:   address,
        is_primary:     isPrimary,
        // Tambahkan ini jika ada inputnya di form Anda:
        courier_note:   document.getElementById('courierNote')?.value || null,
    };

    try {
        // --- PROSES KIRIM KE BACKEND ---
        const response = await fetch('/addresses', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Terjadi kesalahan pada server');
        }

        // --- JIKA BERHASIL, LANJUTKAN UPDATE UI ---
        const addressList = document.getElementById('addressList');
        const emptyText   = document.getElementById('emptyAddress');
        if (emptyText) emptyText.remove();

        if (isPrimary) {
            document.querySelectorAll('.address-card').forEach(card => {
                card.querySelector('.primary-badge')?.remove();
                card.querySelector('.primary-check')?.remove();
                card.dataset.primary = 'false';
            });
        }

        if (editingAddressId) {
            // Mode Update
            const card = document.querySelector(`[data-id="${editingAddressId}"]`);
            updateCardData(card, name, phone, label, address, isPrimary);
            card.innerHTML = addressCardHTML(label, name, phone, address, isPrimary, editingAddressId);
        } else {
            // Mode Tambah Baru (Gunakan ID dari database jika tersedia)
            const id = result.data.id || Date.now(); 
            const card = document.createElement('div');
            card.className = 'address-card border rounded-xl p-4 flex justify-between items-start hover:border-green-600 transition';
            
            updateCardData(card, name, phone, label, address, isPrimary, id);
            card.innerHTML = addressCardHTML(label, name, phone, address, isPrimary, id);
            addressList.prepend(card);
        }

        editingAddressId = null;
        closeAddressModal();
        alert('Alamat berhasil disimpan!');

    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menyimpan alamat: ' + error.message);
    }
}

// Fungsi pembantu untuk merapikan dataset
function updateCardData(card, name, phone, label, address, isPrimary, id = null) {
    if(id) card.dataset.id = id;
    card.dataset.label   = label;
    card.dataset.name    = name;
    card.dataset.phone   = phone;
    card.dataset.address = address;
    card.dataset.primary = isPrimary ? 'true' : 'false';
}

// ================= TEMPLATE CARD =================
function addressCardHTML(label, name, phone, address, isPrimary, id) {
    return `
        <div class="space-y-1 text-sm">
            <div class="flex items-center gap-2">
                <span class="font-semibold">${label}</span>
                ${isPrimary
                    ? `<span class="primary-badge text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">Utama</span>`
                    : ''}
            </div>

            <p class="font-medium">${name}</p>
            <p class="text-gray-500">${phone}</p>
            <p class="text-gray-600">${address}</p>

            <div class="flex gap-4 text-green-600 text-xs mt-2 font-semibold">
                <button
                    onclick="editAddress(this)"
                    data-id="${id}"
                    data-label="${label}"
                    data-name="${name}"
                    data-phone="${phone}"
                    data-address="${address}"
                    data-primary="${isPrimary}"
                    class="text-green-600">
                    Ubah
                </button>

                <button onclick="this.closest('.address-card').remove()">
                    Hapus
                </button>
            </div>
        </div>

        ${isPrimary
            ? `<div class="primary-check text-green-600 text-xl">✓</div>`
            : ''}
    `;
}
</script>

<script>
let editingAddressId = null;
</script>

{{-- ================= SCRIPT ================= --}}
<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.onclick = () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));

        btn.classList.add('active');
        document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
    }
});
function editAddress(btn) {
    editingAddressId = btn.dataset.id;

    document.getElementById('recipientName').value = btn.dataset.name;
    document.getElementById('phoneNumber').value = btn.dataset.phone;
    document.getElementById('addressLabel').value = btn.dataset.label;
    document.getElementById('fullAddress').value = btn.dataset.address;
    document.getElementById('courierNote').value = '';

    document.getElementById('isPrimary').checked =
        btn.dataset.primary === 'true';

    // Aktifkan tombol simpan
    const saveBtn = document.getElementById('saveAddressBtn');
    saveBtn.disabled = false;
    saveBtn.className =
        'w-full py-3 rounded-lg bg-green-600 text-white font-semibold';

    openAddressModal();
}
function openAddressModal() {
    const nameInput   = document.getElementById('recipientName');
    const phoneInput  = document.getElementById('phoneNumber');
    const labelInput  = document.getElementById('addressLabel');
    const addressInput= document.getElementById('fullAddress');
    const noteInput   = document.getElementById('courierNote');
    const primaryCb   = document.getElementById('isPrimary');
    const saveBtn     = document.getElementById('saveAddressBtn');

    // ================= MODE TAMBAH ALAMAT =================
    if (!editingAddressId) {
        nameInput.value    = @json(auth()->user()->name);
        phoneInput.value   = '';
        labelInput.value   = '';
        addressInput.value = '';
        noteInput.value    = '';
        primaryCb.checked  = false;

        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.className =
                'w-full py-3 rounded-lg bg-gray-200 text-gray-400 font-semibold';
        }
    }

    // ================= TAMPILKAN MODAL =================
    document.getElementById('addressModal').classList.remove('hidden');
}
function closeAddressModal() {
    editingAddressId = null;
    document.getElementById('addressModal').classList.add('hidden');
}
function toggleSaveBtn(cb) {
    const btn = document.getElementById('saveAddressBtn');
    btn.disabled = !cb.checked;
    btn.className = cb.checked
        ? 'w-full py-3 rounded-lg bg-green-600 text-white'
        : 'w-full py-3 rounded-lg bg-gray-200 text-gray-400';
}
</script>
{{-- ================= TAB SCRIPT ================= --}}
<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        if (btn.classList.contains('disabled')) return;

        document.querySelectorAll('.tab-btn')
            .forEach(b => b.classList.remove('active'));

        btn.classList.add('active');

        document.querySelectorAll('.tab-content')
            .forEach(c => c.classList.add('hidden'));

        document.getElementById('tab-' + btn.dataset.tab)
            .classList.remove('hidden');
    });
});
</script>
<style>
.tab-btn{width:100%;padding:10px;border-radius:8px;text-align:left}
.tab-btn.active{background:#ecfdf5;color:#15803d;font-weight:600}
</style>
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
