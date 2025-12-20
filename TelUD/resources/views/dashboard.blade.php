<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Sambutan User -->
                    <p class="mb-4">
                        Selamat datang,
                        <strong>{{ Auth::user()->nama }}</strong>
                    </p>

                    <a href="{{ route('deals.index') }}"
                       class="inline-block bg-rose-600 hover:bg-rose-900 text-white px-4 py-2 rounded mb-6">
                        Lihat Deals
                    </a>

                    <hr class="my-6">

                    <!-- Deals Terbaru -->
                    <h3 class="text-lg font-bold mb-4">Deals Terbaru</h3>

                    <div class="grid grid-cols-3 gap-4">
                        @foreach($deals as $deal)
                            <div class="border p-4 rounded">
                                <h4 class="font-bold">{{ $deal->judul }}</h4>
                                <p>Rp {{ number_format($deal->harga) }}</p>

                                <!-- Tombol Beli -->
                                <form action="{{ route('deals.buy', $deal->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                        Beli
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
