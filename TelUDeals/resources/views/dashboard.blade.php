<x-app-layout>
    {{-- ================= BACKGROUND IMAGE ================= --}}
    <div
        class="min-h-screen bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/telubg1.jpg') }}');"
    >
        {{-- OVERLAY MERAH --}}
        <div class="min-h-screen bg-[#7b0f2b]/85 py-10">
{{-- ================= HERO BANNER ================= --}}
<div class="max-w-7xl mx-auto px-4 mt-6">
    <div class="relative overflow-hidden rounded-2xl shadow-lg aspect-[1479/647]">

        {{-- SLIDER --}}
        <div id="hero-carousel"
            class="flex h-full transition-transform duration-700 ease-in-out">

            <div class="flex-none w-full h-full">
                <img
                    src="{{ asset('images/Tel-u Deals (3).png') }}"
                    class="w-full h-full object-cover"
                    alt="Banner 1">
            </div>

            <div class="flex-none w-full h-full">
                <img
                    src="{{ asset('images/Tel-u Deals (4).png') }}"
                    class="w-full h-full object-cover"
                    alt="Banner 2">
            </div>

            <div class="flex-none w-full h-full">
                <img
                    src="{{ asset('images/Tel-u Deals (5).png') }}"
                    class="w-full h-full object-cover"
                    alt="Banner 3">
            </div>

        </div>

        {{-- DOT --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
            <span class="dot w-3 h-3 rounded-full bg-white"></span>
            <span class="dot w-3 h-3 rounded-full bg-white/50"></span>
            <span class="dot w-3 h-3 rounded-full bg-white/50"></span>
        </div>
    </div>
</div>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-[#7b0f2b] text-white mt-16">
        <div class="max-w-7xl mx-auto px-6 py-10">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div>
                    <h3 class="text-xl font-bold mb-2">Tel-U Deals</h3>
                    <p class="text-sm text-gray-200">
                        Marketplace internal Telkom University untuk jual beli aman,
                        cepat, dan terpercaya.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-3">Menu</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
                        <li><a href="{{ route('products.mine') }}" class="hover:underline">Produk Saya</a></li>
                        <li><a href="{{ route('orders.index') }}" class="hover:underline">Pesanan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-3">Akun</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('profile.edit') }}" class="hover:underline">Profil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="hover:underline">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-white/20 mt-8 pt-4 text-center text-sm text-gray-200">
                © {{ date('Y') }} Tel-U Deals. All rights reserved.
            </div>
        </div>
    </footer>

    {{-- ================= SCRIPT SLIDER ================= --}}
    <script>
        const carousel = document.getElementById('hero-carousel');
        const dots = document.querySelectorAll('.dot');
        let index = 0;

        setInterval(() => {
            index = (index + 1) % 3;
            carousel.style.transform = `translateX(-${index * 100}%)`;

            dots.forEach((dot, i) => {
                dot.classList.toggle('bg-white/70', i === index);
                dot.classList.toggle('bg-white/40', i !== index);
            });
        }, 4000);
    </script>

</x-app-layout>
