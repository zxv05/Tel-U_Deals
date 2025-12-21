<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        // Ambil semua item di Keranjang untuk user yang sedang login
        $KeranjangItems = Keranjang::with('product')->where('user_id', Auth::id())->get();
        // Hitung jumlah item di Keranjang
        $KeranjangCount = $KeranjangItems->sum('quantity');

        return view('keranjang.index', compact('KeranjangItems', 'KeranjangCount'));
    }

public function checkout()
    {
        // Ambil semua item di Keranjang untuk user yang sedang login
        $KeranjangItems = Keranjang::with('product')->where('user_id', Auth::id())->get();

        // Pastikan ada item di Keranjang
        if ($KeranjangItems->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Your Keranjang is empty.');
        }

        // Hitung total harga pesanan
        $totalPrice = $KeranjangItems->sum(function ($item) {
            return $item->total_price;
        });

        // Buat pesanan baru
        $order = Order::create([
            'order_id' => 'ORD' . rand(1000, 9999), // Generate order ID unik
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Simpan detail pesanan (produk yang dibeli)
        foreach ($KeranjangItems as $item) {
            $order->orderDetails()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Hapus semua item di Keranjang setelah checkout
        Keranjang::where('user_id', Auth::id())->delete();

        return redirect()->route('keranjang')->with('success', 'Your order has been placed successfully!');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        
        // Tambahkan ke Keranjang
        $Keranjang = Keranjang::updateOrCreate(
            
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->input('quantity', 1), 
                'total_price' => Product::find($request->product_id)->harga_product * $request->input('quantity', 1),
            ]
            
        );

        $product = Product::find($request->product_id);
        $product->stok = $product->stok - $request->quantity;
        $product->save();


        return redirect()->route('keranjang.index')->with('success', 'Product added to Keranjang!');
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        
        // Update item di Keranjang
        $KeranjangItem = Keranjang::findOrFail($id);
        $difference = $KeranjangItem->quantity - $request->quantity;
        $KeranjangItem->quantity = $request->quantity;
        $KeranjangItem->total_price = $KeranjangItem->product->harga_product * $request->quantity;
        $KeranjangItem->save();
        $product = Product::findOrFail($KeranjangItem->product_id);
        $product->stok = $product->stok + $difference;
        $product->save();

        return redirect()->route('keranjang.index')->with('success', 'Keranjang updated successfully!');
    }

    public function destroy($id)
    {
        $KeranjangItem = Keranjang::findOrFail($id);
        $product = Product::findOrFail($KeranjangItem->product_id);
        $product->stok = $product->stok + $KeranjangItem->quantity;
        $product->save();
        $KeranjangItem->delete();

        return redirect()->route('keranjang.index')->with('success', 'Item removed from Keranjang');
    }
}

