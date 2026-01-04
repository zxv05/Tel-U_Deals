<x-app-layout>

{{-- ================= PAGE WRAPPER ================= --}}
<div class="min-h-screen flex flex-col
            bg-cover bg-center"
     style="background-image: url('{{ asset('images/telubg1.jpg') }}');">

    {{-- ================= OVERLAY ================= --}}
    <div class="flex-1 bg-[#7b0f2b]/85">

        {{-- ================= HERO BANNER ================= --}}
        <div class="max-w-7xl mx-auto px-4 pt-10 pb-24">
            <div class="relative overflow-hidden rounded-2xl shadow-lg aspect-[1479/647]">

                {{-- SLIDER --}}
                <div id="hero-carousel"
                     class="flex h-full transition-transform duration-700 ease-in-out">

                    <div class="flex-none w-full h-full">
                        <img src="{{ asset('images/Tel-u Deals (3).png') }}"
                             class="w-full h-full object-cover"
                             alt="Banner 1">
                    </div>

                    <div class="flex-none w-full h-full">
                        <img src="{{ asset('images/Tel-u Deals (4).png') }}"
                             class="w-full h-full object-cover"
                             alt="Banner 2">
                    </div>

                    <div class="flex-none w-full h-full">
                        <img src="{{ asset('images/Tel-u Deals (5).png') }}"
                             class="w-full h-full object-cover"
                             alt="Banner 3">
                    </div>

                </div>

                {{-- DOT INDICATOR --}}
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    <span class="dot w-3 h-3 rounded-full bg-white"></span>
                    <span class="dot w-3 h-3 rounded-full bg-white/40"></span>
                    <span class="dot w-3 h-3 rounded-full bg-white/40"></span>
                </div>

            </div>
        </div>

    </div>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-[#7b0f2b] text-white">
        <div class="max-w-7xl mx-auto px-6 py-12">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- BRAND --}}
                <div>
                    <h3 class="text-xl font-bold mb-3">Tel-U Deals</h3>
                    <p class="text-sm text-gray-200">
                        Marketplace internal Telkom University untuk jual beli aman,
                        cepat, dan terpercaya.
                    </p>
                </div>

                {{-- MENU --}}
                <div>
                    <h4 class="font-semibold mb-4">Menu</h4>
                    <ul class="space-y-2 text-sm text-gray-200">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('deals') }}">Marketplace</a></li>
                        <li><a href="{{ route('orders.index') }}">Pesanan</a></li>
                    </ul>
                </div>

                {{-- ACCOUNT --}}
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

                {{-- SECURITY --}}
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

</div>

{{-- ================= HERO SLIDER SCRIPT ================= --}}
<script>
    const carousel = document.getElementById('hero-carousel');
    const dots = document.querySelectorAll('.dot');
    let index = 0;

    setInterval(() => {
        index = (index + 1) % 3;
        carousel.style.transform = `translateX(-${index * 100}%)`;

        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-white', i === index);
            dot.classList.toggle('bg-white/40', i !== index);
        });
    }, 4000);
</script>

</x-app-layout>
