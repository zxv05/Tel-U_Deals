<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Address;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    
    public function history()
    {
        $userId = Auth::id();

        $purchases = Order::where('user_id', $userId)
            ->with(['orderDetails.product.seller']) // Ditambahin seller
            ->latest()
            ->get();

        $sales = Order::whereHas('orderDetails.product', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['user', 'orderDetails.product'])
            ->latest()
            ->get();

        return view('orders.history', compact('purchases', 'sales'));
    }

    public function detailHistory(Order $order) 
    {
        $userId = Auth::id();
        
        // Proteksi intip pesanan orang
        $isSeller = $order->orderDetails()->whereHas('product', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->exists();
        
        if ($order->user_id !== $userId && !$isSeller) {
            abort(403, 'Lu ngapain ngintip pesanan orang bang?');
        }

        $order->load(['reviews.user', 'orderDetails.product.seller']);

        return view('orders.detail_history', compact('order'));
    }
    public function show(Order $order)
{
    // Proteksi biar orang lain gak bisa liat orderan user lain
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    // Ambil data alamat user
    $addresses = Address::where('user_id', Auth::id())->get();
    
    // Cari pembayaran terakhir
    $payment = $order->payments()->latest()->first();
    $snapToken = null;

    // Logic Midtrans
    if ($order->payment_status == 'unpaid') {
        if (!$payment || !$payment->snap_token) {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_id,
                    'gross_amount' => (int)$order->total_price,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                
                // Simpan atau update token
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    ['status' => 'pending', 'snap_token' => $snapToken]
                );
            } catch (\Exception $e) {
                // Kalau midtrans error, tetep tampilin halaman tapi snapToken null
                $snapToken = null;
            }
        } else {
            $snapToken = $payment->snap_token;
        }
    }

    return view('orders.show', compact('order', 'snapToken', 'addresses'));
}
public function buyNow(Request $request)
    {
        // Validasi input produk dan jumlah
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Cek apakah stok mencukupi
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok nggak cukup bang!');
        }

        DB::beginTransaction(); // Memulai transaksi database agar data konsisten
        try {
            $orderCode = 'ORD-' . strtoupper(uniqid()); // Generate kode order unik

            // 1. Buat data Order utama
            $order = Order::create([
                'order_id'       => $orderCode,
                'user_id'        => Auth::id(),
                'total_price'    => $product->price * $request->quantity,
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // 2. Buat data Detail Order
            OrderDetail::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'price'      => $product->price,
                'quantity'   => $request->quantity,
            ]);

            // 3. Kurangi stok produk
            $product->decrement('stock', $request->quantity);

            DB::commit(); // Simpan perubahan secara permanen

            // Redirect ke halaman pembayaran dengan parameter order ID
            return redirect()->route('orders.show', ['order' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika terjadi error
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage()); // Tampilkan pesan error detail
        }
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:255'
        ]);

        $order = Order::findOrFail($request->order_id);
        
        if($order->user_id !== Auth::id()) {
            abort(403, 'Cuma pembeli yang bisa kasih rating bang.');
        }

        foreach($order->orderDetails as $detail) {
            Review::updateOrCreate(
                [
                    'order_id'   => $order->id, 
                    'product_id' => $detail->product_id,
                    'user_id'    => Auth::id()
                ],
                [
                    'rating'  => $request->rating,
                    'comment' => $request->comment
                ]
            );
        }

        return back()->with('success', 'Mantap bang, ulasan lu udah kesimpen!');
    }

    // ... sisa function show dan buyNow tetap sama seperti kode lu ...
}