<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                
                {{-- BAGIAN INI CUMA MUNCUL KALAU YANG LOGIN ADMIN --}}
                @role('admin')
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <h3 class="font-bold text-lg">🔥 MODE GOD (ADMIN)</h3>
                        <p>Bos Admin bebas ngapain aja di sini.</p>
                        <div class="mt-2">
                            <a href="/admin/test" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                Tes Masuk Ruang Rahasia
                            </a>
                        </div>
                    </div>
                @endrole

                {{-- BAGIAN INI CUMA MUNCUL KALAU YANG LOGIN USER BIASA --}}
                @role('user')
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <h3 class="font-bold text-lg">👋 Halo User Biasa!</h3>
                        <p>Selamat berbelanja santai yaa...</p>
                    </div>
                @endrole

                {{-- INI MUNCUL BUAT SEMUA ORANG --}}
                <p class="mt-4">Status Login: <strong>{{ Auth::user()->name }}</strong></p>
                
            </div>
        </div>
    </div>
</div>