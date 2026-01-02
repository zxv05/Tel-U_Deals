<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        $KeranjangItems = Keranjang::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $KeranjangCount = $KeranjangItems->sum('quantity');

        return view('keranjang.index', compact('KeranjangItems', 'KeranjangCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $item = Keranjang::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ],
            [
                'quantity' => $request->quantity,
                'total_price' => $product->harga_product * $request->quantity,
            ]
        );

        $product->stok -= $request->quantity;
        $product->save();

        return redirect()->route('keranjang.index')->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Keranjang::findOrFail($id);
        $product = Product::findOrFail($item->product_id);

        $difference = $item->quantity - $request->quantity;

        $item->update([
            'quantity' => $request->quantity,
            'total_price' => $product->harga_product * $request->quantity
        ]);

        $product->stok += $difference;
        $product->save();

        return redirect()->route('keranjang.index')->with('success', 'Keranjang diperbarui');
    }

    public function destroy($id)
    {
        $item = Keranjang::findOrFail($id);
        $product = Product::findOrFail($item->product_id);

        $product->stok += $item->quantity;
        $product->save();

        $item->delete();

        return redirect()->route('keranjang.index')->with('success', 'Item dihapus');
    }

    public function checkout()
    {
        $items = Keranjang::where('user_id', Auth::id())->get();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong');
        }

        $total = $items->sum('total_price');

        $order = Order::create([
            'order_id' => 'ORD' . rand(1000, 9999),
            'user_id' => Auth::id(),
            'total_price' => $total,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($items as $item) {
            $order->orderDetails()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->harga_product,
            ]);
        }

        Keranjang::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}
