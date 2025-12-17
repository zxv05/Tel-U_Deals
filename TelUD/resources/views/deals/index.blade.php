<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tel-U Deals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($deals as $deal)
                    <div class="border p-4 rounded shadow-sm bg-white">
                        <h2 class="font-bold text-lg mb-1">
                            {{ $deal->judul }}
                        </h2>

                        <p class="text-gray-600 mb-2">
                            {{ $deal->deskripsi }}
                        </p>

                        <p class="text-red-600 font-bold mb-4">
                            Rp {{ number_format($deal->harga) }}
                        </p>

                        {{-- BUTTON BELI --}}
                        <form action="{{ route('deals.buy', $deal->id) }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                                onclick="return confirm('Yakin mau beli deal ini?')"
                            >
                                Beli
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada deal.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
