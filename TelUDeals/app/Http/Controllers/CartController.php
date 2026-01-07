<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN KERANJANG
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum('total_price');

        return view('cart.index', compact('cartItems', 'total'));
    }
    public function __construct()
{
    $this->middleware('auth');
}

    /*
    |--------------------------------------------------------------------------
    | TAMBAH KE CART
    |--------------------------------------------------------------------------
    */
public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($request->product_id);

    // ❌ Produk habis
    if ($product->stock <= 0) {
        return $this->responseError($request, 'Barang sudah habis');
    }

    // ❌ Tidak boleh beli produk sendiri
    if ($product->user_id === Auth::id()) {
        return $this->responseError($request, 'Kamu tidak bisa membeli produk milik sendiri.');
    }

    // ❌ Stok tidak cukup
    if ($product->stock < $request->quantity) {
        return $this->responseError($request, 'Stok produk tidak mencukupi.');
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

// ================= AJAX REQUEST =================
if ($request->expectsJson()) {
    return response()->json([
        'success' => true,
        'message' => 'Produk ditambahkan ke keranjang'
    ]);
}

// ================= NORMAL FORM =================
return redirect()
    ->route('cart.index')
    ->with('success', 'Produk ditambahkan ke keranjang');

}

    /*
    |--------------------------------------------------------------------------
    | UPDATE JUMLAH ITEM CART
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::with('product')->findOrFail($id);

        // ❌ Proteksi cart bukan milik user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        // ❌ Validasi stok
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

    /*
    |--------------------------------------------------------------------------
    | HAPUS ITEM CART
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT CART → ORDER
    |--------------------------------------------------------------------------
    */
    public function checkout()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        // ❌ Keranjang kosong
        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang kosong');
        }

        // ❌ Double safety: produk milik sendiri
        foreach ($cartItems as $item) {
            if ($item->product->user_id === Auth::id()) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Checkout gagal: terdapat produk milik sendiri.');
            }
        }

        $totalPrice = $cartItems->sum('total_price');

        // Buat order utama
        $order = Order::create([
            'order_id'       => 'ORD-' . strtoupper(uniqid()),
            'user_id'        => Auth::id(),
            'total_price'    => $totalPrice,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Simpan detail order
        foreach ($cartItems as $item) {
            $order->orderDetails()->create([
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);

            // Kurangi stok
            $item->product->decrement('stock', $item->quantity);
        }

        // Kosongkan cart
        Cart::where('user_id', Auth::id())->delete();

        // ✅ FIX PENTING: redirect ke route yang ADA
        return redirect()
            ->route('orders.history')
            ->with('success', 'Order berhasil dibuat');
    }
}
