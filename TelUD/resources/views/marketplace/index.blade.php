<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Marketplace Tel-U Deals 🛒') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[url('/storage/app/public/bg/18410.jpg')] bg-cover overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    
        <a href="{{ route('marketplace.create') }}" 
        class="inline-block bg-rose-600 hover:bg-rose-900 text-white px-4 py-2 rounded mb-6">+ Jual Barang</a>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="grid grid-flow-row-dense grid-cols-3 grid-rows-3 ">
            @foreach($products as $p)
            @if($p->user_id != Auth::user()->id)
            <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">     
            <div class="px-6 py-4 ">
                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{ $p->kategori->nama_kategori }}</span>
                <div class="font-bold text-xl mb-2">{{ $p->nama_barang }}</div>
                
                <p class="text-gray-700 text-base">
                {{ $p->product_detail }}
                </p>
                <div class="font-bold text-xl mb-2">Rp {{ number_format($p->harga_product) }}</div>
                <div class="font-bold text-x mb-2">Stok tersedia: {{ number_format($p->stok) }}</div>
            </div>
            <div class="px-6 pt-4 pb-2">
                <form action="{{ route('keranjang.store')}}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="product_id" value="{{$p->id}}">
                <input type="number" name="quantity" value="1" min="1" max="{{$p->stok}}"
                                        class="form-control w-50 mr-2" style="max-width: 100px;">
                <button type="submit"
                    class="inline-block bg-rose-600 hover:bg-rose-900 text-white px-4 py-2 rounded mb-6">Tambah ke Keranjang
                </button>
                </form>
                </div>
            </div>

           {{--  <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $p->nama_barang }}</h5>
                        <p class="text-muted">{{ $p->kategori->nama_kategori }}</p>
                        <h6 class="text-danger">Rp {{ number_format($p->harga_product) }}</h6>
                        <p class="card-text">{{ $p->product_detail }}</p>
                        
                        <form action="{{ route('marketplace.checkout', $p->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">Tambah ke Keranjang</button>
                        </form>
                    </div>
                </div>
            </div> --}}
            @endif
            @endforeach
        </div>
    </div>
            </div></div></div>
</x-app-layout>
