<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // 🔐 proteksi order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $payment = $order->payments()->latest()->first();
        $snapToken = null;

        if (!$payment) {

            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_id,
                    'gross_amount' => $order->total_price,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            Payment::create([
                'order_id'   => $order->id,
                'amount'     => $order->total_price,
                'status'     => 'pending',
                'snap_token' => $snapToken,
            ]);
        } else {
            $snapToken = $payment->snap_token;
        }

        return view('orders.show', compact('order', 'snapToken'));
    }

public function buyNow(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|integer|min:1'
    ]);

    $product = Product::findOrFail($request->product_id);

    if ($product->stock <= 0) {
    return redirect()->back()
        ->with('error', 'Barang sudah habis');
}

    if ($product->user_id === Auth::id()) {
        abort(403, 'Tidak boleh membeli produk sendiri');
    }

    if ($product->stock < $request->quantity) {
        return back()->with('error', 'Stok tidak mencukupi');
    }

    DB::beginTransaction();

    try {
        // ✅ GENERATE ORDER CODE
        $orderCode = 'ORD-' . strtoupper(uniqid());

        // ✅ CREATE ORDER (FIELD SESUAI DATABASE)
        $order = Order::create([
            'order_id'       => $orderCode,
            'user_id'        => Auth::id(),
            'total_price'    => $product->price * $request->quantity,
            'status'         => 'pending',
            'payment_status' => 'UNPAID',
        ]);

        // ✅ ORDER DETAIL
        OrderDetail::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'price'      => $product->price,
            'quantity'   => $request->quantity,
        ]);

        // ✅ KURANGI STOK
        $product->decrement('stock', $request->quantity);

        DB::commit();

        // ✅ LANGSUNG KE HALAMAN BAYAR
        return redirect()->route('orders.show', $order->id);

    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage()); // sementara buat debug
    }
}
}