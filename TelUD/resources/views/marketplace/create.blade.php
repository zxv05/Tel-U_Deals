<!DOCTYPE html>
<html>
<head>
    <title>Jual Barang - Tel-U Deals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card col-md-6 mx-auto">
            <div class="card-header">Jual Barang Bekas / Jasa</div>
            <div class="card-body">
                <form action="{{ route('marketplace.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" name="NamaBarang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="fk_kategori" class="form-control">
                            @foreach($kategoris as $k)
                                <option value="{{ $k->KategoriID }}">{{ $k->NamaKategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Harga (Rp)</label>
                        <input type="number" name="HargaProduct" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Detail Produk</label>
                        <textarea name="ProductDetail" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Pasang Iklan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>