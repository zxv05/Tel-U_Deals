<x-app-layout>

{{-- ================= PAGE WRAPPER ================= --}}
<div class="min-h-screen flex flex-col bg-cover bg-center"
     style="background-image: url('{{ asset('images/telubg1.jpg') }}');">

    {{-- ================= OVERLAY / CONTENT ================= --}}
    <div class="flex-1 bg-[#7b0f2b]/85">
<div class="h-20"></div>
        {{-- ================= HERO BANNER ================= --}}
        <div class="max-w-7xl mx-auto px-4 pt-10 pb-24">

            @if(isset($errors) && $errors->any())
                <div class="bg-red-500 text-white p-4 rounded-xl mb-6 text-sm font-bold shadow-lg">
                    Terjadi kendala teknis saat memuat data.
                </div>
            @endif

            <div class="relative overflow-hidden rounded-2xl shadow-lg aspect-[1479/647]">

                {{-- SLIDER --}}
<div id="hero-carousel"
     class="flex h-full transition-transform duration-700 ease-in-out">

    {{-- SLIDE 1 - PAKAIAN --}}
    <a href="{{ route('deals') }}?category=pakaian"
       class="flex-none w-full h-full block cursor-pointer group">
        <img src="{{ asset('images/Tel-u Deals (3).png') }}"
             class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
             alt="Pakaian">
    </a>

    {{-- SLIDE 2 - ELEKTRONIK --}}
    <a href="{{ route('deals') }}?category=elektronik"
       class="flex-none w-full h-full block cursor-pointer group">
        <img src="{{ asset('images/Tel-u Deals (4).png') }}"
             class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
             alt="Elektronik">
    </a>

    {{-- SLIDE 3 - FURNITUR --}}
    <a href="{{ route('deals') }}?category=furnitur"
       class="flex-none w-full h-full block cursor-pointer group">
        <img src="{{ asset('images/Tel-u Deals (5).png') }}"
             class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
             alt="Furnitur">
    </a>

</div>
{{-- DOTS (CLICKABLE) --}}
<div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-3 z-10">
    <button class="dot w-3 h-3 rounded-full bg-white"
            data-slide="0"
            aria-label="Slide Pakaian"></button>

    <button class="dot w-3 h-3 rounded-full bg-white/40"
            data-slide="1"
            aria-label="Slide Elektronik"></button>

    <button class="dot w-3 h-3 rounded-full bg-white/40"
            data-slide="2"
            aria-label="Slide Furnitur"></button>
</div>

            </div>
        </div>

        {{-- ================= KATA SAMBUTAN ================= --}}
        {{-- ⬇️ INI YANG DIKASIH JARAK KE FOOTER --}}
        <div class="max-w-7xl mx-auto px-6 text-center mb-32">
            <div class="bg-white/10 backdrop-blur-md border border-white/20
                        p-8 md:p-12 rounded-[2rem] shadow-2xl">

<h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4">
    Selamat Datang,
    <span class="text-yellow-400">
        @auth
            {{ auth()->user()->name }}
        @else
            Pengunjung
        @endauth
    </span>!
</h1>


                <p class="text-lg md:text-xl text-white max-w-2xl mx-auto mb-10 leading-relaxed">
                    Siap menemukan produk terbaik hari ini?
                    Jelajahi marketplace internal Telkom University dan nikmati
                    pengalaman belanja yang aman dan terpercaya.
                </p>

                <a href="{{ route('deals') }}"
                   class="inline-flex items-center justify-center
                          px-10 py-4 bg-white text-[#7b0f2b]
                          font-bold rounded-full shadow-xl
                          hover:bg-yellow-400 hover:scale-105 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6 mr-2"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>

                    Mulai Belanja Sekarang
                </a>
            </div>
        </div>
{{-- SPACER BIAR GA MEPET KE FOOTER --}}
<div class="h-40"></div>

    </div> {{-- END OVERLAY --}}

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
                                <button class="hover:underline">Logout</button>
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

</div>

{{-- ================= SLIDER SCRIPT ================= --}}
<script>
const carousel = document.getElementById('hero-carousel');
const dots = document.querySelectorAll('.dot');

let index = 0;
let interval;

// === PINDAH SLIDE ===
function goToSlide(i) {
    index = i;
    carousel.style.transform = `translateX(-${index * 100}%)`;

    dots.forEach((dot, idx) => {
        dot.classList.toggle('bg-white', idx === index);
        dot.classList.toggle('bg-white/40', idx !== index);
    });
}

// === AUTO SLIDE ===
function startAutoSlide() {
    interval = setInterval(() => {
        index = (index + 1) % dots.length;
        goToSlide(index);
    }, 4000);
}

// === STOP AUTO SLIDE ===
function stopAutoSlide() {
    clearInterval(interval);
}

// === CLICK DOT ===
dots.forEach(dot => {
    dot.addEventListener('click', () => {
        stopAutoSlide();
        goToSlide(Number(dot.dataset.slide));
        startAutoSlide();
    });
});

// START
startAutoSlide();
</script>
<style>
.dot {
    transition: transform 0.2s ease, opacity 0.2s ease;
}

.dot:hover {
    transform: scale(1.4);
    opacity: 1;
}
</style>

</x-app-layout>
