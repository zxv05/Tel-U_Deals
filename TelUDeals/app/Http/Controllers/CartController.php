<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
public function index()
{
    $cartItems = Cart::with('product')
        ->where('user_id', auth::id())
        ->get();

    $total = $cartItems->sum('total_price');

    return view('cart.index', compact('cartItems', 'total'));
}

    /**
     * TAMBAH KE CART
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // ❌ BLOK JIKA USER COBA BELI PRODUK SENDIRI
        if ($product->user_id === Auth::id()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Kamu tidak bisa membeli produk milik sendiri.');
        }

        // ❌ BLOK JIKA STOK HABIS
        if ($product->stock < $request->quantity) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Stok produk tidak mencukupi.');
        }

        Cart::updateOrCreate(
            [
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
            ],
            [
                'quantity'    => $request->quantity,
                'total_price' => $product->price * $request->quantity,
            ]
        );

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produk ditambahkan ke keranjang');
    }

    /**
     * UPDATE JUMLAH CART
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::with('product')->findOrFail($id);

        // ❌ PASTIKAN CART MILIK USER SENDIRI
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        // ❌ VALIDASI STOK
        if ($cartItem->product->stock < $request->quantity) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Stok produk tidak mencukupi.');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->total_price = $cartItem->product->price * $request->quantity;
        $cartItem->save();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Keranjang diperbarui');
    }

    /**
     * HAPUS CART
     */
    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);

        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item dihapus dari keranjang');
    }

    /**
     * CHECKOUT
     */
    public function checkout()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang kosong');
        }

        // ❌ DOUBLE SAFETY: CEK ADA PRODUK MILIK SENDIRI ATAU TIDAK
        foreach ($cartItems as $item) {
            if ($item->product->user_id === Auth::id()) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Checkout gagal: terdapat produk milik sendiri.');
            }
        }

        $totalPrice = $cartItems->sum('total_price');

        $order = Order::create([
            'order_id'       => 'ORD-' . strtoupper(uniqid()),
            'user_id'        => Auth::id(),
            'total_price'    => $totalPrice,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($cartItems as $item) {
            $order->orderDetails()->create([
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);

            // OPTIONAL: KURANGI STOK
            $item->product->decrement('stock', $item->quantity);
        }

        Cart::where('user_id', Auth::id())->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order berhasil dibuat');
    }
}
