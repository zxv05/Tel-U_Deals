<!DOCTYPE html>
<html>
<head>
    <title>Tel-U Deals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1>Tel-U Deals 🛒</h1>
        <a href="{{ route('marketplace.create') }}" class="btn btn-primary mb-3">+ Jual Barang</a>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            @foreach($products as $p)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $p->NamaBarang }}</h5>
                        <p class="text-muted">{{ $p->kategori->NamaKategori }}</p>
                        <h6 class="text-danger">Rp {{ number_format($p->HargaProduct) }}</h6>
                        <p class="card-text">{{ $p->ProductDetail }}</p>
                        
                        <form action="{{ route('marketplace.checkout', $p->ProductID) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">Beli Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>