<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Kategori;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class MarketplaceController extends Controller
{
    // 1. Halaman Utama (List Barang)
    public function index()
    {
        $products = Product::with('kategori')->get();
        $carts = Keranjang::where('user_id',Auth::id())->get();
        return view('marketplace.index', compact('products','carts'));
    }

    // 2. Halaman Jual Barang (Form)
    public function create()
    {
        $kategoris = Kategori::all();
        return view('marketplace.create', compact('kategoris'));
    }

    // 3. Logic Simpan Barang (Store)
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'harga_product' => 'required|numeric',
            'kategori_id' => 'required',
            'product_detail' => 'required',
            'stok' => 'required|min:0'
        ]);

        Product::create([
            'user_id' => Auth::id(), // ID User yang login
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'harga_product' => $request->harga_product,
            'product_detail' => $request->product_detail,
            'stok' => $request->kuantitas
        ]);

        return redirect()->route('marketplace.index')->with('success', 'Barang berhasil diupload ke Marketplace!');
    }

    // 4. Logic Beli Barang (Checkout Simple)
    public function checkout($id_barang)
    {
        // Cari barangnya
        $product = Product::findOrFail($id_barang);

        // Gunakan Transaction biar data aman
        DB::transaction(function () use ($product) {
            // A. Buat Order Header
            $order = Order::create([
                'user_id' => Auth::id() // Pembeli
            ]);

            // B. Buat Detail Order
            OrderDetail::create([
                'order_id' => $order->OrderID,
                'product_id' => $product->ProductID,
                'user_id' => Auth::id(),
                'date' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Berhasil dibeli! Cek riwayat pesanan.');
    }
    public function addToCart($id_barang){

    }
}