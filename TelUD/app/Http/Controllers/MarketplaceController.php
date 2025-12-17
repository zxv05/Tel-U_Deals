<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarketplaceController extends Controller
{
    // 1. Halaman Utama (List Barang)
    public function index()
    {
        $products = Product::with('kategori')->get();
        return view('marketplace.index', compact('products'));
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
            'NamaBarang' => 'required',
            'HargaProduct' => 'required|numeric',
            'fk_kategori' => 'required',
            'ProductDetail' => 'required'
        ]);

        Product::create([
            'fk_user' => Auth::id(), // ID User yang login
            'NamaBarang' => $request->NamaBarang,
            'fk_kategori' => $request->fk_kategori,
            'HargaProduct' => $request->HargaProduct,
            'ProductDetail' => $request->ProductDetail,
        ]);

        return redirect()->route('marketplace.index')->with('success', 'Barang berhasil dijual!');
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
                'fk_User' => Auth::id() // Pembeli
            ]);

            // B. Buat Detail Order
            OrderDetail::create([
                'fk_order' => $order->OrderID,
                'fk_product' => $product->ProductID,
                'fk_User' => Auth::id(),
                'Date' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Berhasil dibeli! Cek riwayat pesanan.');
    }
}